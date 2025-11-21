<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use App\Models\VariantSize; // Pastikan model ini di-import
use App\Models\ProductImage; // Pastikan model ini di-import
use App\Models\DefaultProductSize; // Pastikan model ini di-import

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
        $user       = Auth::user();
        $isReseller = ($user && $user->role === 'reseller');

        $query = Product::with([
            'category',
            'images',
            'variants.sizes.size', // Pastikan relasi ini benar di Model
            'defaultSizes.size',   // Pastikan relasi ini benar di Model
        ]);

        if ($isReseller) {
            $query->where('stok', '>', 5);
        } else {
            $query->where('stok', '>', 0);
        }

        $product = $query->findOrFail($id);

        // MINIMAL QTY
        $minQuantity = $isReseller ? 5 : 1;

        // VARIAN DATA (Untuk produk bervarian warna + ukuran)
        $variantData = $product->variants->map(function ($variant) {
            return [
                'id'    => $variant->id,
                'warna' => $variant->warna,
                'stok'  => (int) $variant->stok,
                'sizes' => $variant->sizes->map(function ($vs) {
                    return [
                        'row_id' => $vs->id, // <--- ID BARIS STOK VARIANT_SIZES
                        'id'    => $vs->size->id ?? null, // size_id
                        'name'  => $vs->size->name ?? 'N/A', 
                        'stok'  => (int) $vs->stok,
                    ];
                })->values(),
            ];
        })->values();

        // DEFAULT SIZE (Untuk produk non-varian, hanya ukuran)
        $defaultSizesData = $product->defaultSizes->map(function ($row) {
            return [
                'row_id' => $row->id, // <--- ID BARIS STOK DEFAULT_PRODUCT_SIZES
                'id'     => $row->size->id ?? null, // size_id (ID S/M/L)
                'name'   => $row->size->name ?? 'N/A',
                'stok'   => (int) $row->stok,
            ];
        })->values();

        return view('detail_produk', [
            'product'          => $product,
            'isReseller'       => $isReseller,
            'minQuantity'      => $minQuantity,
            'variantData'      => $variantData,
            'defaultSizesData' => $defaultSizesData,
        ]);
    }

    public function store(Request $request)
    {
        // VALIDASI DASAR
        $request->validate([
            'nama'           => 'required|string',
            'deskripsi'      => 'required',
            'harga_normal'   => 'required|integer',
            'harga_reseller' => 'required|integer',
            'category_id'    => 'required|integer',
            'gambar'         => 'required|image',
            // Tambahkan validasi untuk variants jika use_variants dicentang
            'variants'       => 'nullable|string', 
        ]);

        // UPLOAD GAMBAR UTAMA
        $gambar = $request->file('gambar')->store('products', 'public');

        // 1️⃣ SIMPAN PRODUK
        $product = Product::create([
            'nama'           => $request->nama,
            'deskripsi'      => $request->deskripsi,
            'harga_normal'   => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'category_id'    => $request->category_id,
            'stok'           => 0, // di-update setelah simpan varian/size
            'gambar'         => $gambar,
        ]);

        // 2️⃣ SIMPAN GALERI
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $path = $file->store('products/gallery', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        // 3️⃣ AMBIL DATA JSON DARI FORM
        // Decode JSON variants. Jika gagal/null, default ke array kosong.
        $variants = json_decode($request->variants, true) ?? [];
        $defaultSizes = json_decode($request->default_sizes, true) ?? [];

        $totalStock = 0;

        // 4️⃣ PRODUK PAKAI VARIAN WARNA
        if ($request->boolean('use_variants')) { // Gunakan boolean helper
            
            foreach ($variants as $index => $v) {
                $warna = trim($v['warna'] ?? '');

                // Validasi Manual: Warna Wajib Diisi
                if (empty($warna)) {
                    continue; 
                }

                $sizes = $v['sizes'] ?? [];
                $totalVarian = 0;

                // Buat record varian
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'warna'      => $warna,
                    'stok'       => 0, // Nanti diupdate
                ]);

                // Simpan ukuran dalam variant_sizes
                foreach ($sizes as $s) {
                    $sizeId   = $s['id']   ?? null; // Pastikan ID size dikirim dari form
                    $stokSize = (int)($s['stok'] ?? 0);

                    // Jika size_id valid (dari database sizes)
                    if ($sizeId && $stokSize >= 0) {
                        VariantSize::create([
                            'product_variant_id' => $variant->id,
                            'size_id'            => $sizeId,
                            'stok'               => $stokSize,
                        ]);
                        $totalVarian += $stokSize;
                    }
                }
                
                // Update stok varian
                $variant->update(['stok' => $totalVarian]);
                $totalStock += $totalVarian;
            }
        }

        // 5️⃣ PRODUK TANPA VARIAN – PAKAI DEFAULT SIZES
        else {
            foreach ($defaultSizes as $s) {
                $sizeId   = $s['id']   ?? null;
                $stokSize = (int)($s['stok'] ?? 0);

                if ($sizeId && $stokSize >= 0) {
                    DefaultProductSize::create([
                        'product_id' => $product->id,
                        'size_id'    => $sizeId,
                        'stok'       => $stokSize,
                    ]);
                    $totalStock += $stokSize;
                }
            }
        }

        // Update total stok produk
        $product->update(['stok' => $totalStock]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama'           => 'required|string',
            'deskripsi'      => 'required|string',
            'harga_normal'   => 'required|integer',
            'harga_reseller' => 'required|integer',
            'category_id'    => 'required|integer',
        ]);

        // Update produk dasar
        $product->update([
            'nama'           => $request->nama,
            'deskripsi'      => $request->deskripsi,
            'harga_normal'   => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'category_id'    => $request->category_id,
        ]);

        // IMAGE OPTIONAL
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            
            $gambar = $request->file('gambar')->store('products', 'public');
            $product->update(['gambar' => $gambar]);
        }

        // Data JSON dari admin form
        $variants      = json_decode($request->variants, true) ?? [];
        $defaultSizes  = json_decode($request->default_sizes, true) ?? [];

        // Bersihkan data lama (Hati-hati: ini menghapus semua varian lama & buat baru)
        
        // Hapus Varian Lama
        foreach ($product->variants as $oldVariant) {
            $oldVariant->sizes()->delete(); // Hapus variant_sizes terkait
            $oldVariant->delete();          // Hapus variant itu sendiri
        }
        
        // Hapus Default Sizes Lama
        $product->defaultSizes()->delete();

        $totalStock = 0;

        // =============== VARIAN MODE ===============
        if ($request->boolean('use_variants') && count($variants) > 0) {
            foreach ($variants as $v) {
                $warna = trim($v['warna'] ?? '');

                if ($warna === '') continue;

                $sizesData = $v['sizes'] ?? [];
                $totalStokVarian = 0;

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'warna'      => $warna,
                    'stok'       => 0,
                ]);

                foreach ($sizesData as $s) {
                    $sizeId   = $s['id']   ?? null;
                    $stokSize = (int)($s['stok'] ?? 0);

                    if ($sizeId && $stokSize >= 0) {
                        VariantSize::create([
                            'product_variant_id' => $variant->id,
                            'size_id'            => $sizeId,
                            'stok'               => $stokSize,
                        ]);
                        $totalStokVarian += $stokSize;
                    }
                }
                
                $variant->update(['stok' => $totalStokVarian]);
                $totalStock += $totalStokVarian;
            }
        }

        // =============== DEFAULT SIZE MODE ===============
        else {
            foreach ($defaultSizes as $s) {
                $sizeId   = $s['id']   ?? null;
                $stokSize = (int)($s['stok'] ?? 0);

                if ($sizeId && $stokSize >= 0) {
                    DefaultProductSize::create([
                        'product_id' => $product->id,
                        'size_id'    => $sizeId,
                        'stok'       => $stokSize,
                    ]);
                    $totalStock += $stokSize;
                }
            }
        }

        $product->update([
            'stok' => $totalStock
        ]);

        return back()->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
            Storage::disk('public')->delete($product->gambar);
        }

        // Hapus galeri juga jika ada
        foreach ($product->images as $img) {
             if (Storage::disk('public')->exists($img->path)) {
                Storage::disk('public')->delete($img->path);
            }
            $img->delete();
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }
}