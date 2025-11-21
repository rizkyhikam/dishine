<?php $__env->startSection('title', 'Dashboard - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section dengan Gradient -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-[#b48a60] to-[#d4a574] rounded-2xl p-8 shadow-lg">
            <h1 class="text-4xl font-bold text-white mb-2">Selamat Datang, <?php echo e(Auth::user()->nama); ?>!</h1>
            <p class="text-white text-lg opacity-90">Berikut adalah ringkasan aktivitas toko Anda hari ini.</p>
        </div>
    </div>

    <!-- 
    =================================
    KARTU STATISTIK (KPI) - REDESIGN
    =================================
    -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Pendapatan -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 p-4 rounded-xl">
                    <i data-lucide="wallet" class="w-8 h-8 text-green-600"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Pendapatan</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    Rp<?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?>

                </p>
                <p class="text-xs text-gray-500">Pesanan Selesai</p>
            </div>
        </div>

        <!-- Card 2: Pesanan Baru -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-100 p-4 rounded-xl">
                    <i data-lucide="shopping-cart" class="w-8 h-8 text-yellow-600"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Pesanan Baru</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    <?php echo e($pesananBaru); ?>

                </p>
                <p class="text-xs text-gray-500">Perlu Diproses</p>
            </div>
        </div>

        <!-- Card 3: Total Produk -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-4 rounded-xl">
                    <i data-lucide="package" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Produk</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    <?php echo e($totalProduk); ?>

                </p>
                <p class="text-xs text-gray-500">Produk Aktif</p>
            </div>
        </div>

        <!-- Card 4: Total Pelanggan -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-4 rounded-xl">
                    <i data-lucide="users" class="w-8 h-8 text-purple-600"></i>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Pelanggan</p>
                <p class="text-3xl font-bold text-gray-800 mb-1">
                    <?php echo e($totalPelanggan); ?>

                </p>
                <p class="text-xs text-gray-500">Pengguna Terdaftar</p>
            </div>
        </div>
    </div>

    <!-- 
    =================================
    TABEL PESANAN MENUNGGU VERIFIKASI - REDESIGN
    =================================
    -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-[#b48a60] to-[#d4a574] px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Pesanan Menunggu Verifikasi</h2>
                </div>
                <span class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-full text-sm font-semibold">
                    <?php echo e($pesananTerbaru->count()); ?> Pesanan
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            No. Pesanan
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $pesananTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="bg-[#b48a60] bg-opacity-10 p-2 rounded-lg mr-3">
                                        <i data-lucide="file-text" class="w-4 h-4 text-[#b48a60]"></i>
                                    </div>
                                    <span class="font-bold text-gray-900">ORD-<?php echo e($order->id); ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-600 font-semibold mr-3">
                                        <?php echo e(substr($order->user->nama ?? 'U', 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900"><?php echo e($order->user->nama ?? 'User Dihapus'); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($order->user->email ?? '-'); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                    <?php echo e(ucwords(str_replace('_', ' ', $order->status))); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <span class="text-lg font-bold text-gray-900">
                                    Rp<?php echo e(number_format($order->total_harga, 0, ',', '.')); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" 
                                   class="inline-flex items-center px-4 py-2 bg-[#b48a60] text-white text-sm font-semibold rounded-lg hover:bg-[#a07850] transition-colors duration-200">
                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <div class="bg-green-100 p-6 rounded-full mb-4">
                                        <i data-lucide="check-circle" class="w-16 h-16 text-green-600"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-700 mb-2">Semua Pesanan Terverifikasi!</h3>
                                    <p class="text-gray-500">Tidak ada pesanan yang menunggu verifikasi. Kerja bagus!</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script untuk inisialisasi Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>