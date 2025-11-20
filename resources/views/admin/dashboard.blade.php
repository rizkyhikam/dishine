@extends('layouts.admin')

@section('title', 'Dashboard - DISHINE Admin')

@section('content')
    <!-- Header Selamat Datang -->
    <div class="mb-6">
        <h1 class.="text-3xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->nama }}!</h1>
        <p class="text-gray-600">Berikut adalah ringkasan aktivitas toko Anda hari ini.</p>
    </div>

    <!-- 
    =================================
    KARTU STATISTIK (KPI)
    =================================
    -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Pendapatan -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4">
            <div class="bg-green-100 p-3 rounded-full">
                <i data-lucide="wallet" class="w-6 h-6 text-green-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pendapatan (Selesai)</p>
                <p class="text-2xl font-bold text-gray-800">
                    Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Card 2: Pesanan Baru -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4">
            <div class="bg-yellow-100 p-3 rounded-full">
                <i data-lucide="archive" class="w-6 h-6 text-yellow-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pesanan Baru (Perlu Diproses)</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $pesananBaru }}
                </p>
            </div>
        </div>

        <!-- Card 3: Total Produk -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <i data-lucide="package" class="w-6 h-6 text-blue-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Produk</T(p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $totalProduk }}
                </p>
            </div>
        </div>

        <!-- Card 4: Total Pelanggan -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex items-center space-x-4">
            <div class="bg-purple-100 p-3 rounded-full">
                <i data-lucide="users" class="w-6 h-6 text-purple-700"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $totalPelanggan }}
                </p>
            </div>
        </div>
    </div>

    <!-- 
    =================================
    TABEL PESANAN MENUNGGU VERIFIKASI
    =================================
    -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
            <!-- INI JUDUL YANG DIUBAH -->
            <h2 class="text-lg font-semibold text-gray-700">Pesanan Menunggu Verifikasi</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <!-- Loop ini sekarang HANYA berisi pesanan yang 'menunggu_verifikasi' -->
                    @forelse ($pesananTerbaru as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900">ORD-{{ $order->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order->user->nama ?? 'User Dihapus' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Status Pill -->
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                Rp{{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <!-- INI PESAN YANG DIUBAH -->
                            <td colspan="5" class="text-center py-10 text-gray-500">
                                Tidak ada pesanan yang menunggu verifikasi. Kerja bagus!
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>
@endsection