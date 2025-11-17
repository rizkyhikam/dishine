@extends('layouts.app')

@section('content')
<div class="bg-[#f3e8e3] py-12">
    
    <!-- Bagian Banner Atas (Sama seperti lama) -->
    <div class="text-center mb-10">
        <img src="{{ asset('modelkatalog.png') }}" 
             alt="Dishine Collection Models" 
             class="w-full h-64 md:h-80 lg:h-100 object-cover"
             data-aos="fade-up"> <br>
        <p class="text-[#6b5a4a]" data-aos="fade-up" data-aos-delay="80">Koleksi eksklusif Dishine untuk muslimah yang ingin tampil menawan tanpa meninggalkan kesederhanaan.</p>
    </div>

    <!-- 
        =================================
        KONTEN DINAMIS (BARU)
        =================================
    -->
    <div class="max-w-6xl mx-auto px-6">

        @if($is_search)
            <!-- 
                =================================
                TAMPILAN JIKA SEDANG MENCARI
                =================================
            -->
            <h2 class="text-2xl font-bold text-[#3c2f2f] mb-6" data-aos="fade-up">
                Hasil pencarian untuk: <span class="text-[#a07850]">"{{ $search_term }}"</span>
            </h2>

            @if($search_results->isEmpty())
                <div class="text-center text-[#a07850] font-medium" data-aos="fade-up">
                    Tidak ada produk yang cocok dengan pencarian Anda.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach($search_results as $product)
                        <!-- Ini adalah Card Produk Anda (Tampilan Datar) -->
                        @include('partials.product_card', ['product' => $product, 'delay' => $loop->index * 30])
                    @endforeach
                </div>
            @endif

        @else
            <!-- 
                =================================
                TAMPILAN DEFAULT (BROWSE PER KATEGORI)
                (Sesuai Hi-Fi Anda)
                =================================
            -->
            @if($categories->isEmpty())
                <div class="text-center text-[#a07850] font-medium" data-aos="fade-up">
                    Belum ada produk yang tersedia.
                </div>
            @else
                @foreach($categories as $category)
                    <div class="mb-12" data-aos="fade-up">
                        <!-- Judul Kategori (e.g., "Dress", "Hijab") -->
                        <h2 class="text-3xl font-bold text-[#3c2f2f] mb-6 border-b-2 border-[#d3c1b6] pb-2">
                            {{ $category->name }}
                        </h2>
                        
                        <!-- Grid Produk per Kategori -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            @foreach($category->products as $product)
                                <!-- Ini adalah Card Produk Anda -->
                                @include('partials.product_card', ['product' => $product, 'delay' => $loop->index * 30])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif

        @endif
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection