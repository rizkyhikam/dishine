@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">🛍️ Katalog Produk Dishine</h2>

    @if($products->isEmpty())
        <div class="alert alert-warning text-center">
            Belum ada produk yang tersedia.
        </div>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="{{ asset('storage/' . $product->gambar) }}" 
                             class="card-img-top" 
                             alt="{{ $product->nama }}" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->nama }}</h5>
                            <p class="card-text text-muted" style="min-height: 50px;">
                                {{ Str::limit($product->deskripsi, 60) }}
                            </p>
                            <p class="fw-bold mb-2 text-success">
                                Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
                            </p>
                            <button class="btn btn-primary w-100">Tambah ke Keranjang</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
