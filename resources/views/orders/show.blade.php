@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->id . ' - Dishine')

@section('content')
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-4xl mx-auto px-6">

        <!-- Tombol Kembali -->
        <a href="{{ route('orders.view') }}" class="flex items-center text-[#6b5a4a] hover:text-[#3c2f2f] font-medium mb-4">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Kembali ke Riwayat Pesanan
        </a>

        <!-- Header Detail -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-[#3c2f2f]">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500">
                        Tanggal: {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d F Y, H:i') }}
                    </p>
                </div>
                <!-- Status -->
                <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                    @if($order->status == 'selesai') bg-green-100 text-green-800
                    @elseif($order->status == 'dibatalkan') bg-red-100 text-red-800
                    @elseif($order->status == 'dikirim') bg-blue-100 text-blue-800
                    @elseif($order->status == 'diproses') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif
                ">
                    Status: {{ ucwords(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
        </div>

        <!-- Grid Detail -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Item & Pembayaran -->
            <div class="md:col-span-2 space-y-6">
                <!-- Rincian Item -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-[#3c2f2f] mb-4">Produk yang Dipesan</h3>
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $item->product->gambar) }}" alt="{{ $item->product->nama }}"
                                     class="w-16 h-16 object-cover rounded-md border">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->product->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->jumlah }} x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Rincian Pembayaran -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-[#3c2f2f] mb-4">Rincian Pembayaran</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal Produk:</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pengiriman ({{ $order->kurir }} - {{ $order->layanan_kurir }}):</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Layanan:</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($order->biaya_layanan, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-base font-bold text-[#3c2f2f]">
                            <span>Total Bayar:</span>
                            <span>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Alamat & Bukti Bayar -->
            <div class="space-y-6">
                <!-- Alamat Pengiriman -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-[#3c2f2f] mb-4">Alamat Pengiriman</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $order->alamat_pengiriman }}
                    </p>
                </div>

                <!-- Bukti Pembayaran -->
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-lg font-semibold text-[#3c2f2f] mb-4">Bukti Pembayaran</h3>
                    @if($order->payment)
                        <a href="{{ asset('storage/' . $order->payment->bukti_transfer) }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('storage/' . $order->payment->bukti_transfer) }}" 
                                 alt="Bukti Transfer" class="w-full rounded-md shadow-sm">
                        </a>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Status: {{ ucwords(str_replace('_', ' ', $order->payment->status_verifikasi)) }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500">Bukti pembayaran tidak ditemukan.</p>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
<script>
    lucide.createIcons();
</script>
@endsection