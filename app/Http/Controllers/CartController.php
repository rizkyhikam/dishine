<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // 🧺 Tampilkan isi keranjang user
    public function index()
{
    $user = Auth::user();

    $cartItems = \App\Models\Cart::where('user_id', $user->id)
        ->with('product')
        ->get();

    $total = $cartItems->sum(fn($item) => $item->product->harga_normal * $item->quantity);

    return view('cart.index', compact('cartItems', 'total'));

}


    // ➕ Tambah produk ke keranjang
    public function addToCart($id)
{
    $product = \App\Models\Product::findOrFail($id);
    $userId = auth()->id();

    // Cek apakah sudah ada di cart user ini
    $cartItem = \App\Models\Cart::where('user_id', $userId)
        ->where('product_id', $id)
        ->first();

    if ($cartItem) {
        // Jika sudah ada → tambah jumlah
        $cartItem->quantity += 1;
        $cartItem->save();
    } else {
        // Jika belum ada → buat baru
        \App\Models\Cart::create([
            'user_id' => $userId,
            'product_id' => $id,
            'quantity' => 1,
        ]);
    }

    return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
}


    // 🗑️ Hapus produk dari keranjang
    public function removeFromCart($id)
    {
        $item = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus.');
    }

    // 🔁 Update jumlah barang
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui.');
    }
}
