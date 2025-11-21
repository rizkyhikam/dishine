<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\VariantSize;       // WAJIB DIIMPOR
use App\Models\DefaultProductSize; // WAJIB DIIMPOR

class CartController extends Controller
{
    /**
     * Helper: Mencari baris stok berdasarkan ID-nya di salah satu dari dua tabel stok.
     * @param mixed $rowId ID dari tabel variant_sizes ATAU default_product_sizes
     * @return Model|null
     */
    public static function findStokRow($rowId)
    {
        if (empty($rowId)) {
            return null;
        }

        // 1. Coba cari di VariantSize (Produk bervarian: Warna + Ukuran)
        // KRITIS: Muat relasi bersarang di sini
        $variantSize = VariantSize::with(['size', 'productVariant'])->find($rowId);
        if ($variantSize) {
            return $variantSize;
        }

        // 2. Coba cari di DefaultProductSize (Produk non-varian: Hanya Ukuran)
        // KRITIS: Muat relasi bersarang di sini
        $defaultSize = DefaultProductSize::with(['size'])->find($rowId);
        if ($defaultSize) {
            return $defaultSize;
        }

        return null;
    }

    /**
     * Menampilkan halaman keranjang
     */
    public function index()
    {
        $user = Auth::user();
        $isReseller = ($user->role == 'reseller');
        
        // Memuat relasi Product dan VariantSize untuk akses di View
        $cartItems = Cart::with([
                'product', 
                'variantSize.size',          // Untuk mendapatkan nama ukuran (S, M, L, All Size)
                'variantSize.productVariant', // Untuk mendapatkan Warna
                // Kita juga perlu memuat defaultSizes jika ada
            ])
            ->where('user_id', $user->id)
            ->get();

        // Hitung ulang total berdasarkan role (Harga diambil dari Product, seperti semula)
        $total = $cartItems->sum(function ($item) use ($isReseller) {
            if (!$item->product) return 0;
            
            if ($isReseller) {
                return $item->product->harga_reseller * $item->quantity;
            } else {
                return $item->product->harga_normal * $item->quantity;
            }
        });
        
        session(['cart_count' => $cartItems->count()]);

        return view('cart.index', compact('cartItems', 'total', 'isReseller'));
    }

