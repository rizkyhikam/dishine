@extends('layouts.app')

@section('content')
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Notifikasi Sukses/Error -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <!-- KOLOM KIRI: GALERI FOTO -->
            <div data-aos="fade-right">
                <!-- ... (Galeri foto Anda, tidak perlu diubah) ... -->
                <div class="mb-4 rounded-xl overflow-hidden shadow-md">
                    <img id="main-image" 
                         src="{{ asset('storage/' . $product->gambar) }}" 
                         alt="{{ $product->nama }}" 
                         class="w-full h-auto object-cover transition-all duration-300 aspect-square">
                </div>
                <div class="flex space-x-3 overflow-x-auto pb-2">
                    <div class="w-24 h-24 flex-shrink-0">
                        <img src="{{ asset('storage/' . $product->gambar) }}" 
                             alt="Thumbnail (Cover)" 
                             class="thumbnail-image w-full h-full object-cover rounded-md cursor-pointer border-2 border-[#a07850]">
                    </div>
                    @foreach($product->images as $image)
                        <div class="w-24 h-24 flex-shrink-0">
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="Thumbnail Galeri" 
                                 class="thumbnail-image w-full h-full object-cover rounded-md cursor-pointer border-2 border-transparent hover:border-[#a07850]">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- KOLOM KANAN: INFO PRODUK -->
            <div data-aos="fade-left" data-aos-delay="100">
                <p class="text-sm text-[#a07850] font-semibold mb-2">
                    Kategori: {{ $product->category->name ?? 'N/A' }}
                </p>

                <h1 class="text-3xl lg:text-4xl font-bold text-[#3c2f2f] mb-3">
                    {{ $product->nama }}
                </h1>
                
                <!-- HARGA (Sudah benar dari langkah sebelumnya) -->
                <div class="text-2xl font-bold text-[#4a3b2f] mb-4">
                    @auth
                        @if(Auth::user()->role == 'reseller')
                            Rp {{ number_format($product->harga_reseller, 0, ',', '.') }}
                            <span class="text-lg text-red-600 line-through ml-2">Rp {{ number_format($product->harga_normal, 0, ',', '.') }}</span>
                        @else
                            Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
                        @endif
                    @else
                        Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
                    @endauth
                </div>

                <p class="text-sm text-[#7a6a5a] mb-4">
                    Stok Tersedia: <span class="font-semibold">{{ $product->stok }}</span>
                </p>

                <!-- 
                =================================
                FORM KERANJANG (DIPERBAIKI TOTAL)
                =================================
                Sekarang 1 form dengan 2 tombol
                -->
                <form method="POST">
                    @csrf
                    
                    @php
                        $minQuantity = 1;
                        if(Auth::check() && Auth::user()->role == 'reseller') {
                            $minQuantity = 5; // Reseller minimal 5
                        }
                    @endphp

                    <!-- Kuantitas -->
                    <div class="w-24 mb-6">
                        <label for="quantity" class="text-sm font-medium text-[#6b5a4a]">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" 
                               class="w-full border border-[#b48a60] rounded-md p-2 text-center" 
                               value="{{ $minQuantity }}" 
                               min="{{ $minQuantity }}" 
                               max="{{ $product->stok }}">
                    </div>

                    @if($minQuantity > 1)
                    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 text-sm p-3 rounded-md mb-4">
                        Minimal pembelian untuk Reseller adalah 5 item.
                    </div>
                    @endif
                    
                    <!-- Grup Tombol Aksi -->
                    <div class="flex items-center space-x-3 w-full">
                        
                        <!-- Tombol Beli Sekarang -->
                        <button type="submit" 
                                formaction="{{ route('cart.buyNow', $product->id) }}"
                                class="flex-1 bg-[#44351f] text-white px-6 py-2.5 rounded-md hover:bg-[#a07850] transition text-center flex items-center justify-center">
                            Beli Sekarang
                        </button>
                        
                        <!-- Tombol Tambah ke Keranjang -->
                        <button type="submit" 
                                formaction="{{ route('cart.add', $product->id) }}"
                                class="p-3 rounded-md border border-[#b48a60] text-[#b48a60] hover:bg-[#b48a60] hover:text-white transition">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>
                <!-- =============================== -->

                <!-- Deskripsi -->
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-[#3c2f2f] mb-2">Deskripsi Produk</h4>
                    <div class="text-[#7a6a5a] space-y-2">
                        {!! nl2br(e($product->deskripsi)) !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // ... (JavaScript galeri Anda, tidak perlu diubah) ...
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('main-image');
        const thumbnails = document.querySelectorAll('.thumbnail-image');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function () {
                mainImage.src = this.src;
                thumbnails.forEach(t => t.classList.remove('border-[#a07850]', 'border-2'));
                thumbnails.forEach(t => t.classList.add('border-transparent'));
                this.classList.add('border-[#a07850]', 'border-2');
                this.classList.remove('border-transparent');
            });
        });
        
        lucide.createIcons();
    });
</script>
@endsection