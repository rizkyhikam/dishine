@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Dishine')

@section('content')
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-6xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-[#3c2f2f] mb-8">Riwayat Pesanan Anda</h1>

        @if($orders->isEmpty())
            <div class="bg-white p-8 rounded-xl shadow-md text-center">
                <p class="text-lg text-gray-500">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ url('/katalog') }}" class="mt-4 inline-block bg-[#44351f] text-white px-6 py-2 rounded-md hover:bg-[#a07850] transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up">
                        <div class="p-6">
                            <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-[#3c2f2f]">
                                        Pesanan #{{ $order->id }}
                                    </h2>
                                    <p class="text-sm text-gray-500">
                                        Tanggal: {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d F Y') }}
                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                        @if($order->status == 'selesai') bg-green-100 text-green-800
                                        @elseif($order->status == 'dibatalkan') bg-red-100 text-red-800
                                        @elseif($order->status == 'dikirim') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'diproses') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    <p class="text-lg font-bold text-[#4a3b2f] mt-1">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ asset('storage/' . $item->product->gambar) }}" alt="{{ $item->product->nama }}"
                                             class="w-16 h-16 object-cover rounded-md border">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item->product->nama }}</p>
                                            
                                            {{-- >>> TAMPILKAN VARIAN (WARNA/SIZE) <<< --}}
                                            @if($item->deskripsi_varian)
                                                <p class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mb-1">
                                                    {{ $item->deskripsi_varian }}
                                                </p>
                                            @endif
                                            {{-- >>> SELESAI <<< --}}

                                            <p class="text-sm text-gray-500">
                                                {{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <a href="{{ url('/orders/' . $order->id) }}" 
                               class="bg-[#44351f] text-white px-5 py-2 rounded-md hover:bg-[#a07850] transition text-sm font-medium">
                                Lihat Detail Pesanan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
<script>
    if(window.lucide) {
        lucide.createIcons();
    }
</script>
@endsection