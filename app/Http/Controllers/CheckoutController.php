<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\RajaOngkirService;
use App\Services\WhatsAppService;

class CheckoutController extends Controller
{

    public function index()
{
    $userId = auth()->id();

    // Ambil cart user
    $cartItems = \App\Models\Cart::with('product')
        ->where('user_id', $userId)
        ->get();

    $total = $cartItems->sum(function ($item) {
        return $item->product->harga_normal * $item->quantity;
    });

    return view('checkout.index', compact('cartItems', 'total'));
}



    public function storeFullCheckout(Request $request, RajaOngkirService $rajaOngkir, WhatsAppService $whatsapp)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
            'kurir' => 'required|string',
            'destination' => 'required|string', // ID kota tujuan dari RajaOngkir
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil cart dari session
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        // Hitung total produk
        $totalProduk = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

        // Hitung ongkir dari RajaOngkir
        try {
            $ongkirData = $rajaOngkir->cekOngkir(
                origin: '501', // Bogor (bisa disesuaikan nanti)
                destination: $request->destination,
                weight: 1000,
                courier: $request->kurir
            );
            $ongkir = $ongkirData['cost'] ?? 10000; // fallback 10rb kalau gagal
        } catch (\Exception $e) {
            $ongkir = 10000;
            \Log::warning('Gagal hitung ongkir: ' . $e->getMessage());
        }

        // Buat order
        $order = Order::create([
            'user_id' => Auth::id(),
            'tanggal_pesan' => now(),
            'total' => $totalProduk,
            'ongkir' => $ongkir,
            'status' => Order::STATUS_MENUNGGU_VERIFIKASI,
            'alamat_pengiriman' => $request->alamat_pengiriman,
        ]);

        // Simpan item order
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'jumlah' => $item['quantity'],
                'harga_satuan' => $item['harga'],
            ]);
        }

        // Upload bukti transfer
        $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');

        Payment::create([
            'order_id' => $order->id,
            'bukti_transfer' => $buktiPath,
            'status_verifikasi' => Payment::STATUS_BELUM_DIVERIFIKASI,
        ]);

        // Bersihkan keranjang
        session()->forget('cart');

        // Kirim notifikasi WA ke admin
        try {
            $buktiUrl = asset('storage/' . $buktiPath);
            $message = "💸 *Pesanan Baru Masuk!*\n\n"
                . "👤 *Nama:* {$order->user->name}\n"
                . "🚚 *Kurir:* {$request->kurir}\n"
                . "🏠 *Alamat:* {$order->alamat_pengiriman}\n"
                . "💰 *Total:* Rp" . number_format($order->total + $order->ongkir, 0, ',', '.') . "\n\n"
                . "📎 *Bukti Transfer:* (lihat gambar di bawah)";

            $whatsapp->sendImageToAdmin($buktiUrl, $message);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi WA ke admin: ' . $e->getMessage());
        }

        return response()->json([
            'message' => '✅ Pesanan berhasil dibuat & bukti transfer dikirim ke admin.',
            'order' => $order->load('orderItems.product'),
        ]);
    }
}
