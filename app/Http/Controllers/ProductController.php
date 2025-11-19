<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;


class ProductController extends Controller
{
    public function __construct()
    {
        if (!env('DEV_MODE', false)) {
            // $this->middleware('role:admin')->only(['store', 'update', 'destroy']);
        }
    }


    public function index()
    {
        $products = Product::all();
        return response()->json(['products' => $products], 200);
    }

        public function showKatalog(Request $request)
    {
        $user = Auth::user();
        $isReseller = ($user && $user->role == 'reseller');

        // Cek apakah ada query pencarian di URL (misal: ?q=dress)
        if ($request->has('q') && $request->q != '') {
            
            // --- MODE PENCARIAN ---
            $q = $request->q;

            $productQuery = Product::with('category')
                ->where(function ($query) use ($q) {
                    $query->where('nama', 'LIKE', "%$q%")
                          ->orWhereHas('category', function($subQuery) use ($q) {
                              $subQuery->where('name', 'LIKE', "%$q%");
                          });
                });

            // --- FILTER STOK BERDASARKAN ROLE ---
            if ($isReseller) {
                $productQuery->where('stok', '>', 5); // Reseller: stok > 5
            } else {
                $productQuery->where('stok', '>', 0); // Pelanggan: stok > 0
            }

            $products = $productQuery->get();

            return view('katalog', [
                'is_search' => true,
                'search_term' => $q,
                'search_results' => $products
            ]);

        } else {
            
            // --- MODE BROWSE (DEFAULT) ---
            $categories = Category::query()
                ->whereHas('products', function($query) use ($isReseller) {
                    // Hanya ambil kategori yang punya produk SESUAI STOK ROLE
                    if ($isReseller) {
                        $query->where('stok', '>', 5);
                    } else {
                        $query->where('stok', '>', 0);
                    }
                })
                ->with(['products' => function($query) use ($isReseller) {
                    // Ambil produk di dalamnya, SESUAI STOK ROLE
                    if ($isReseller) {
                        $query->where('stok', '>', 5);
                    } else {
                        $query->where('stok', '>', 0);
                    }
                }])
                ->get();

            return view('katalog', [
                'is_search' => false,
                'categories' => $categories
            ]);
        }
    }


    /**
     * Menampilkan halaman detail untuk satu produk.
     */
    public function show($id)
    {
        $user = Auth::user();
        $isReseller = ($user && $user->role == 'reseller');

        $query = Product::with(['category', 'images']);

        // --- FILTER STOK BERDASARKAN ROLE ---
        if ($isReseller) {
            $query->where('stok', '>', 5); // Reseller hanya boleh lihat jika stok > 5
        } else {
            $query->where('stok', '>', 0); // Pelanggan hanya boleh lihat jika stok > 0
        }

        // Ambil produk, atau 404 jika tidak ditemukan (atau stok tidak memadai)
        $product = $query->findOrFail($id);

        return view('detail_produk', compact('product'));
    }

    public function store(Request $request)
    {
        // DEBUG dulu biar kamu bisa lihat datanya masuk
        dd($request->all());

        // 1. Simpan produk utama
        $product = Product::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga_normal' => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'stok' => $request->use_variants ? 0 : ($request->stok ?? 0), 
            'category_id' => $request->category_id,
            'gambar' => $request->gambar,
        ]);

        // 2. Simpan varian jika checkbox dicentang
        if ($request->use_variants && $request->variant_warna) {

            foreach ($request->variant_warna as $i => $warna) {

                if (!$warna) continue; // skip jika kosong

                ProductVariant::create([
                    'product_id' => $product->id,
                    'warna' => $warna,
                    'stok' => $request->variant_stok[$i] ?? 0,
                    'harga' => $request->variant_harga[$i] ?? null,
                ]);
            }

            // Update stok produk dari total stok varian
            $product->update([
                'stok' => $product->variants()->sum('stok')
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama' => 'required',
            'harga_normal' => 'required|integer',
            'harga_reseller' => 'required|integer',
            'stok'  => 'nullable|integer',

            'variant_id.*' => 'nullable|integer',
            'variant_warna.*' => 'nullable|string',
            'variant_stok.*'  => 'nullable|integer',
            'variant_harga.*' => 'nullable|integer',
        ]);

        // UPDATE PRODUK
        $product->update([
            'nama' => $request->nama,
            'harga_normal' => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok ?? $product->stok,
        ]);

        // UPDATE VARIAN YANG SUDAH ADA
        if ($request->variant_id) {
            foreach ($request->variant_id as $i => $id) {
                if ($id) {
                    $variant = ProductVariant::find($id);
                    if ($variant) {
                        $variant->update([
                            'warna' => $request->variant_warna[$i],
                            'stok' => $request->variant_stok[$i],
                            'harga' => $request->variant_harga[$i] ?? null,
                        ]);
                    }
                }
            }
        }

        // TAMBAH VARIAN BARU
        if ($request->variant_warna) {
            foreach ($request->variant_warna as $i => $warna) {
                if (!isset($request->variant_id[$i]) && $warna) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'warna' => $warna,
                        'stok' => $request->variant_stok[$i] ?? 0,
                        'harga' => $request->variant_harga[$i] ?? null,
                    ]);
                }
            }
        }

        // SYNC STOK DARI VARIAN
        if ($product->variants()->count() > 0) {
            $product->update([
                'stok' => $product->variants()->sum('stok')
            ]);
        }

        return back()->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}
