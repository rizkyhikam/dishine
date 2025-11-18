<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman Home
     * (VERSI UPGRADE - Mengambil Produk Unggulan)
     */
    public function index()
    {
        // --- LOGIKA UNTUK MENGAMBIL PRODUK UNGGULAN ---

        // 1. Tentukan aturan stok berdasarkan role
        $user = Auth::user();
        $isReseller = ($user && $user->role == 'reseller');
        $minStock = $isReseller ? 6 : 1; // Reseller > 5, Pelanggan > 0

        // 2. Ambil 4 ID produk terlaris dari 'order_items'
        $topProductIds = OrderItem::select('product_id', DB::raw('SUM(jumlah) as total_terjual'))
            ->groupBy('product_id')
            ->orderByDesc('total_terjual')
            ->take(4)
            ->pluck('product_id');

        // 3. Ambil data produk-produk tersebut, DAN pastikan stoknya masih ada
        $featuredProducts = Product::whereIn('id', $topProductIds)
            ->where('stok', '>=', $minStock) // Filter berdasarkan stok
            ->get()
            // Urutkan lagi berdasarkan urutan terlaris (karena whereIn mengacak urutan)
            ->sortBy(function($product) use ($topProductIds) {
                return array_search($product->id, $topProductIds->toArray());
            });

        // 4. Kirim data ke view
        return view('home', compact('featuredProducts'));
    }
}