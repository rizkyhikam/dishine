<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\RajaOngkirService;
use App\Services\WhatsAppService;

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

    public function storeOrder(Request $request, RajaOngkirService $rajaOngkir)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
            'kota_tujuan' => 'required|string',
            'kurir' => 'required|string|in:jne,tiki,pos',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        // Hitung total berat (misal: 200 gram per item)
        $totalBerat = 0;
        foreach ($cart as $item) {
            $totalBerat += 200 * $item['quantity'];
        }

        // Hitung ongkir via RajaOngkir
        $origin = env('RAJAONGKIR_ORIGIN');
        $destination = $request->kota_tujuan;
        $kurir = $request->kurir;

        $ongkirResponse = $rajaOngkir->calculateOngkir($origin, $destination, $totalBerat, $kurir);

        if (isset($ongkirResponse['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'])) {
            $ongkir = $ongkirResponse['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
        } else {
            $ongkir = 10000; // fallback
        }

        // Hitung total harga barang
        $totalBarang = collect($cart)->sum(fn($i) => $i['harga'] * $i['quantity']);

        $order = Order::create([
            'user_id' => Auth::id(),
            'tanggal_pesan' => now(),
            'total' => $totalBarang,
            'ongkir' => $ongkir,
            'status' => 'pending',
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

        session()->forget('cart');

        return response()->json([
            'message' => 'Pesanan berhasil dibuat. Silakan upload bukti transfer untuk konfirmasi.',
            'ongkir' => $ongkir,
            'order' => $order->load('orderItems.product'),
        ], 201);
    }

    public function viewOrders()
    {
        // Ambil semua pesanan milik user yang sedang login
        // 'with('orderItems.product')' = Ambil juga detail item & produknya
        // 'latest()' = Urutkan dari yang paling baru
        $orders = Order::where('user_id', Auth::id())
                        ->with('orderItems.product')
                        ->latest()
                        ->get();
        
        // Ganti dari 'return $orders;' (JSON) menjadi 'return view(...)' (Halaman HTML)
        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan daftar pesanan untuk Halaman Admin (Manajemen Pesanan).
     * Ini adalah method yang digunakan oleh route 'admin.orders'.
     */
    public function adminIndex(Request $request)
    {
        // Ambil filter dari request
        $filters = $request->only(['search_nama', 'tanggal_mulai', 'tanggal_selesai']);

        // Mulai query dengan eager loading relasi yang dibutuhkan
        $query = Order::with(['user', 'orderItems'])
                       ->latest('tanggal_pesan'); // Urutkan berdasarkan tanggal pesan terbaru secara default

        // Filter berdasarkan Nama Pelanggan (FIX: tanpa join, gunakan whereHas)
        if ($filters['search_nama'] ?? false) {
            $search = $filters['search_nama'];
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan Tanggal Mulai
        if ($filters['tanggal_mulai'] ?? false) {
            $query->whereDate('tanggal_pesan', '>=', $filters['tanggal_mulai']);
        }

        // Filter berdasarkan Tanggal Selesai
        if ($filters['tanggal_selesai'] ?? false) {
            $query->whereDate('tanggal_pesan', '<=', $filters['tanggal_selesai']);
        }

        // PERBAIKAN: Gunakan paginate() untuk mendapatkan LengthAwarePaginator
        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders', compact('orders', 'filters'));
    }

    /**
     * Menampilkan halaman DETAIL SATU pesanan
     */
    public function showOrder($id)
    {
        // Ambil 1 pesanan, TAPI pastikan itu milik user yang sedang login
        $order = Order::with(['orderItems.product', 'payment'])
                    ->where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail(); // Jika bukan miliknya, akan 404

        return view('orders.show', compact('order'));
    }

    /**
     * Upload bukti transfer
     * Setelah upload -> ubah status + kirim notifikasi WA ke admin
     */
    public function uploadProof(Request $request, $id, WhatsAppService $wa)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Simpan file bukti
        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        $order->bukti_transfer = $path;
        $order->status = 'menunggu_verifikasi';
        $order->save();

        // Kirim notifikasi WA ke admin
        try {
            $adminNumber = env('ADMIN_WA');
            $user = Auth::user();

            $message = "💸 *Bukti Transfer Baru Diterima!*\n\n" .
                        "Nama: {$user->name}\n" .
                        "Email: {$user->email}\n" .
                        "ID Pesanan: #{$order->id}\n" .
                        "Total: Rp " . number_format($order->total + $order->ongkir, 0, ',', '.') . "\n" .
                        "Status: Menunggu Verifikasi\n\n" .
                        "Silakan periksa dashboard admin untuk memverifikasi pembayaran.";

            $wa->sendMessage($adminNumber, $message);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi WA: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Bukti transfer berhasil diunggah. Admin akan memverifikasi pembayaran Anda.',
            'order' => $order,
        ]);
    }
}