@extends('layouts.app')

@section('title', 'Keranjang Belanja - Dishine')

@section('content')
<div class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Header Section -->
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#CC8650] rounded-2xl mb-4">
                <i data-lucide="shopping-cart" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Keranjang Belanja</h1>
            <p class="text-lg text-[#AE8B56]">Review pesanan Anda sebelum checkout</p>
        </div>

        @if($cartItems->isEmpty())
            <!-- Empty Cart -->
            <div class="text-center py-16" data-aos="fade-up">
                <div class="bg-white p-12 rounded-2xl shadow-sm inline-block">
                    <i data-lucide="shopping-bag" class="w-20 h-20 text-[#AE8B56] mx-auto mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Keranjang Kosong</h3>
                    <p class="text-gray-500 mb-8 max-w-sm">Belum ada item dalam keranjang belanja Anda</p>
                    <a href="{{ route('katalog') }}" 
                       class="inline-flex items-center px-8 py-3 bg-[#CC8650] text-white rounded-xl hover:bg-[#AE8B56] font-semibold transition-all">
                        <i data-lucide="tag" class="w-4 h-4 mr-2"></i>
                        Jelajahi Katalog
                    </a>
                </div>
            </div>
        @else
            <!-- Cart Items -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up">
                
                <!-- Table Header -->
                <div class="bg-[#F0E7DB] px-8 py-4 border-b border-[#D4BA98]">
                    <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-700">
                        <div class="col-span-5 flex items-center space-x-4">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 text-[#CC8650] rounded focus:ring-[#CC8650]">
                            <span>Produk</span>
                        </div>
                        <div class="col-span-2 text-center">Harga</div>
                        <div class="col-span-2 text-center">Kuantitas</div>
                        <div class="col-span-2 text-center">Subtotal</div>
                        <div class="col-span-1 text-center"></div>
                    </div>
                </div>

                <!-- Cart Items List -->
                <div class="divide-y divide-gray-100">
                    @foreach($cartItems as $item)
                        @include('cart.partials.item', ['item' => $item])
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="bg-[#F0E7DB] px-8 py-6 border-t border-[#D4BA98]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="selectAllBottom" class="w-4 h-4 text-[#CC8650] rounded focus:ring-[#CC8650]">
                                <span class="text-sm text-gray-600">Pilih Semua ({{ count($cartItems) }} item)</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-8">
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total Belanja</p>
                                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            
                            <a href="/checkout" 
                               class="inline-flex items-center px-8 py-3 bg-[#CC8650] text-white rounded-xl hover:bg-[#AE8B56] font-semibold transition-all shadow-lg hover:shadow-xl">
                                <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                                Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continue Shopping -->
            <div class="text-center mt-8" data-aos="fade-up">
                <a href="{{ route('katalog') }}" 
                   class="inline-flex items-center text-[#CC8650] hover:text-[#AE8B56] font-semibold transition-all">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Lanjutkan Belanja
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize icons
    lucide.createIcons();

    // Quantity controls
    document.querySelectorAll(".minus-btn").forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.target;
            const input = document.getElementById(id);
            if (input.value > 1) {
                input.value--;
                input.dispatchEvent(new Event('change'));
            }
        };
    });

    document.querySelectorAll(".plus-btn").forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.target;
            const input = document.getElementById(id);
            input.value++;
            input.dispatchEvent(new Event('change'));
        };
    });

    // Select all functionality
    const selectAllTop = document.getElementById('selectAll');
    const selectAllBottom = document.getElementById('selectAllBottom');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    function updateSelectAll() {
        const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
        selectAllTop.checked = allChecked;
        selectAllBottom.checked = allChecked;
    }

    selectAllTop.addEventListener('change', function() {
        const isChecked = this.checked;
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        selectAllBottom.checked = isChecked;
    });

    selectAllBottom.addEventListener('change', function() {
        const isChecked = this.checked;
        itemCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        selectAllTop.checked = isChecked;
    });

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectAll);
    });
});
</script>
@endsection