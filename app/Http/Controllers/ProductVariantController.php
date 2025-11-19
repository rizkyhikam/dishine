<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'warna' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'nullable|integer|min:0'
        ]);

        $variant = ProductVariant::create($request->all());

        // sync stok produk
        $variant->product->update([
            'stok' => $variant->product->variants->sum('stok')
        ]);

        return back()->with('success', 'Varian berhasil ditambahkan');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'warna' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'nullable|integer|min:0'
        ]);

        $variant->update($request->all());

        // sync stok produk
        $variant->product->update([
            'stok' => $variant->product->variants->sum('stok')
        ]);

        return back()->with('success', 'Varian berhasil diperbarui');
    }

    public function destroy(ProductVariant $variant)
    {
        $product = $variant->product;

        $variant->delete();

        // sync stok produk
        $product->update([
            'stok' => $product->variants->sum('stok')
        ]);

        return back()->with('success', 'Varian berhasil dihapus');
    }

}
