<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Faq;
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
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_normal' => 'required|numeric|min:0',
            'harga_reseller' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $gambarPath = $request->file('gambar')->store('products', 'public');

        Product::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga_normal' => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'stok' => $request->stok,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * -----------------------------------------------------------
     * FUNGSI BARU — Menampilkan Form Edit Produk
     * -----------------------------------------------------------
     */
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products_edit', compact('product'));
    }

    /**
     * -----------------------------------------------------------
     * FUNGSI BARU — Update Produk + Hapus Gambar Lama
     * -----------------------------------------------------------
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_normal' => 'required|numeric',
            'harga_reseller' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Update data dasar
        $product->nama = $request->nama;
        $product->harga_normal = $request->harga_normal;
        $product->harga_reseller = $request->harga_reseller;
        $product->stok = $request->stok;
        $product->deskripsi = $request->deskripsi;

        // Jika upload gambar baru
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }

            // Upload gambar baru
            $path = $request->file('gambar')->store('products', 'public');
            $product->gambar = $path;
        }

        // Simpan perubahan
        $product->save();

        return redirect()->route('admin.products')
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    // ---------------------------------------------------------
    // CRUD PESANAN
    // ---------------------------------------------------------
    public function manageOrders()
    {
        $orders = Order::with('user', 'orderItems.product')->get();
        return view('admin.orders', compact('orders'));
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
}
