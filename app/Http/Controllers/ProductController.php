<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function showKatalog()
    {
        $products = \App\Models\Product::all();

        return view('katalog', compact('products'));
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['product' => $product], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga_normal' => 'required|numeric|min:0',
            'harga_reseller' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $gambarPath = $request->file('gambar')->store('products', 'public');

        $product = Product::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga_normal' => $request->harga_normal,
            'harga_reseller' => $request->harga_reseller,
            'stok' => $request->stok,
            'gambar' => $gambarPath,
        ]);

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'harga_normal' => 'sometimes|required|numeric|min:0',
            'harga_reseller' => 'sometimes|required|numeric|min:0',
            'stok' => 'sometimes|required|integer|min:0',
            'gambar' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('nama')) $product->nama = $request->nama;
        if ($request->has('deskripsi')) $product->deskripsi = $request->deskripsi;
        if ($request->has('harga_normal')) $product->harga_normal = $request->harga_normal;
        if ($request->has('harga_reseller')) $product->harga_reseller = $request->harga_reseller;
        if ($request->has('stok')) $product->stok = $request->stok;

        if ($request->hasFile('gambar')) {
            if ($product->gambar && Storage::disk('public')->exists($product->gambar)) {
                Storage::disk('public')->delete($product->gambar);
            }
            $product->gambar = $request->file('gambar')->store('products', 'public');
        }

        $product->save();

        return response()->json(['message' => 'Produk berhasil diperbarui', 'product' => $product], 200);
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