    /**
     * Fungsi Inti: Menambahkan produk ke keranjang
     */
    private function logicAddToCart(Request $request, $id)
    {
        $user = Auth::user();
        $isReseller = ($user->role == 'reseller');
        $product = Product::findOrFail($id);
        
        // --- LOGIKA VARIAN DAN STOK PER BARIS (BARU) ---
        $variantSizeId = $request->input('variant_size_id'); 
        $stokTersedia = $product->stok; // Fallback: stok global produk (untuk produk tanpa opsi ukuran)

        // === 1. TENTUKAN STOK BERDASARKAN ID BARIS STOK YANG DIKIRIM ===
        if ($variantSizeId) {
            
            $stokRow = $this->findStokRow($variantSizeId);
            
            if (!$stokRow) {
                // Ini akan muncul jika ID terkirim tapi data di DB tidak ada
                return ['success' => false, 'message' => 'Varian/Ukuran yang dipilih tidak ditemukan stoknya.'];
            }
            $stokTersedia = $stokRow->stok; // Ambil stok dari baris varian/default size
        } else {
            // Jika produk punya varian/ukuran, tapi user tidak memilih (ID kosong)
            if ($product->defaultSizes->isNotEmpty() || $product->variants->isNotEmpty()) {
                 return ['success' => false, 'message' => 'Varian/Ukuran wajib dipilih.'];
            }
            // Jika tidak ada varian/ukuran (Kasus lama): $stokTersedia tetap $product->stok
        }
        
        // --- END LOGIKA VARIAN ---
        
        
        // Tentukan kuantitas minimum berdasarkan role
        $minQuantity = $isReseller ? 5 : 1;

        // Ambil kuantitas dari request, ATAU default ke minimum
        $quantity = $request->input('quantity', $minQuantity);
        
        // Pastikan kuantitas tidak kurang dari minimum
        $quantity = max($quantity, $minQuantity);

        // --- ATURAN VALIDASI UNTUK RESELLER (MENGGUNAKAN STOK BARU) ---
        if ($isReseller) {
            // Menggunakan $stokTersedia (stok per varian) untuk validasi Reseller
            if ($stokTersedia < 5) {
                return ['success' => false, 'message' => 'Stok varian ini kurang dari 5, tidak tersedia untuk reseller.'];
            }
        }

        // Cek stok umum (MENGGUNAKAN STOK BARU)
        if ($stokTersedia < $quantity) {
            return ['success' => false, 'message' => 'Stok tidak mencukupi. Tersedia: ' . $stokTersedia];
        }

        // Cek apakah produk sudah ada di keranjang (DIMODIFIKASI UNTUK MEMASUKKAN VARIAN)
        $query = Cart::where('user_id', $user->id)
                     ->where('product_id', $product->id);
                     
        // Tambahkan kondisi varian/ukuran untuk deduplikasi
        if ($variantSizeId) {
            $query->where('variant_size_id', $variantSizeId);
        } else {
            $query->whereNull('variant_size_id');
        }
        
        $cartItem = $query->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan kuantitasnya
            $newQuantity = $cartItem->quantity + $quantity;

            if ($isReseller && $newQuantity < 5) {
                $newQuantity = 5; // Paksa jadi 5 jika sudah ada di keranjang
            }
            
            // Cek stok lagi dengan kuantitas baru (MENGGUNAKAN $stokTersedia)
            if ($stokTersedia < $newQuantity) {
                return ['success' => false, 'message' => 'Stok tidak mencukupi di keranjang setelah digabungkan.'];
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'variant_size_id' => $variantSizeId, // <-- SIMPAN ID VARIAN BARU
            ]);
        }
        
        // Update cart count di session
        $cartCount = Cart::where('user_id', $user->id)->count();
        session(['cart_count' => $cartCount]);

        return ['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.'];
    }

    /**
     * Fungsi "Tambah ke Keranjang"
     */
    public function addToCart(Request $request, $id)
    {
        $result = $this->logicAddToCart($request, $id);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Fungsi "Beli Sekarang"
     */
    public function buyNow(Request $request, $id)
    {
        $result = $this->logicAddToCart($request, $id);

        if ($result['success']) {
            return redirect()->route('checkout.index');
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }

    /**
     * Update kuantitas di keranjang
     */
    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::with('product')->findOrFail($id); 
        $user = Auth::user();

        if ($cartItem->user_id != $user->id) { abort(403); }
        
        $quantity = $request->input('quantity', 1);
        $stokTersedia = 0;

        // === LOGIKA STOK BERDASARKAN ID BARIS YANG TERSIMPAN DI KERANJANG ===
        if ($cartItem->variant_size_id) {
            // Jika ada variant_size_id (harus dicari menggunakan findStokRow)
            
            $stokRow = $this->findStokRow($cartItem->variant_size_id);
            
            if ($stokRow) {
                $stokTersedia = $stokRow->stok;
            } else {
                // ID baris stok hilang/terhapus
                return redirect()->route('cart.index')->with('error', 'Kesalahan sistem: Stok varian/ukuran tidak ditemukan di database.');
            }
        } else {
            // Produk non-varian lama (stok dari Product)
            $stokTersedia = $cartItem->product->stok;
        }

        // 1. Hapus jika kuantitas <= 0
        if ($quantity <= 0) {
            $cartItem->delete();
            
            $cartCount = Cart::where('user_id', $user->id)->count();
            session(['cart_count' => $cartCount]);
            
            return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
        }

        // 2. Cek Stok Varian
        if ($stokTersedia < $quantity) {
             return redirect()->route('cart.index')->with('error', 'Stok varian tidak mencukupi. Tersedia: ' . $stokTersedia);
        }
        
        // 3. Cek Aturan Reseller
        if ($user->role == 'reseller' && $quantity < 5) {
             return redirect()->route('cart.index')->with('error', 'Minimal pembelian reseller adalah 5 item.');
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil diupdate.');
    }
    
    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart($id)
    {
        $cartItem = Cart::findOrFail($id);

        if ($cartItem->user_id != Auth::id()) { abort(403); }
        
        $cartItem->delete();
        
        $cartCount = Cart::where('user_id', Auth::id())->count();
        session(['cart_count' => $cartCount]);

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}