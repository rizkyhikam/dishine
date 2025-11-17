<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Services\RajaOngkirService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
    // Validasi data profil
    if (empty(Auth::user()->alamat) || empty(Auth::user()->no_hp)) {
        return redirect()->route('profil')
            ->with('error', 'Silakan lengkapi alamat dan nomor HP Anda terlebih dahulu sebelum checkout.');
    }

    $userId = auth()->id();

    // Ambil cart user dari database
    $cartItems = Cart::with('product')
        ->where('user_id', $userId)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
    }

    $total = $cartItems->sum(function ($item) {
        return $item->product->harga_normal * $item->quantity;
    });

    return view('checkout.index', compact('cartItems', 'total'));
    }

    public function storeFullCheckout(Request $request, RajaOngkirService $rajaOngkir, WhatsAppService $whatsapp)
    {
        // 1. Validasi Alamat (Kita cek langsung dari data user, bukan request)
        $user = Auth::user();
        if (empty($user->alamat) || empty($user->no_hp)) {
            return response()->json([
                'message' => 'Silakan lengkapi alamat dan nomor HP Anda terlebih dahulu.'
            ], 400);
        }

        // 2. Validasi Request (Sama seperti sebelumnya)
        $request->validate([
            'kurir' => 'required|string|in:jne,pos,tiki',
            'destination' => 'required|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ongkir_value' => 'required|integer|min:0',
            'layanan_selected_name' => 'required|string',
        ]);
        
        // -----------------------------------------------------------------
        // KITA GUNAKAN DB::TRANSACTION AGAR AMAN
        // -----------------------------------------------------------------
        try {
            $result = DB::transaction(function () use ($request, $user, $whatsapp) {
                // Ambil cart dari database
                $cartItems = Cart::with('product')
                    ->where('user_id', $user->id)
                    ->get();

                if ($cartItems->isEmpty()) {
                    return response()->json(['message' => 'Keranjang kosong'], 400);
                }

                // --- LANGKAH 1 (BARU): VALIDASI STOK ---
                foreach ($cartItems as $cartItem) {
                    // Cek apakah produk masih ada
                    if (!$cartItem->product) {
                         return response()->json([
                            'message' => 'Produk ' . ($cartItem->product_id ?? 'N/A') . ' tidak ditemukan.'
                        ], 404);
                    }
                    // Cek apakah stok cukup
                    if ($cartItem->product->stok < $cartItem->quantity) {
                        return response()->json([
                            'message' => 'Stok untuk produk ' . $cartItem->product->nama . ' tidak mencukupi (sisa ' . $cartItem->product->stok . ').'
                        ], 422); // 422 Unprocessable Entity
                    }
                }

                // --- LANGKAH 2: HITUNG TOTAL (SAMA SEPERTI LAMA) ---
                $totalProduk = $cartItems->sum(function ($item) {
                    return $item->product->harga_normal * $item->quantity;
                });

                $biayaLayanan = 2000; // <-- Didefinisikan dengan 'y'
                $ongkir = $request->ongkir_value;
                $total = $totalProduk + $ongkir + $biayaLayanan; // <-- Digunakan dengan 'y' (Benar)

                // --- LANGKAH 3: BUAT ORDER (SAMA SEPERTI LAMA) ---
                $order = Order::create([
                    'user_id' => $user->id,
                    'tanggal_pesan' => now(),
                    'total' => $totalProduk,
                    'ongkir' => $ongkir,
                    
                    // --- INI DIA PERBAIKANNYA ---
                    'biaya_layanan' => $biayaLayanan, // <-- SEKARANG SUDAH BENAR (pakai 'y')
                    
                    'total_bayar' => $total,
                    'status' => Order::STATUS_MENUNGGU_VERIFIKASI,
                    'alamat_pengiriman' => $user->alamat,
                    'kurir' => $request->kurir,
                    'layanan_kurir' => $request->layanan_selected_name,
                    'kota_tujuan' => $request->destination,
                ]);

                // --- LANGKAH 4: SIMPAN ORDER ITEM & KURANGI STOK (UPGRADE) ---
                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'jumlah' => $cartItem->quantity,
                        'harga_satuan' => $cartItem->product->harga_normal,
                        'subtotal' => $cartItem->product->harga_normal * $cartItem->quantity,
                    ]);
                    
                    // --- INI LOGIKA BARU UNTUK KURANGI STOK ---
                    $product = $cartItem->product;
                    $product->stok -= $cartItem->quantity; // Kurangi stok
                    $product->save(); // Simpan perubahan stok
                    // ------------------------------------------
                }

                // --- LANGKAH 5: UPLOAD BUKTI TRANSFER (SAMA) ---
                $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');

                Payment::create([
                    'order_id' => $order->id,
                    'bukti_transfer' => $buktiPath,
                    'status_verifikasi' => Payment::STATUS_BELUM_DIVERIFIKASI,
                    'metode_pembayaran' => 'transfer',
                ]);

                // --- LANGKAH 6: BERSIHKAN KERANJANG (SAMA) ---
                Cart::where('user_id', $user->id)->delete();

                // --- LANGKAH 7: KIRIM WA (DIPERBAIKI) ---
                try {
                    $buktiUrl = asset('storage/' . $buktiPath);
                    $message = "💸 *Pesanan Baru Masuk!*\n\n"
                        . "👤 *Nama:* " . $user->nama . "\n"
                        . "📞 *No HP:* " . ($user->no_hp ?? '-') . "\n"
                        . "🚚 *Kurir:* " . strtoupper($request->kurir) . " ({$request->layanan_selected_name})\n"
                        . "🏠 *Alamat:* {$order->alamat_pengiriman}\n"
                        . "💰 *Total Produk:* Rp " . number_format($totalProduk, 0, ',', '.') . "\n"
                        . "🚛 *Ongkir:* Rp " . number_format($ongkir, 0, ',', '.') . "\n"
                        . "💳 *Biaya Layanan:* Rp " . number_format($biayaLayanan, 0, ',', '.') . "\n"
                        . "💵 *Total Bayar:* Rp " . number_format($total, 0, ',', '.') . "\n\n"
                        . "📎 *Bukti Transfer:* (lihat gambar di bawah)";

                    // INI FUNGSI YANG BENAR (dari pesan sebelumnya)
                    $whatsapp->sendToAdmin($message, $buktiUrl); 

                } catch (\Exception $e) {
                    // Jika WA gagal, jangan batalkan pesanan
                    \Log::error('Gagal kirim notifikasi WA ke admin: ' . $e->getMessage());
                }

                // Jika semua berhasil, kembalikan JSON sukses
                return response()->json([
                    'message' => '✅ Pesanan berhasil dibuat & bukti transfer dikirim ke admin.',
                    'order_id' => $order->id,
                ], 200);
            });

            // Kembalikan hasil dari DB::transaction
            return $result;

        } catch (\Exception $e) {
            // Tangkap error jika transaksi gagal
            \Log::error('Checkout Gagal Total: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi error internal: ' . $e->getMessage()
            ], 500);
        }
    }
}