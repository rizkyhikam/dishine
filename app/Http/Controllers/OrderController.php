<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }
        return response()->json(['cart' => $cart], 200);
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        // Ongkir dummy Rp10.000
        $ongkir = 10000;

        $order = Order::create([
            'user_id' => Auth::id(),
            'tanggal_pesan' => now()->toDateString(),
            'total' => $total,
            'ongkir' => $ongkir,
            'status' => Order::STATUS_BARU, // Status default "baru"
            'alamat_pengiriman' => $request->alamat_pengiriman,
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'jumlah' => $item['quantity'],
                'harga_satuan' => $item['harga'],
            ]);
        }

        // Bersihkan keranjang
        session()->forget('cart');

        // Kirim notifikasi ke admin via WA (nanti disambung ke API)
        try {
            $this->sendWhatsAppNotification("Pesanan baru dari " . Auth::user()->nama . " senilai Rp" . number_format($total + $ongkir, 0, ',', '.'));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi WA: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Pesanan berhasil dibuat',
            'order' => $order->load('orderItems.product'),
            'cart_cleared' => true
        ], 201);
    }

    public function viewOrders()
    {
        $orders = Auth::user()->orders()->with('orderItems.product')->get();
        return response()->json(['orders' => $orders], 200);
    }

    private function sendWhatsAppNotification($message)
    {
        // TODO: Nanti kita sambungkan ke API (Fonnte / Wablas)
    }
}
    