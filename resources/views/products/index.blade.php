@extends('layouts.app')

@section('title', 'Katalog Produk - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Katalog Produk</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md p-4">
            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-48 object-cover rounded">
            <h3 class="text-xl font-semibold mt-4">{{ $product->nama }}</h3>
            <p class="text-[#b48a60] font-bold">Rp {{ number_format($product->harga_normal) }}</p>
            <a href="/products/{{ $product->id }}" class="btn-primary block text-center mt-4 px-4 py-2 rounded">Lihat Detail</a>
        </div>
        @endforeach
    </div>
</div>
@endsection