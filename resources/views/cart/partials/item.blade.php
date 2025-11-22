<div class="grid grid-cols-12 items-center py-6 px-8 border-b border-gray-100 bg-white hover:bg-gray-50 transition-all" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

    {{-- Checkbox + gambar + nama --}}
    <div class="col-span-5 flex items-center space-x-4">
        <input type="checkbox" checked class="w-4 h-4 text-[#CC8650] rounded focus:ring-[#CC8650]">

        <img src="{{ asset('storage/' . $item->product->gambar) }}" 
             class="w-16 h-16 rounded-xl object-cover border border-gray-200 shadow-sm">

        <div class="flex-1">
            <span class="font-semibold text-gray-800 block text-lg">
                {{ $item->product->nama }}
            </span>
            
            {{-- TAMPILAN DETAIL VARIAN/UKURAN (LOGIKA BARU) --}}
            @if($item->variant_size_id)
                @php
                    $stokRow = App\Http\Controllers\CartController::findStokRow($item->variant_size_id);
                    $detailText = null;

                    if ($stokRow) {
                        // Cek apakah ini VariantSize (Varian Penuh: Warna + Ukuran)
                        if ($stokRow instanceof App\Models\VariantSize) {
                            // Relasi sudah dimuat di controller!
                            $warna = $stokRow->productVariant->warna ?? 'N/A';
                            $ukuran = $stokRow->size->name ?? 'N/A';
                            
                            $detailText = "Varian: {$warna}, Ukuran: {$ukuran}";
                        } 
                        // Cek apakah ini DefaultProductSize (Non-Varian/Hanya Ukuran)
                        elseif ($stokRow instanceof App\Models\DefaultProductSize) {
                            // Relasi sudah dimuat di controller!
                            $ukuran = $stokRow->size->name ?? 'N/A';
                            
                            // HANYA tampilkan ukuran tanpa label "Varian:"
                            $detailText = "Ukuran: {$ukuran}"; 
                        }
                    }
                @endphp
                
                @if($detailText)
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="inline-flex items-center px-2 py-1 bg-[#F0E7DB] text-[#AE8B56] rounded-lg text-xs font-medium">
                            <i data-lucide="palette" class="w-3 h-3 mr-1"></i>
                            {!! $detailText !!}
                        </span>
                    </div>
                @else
                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-600 rounded-lg text-xs font-medium mt-2">
                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                        Detail Varian Hilang
                    </span>
                @endif
                
            @else
                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium mt-2">
                    <i data-lucide="package" class="w-3 h-3 mr-1"></i>
                    Produk Standar
                </span>
            @endif
            {{-- AKHIR LOGIKA VARIAN --}}

        </div>
    </div>

    {{-- Harga --}}
    <div class="col-span-2 text-center">
        {{-- Logika Harga Asli --}}
        @php
            $currentPrice = (Auth::check() && Auth::user()->role == 'reseller') ? $item->product->harga_reseller : $item->product->harga_normal;
        @endphp
        <div class="font-semibold text-gray-800 text-lg">
            Rp {{ number_format($currentPrice, 0, ',', '.') }}
        </div>
    </div>

    {{-- Qty --}}
    <div class="col-span-2 text-center">
        <form action="{{ route('cart.update', $item->id) }}" 
            method="POST"
            class="inline-flex items-center justify-center space-x-2">
            @csrf
            @method('PUT')

            <button type="button" 
                    class="w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all flex items-center justify-center minus-btn"
                    data-target="qty-{{ $item->id }}">
                <i data-lucide="minus" class="w-3 h-3 text-gray-600"></i>
            </button>

            <input id="qty-{{ $item->id }}" 
                type="number" name="quantity"
                value="{{ $item->quantity }}" 
                min="1"
                class="w-12 text-center border-2 border-gray-200 rounded-lg focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                onchange="this.form.submit()">

            <button type="button" 
                    class="w-8 h-8 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all flex items-center justify-center plus-btn"
                    data-target="qty-{{ $item->id }}">
                <i data-lucide="plus" class="w-3 h-3 text-gray-600"></i>
            </button>
        </form>
    </div>

    {{-- Subtotal --}}
    <div class="col-span-2 text-center">
        {{-- Logika Subtotal Asli --}}
        <div class="font-bold text-gray-800 text-lg">
            Rp {{ number_format($currentPrice * $item->quantity, 0, ',', '.') }}
        </div>
    </div>

    {{-- Hapus --}}
    <div class="col-span-1 text-center">
        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="w-10 h-10 bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 rounded-lg transition-all flex items-center justify-center">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</div>