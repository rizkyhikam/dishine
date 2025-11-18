<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang
     * (VERSI UPGRADE - Menghitung ulang total berdasarkan role)
     */
    public function index()
    {
        $user = Auth::user();
        $isReseller = ($user->role == 'reseller');
        
        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        // Hitung ulang total berdasarkan role
        $total = $cartItems->sum(function ($item) use ($isReseller) {
            if (!$item->product) return 0; // Jika produk terhapus
            
            if ($isReseller) {
                return $item->product->harga_reseller * $item->quantity;
            } else {
                return $item->product->harga_normal * $item->quantity;
            }
        });
        
        // Update cart count di session
        session(['cart_count' => $cartItems->count()]);

        return view('cart.index', compact('cartItems', 'total', 'isReseller'));
    }

    /**
     * Fungsi Inti: Menambahkan produk ke keranjang
     * (Fungsi ini akan dipakai oleh addToCart dan buyNow)
     */
    private function logicAddToCart(Request $request, $id)
    {
        $user = Auth::user();
        $isReseller = ($user->role == 'reseller');
        $product = Product::findOrFail($id);
        
        // Tentukan kuantitas minimum berdasarkan role
        $minQuantity = $isReseller ? 5 : 1;

        // Ambil kuantitas dari request, ATAU default ke minimum
        $quantity = $request->input('quantity', $minQuantity);
        
        // Pastikan kuantitas tidak kurang dari minimum
        $quantity = max($quantity, $minQuantity);

        // --- ATURAN VALIDASI UNTUK RESELLER ---
        if ($isReseller) {
            if ($product->stok <= 5) {
                 return ['success' => false, 'message' => 'Produk ini tidak tersedia untuk reseller.'];
            }
        }

        // Cek stok umum
        if ($product->stok < $quantity) {
            return ['success' => false, 'message' => 'Stok tidak mencukupi.'];
        }

        // Cek apakah produk sudah ada di keranjang
        $cartItem = Cart::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan kuantitasnya
            $newQuantity = $cartItem->quantity + $quantity;

            if ($isReseller && $newQuantity < 5) {
                 $newQuantity = 5; // Paksa jadi 5 jika sudah ada di keranjang
            }
            
            if ($product->stok < $newQuantity) {
                 return ['success' => false, 'message' => 'Stok tidak mencukupi di keranjang.'];
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
        
        // Update cart count di session
        $cartCount = Cart::where('user_id', $user->id)->count();
        session(['cart_count' => $cartCount]);

        return ['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang.'];
    }

    /**
     * -----------------------------------------------------------------
     * PERUBAHAN #1: Fungsi "Tambah ke Keranjang"
     * (Sekarang redirect KEMBALI, bukan ke halaman keranjang)
     * -----------------------------------------------------------------
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
     * -----------------------------------------------------------------
     * FUNGSI BARU: Fungsi "Beli Sekarang"
     * (Menambahkan ke keranjang, LALU redirect ke checkout)
     * -----------------------------------------------------------------
     */
    public function buyNow(Request $request, $id)
    {
        $result = $this->logicAddToCart($request, $id);

        if ($result['success']) {
            // Perbedaan utamanya ada di sini:
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
        $cartItem = Cart::findOrFail($id);

        if ($cartItem->user_id != Auth::id()) { abort(403); }
        
        $quantity = $request->input('quantity', 1);
        if ($quantity <= 0) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
        }

        if ($cartItem->product->stok < $quantity) {
             return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi.');
        }
        
        if (Auth::user()->role == 'reseller' && $quantity < 5) {
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