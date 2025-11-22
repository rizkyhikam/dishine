<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Faq;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use App\Models\VariantSize;
use App\Models\DefaultProductSize;



class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    public function dashboard()
    {
        // 1. Hitung Total Pendapatan (hanya dari pesanan 'selesai')
        // PERHATIAN: Pastikan kolomnya 'total' atau 'total_harga' sesuai model Order Anda.
        $totalPendapatan = Order::where('status', 'selesai')->sum('total'); 

        // 2. Hitung Pesanan Baru (yang perlu diproses)
        $pesananBaru = Order::whereIn('status', ['menunggu_verifikasi', 'diproses'])->count();

        // 3. Hitung Total Produk
        $totalProduk = Product::count();

        // 4. Hitung Total Pelanggan (kecuali admin)
        $totalPelanggan = User::where('role', '!=', 'admin')->count();

        // 5. Ambil 5 Pesanan Terbaru (untuk tabel)
        // Catatan: Asumsi Anda punya konstanta di model Order: Order::STATUS_MENUNGGU_VERIFIKASI
        $pesananTerbaru = Order::with('user') // Ambil relasi user untuk nama
                             ->where('status', 'menunggu_verifikasi') // Ganti dengan string jika konstanta tidak didefinisikan
                             ->latest()      // Urutkan dari yg terbaru
                             ->get();      // Ambil SEMUA yang menunggu

        // 6. Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalPendapatan', 
            'pesananBaru', 
            'totalProduk', 
            'totalPelanggan', 
            'pesananTerbaru'
        ));
    }

    // ---------------------------------------------------------
    // CRUD PRODUK
    // ---------------------------------------------------------
    /**
     * Menampilkan halaman Manajemen Produk
     * PERBAIKAN: Mengganti ->get() menjadi ->paginate() untuk mengatasi error 'total does not exist'
     */
    public function manageProducts(Request $request) // <-- Tambahkan Request $request
    {
        // 1. Mulai query dasar
        $query = Product::with('category');

        // 2. Terapkan Filter NAMA PRODUK (jika ada)
        if ($request->filled('search_nama')) {
            // Gunakan 'products.nama' agar lebih spesifik
            $query->where('products.nama', 'LIKE', '%' . $request->search_nama . '%');
        }

        // 3. Ambil data DENGAN PAGINASI
        $products = $query->paginate(15)->withQueryString(); // <-- INI PERUBAHANNYA
        $categories = Category::all(); // <-- Tetap ambil kategori untuk form "Tambah"
        
        // 4. Kirim data ke view
        return view('admin.products', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search_nama']) // Kirim filter untuk mengisi ulang search bar
        ]);
    }

    public function storeProduct(Request $request)
    {
        // 1. VALIDASI REQUEST
        $request->validate([
            'nama'            => 'required|string|max:255',
            'harga_normal'    => 'required|numeric',
            'harga_reseller'  => 'required|numeric',
            'deskripsi'       => 'required|string',
            'category_id'     => 'required|exists:categories,id',
            'gambar'          => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            // Kita validasi string JSON-nya saja dulu
            'variants'        => 'nullable|string',
            'default_sizes'   => 'nullable|string',
        ]);
    
        // 2. DEBUGGING (PENTING!)
        // Hapus komentar di bawah ini untuk melihat data mentah yang dikirim browser.
        // $variantsData = json_decode($request->variants, true);
        // dd($variantsData); 
    
    
        DB::beginTransaction(); // Pakai transaksi agar aman
        try {
            // 3. UPLOAD GAMBAR
            $gambarPath = $request->file('gambar')->store('products', 'public');
    
            // 4. BUAT PRODUK (Stok 0 dulu)
            $product = Product::create([
                'nama'            => $request->nama,
                'harga_normal'    => $request->harga_normal,
                'harga_reseller'  => $request->harga_reseller,
                'deskripsi'       => $request->deskripsi,
                'category_id'     => $request->category_id,
                'gambar'          => $gambarPath,
                'stok'            => 0,
            ]);
    
            $totalStokProduk = 0;
            $variants = json_decode($request->variants, true) ?? [];
            $defaultSizes = json_decode($request->default_sizes, true) ?? [];
    
            // 5. LOGIKA VARIAN
            if ($request->boolean('use_variants')) {
                
                // Cek apakah varian kosong
                if (empty($variants)) {
                    // Jika user centang "Pakai Varian" tapi tidak isi varian, kita bisa throw error
                    // atau biarkan produk tersimpan tanpa stok.
                }
    
                foreach ($variants as $index => $v) {
                    $warna = trim($v['warna'] ?? '');
    
                    // Validasi Warna Manual
                    if ($warna === '') {
                        // Jika warna kosong, kita skip atau lempar error spesifik
                        continue; 
                    }
    
                    $sizes = $v['sizes'] ?? [];
                    $totalVarian = 0;
    
                    // Buat Varian di DB
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'warna'      => $warna,
                        'stok'       => 0, // Nanti diupdate
                    ]);
    
                    // Loop Sizes
                    foreach ($sizes as $s) {
                        $sizeId   = $s['id'] ?? null;
                        $stokSize = (int)($s['stok'] ?? 0);
    
                        if ($sizeId && $stokSize > 0) {
                            VariantSize::create([
                                'product_variant_id' => $variant->id,
                                'size_id'            => $sizeId,
                                'stok'               => $stokSize,
                            ]);
                            $totalVarian += $stokSize;
                        }
                    }
                    
                    // Update Stok Varian
                    $variant->update(['stok' => $totalVarian]);
                    $totalStokProduk += $totalVarian;
                }
    
            } 
            // 6. LOGIKA DEFAULT SIZE (TANPA VARIAN)
            else {
                foreach ($defaultSizes as $s) {
                    $sizeId   = $s['id'] ?? null;
                    $stokSize = (int)($s['stok'] ?? 0);
    
                    if ($sizeId && $stokSize > 0) {
                        DefaultProductSize::create([
                            'product_id' => $product->id,
                            'size_id'    => $sizeId,
                            'stok'       => $stokSize,
                        ]);
                        $totalStokProduk += $stokSize;
                    }
                }
            }
    
            // 7. UPDATE TOTAL STOK
            $product->update(['stok' => $totalStokProduk]);
    
            // 8. SIMPAN GALERI
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $path,
                    ]);
                }
            }
    
            DB::commit(); // Simpan semua perubahan ke DB
    
            return redirect()
                ->route('admin.products')
                ->with('success', 'Produk berhasil ditambahkan.');
    
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            
            // Hapus gambar yang terlanjur diupload
            if (isset($gambarPath) && Storage::disk('public')->exists($gambarPath)) {
                Storage::disk('public')->delete($gambarPath);
            }
    
            return back()
                ->withInput()
                ->withErrors(['msg' => 'Gagal menyimpan produk: ' . $e->getMessage()]);
        }
    }


    /**
     * -----------------------------------------------------------
     * FUNGSI BARU — Update Produk + Hapus Gambar Lama
     * -----------------------------------------------------------
     */
    public function editProduct($id)
    {
        $product = Product::with([
            'variants.sizes.size',      // varian + size + nama size
            'defaultSizes.size',        // ukuran default
            'images'                    // galeri
        ])->findOrFail($id);

        $categories = Category::all();
        $sizes = \App\Models\Size::all();

        return view('admin.products_edit', compact('product', 'categories', 'sizes'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // VALIDASI DASAR
        $request->validate([
            'nama'           => 'required|string',
            'deskripsi'      => 'required|string',
            'harga_normal'   => 'required|numeric',
            'harga_reseller' => 'required|numeric',
            'category_id'    => 'required|exists:categories,id',
        ]);

        // UPDATE DATA PRODUK UTAMA
        $product->update([
            'nama'           => $request->nama,
            'deskripsi'      => $request->deskripsi,
            'harga_normal'   => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'category_id'    => $request->category_id,
        ]);

        /* ============================================================
          1. MODE VARIAN (warna + size)
        ============================================================ */
        $useVariants = $request->use_variants == 1;

        if ($useVariants) {

            // HAPUS default sizes (karena pake varian)
            DefaultProductSize::where('product_id', $product->id)->delete();

            // Ambil VARIANTS JSON
            $variants = json_decode($request->variants, true);

            // Hapus varian lama + ukuran lama
            // Hapus data VariantSize terlebih dahulu (melalui relasi jika diatur, atau manual)
            VariantSize::whereIn('product_variant_id', $product->variants()->pluck('id'))->delete();
            ProductVariant::where('product_id', $product->id)->delete();

            // Simpan semua varian baru
            foreach ($variants as $v) {
                // Skip jika data varian tidak lengkap atau tidak ada
                if (empty($v['warna']) || !isset($v['sizes'])) continue;

                // Insert varian warna
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'warna'      => $v['warna'],
                    // total stok dihitung dari array_sum
                    'stok'       => collect($v['sizes'])->sum(fn($s) => $s['stok']), 
                ]);

                // Insert ukuran tiap varian
                foreach ($v['sizes'] as $s) {
                    if (isset($s['id']) && $s['stok'] > 0) {
                        VariantSize::create([
                            'product_variant_id' => $variant->id,
                            'size_id'            => $s['id'],
                            'stok'               => $s['stok'],
                        ]);
                    }
                }
            }
        }

        /* ============================================================
          2. MODE NON-VARIAN (default size saja)
        ============================================================ */
        else {

            // HAPUS semua varian warna + ukuran varian
            VariantSize::whereIn('product_variant_id', $product->variants()->pluck('id'))->delete();
            ProductVariant::where('product_id', $product->id)->delete();


            // Ambil DEFAULT SIZE JSON
            $defaultSizes = json_decode($request->default_sizes, true);

            // Hapus ukuran lama
            DefaultProductSize::where('product_id', $product->id)->delete();

            // Simpan ukuran baru
            foreach ($defaultSizes as $s) {
                if (isset($s['id']) && $s['stok'] > 0) {
                    DefaultProductSize::create([
                        'product_id' => $product->id,
                        'size_id'    => $s['id'],
                        'stok'       => $s['stok'],
                    ]);
                }
            }
        }
        
        // RECALCULATE TOTAL STOK (penting!)
        $totalStokProduk = 0;
        if ($useVariants) {
            $totalStokProduk = $product->variants->sum('stok'); // Hitung dari total stok varian
        } else {
            $totalStokProduk = $product->defaultSizes->sum('stok'); // Hitung dari total stok default sizes
        }

        $product->update(['stok' => $totalStokProduk]);


        return redirect()
            ->route('admin.products.edit', $product->id)
            ->with('success', 'Produk berhasil diperbarui!');
    }



    public function destroyProduct($id)
    {
        // 1. Hapus relasi gambar
        ProductImage::where('product_id', $id)->delete();
        
        // 2. Hapus varian dan default sizes (diasumsikan sudah di-cascading di model, jika tidak, harus dihapus manual)
        // Jika tidak ada cascading delete:
        // VariantSize::whereIn('product_variant_id', ProductVariant::where('product_id', $id)->pluck('id'))->delete();
        // ProductVariant::where('product_id', $id)->delete();
        // DefaultProductSize::where('product_id', $id)->delete();

        // 3. Hapus produk utama
        Product::findOrFail($id)->delete();
        
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    // ---------------------------------------------------------
    // CRUD PESANAN
    // ---------------------------------------------------------
    public function manageOrders(Request $request)
    {
        // 1. Mulai query dasar (jangan panggil ->get() dulu)
        $query = Order::with(['user', 'orderItems', 'payment'])
                      ->latest('tanggal_pesan'); // Urutkan terbaru secara default

        // 2. Terapkan Filter NAMA PELANGGAN (jika ada)
        if ($request->filled('search_nama')) {
            $searchTerm = $request->search_nama;
            
            // 'whereHas' mencari di dalam relasi 'user'
            $query->whereHas('user', function ($q) use ($searchTerm) {
                
                // --- PENTING: Pastikan kolomnya benar (nama di tabel users) ---
                $q->where('nama', 'LIKE', "%{$searchTerm}%");
            
            });
        }

        // 3. Terapkan Filter TANGGAL MULAI (jika ada)
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pesan', '>=', $request->tanggal_mulai);
        }

        // 4. Terapkan Filter TANGGAL SELESAI (jika ada)
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pesan', '<=', $request->tanggal_selesai);
        }

        // 5. Ambil hasilnya dengan PAGINASI (solusi untuk error "total does not exist")
        $orders = $query->paginate(10)->withQueryString();
        
        // 6. Kirim data pesanan DAN data filter (agar input tetap terisi)
        return view('admin.orders', [
            'orders' => $orders,
            'filters' => $request->only(['search_nama', 'tanggal_mulai', 'tanggal_selesai'])
        ]);
    }        

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return redirect()->route('admin.orders')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // ---------------------------------------------------------
    // CRUD FAQ
    // ---------------------------------------------------------
    public function manageFAQ()
    {
        $faqs = Faq::all();
        return view('admin.faq', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        Faq::create($request->only(['pertanyaan', 'jawaban']));
        return redirect()->route('admin.faq')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function updateFaq(Request $request, $id)
    {
        Faq::findOrFail($id)->update($request->only(['pertanyaan', 'jawaban']));
        return redirect()->route('admin.faq')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroyFaq($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->route('admin.faq')->with('success', 'FAQ berhasil dihapus.');
    }

    // -----------------------------------------------------------------
    // FUNGSI BARU (3) UNTUK MANAJEMEN KATEGORI
    // -----------------------------------------------------------------

    /**
     * Menampilkan halaman Manajemen Kategori
     */
    public function manageCategories()
    {
        // 'with('products')' = Ambil kategori, sekaligus semua produk di dalamnya
        $categories = Category::with('products')->get();
        
        return view('admin.categories', compact('categories'));
    }

    /**
     * Menyimpan Kategori baru
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Menghapus Kategori
     */
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Kategori berhasil dihapus.');
    }

    public function showOrder($id)
    {
        // Ambil data order LENGKAP dengan relasinya
        $order = Order::with(['user', 'orderItems.product', 'payment'])
                      ->findOrFail($id);
                      
        return view('admin.orders_show', compact('order'));
    }

    // -----------------------------------------------------------------
    // FUNGSI BARU UNTUK MANAJEMEN PENGGUNA
    // -----------------------------------------------------------------
    public function manageUsers(Request $request)
    {
        $search = $request->input('search');

        // 1. Mulai query User
        $query = User::query()
            ->where('role', '!=', 'admin'); // <-- 1. Kecualikan admin

        // 2. Terapkan filter pencarian (jika ada)
        if ($search) {
            // <-- 2. Cari berdasarkan nama ATAU no_hp
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('no_hp', 'LIKE', "%{$search}%");
            });
        }

        // 3. Ambil data (kita pakai pagination agar rapi)
        $users = $query->latest()->paginate(15); // Ambil 15 user per halaman

        // 4. Kirim data ke view
        return view('admin.users', [
            'users' => $users,
            'filters' => ['search' => $search] // Kirim balik filter untuk mengisi input
        ]);
    }
    /**
     * -----------------------------------------------------------------
     * FUNGSI BARU: Tandai notifikasi sebagai sudah dibaca
     * -----------------------------------------------------------------
     */
    public function markNotificationAsRead($id)
    {
        // 1. Cari notifikasi milik user yang sedang login
        $notification = Auth::user()->notifications()->findOrFail($id);

        // 2. Tandai sudah dibaca
        if ($notification) {
            $notification->markAsRead();
        }

        // 3. Ambil ID pesanan dari data notifikasi
        $orderId = $notification->data['order_id'];

        // 4. Redirect admin ke halaman detail pesanan
        return redirect()->route('admin.orders.show', $orderId);
    }

    

}