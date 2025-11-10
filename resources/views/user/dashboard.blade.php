@extends('layouts.app')

@section('title', 'Dashboard - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-4">Selamat datang, {{ $user->nama }} 👋</h1>
    <p class="mb-6">
        Role kamu: 
        <span class="font-semibold text-[#b48a60]">{{ ucfirst($user->role) }}</span>
    </p>

    <h2 class="text-xl font-semibold mb-4">Daftar Produk</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="border rounded-lg shadow p-4 bg-white">
                <img src="{{ asset('storage/'.$product->gambar) }}" alt="{{ $product->nama }}" class="rounded w-full h-40 object-cover mb-3">
                <h3 class="text-lg font-semibold">{{ $product->nama }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $product->deskripsi }}</p>
                
                @if($user->role === 'reseller')
                    <p class="font-bold text-green-600">Rp{{ number_format($product->harga_reseller, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Minimal beli: 5 pcs</p>
                @else
                    <p class="font-bold text-blue-600">Rp{{ number_format($product->harga_normal, 0, ',', '.') }}</p>
                @endif

                <p class="text-sm text-gray-500">Stok: {{ $product->stok }}</p>
                <button class="mt-3 w-full py-2 bg-[#b48a60] text-white rounded hover:bg-[#9c774d]">Tambah ke Keranjang</button>
            </div>
        @empty
            <p>Tidak ada produk yang tersedia saat ini.</p>
        @endforelse
    </div>
</div>
@endsection
