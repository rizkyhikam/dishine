@extends('layouts.app')

@section('title', 'Keranjang - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($carts->isEmpty())
        <p>Keranjang kamu masih kosong.</p>
    @else
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border">Produk</th>
                    <th class="p-3 border">Harga</th>
                    <th class="p-3 border">Jumlah</th>
                    <th class="p-3 border">Total</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                    <tr>
                        <td class="p-3 border">{{ $cart->product->barang }}</td>
                        <td class="p-3 border">${{ $cart->product->harga }}</td>
                        <td class="p-3 border">
                            <form action="/cart/update/{{ $cart->id }}" method="POST" class="flex items-center justify-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="w-16 text-center border rounded">
                                <button type="submit" class="text-blue-500 hover:underline">Ubah</button>
                            </form>
                        </td>
                        <td class="p-3 border">${{ $cart->product->harga * $cart->quantity }}</td>
                        <td class="p-3 border">
                            <form action="/cart/remove/{{ $cart->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 text-right">
            <a href="/checkout" class="btn-primary px-6 py-2 rounded">Lanjut ke Checkout</a>
        </div>
    @endif
</div>
@endsection
