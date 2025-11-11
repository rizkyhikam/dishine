@extends('layouts.app')

@section('title', 'Home - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-4xl font-bold text-center mb-8">Selamat Datang di Dishine</h1>
    <p class="text-center mb-8">Temukan produk terbaik untuk kebutuhan Anda.</p>
    <a href="{{ route('katalog') }}" class="btn btn-primary mt-3">Lihat Katalog</a>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Contoh produk unggulan -->
        @foreach($featuredProducts ?? [] as $product)
        <div class="bg-white rounded-lg shadow-md p-4">
            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-48 object-cover rounded">
            <h3 class="text-xl font-semibold mt-4">{{ $product->nama }}</h3>
            <p class="text-[#b48a60] font-bold">Rp {{ number_format($product->harga_normal) }}</p>
            <a href="/katalog/{{ $product->id }}" class="btn-primary block text-center mt-4 px-4 py-2 rounded">Lihat Detail</a>
            <a href="{{ route('cart.index') }}" class="btn btn-primary">Lihat Keranjang</a>
        </div>
        @endforeach
    </div>
</div>
@endsection