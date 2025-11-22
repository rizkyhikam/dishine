@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - DISHINE Admin')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="shopping-bag" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Manajemen Pesanan</h1>
                <p class="text-gray-600 mt-1">Kelola dan pantau semua pesanan pelanggan</p>
            </div>
        </div>
    </div>

    <!-- Card Putih untuk Tabel -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        
        <!-- Form Filter dengan Gradient Header -->
        <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <i data-lucide="filter" class="w-5 h-5 text-white"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Filter Pesanan</h2>
            </div>
            
            <form action="{{ route('admin.orders') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    
                    <!-- Filter Nama Pelanggan -->
                    <div class="md:col-span-2">
                        <label for="search_nama" class="block text-sm font-semibold text-white mb-2">Nama Pelanggan</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="user-search" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="text" name="search_nama" id="search_nama" 
                                   class="w-full border-2 border-white border-opacity-30 bg-white rounded-xl pl-10 pr-4 py-3 text-sm focus:border-white focus:ring focus:ring-white focus:ring-opacity-30 transition-all" 
                                   placeholder="Ketik nama pelanggan..."
                                   value="{{ $filters['search_nama'] ?? '' }}">
                        </div>
                    </div>

                    <!-- Filter Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-semibold text-white mb-2">Dari Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                                   class="w-full border-2 border-white border-opacity-30 bg-white rounded-xl pl-10 pr-4 py-3 text-sm focus:border-white focus:ring focus:ring-white focus:ring-opacity-30 transition-all"
                                   value="{{ $filters['tanggal_mulai'] ?? '' }}">
                        </div>
                    </div>

                    <!-- Filter Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-semibold text-white mb-2">Sampai Tanggal</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                                   class="w-full border-2 border-white border-opacity-30 bg-white rounded-xl pl-10 pr-4 py-3 text-sm focus:border-white focus:ring focus:ring-white focus:ring-opacity-30 transition-all"
                                   value="{{ $filters['tanggal_selesai'] ?? '' }}">
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-white text-[#CC8650] px-4 py-3 rounded-xl hover:shadow-lg font-bold text-sm flex items-center justify-center transition-all">
                            <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.orders') }}" class="flex-1 bg-[#8B6F47] text-white px-4 py-3 rounded-xl hover:bg-[#725a38] font-semibold text-sm flex items-center justify-center transition-all">
                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Bar -->
        <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-gray-700">
                    <i data-lucide="package" class="w-5 h-5 text-[#AE8B56]"></i>
                    <span class="text-sm font-semibold">Total Pesanan: <span class="text-[#CC8650] font-bold">{{ $orders->total() }}</span></span>
                </div>
                @if(request()->has('search_nama') || request()->has('tanggal_mulai'))
                    <span class="text-sm text-gray-600">
                        <i data-lucide="filter" class="w-4 h-4 inline"></i>
                        Filter aktif
                    </span>
                @endif
            </div>
        </div>

        <!-- Tabel Pesanan -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            No. Pesanan
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Items
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gradient-to-r hover:from-[#F0E7DB] hover:to-transparent transition-all duration-150">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-[#CC8650] bg-opacity-10 p-2 rounded-lg mr-3">
                                        <i data-lucide="file-text" class="w-4 h-4 text-[#CC8650]"></i>
                                    </div>
                                    <span class="font-bold text-gray-900">ORD-{{ $order->id }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-700">
                                    <i data-lucide="calendar-days" class="w-4 h-4 mr-2 text-[#AE8B56]"></i>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('d M Y') }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($order->tanggal_pesan)->format('H:i') }}</span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center bg-gradient-to-br from-[#CC8650] to-[#D4BA98] text-white font-bold mr-3 shadow-sm">
                                        {{ substr($order->user->nama ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $order->user->nama ?? 'User Dihapus' }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-[#D4BA98] to-[#D0B682] text-white shadow-sm">
                                    <i data-lucide="package-2" class="w-4 h-4 mr-1"></i>
                                    {{ $order->orderItems->sum('jumlah') }}
                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <span class="text-lg font-bold text-gray-900">
                                    Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                @if($order->status == 'selesai')
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Selesai
                                    </span>
                                @elseif($order->status == 'dibatalkan')
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                        Dibatalkan
                                    </span>
                                @elseif($order->status == 'dikirim')
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                        Dikirim
                                    </span>
                                @elseif($order->status == 'diproses')
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                        Diproses
                                    </span>
                                @elseif($order->status == 'menunggu_verifikasi')
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                        <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                        <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-[#AE8B56] text-white text-sm font-semibold rounded-lg hover:bg-[#8B6F47] transition-all">
                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    @if(request()->has('search_nama') || request()->has('tanggal_mulai'))
                                        <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                            <i data-lucide="search-x" class="w-16 h-16 text-[#AE8B56]"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Hasil</h3>
                                        <p class="text-gray-500 mb-4">Tidak ada pesanan yang cocok dengan filter Anda.</p>
                                        <a href="{{ route('admin.orders') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                                            <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                            Hapus Filter
                                        </a>
                                    @else
                                        <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                            <i data-lucide="inbox" class="w-16 h-16 text-[#AE8B56]"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Pesanan</h3>
                                        <p class="text-gray-500">Belum ada pesanan yang masuk dari pelanggan.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel (Pagination) -->
        @if($orders->hasPages())
            <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-bold text-[#CC8650]">{{ $orders->firstItem() }}</span> - <span class="font-bold text-[#CC8650]">{{ $orders->lastItem() }}</span> dari <span class="font-bold text-[#CC8650]">{{ $orders->total() }}</span> pesanan
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Script untuk inisialisasi Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
@endsection