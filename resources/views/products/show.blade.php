@extends('layouts.app')

@section('title', 'Detail Produk - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="bg-white rounded-lg shadow-md p-6">
        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-64 object-cover rounded mb-6">
        <h1 class="text-3xl font-bold mb-4">{{ $product->nama }}</h1>
        <p class="text-lg mb-4">{{ $product->deskripsi }}</p>
        <p class="text-[#b48a60] font-bold text-xl mb-4">Rp {{ number_format($product->harga_normal) }}</p>
        <p class="mb-4">Stok: {{ $product->stok }}</p>
        <button class="btn-primary px-6 py-3 rounded" onclick="addToCart({{ $product->id }})">Tambah ke Keranjang</button>
    </div>
</div>
<script>
function addToCart(id) {
    fetch(`/cart/add/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => alert(data.message));
}
</script>
@endsection