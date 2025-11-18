<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product; // <-- Saya tambahkan ini untuk cek stok
use App\Services\RajaOngkirService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout
     * (VERSI UPGRADE - Menghitung total berdasarkan role)
     */
    public function index()
    {
        // Validasi data profil
        if (empty(Auth::user()->alamat) || empty(Auth::user()->no_hp)) {
            return redirect()->route('profil')
                ->with('error', 'Silakan lengkapi alamat dan nomor HP Anda terlebih dahulu sebelum checkout.');
        }

        // --- PERUBAHAN DIMULAI ---
        $user = Auth::user(); // Ambil user
        $isReseller = ($user->role == 'reseller'); // Cek role
        $userId = $user->id;
        // --- AKHIR PERUBAHAN ---

        // Ambil cart user dari database
        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // --- PERUBAHAN DIMULAI ---
        // Hitung total berdasarkan role
        $total = $cartItems->sum(function ($item) use ($isReseller) {
            // Cek jika produknya masih ada
            if (!$item->product) return 0; 
            
            if ($isReseller) {
                // Gunakan Harga Reseller
                return $item->product->harga_reseller * $item->quantity;
            } else {
                // Gunakan Harga Normal
                return $item->product->harga_normal * $item->quantity;
            }
        });
        // --- AKHIR PERUBAHAN ---

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Menyimpan pesanan
     * (VERSI UPGRADE - Menggunakan harga & validasi role)
     */
    public function storeFullCheckout(Request $request, RajaOngkirService $rajaOngkir, WhatsAppService $whatsapp)
    {
        // 1. Validasi Alamat
        $user = Auth::user();
        // --- PERUBAHAN DIMULAI ---
        $isReseller = ($user->role == 'reseller'); // Cek role
        // --- AKHIR PERUBAHAN ---

        if (empty($user->alamat) || empty($user->no_hp)) {
            return response()->json(['message' => 'Silakan lengkapi alamat dan nomor HP Anda terlebih dahulu.'], 400);
        }

        // 2. Validasi Request
        $request->validate([
            'kurir' => 'required|string|in:jne,pos,tiki',
            'destination' => 'required|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ongkir_value' => 'required|integer|min:0',
            'layanan_selected_name' => 'required|string',
        ]);
        
        try {
            $result = DB::transaction(function () use ($request, $user, $isReseller, $whatsapp) {
                
                $cartItems = Cart::with('product')
                    ->where('user_id', $user->id)
                    ->get();

                if ($cartItems->isEmpty()) {
                    return response()->json(['message' => 'Keranjang kosong'], 400);
                }

                // --- PERUBAHAN DIMULAI: VALIDASI HARGA & STOK ---
                $totalProduk = 0; // Pindahkan inisialisasi ke sini

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    
                    if (!$product) {
                         return response()->json(['message' => 'Produk ' . ($cartItem->product_id ?? 'N/A') . ' tidak ditemukan.'], 404);
                    }
                    
                    $hargaSatuan = 0;

                    if ($isReseller) {
                        // Cek aturan Reseller
                        if ($cartItem->quantity < 5) {
                            return response()->json(['message' => "Minimal pembelian reseller 5 item untuk '{$product->nama}'."], 422);
                        }
                        if ($product->stok <= 5) {
                             return response()->json(['message' => "Stok '{$product->nama}' tidak tersedia untuk reseller."], 422);
                        }
                        $hargaSatuan = $product->harga_reseller;
                    } else {
                        // Cek aturan Pelanggan
                        if ($product->stok <= 0) {
                             return response()->json(['message' => "Stok '{$product->nama}' habis."], 422);
                        }
                        $hargaSatuan = $product->harga_normal;
                    }

                    // Cek stok umum
                    if ($product->stok < $cartItem->quantity) {
                        return response()->json(['message' => "Stok '{$product->nama}' tidak mencukupi (sisa {$product->stok})."], 422);
                    }
                    
                    // Akumulasi total produk berdasarkan harga yang BENAR
                    $totalProduk += ($hargaSatuan * $cartItem->quantity);
                }
                // --- AKHIR PERUBAHAN ---


                // --- LANGKAH 2: HITUNG TOTAL ---
                // (LOGIKA LAMA DIHAPUS, KARENA SUDAH DIHITUNG DI ATAS)
                // $totalProduk = $cartItems->sum(function ($item) { ... }); // <-- HAPUS

                $biayaLayanan = 2000;
                $ongkir = $request->ongkir_value;
                $total = $totalProduk + $ongkir + $biayaLayanan; // Total ini sekarang benar

                // --- LANGKAH 3: BUAT ORDER ---
                $order = Order::create([
                    'user_id' => $user->id,
                    'tanggal_pesan' => now(),
                    'total' => $totalProduk, // <-- Total produk sudah benar
                    'ongkir' => $ongkir,
                    'biaya_layanan' => $biayaLayanan,
                    'total_bayar' => $total,
                    'status' => Order::STATUS_MENUNGGU_VERIFIKASI,
                    'alamat_pengiriman' => $user->alamat,
                    'kurir' => $request->kurir,
                    'layanan_kurir' => $request->layanan_selected_name,
                    'kota_tujuan' => $request->destination,
                ]);

                // --- LANGKAH 4: SIMPAN ORDER ITEM & KURANGI STOK ---
                foreach ($cartItems as $cartItem) {
                    
                    // --- PERUBAHAN DIMULAI: AMBIL HARGA YANG BENAR ---
                    $hargaSatuan = $isReseller ? $cartItem->product->harga_reseller : $cartItem->product->harga_normal;
                    // --- AKHIR PERUBAHAN ---

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'jumlah' => $cartItem->quantity,
                        'harga_satuan' => $hargaSatuan, // <-- DIUBAH
                        'subtotal' => $hargaSatuan * $cartItem->quantity, // <-- DIUBAH
                    ]);
                    
                    $product = $cartItem->product;
                    $product->stok -= $cartItem->quantity;
                    $product->save();
                }

                // --- LANGKAH 5: UPLOAD BUKTI TRANSFER ---
                $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');
                Payment::create([
                    'order_id' => $order->id,
                    'bukti_transfer' => $buktiPath,
                    'status_verifikasi' => Payment::STATUS_BELUM_DIVERIFIKASI,
                    'metode_pembayaran' => 'transfer',
                ]);

                // --- LANGKAH 6: BERSIHKAN KERANJANG ---
                Cart::where('user_id', $user->id)->delete();

                // --- LANGKAH 7: KIRIM NOTIFIKASI ADMIN ---
                try {
                    $admins = User::where('role', 'admin')->get(); 
                    Notification::send($admins, new NewOrderNotification($order));
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim notifikasi database ke admin: ' . $e->getMessage());
                }
                
                // --- LANGKAH 8: KIRIM WA ---
                try {
                    $buktiUrl = asset('storage/' . $buktiPath);
                    $message = "💸 *Pesanan Baru Masuk!*\n\n"
                        . "👤 *Nama:* " . $user->nama . "\n"
                        . "📞 *No HP:* " . ($user->no_hp ?? '-') . "\n"
                        . "🚚 *Kurir:* " . strtoupper($request->kurir) . " ({$request->layanan_selected_name})\n"
                        . "🏠 *Alamat:* {$order->alamat_pengiriman}\n"
                        . "💰 *Total Produk:* Rp " . number_format($totalProduk, 0, ',', '.') . "\n" // <-- Total sudah benar
                        . "🚛 *Ongkir:* Rp " . number_format($ongkir, 0, ',', '.') . "\n"
                        . "💳 *Biaya Layanan:* Rp " . number_format($biayaLayanan, 0, ',', '.') . "\n"
                        . "💵 *Total Bayar:* Rp " . number_format($total, 0, ',', '.') . "\n\n"
                        . "📎 *Bukti Transfer:* (lihat gambar di bawah)";
                    
                    $whatsapp->sendToAdmin($message, $buktiUrl); 
                } catch (\Exception $e) {
                    \Log::error('Gagal kirim notifikasi WA ke admin: ' . $e->getMessage());
                }

                return response()->json([
                    'message' => '✅ Pesanan berhasil dibuat & bukti transfer dikirim ke admin.',
                    'order_id' => $order->id,
                ], 200);
            });

            return $result;

        } catch (\Exception $e) {
            \Log::error('Checkout Gagal Total: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi error internal: ' . $e->getMessage()
            ], 500);
        }
    }
}