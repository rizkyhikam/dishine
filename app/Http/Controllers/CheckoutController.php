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
        // Validasi data profil
        if (empty(Auth::user()->alamat) || empty(Auth::user()->no_hp)) {
            return response()->json([
                'message' => 'Silakan lengkapi alamat dan nomor HP Anda terlebih dahulu.'
            ], 400);
        }

        $request->validate([
            'alamat_pengiriman' => 'required|string|max:500',
            'kurir' => 'required|string|in:jne,pos,tiki',
            'destination' => 'required|string',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ongkir_value' => 'required|integer|min:0',
            'layanan_selected_name' => 'required|string',
        ]);
        
        return DB::transaction(function () use ($request, $rajaOngkir, $whatsapp) {
            // Ambil cart dari database
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Keranjang kosong'], 400);
            }

            // Hitung total produk
            $totalProduk = $cartItems->sum(function ($item) {
                return $item->product->harga_normal * $item->quantity;
            });

            $biayaLayanan = 2000;
            $ongkir = $request->ongkir_value;
            $total = $totalProduk + $ongkir + $biayaLayanan;

            // Buat order
            $order = Order::create([
                'user_id' => Auth::id(),
                'tanggal_pesan' => now(),
                'total' => $totalProduk,
                'ongkir' => $ongkir,
                'biaya_layanan' => $biayaLayanan,
                'total_bayar' => $total,
                'status' => Order::STATUS_MENUNGGU_VERIFIKASI,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'kurir' => $request->kurir,
                'layanan_kurir' => $request->layanan_selected_name,
                'kota_tujuan' => $request->destination,
            ]);

            // Simpan item order
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'jumlah' => $cartItem->quantity,
                    'harga_satuan' => $cartItem->product->harga_normal,
                    'subtotal' => $cartItem->product->harga_normal * $cartItem->quantity,
                ]);
            }

            // Upload bukti transfer
            $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');

            Payment::create([
                'order_id' => $order->id,
                'bukti_transfer' => $buktiPath,
                'status_verifikasi' => Payment::STATUS_BELUM_DIVERIFIKASI,
                'metode_pembayaran' => 'transfer',
            ]);

            // Bersihkan keranjang
            Cart::where('user_id', Auth::id())->delete();

            // Kirim notifikasi WA ke admin
            try {
                $buktiUrl = asset('storage/' . $buktiPath);
                $message = "💸 *Pesanan Baru Masuk!*\n\n"
                    . "👤 *Nama:* " . Auth::user()->nama . "\n"
                    . "📞 *No HP:* " . (Auth::user()->no_hp ?? '-') . "\n"
                    . "🚚 *Kurir:* " . strtoupper($request->kurir) . " ({$request->layanan_selected_name})\n"
                    . "🏠 *Alamat:* {$order->alamat_pengiriman}\n"
                    . "💰 *Total Produk:* Rp " . number_format($totalProduk, 0, ',', '.') . "\n"
                    . "🚛 *Ongkir:* Rp " . number_format($ongkir, 0, ',', '.') . "\n"
                    . "💳 *Biaya Layanan:* Rp " . number_format($biayaLayanan, 0, ',', '.') . "\n"
                    . "💵 *Total Bayar:* Rp " . number_format($total, 0, ',', '.') . "\n\n"
                    . "📎 *Bukti Transfer:* (lihat gambar di bawah)";

                $whatsapp->sendImageToAdmin($buktiUrl, $message);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi WA ke admin: ' . $e->getMessage());
            }

            return response()->json([
                'message' => '✅ Pesanan berhasil dibuat & bukti transfer dikirim ke admin.',
                'order_id' => $order->id,
            ], 200);
        });
    }
}