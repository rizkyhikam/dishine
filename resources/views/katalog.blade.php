@extends('layouts.app')

@section('content')
<div class="bg-[#f3e8e3] py-12">
    <div class="text-center mb-10">
        <img src="{{ asset('modelkatalog.png') }}" 
                 alt="Dishine Collection Models" 
                 class="w-full h-64 md:h-80 lg:h-100 object-cover"
                 data-aos="fade-up"> <br>
        <p class="text-[#6b5a4a]" data-aos="fade-up" data-aos-delay="80">Koleksi eksklusif Dishine untuk muslimah yang ingin tampil menawan tanpa meninggalkan kesederhanaan.</p>
    </div>

    @if($products->isEmpty())
        <div class="text-center text-[#a07850] font-medium">Belum ada produk yang tersedia.</div>
    @else
        <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 px-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300"
                        data-aos="fade-up" 
                        data-aos-delay="{{ $loop->index * 30 }}">
                <a href="{{ url('/products/' . $product->id) }}" class="block">    
                <img src="{{ asset('storage/' . $product->gambar) }}" 
                         alt="{{ $product->nama }}" 
                         class="w-full h-64 object-cover"></a>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-[#3c2f2f] mb-2">{{ $product->nama }}</h3>
                        <p class="text-sm text-[#7a6a5a] mb-3 min-h-[48px]">
                            {{ Str::limit($product->deskripsi, 60) }}
                        </p>
                        <p class="text-[#4a3b2f] font-bold mb-4">
                            Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
                        </p>
                        <div class="flex items-center space-x-3 w-full">

                        <!-- Tombol beli sekarang -->
                        <a href="/checkout">
                        <button class="bg-[#44351f] text-white w-full px-7 py-2 rounded-md hover:bg-[#a07850] transition text-center">
                            Beli Sekarang
                        </button>
                        </a>

                        <!-- Tombol tambah ke keranjang -->
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                class="text-[#b48a60] hover:text-[#a07850]">
                                <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                            </button>
                        </form>

                    </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
