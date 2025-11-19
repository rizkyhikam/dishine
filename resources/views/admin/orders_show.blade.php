@extends('layouts.admin')

@section('title', 'Detail Pesanan ORD-' . $order->id . ' - DISHINE Admin')

@section('content')
    
    <!-- Tombol Kembali -->
    <a href="{{ route('admin.orders') }}" class="flex items-center text-gray-600 hover:text-gray-800 font-medium mb-4">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Kembali ke Daftar Pesanan
    </a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Detail & Item -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Detail Pesanan & Item -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Pesanan ORD-{{ $order->id }}</h1>
                        <p class="text-sm text-gray-500">
                            Tanggal: {{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d F Y, H:i') }}
                        </p>
                    </div>
                    <!-- Status -->
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                        @if($order->status == 'selesai') bg-green-200 text-green-800
                        @elseif($order->status == 'dibatalkan') bg-red-200 text-red-800
                        @elseif($order->status == 'dikirim') bg-blue-200 text-blue-800
                        @elseif($order->status == 'diproses') bg-yellow-200 text-yellow-800
                        @else bg-gray-200 text-gray-800 @endif
                    ">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>

                <hr class="my-4">

                <h3 class="text-lg font-semibold text-gray-700 mb-4">Produk yang Dipesan</h3>
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

            <!-- Detail Pembayaran -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Rincian Pembayaran</h3>
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
                    <div class="flex justify-between text-base font-bold text-gray-800">
                        <span>Total Bayar:</span>
                        <span>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Pelanggan, Alamat, Aksi -->
        <div class="space-y-6">
            
            <!-- Aksi / Ubah Status -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Aksi Pesanan</h3>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Status Pesanan</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 mb-3">
                        <option value="menunggu_verifikasi" {{ $order->status == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="submit" class="w-full bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Detail Pelanggan -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Detail Pelanggan</h3>
                <p class="text-sm text-gray-600"><strong>Nama:</strong> {{ $order->user->nama ?? 'N/A' }}</T(p>
                <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600"><strong>No. HP:</strong> {{ $order->user->no_hp ?? 'N/A' }}</p>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Alamat Pengiriman</h3>
                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ $order->alamat_pengiriman }}
                </p>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Bukti Pembayaran</h3>
                @if($order->payment)
                    <a href="{{ asset('storage/' . $order->payment->bukti_transfer) }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('storage/' . $order->payment->bukti_transfer) }}" 
                             alt="Bukti Transfer" class="w-full rounded-md shadow-sm">
                    </a>
                @else
                    <p class="text-sm text-gray-500">Belum ada bukti pembayaran.</p>
                @endif
            </div>
        </div>
    </div>
@endsection