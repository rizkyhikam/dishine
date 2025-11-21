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
use App\Services\RajaOngkirService; 
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
        if (empty($user->no_hp)) {
            return redirect()->route('profil')
                ->with('error', 'Silakan lengkapi nomor HP Anda terlebih dahulu di menu Profil sebelum checkout.');
        }

        $isReseller = ($user->role == 'reseller');
        
        // 2. Ambil Cart dari Database (DENGAN VARIAN & SIZE)
        // ==================================================
        $cartItems = Cart::with([
                'product',
                'variantSize.productVariant', // Load Data Warna
                'variantSize.size'            // Load Data Ukuran
            ])
            ->where('user_id', $user->id)
            ->get();
        // ==================================================

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
            
            // Asumsi berat 200g per item jika tidak ada data berat di DB Product
            $itemWeight = $item->product->berat ?? 200; 
            $totalWeight += $item->quantity * $itemWeight;
        }

        // Minimal berat 1 gram agar API tidak error
        if ($totalWeight < 1) $totalWeight = 1;

        // Admin Fee
        $adminFee = 2000;

        // 4. Ambil Data Provinsi (Untuk Dropdown di View)
        $provinces = [];
        try {
            $provinces = $rajaOngkir->getProvinces();
        } catch (\Exception $e) {
            \Log::error("Checkout - Gagal ambil provinsi: " . $e->getMessage());
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
            'destination' => 'required', // ID Kota tujuan (Ongkir)
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ongkir_value' => 'required|numeric|min:0',
            'layanan_selected_name' => 'required|string',
            
            // Field optional (diisi jika alamat baru/ubah alamat)
            'detail_alamat' => 'nullable|string',
            'province_id'   => 'nullable',
            'city_id'       => 'nullable',
            'provinsi_name' => 'nullable|string', 
            'kota_name'     => 'nullable|string',
        ]);

        // --- LOGIKA ALAMAT & USER UPDATE ---
        $alamatFinal = $user->alamat;
        $cityIdFinal = $request->destination; // Default ambil dari pilihan ongkir saat ini

        // Cek apakah User mengisi form alamat baru?
        // Indikasinya: field 'detail_alamat' dikirim dari frontend
        if ($request->detail_alamat && $request->province_id && $request->city_id) {
            
            // 1. Buat String Alamat Lengkap
            $alamatBaru = $request->detail_alamat . ", " . $request->kota_name . ", " . $request->provinsi_name;
            
            // 2. Set variabel untuk disimpan di Order
            $alamatFinal = $alamatBaru;
            $cityIdFinal = $request->city_id;

            // 3. UPDATE USER DATABASE (PENTING!)
            // Simpan alamat teks, province_id, dan city_id agar besok otomatis terisi
            $user->update([
                'alamat'      => $alamatBaru,
                'province_id' => $request->province_id,
                'city_id'     => $request->city_id
            ]);
        
        } elseif (empty($alamatFinal)) {
            // Jika di DB kosong TAPI user tidak isi form -> Error
            return response()->json(['message' => 'Mohon lengkapi alamat pengiriman.'], 400);
        }
        // --- END LOGIKA ALAMAT ---

        try {
            $result = DB::transaction(function () use ($request, $user, $isReseller, $whatsapp, $alamatFinal, $cityIdFinal) {
                
                // 2. Cek Keranjang
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
                    
                    if (!$product) throw new \Exception('Produk tidak ditemukan.');

                    // Cek Stok
                    if ($product->stok < $cartItem->quantity) {
                        throw new \Exception("Stok '{$product->nama_produk}' habis.");
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
                    'alamat_pengiriman' => $alamatFinal, // Alamat Teks Lengkap
                    'kota_tujuan' => $cityIdFinal,       // ID Kota (penting untuk history ongkir)
                    'tanggal_pesan' => now(),
                    'total' => $totalProduk,
                    'ongkir' => $ongkir,
                    'biaya_layanan' => $biayaLayanan,
                    'total_harga' => $totalBayar,
                    'status' => 'menunggu_verifikasi',
                    'kurir' => $request->kurir,
                    'layanan_kurir' => $request->layanan_selected_name,
                    'metode_pembayaran' => 'transfer',
                    'bukti_pembayaran' => $buktiPath,
                ]);

                // 7. Simpan Order Items & Kurangi Stok
                // 7. Simpan Order Items & Kurangi Stok
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product;
                    $hargaSatuan = $isReseller ? $product->harga_reseller : $product->harga_normal;

                    // --- LOGIKA MENGAMBIL DETAIL VARIAN ---
                    $varianString = null;
                    
                    // Cek apakah item keranjang punya relasi variantSize
                    if ($cartItem->variantSize) {
                        $parts = [];
                        
                        // Ambil Warna
                        if ($cartItem->variantSize->productVariant && $cartItem->variantSize->productVariant->warna) {
                            $parts[] = "Warna: " . $cartItem->variantSize->productVariant->warna;
                        }
                        
                        // Ambil Size
                        if ($cartItem->variantSize->size && $cartItem->variantSize->size->name) {
                            $parts[] = "Size: " . $cartItem->variantSize->size->name;
                        }
                        
                        // Gabungkan jadi string (Contoh: "Warna: Merah, Size: L")
                        if (!empty($parts)) {
                            $varianString = implode(', ', $parts);
                        }
                    }
                    // -------------------------------------

                    OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => $cartItem->product_id,
                        'deskripsi_varian' => $varianString, // <--- SIMPAN DI SINI
                        'jumlah'         => $cartItem->quantity,
                        'harga_satuan'   => $hargaSatuan,
                        'subtotal'       => $hargaSatuan * $cartItem->quantity,
                    ]);

                    // Kurangi Stok
                    $product->decrement('stok', $cartItem->quantity);
                    
                    // Opsional: Jika Anda ingin mengurangi stok spesifik per varian juga
                    if ($cartItem->variantSize) {
                        $cartItem->variantSize->decrement('stok', $cartItem->quantity);
                    }
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

            // Notif DB
            try {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new NewOrderNotification($order));
            } catch (\Exception $e) {
                \Log::error('Notif DB Gagal: ' . $e->getMessage());
            }

            // Notif WA
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