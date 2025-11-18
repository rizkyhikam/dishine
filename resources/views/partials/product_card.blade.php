<!-- 
    File ini berisi 1 kartu produk.
-->
<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300"
     data-aos="fade-up" 
     data-aos-delay="{{ $delay ?? 0 }}">
    
    <a href="{{ url('/products/' . $product->id) }}" class="block">     
        <img src="{{ asset('storage/' . $product->gambar) }}" 
             alt="{{ $product->nama }}" 
             class="w-full h-64 object-cover">
    </a>
    
    <div class="p-4">
        <h3 class="text-lg font-semibold text-[#3c2f2f] mb-2 truncate">{{ $product->nama }}</h3>
        <p class="text-xs text-[#a07850] mb-2">{{ $product->category->name ?? 'N/A' }}</p>
        <p class="text-sm text-[#7a6a5a] mb-3 min-h-[48px]">
            {{ Str::limit($product->deskripsi, 60) }}
        </p>
        
        <div class="text-[#4a3b2f] font-bold mb-4">
            @auth
                @if(Auth::user()->role == 'reseller')
                    Rp {{ number_format($product->harga_reseller, 0, ',', '.') }}
                    <span class="text-xs text-red-600 line-through ml-1">Rp {{ number_format($product->harga_normal, 0, ',', '.') }}</span>
                @else
                    Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
                @endif
            @else
                Rp {{ number_format($product->harga_normal, 0, ',', '.') }}
            @endauth
        </div>

        <!-- 
        =================================
        TOMBOL (SUDAH DIPERBAIKI)
        =================================
        -->
        <div class="flex items-center space-x-3 w-full">
            
            <!-- Tombol beli sekarang (SEKARANG MENJADI FORM) -->
            <form action="{{ route('cart.buyNow', $product->id) }}" method="POST" class="w-full">
                @csrf
                <!-- 
                    Tidak ada input kuantitas di sini.
                    Controller (logicAddToCart) akan otomatis memakai
                    kuantitas minimum (1 untuk pelanggan, 5 untuk reseller)
                -->
                <button type="submit" class="bg-[#44351f] text-white w-full px-7 py-2 rounded-md hover:bg-[#a07850] transition text-center">
                    Beli Sekarang
                </button>
            </form>

            <!-- Tombol tambah ke keranjang (Sudah benar) -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <button type="submit" 
                    class="text-[#b48a60] hover:text-[#a07850]">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </button>
            </form>
        </div>
        <!-- =============================== -->
    </div>
</div>