@extends('layouts.app')

@section('content')
<div class="bg-[#f3e8e3] py-12">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-semibold text-[#3c2f2f] mb-2">🛍️ Katalog Produk Dishine</h2>
        <p class="text-[#6b5a4a]">Koleksi eksklusif Dishine untuk muslimah yang ingin tampil menawan tanpa meninggalkan kesederhanaan.</p>
    </div>

    @if($products->isEmpty())
        <div class="text-center text-[#a07850] font-medium">Belum ada produk yang tersedia.</div>
    @else
        <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300">
                    <img src="{{ asset('storage/' . $product->gambar) }}" 
                         alt="{{ $product->nama }}" 
                         class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-[#3c2f2f] mb-2">{{ $product->nama }}</h3>
                        <p class="text-sm text-[#7a6a5a] mb-3 min-h-[48px]">
                            {{ Str::limit($product->deskripsi, 60) }}
                        </p>
                        <p class="text-[#4a3b2f] font-bold mb-4">
                            Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
                        </p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-[#b48a60] text-white w-full py-2 rounded-md hover:bg-[#a07850] transition flex items-center justify-center space-x-2">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                <span>Beli Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
