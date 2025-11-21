<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\User;
use App\Services\RajaOngkirService; // Pastikan namespace ini sesuai dengan file Service teman Anda
use App\Services\WhatsAppService;
use App\Notifications\NewOrderNotification;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout
     */
    public function index(RajaOngkirService $rajaOngkir)
    {
        $user = Auth::user();

        // 1. Validasi Profil Dasar (Hanya No HP)
        // Alamat dihapus dari sini karena akan diisi di form checkout jika kosong
        if (empty($user->no_hp)) {
            return redirect()->route('profil')
                ->with('error', 'Silakan lengkapi nomor HP Anda terlebih dahulu di menu Profil sebelum checkout.');
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
            
            // Asumsi berat 200g per item jika tidak ada data berat
            // Jika ada kolom berat di database product, ganti jadi $item->product->berat
            $itemWeight = $item->product->berat ?? 200; 
            $totalWeight += $item->quantity * $itemWeight;
        }

        // Minimal berat 1kg (1000g) aturan ekspedisi biasanya, atau biarkan sesuai real
        // Kita set minimal 1 gram agar tidak error di API
        if ($totalWeight < 1) $totalWeight = 1;

        // Admin Fee
        $adminFee = 2000;

        // 4. Ambil Data Provinsi (Integrasi dengan Service Teman Anda)
        $provinces = [];
        try {
            // Service teman Anda mengembalikan array langsung
            $provinces = $rajaOngkir->getProvinces();
        } catch (\Exception $e) {
            \Log::error("Checkout - Gagal ambil provinsi: " . $e->getMessage());
            // Tetap lanjut agar halaman tidak error, meski dropdown kosong
        }

        // Kirim ke View
        return view('checkout.index', compact('cartItems', 'total', 'totalWeight', 'isReseller', 'adminFee', 'provinces', 'user'));
    }

    /**
     * Memproses Checkout (Simpan Order)
     */
    public function storeFullCheckout(Request $request, RajaOngkirService $rajaOngkir, WhatsAppService $whatsapp)
    {
        $user = Auth::user();
        $isReseller = ($user->role == 'reseller');

        // 1. Validasi Input
        $request->validate([
            'kurir' => 'required|string|in:jne,pos,tiki',
            'destination' => 'required', // Ini adalah ID Kota tujuan
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ongkir_value' => 'required|numeric|min:0',
            'layanan_selected_name' => 'required|string',
            
            // Field optional untuk alamat baru
            'detail_alamat' => 'nullable|string',
            'provinsi_name' => 'nullable|string', 
            'kota_name'     => 'nullable|string',
        ]);

        // --- LOGIKA PENYIMPANAN ALAMAT ---
        // Jika user belum punya alamat, atau user ingin mengubah alamat lewat form ini
        // Kita prioritaskan alamat yang ada di database, KECUALI database kosong.
        
        $alamatFinal = $user->alamat; // Default ambil dari DB

        if (empty($alamatFinal)) {
            // Jika di DB kosong, wajib ambil dari input form
            if ($request->detail_alamat && $request->provinsi_name && $request->kota_name) {
                
                // Format Alamat: "Jl. Mawar No 5, Kota Bandung, Jawa Barat"
                $alamatBaru = $request->detail_alamat . ", " . $request->kota_name . ", " . $request->provinsi_name;
                
                // Update variabel lokal untuk disimpan di Order
                $alamatFinal = $alamatBaru;

                // Update ke Database User (Permanent) agar user tidak perlu isi lagi next time
                // Juga simpan city_id agar next time ongkir otomatis terhitung
                $user->update([
                    'alamat' => $alamatBaru,
                    'city_id' => $request->destination // Simpan ID kota untuk RajaOngkir
                ]);
            } else {
                // Jika DB kosong DAN form tidak lengkap
                return response()->json(['message' => 'Mohon lengkapi detail alamat (Jalan, Provinsi, Kota).'], 400);
            }
        }
        // --- END LOGIKA ALAMAT ---

        try {
            $result = DB::transaction(function () use ($request, $user, $isReseller, $whatsapp, $alamatFinal) {
                
                // 2. Cek Keranjang (Validasi Ulang)
                $cartItems = Cart::with('product')
                    ->where('user_id', $user->id)
                    ->get();

                if ($cartItems->isEmpty()) {
                    throw new \Exception('Keranjang belanja kosong.');
                }

                // 3. Hitung Ulang Total & Cek Stok
                $totalProduk = 0;

                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    
                    if (!$product) throw new \Exception('Salah satu produk tidak ditemukan.');

                    // Cek Stok
                    if ($product->stok < $cartItem->quantity) {
                        throw new \Exception("Stok '{$product->nama_produk}' habis / tidak cukup.");
                    }

                    $hargaSatuan = $isReseller ? $product->harga_reseller : $product->harga_normal;

                    // Cek Syarat Reseller
                    if ($isReseller && $cartItem->quantity < 5) {
                        throw new \Exception("Reseller wajib beli minimal 5 item per produk.");
                    }

                    $totalProduk += ($hargaSatuan * $cartItem->quantity);
                }

                // 4. Kalkulasi Akhir
                $biayaLayanan = 2000; 
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
                    'alamat_pengiriman' => $alamatFinal, // Gunakan alamat yang sudah dipastikan ada
                    'tanggal_pesan' => now(),
                    'total' => $totalProduk, // Subtotal barang
                    'ongkir' => $ongkir,
                    'biaya_layanan' => $biayaLayanan,
                    'total_harga' => $totalBayar, // Grand Total
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

                    // Kurangi Stok
                    $product->decrement('stok', $cartItem->quantity);
                }

                // 8. Simpan Payment (Opsional, jika tabel payment dipisah)
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

            // --- NOTIFIKASI (Diluar Transaksi DB) ---
            $order = $result;

            // Notif ke Admin (Database)
            try {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new NewOrderNotification($order));
            } catch (\Exception $e) {
                \Log::error('Notif DB Gagal: ' . $e->getMessage());
            }

            // Notif ke Admin (WhatsApp)
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