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
use Illuminate\Support\Facades\Storage; // <<< WAJIB untuk hapus gambar
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    public function dashboard()
    {
        // 1. Hitung Total Pendapatan (hanya dari pesanan 'selesai')
        $totalPendapatan = Order::where('status', 'selesai')->sum('total_bayar');

        // 2. Hitung Pesanan Baru (yang perlu diproses)
        $pesananBaru = Order::whereIn('status', ['menunggu_verifikasi', 'diproses'])->count();

        // 3. Hitung Total Produk
        $totalProduk = Product::count();

        // 4. Hitung Total Pelanggan (kecuali admin)
        $totalPelanggan = User::where('role', '!=', 'admin')->count();

        // 5. Ambil 5 Pesanan Terbaru (untuk tabel)
       $pesananTerbaru = Order::with('user') // Ambil relasi user untuk nama
                            ->where('status', Order::STATUS_MENUNGGU_VERIFIKASI) // <-- HANYA AMBIL YANG PERLU VERIFIKASI
                            ->latest()      // Urutkan dari yg terbaru
                            ->get();       // Ambil SEMUA yang menunggu (bukan cuma 5)

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
    public function manageProducts(Request $request) // <-- Tambahkan Request $request
    {
        // 1. Mulai query dasar
        $query = Product::with('category');

        // 2. Terapkan Filter NAMA PRODUK (jika ada)
        if ($request->filled('search_nama')) {
            // Gunakan 'products.nama' agar lebih spesifik
            $query->where('products.nama', 'LIKE', '%' . $request->search_nama . '%');
        }

        // 3. Ambil data
        $products = $query->get();
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
        // 1. Validasi Input (sudah termasuk kategori & galeri)
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_normal' => 'required|numeric',
            'harga_reseller' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'required|string',
            'category_id' => 'required|exists:categories,id', // <-- Validasi Kategori
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // <-- Gambar Sampul
            'gallery' => 'nullable|array', // <-- Validasi Galeri (boleh kosong)
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048' // Validasi SETIAP file di galeri
        ]);

        // 2. Simpan Gambar Sampul (Cover Image)
        $gambarPath = $request->file('gambar')->store('products', 'public');

        // 3. Buat Produk Baru di Database
        $product = Product::create([
            'nama' => $request->nama,
            'harga_normal' => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id, // <-- Simpan Kategori
            'gambar' => $gambarPath, // <-- Simpan Gambar Sampul
        ]);

        // 4. Simpan Galeri Foto (Jika ada)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                // Simpan setiap file galeri
                $galleryPath = $file->store('products/gallery', 'public');
                
                // Buat record baru di tabel 'product_images'
                ProductImage::create([
                    'product_id' => $product->id, // Link ke produk yang baru dibuat
                    'path' => $galleryPath
                ]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * -----------------------------------------------------------
     * FUNGSI BARU — Menampilkan Form Edit Produk
     * -----------------------------------------------------------
     */
    public function editProduct($id)
    {
        // Temukan produk, ATAU GAGAL
        // Kita pakai 'with' agar Laravel sekaligus mengambil relasi (efisien)
        $product = Product::with('images', 'category')->findOrFail($id);
        
        // Ambil SEMUA kategori untuk dropdown
        $categories = Category::all(); 
        
        // Kirim produk & kategori ke view
        return view('admin.products_edit', compact('product', 'categories'));
    }

    /**
     * -----------------------------------------------------------
     * FUNGSI BARU — Update Produk + Hapus Gambar Lama
     * -----------------------------------------------------------
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validasi (Sama seperti 'store' tapi 'gambar' boleh kosong)
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_normal' => 'required|numeric',
            'harga_reseller' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Boleh kosong
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 2. Update Data Teks dan Kategori
        $product->update([
            'nama' => $request->nama,
            'harga_normal' => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'category_id' => $request->category_id,
        ]);

        // 3. Update Gambar Sampul (Cover) JIKA ada file baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('products', 'public');
            $product->gambar = $gambarPath;
            $product->save(); // Simpan perubahan gambar
        }

        // 4. HAPUS Foto Galeri yang dicentang
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                
                // Pastikan gambar ini milik produk yang sedang diedit (keamanan)
                if ($image && $image->product_id == $product->id) {
                    Storage::disk('public')->delete($image->path); // Hapus file
                    $image->delete(); // Hapus data di database
                }
            }
        }

        // 5. TAMBAH Foto Galeri Baru (Jika ada)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $galleryPath
                ]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    // ---------------------------------------------------------
    // CRUD PESANAN
    // ---------------------------------------------------------
    public function manageOrders(Request $request)
    {
        // 1. Mulai query dasar (jangan panggil ->get() dulu)
        $query = Order::with(['user', 'orderItems', 'payment']);

        // 2. Terapkan Filter NAMA PELANGGAN (jika ada)
        if ($request->filled('search_nama')) {
            $searchTerm = $request->search_nama;
            
            // 'whereHas' mencari di dalam relasi 'user'
            $query->whereHas('user', function ($q) use ($searchTerm) {
                
                // --- INI DIA PERBAIKANNYA ---
                // Kolom Anda 'nama', bukan 'name'
                $q->where('users.nama', 'LIKE', "%{$searchTerm}%");
            
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

        // 5. Ambil hasilnya (setelah semua filter diterapkan)
        $orders = $query->latest()->get();
        
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
