<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tampilkan isi keranjang user
    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('carts'));
    }

    // Tambah produk ke keranjang
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->update(['quantity' => $cart->quantity + 1]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        return redirect('/cart')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    // Update jumlah barang di keranjang
    public function updateCart(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect('/cart')->with('success', 'Jumlah produk diperbarui.');
    }

    // Hapus produk dari keranjang
    public function removeFromCart($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();

        return redirect('/cart')->with('success', 'Produk dihapus dari keranjang.');
    }
}
