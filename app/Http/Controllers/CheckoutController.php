<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Services\RajaOngkirService;
use App\Services\WhatsAppService;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Validasi Profil
        if (empty($user->alamat) || empty($user->no_hp)) {
            return redirect()->route('profil')
                ->with('error', 'Silakan lengkapi alamat dan nomor HP Anda terlebih dahulu sebelum checkout.');
        }

        $isReseller = ($user->role == 'reseller');
        
        // 2. Ambil Cart dari Database
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('katalog')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // 3. Hitung Total Harga & Berat
        $total = 0;
        $totalWeight = 0;

        foreach ($cartItems as $item) {
            if (!$item->product) continue;

            $price = $isReseller ? $item->product->harga_reseller : $item->product->harga_normal;
            
            $total += $price * $item->quantity;
            
            // Asumsi berat 200g per item
            $totalWeight += $item->quantity * 200;
        }

        // Minimal berat 200g
        if ($totalWeight < 200) $totalWeight = 200;

        // --- PERBAIKAN: DEFINISIKAN ADMIN FEE ---
        $adminFee = 2000;

        // Kirim ke View (tambahkan 'adminFee')
        return view('checkout.index', compact('cartItems', 'total', 'totalWeight', 'isReseller', 'adminFee'));
    }

    /**
     * Memproses Checkout
     */
    public function storeFullCheckout(Request $request, RajaOngkirService $rajaOngkir, WhatsAppService $whatsapp)
    {
        $user = Auth::user();
        $isReseller = ($user->role == 'reseller');

        // 1. Validasi Input
        $request->validate([
            'kurir' => 'required|string|in:jne,pos,tiki',
            'destination' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ongkir_value' => 'required|numeric|min:0',
            'layanan_selected_name' => 'required|string',
        ]);

        if (empty($user->alamat) || empty($user->no_hp)) {
            return response()->json(['message' => 'Lengkapi profil alamat & HP dahulu.'], 400);
        }

        try {
            $result = DB::transaction(function () use ($request, $user, $isReseller, $whatsapp) {
                
                // 2. Cek Keranjang
                $cartItems = Cart::with('product')
                    ->where('user_id', $user->id)
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Keranjang belanja kosong saat diproses.');
                }

                // 3. Hitung Ulang Total & Cek Stok
                $totalProduk = 0;

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    
                    if (!$product) {
                        throw new \Exception('Salah satu produk tidak ditemukan.');
                    }

                    if ($product->stok < $cartItem->quantity) {
                        throw new \Exception("Stok '{$product->nama_produk}' tidak mencukupi (Sisa: {$product->stok}).");
                    }

                    $hargaSatuan = $isReseller ? $product->harga_reseller : $product->harga_normal;

                    if ($isReseller && $cartItem->quantity < 5) {
                        throw new \Exception("Reseller wajib beli minimal 5 item untuk '{$product->nama_produk}'.");
                    }

                    $totalProduk += ($hargaSatuan * $cartItem->quantity);
                }

                // 4. Kalkulasi Akhir
                $biayaLayanan = 2000; // Pastikan ini sama dengan di index()
                $ongkir = $request->ongkir_value;
                $totalBayar = $totalProduk + $ongkir + $biayaLayanan;

                // 5. Upload Bukti
                $buktiPath = null;
                if ($request->hasFile('bukti_transfer')) {
                    $buktiPath = $request->file('bukti_transfer')->store('payments', 'public');
                }

                // 6. Simpan Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'nama_penerima' => $user->nama,
                    'no_hp' => $user->no_hp,
                    'alamat_pengiriman' => $user->alamat,
                    'tanggal_pesan' => now(),
                    'total' => $totalProduk,
                    'ongkir' => $ongkir,
                    'biaya_layanan' => $biayaLayanan,
                    'total_harga' => $totalBayar,
                    'status' => 'menunggu_verifikasi',
                    'kurir' => $request->kurir,
                    'layanan_kurir' => $request->layanan_selected_name,
                    'kota_tujuan' => $request->destination,
                    'metode_pembayaran' => 'transfer',
                    'bukti_pembayaran' => $buktiPath,
                ]);

                // 7. Simpan Order Items & Kurangi Stok
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    $hargaSatuan = $isReseller ? $product->harga_reseller : $product->harga_normal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'jumlah' => $cartItem->quantity,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $hargaSatuan * $cartItem->quantity,
                    ]);

                    $product->decrement('stok', $cartItem->quantity);
                }

                // 8. Simpan Payment
                if ($buktiPath) {
                    Payment::create([
                        'order_id' => $order->id,
                        'bukti_transfer' => $buktiPath,
                        'status_verifikasi' => 'menunggu_verifikasi',
                        'metode_pembayaran' => 'transfer',
                        'jumlah_bayar' => $totalBayar
                    ]);
                }

                // 9. Hapus Keranjang
                Cart::where('user_id', $user->id)->delete();
                session(['cart_count' => 0]);

                return $order;
            });

            // --- NOTIFIKASI ---
            $order = $result;

            try {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new NewOrderNotification($order));
            } catch (\Exception $e) {
                \Log::error('Notif DB Gagal: ' . $e->getMessage());
            }

            try {
                if ($whatsapp && isset($order->bukti_pembayaran)) {
                    $buktiUrl = asset('storage/' . $order->bukti_pembayaran);
                    $msg = "💸 *Pesanan Baru #{$order->id}*\n" .
                           "👤 {$user->nama}\n" .
                           "💰 Total: Rp " . number_format($order->total_harga, 0, ',', '.') . "\n" .
                           "Login admin untuk cek detail.";
                    $whatsapp->sendToAdmin($msg, $buktiUrl);
                }
            } catch (\Exception $e) {
                \Log::error('Notif WA Gagal: ' . $e->getMessage());
            }

            return response()->json([
                'message' => '✅ Pesanan berhasil dibuat!',
                'order_id' => $order->id
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}