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


class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    public function dashboard()
    {
        // Statistik
        $totalUsers = User::where('role', 'pelanggan')->count();
        $totalResellers = User::where('role', 'reseller')->count();
        $totalProducts = Product::count();
        $totalFaqs = Faq::count();

        $salesData = Order::selectRaw('EXTRACT(MONTH FROM tanggal_pesan) as month, SUM(total) as total')
            ->where('status', Order::STATUS_SELESAI)
            ->whereYear('tanggal_pesan', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $newOrders = Order::where('status', Order::STATUS_BARU)->count();
        if ($newOrders > 0) {
            session()->flash('notification', "Ada {$newOrders} pesanan baru yang perlu diperiksa.");
        }

        return view('admin.dashboard', compact('totalUsers', 'totalResellers', 'totalProducts', 'totalFaqs', 'salesData'));
    }

    // ---------------------------------------------------------
    // CRUD PRODUK
    // ---------------------------------------------------------
    public function manageProducts()
    {
        $products = Product::with('category')->get(); // 'with' lebih efisien
        $categories = Category::all(); // <-- KITA AMBIL SEMUA KATEGORI
        
        // Kirim 'categories' ke view
        return view('admin.products', compact('products', 'categories'));
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
        $categories = Category::all();
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
}
