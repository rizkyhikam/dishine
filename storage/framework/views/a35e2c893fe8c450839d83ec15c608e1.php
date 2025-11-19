<?php $__env->startSection('title', 'Manajemen Pesanan - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Pesanan</h1>

    <!-- Card Putih untuk Tabel -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        
        <!-- Form Filter -->
        <form action="<?php echo e(route('admin.orders')); ?>" method="GET">
            <div class="p-4 border-b border-gray-200 bg-gray-50 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <!-- Filter Nama Pelanggan -->
                <div>
                    <label for="search_nama" class="block text-xs font-medium text-gray-500 mb-1">Cari Nama Pelanggan</label>
                    <input type="text" name="search_nama" id="search_nama" 
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                           placeholder="Ketik nama..."
                           value="<?php echo e($filters['search_nama'] ?? ''); ?>">
                </div>

                <!-- Filter Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                           value="<?php echo e($filters['tanggal_mulai'] ?? ''); ?>">
                </div>

                <!-- Filter Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                           value="<?php echo e($filters['tanggal_selesai'] ?? ''); ?>">
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex space-x-2">
                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium flex items-center justify-center">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                        Filter
                    </button>
                    <a href="<?php echo e(route('admin.orders')); ?>" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-sm font-medium flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <!-- =============================== -->


        <!-- Tabel Pesanan -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tgl. Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Barang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <!-- 
                        --- PERUBAHAN DI SINI ---
                        Kolom 'Bukti Pembayaran' diubah menjadi 'Aksi'
                        -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Ini sekarang hanya teks -->
                                <span class="font-medium text-gray-900">ORD-<?php echo e($order->id); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($order->tanggal_pesan)->format('d/m/Y')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo e($order->user->nama ?? 'User Dihapus'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                                <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                                    <?php echo e($order->orderItems->sum('jumlah')); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                                Rp<?php echo e(number_format($order->total_harga, 0, ',', '.')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Status Pill -->
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    <?php if($order->status == 'selesai'): ?> bg-green-200 text-green-800
                                    <?php elseif($order->status == 'dibatalkan'): ?> bg-red-200 text-red-800
                                    <?php elseif($order->status == 'dikirim'): ?> bg-blue-200 text-blue-800
                                    <?php elseif($order->status == 'diproses'): ?> bg-yellow-200 text-yellow-800
                                    <?php else: ?> bg-gray-200 text-gray-800 <?php endif; ?>
                                ">
                                    <?php echo e(ucwords(str_replace('_', ' ', $order->status))); ?>

                                </span>
                            </td>
                            <!-- 
                            --- PERUBAHAN DI SINI ---
                            Kolom 'Bukti Pembayaran' diganti dengan tombol 'Lihat Detail'
                            -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <!-- Colspan diperbarui menjadi 7 -->
                            <td colspan="7" class="text-center py-10 text-gray-500">
                                <?php if(request()->has('search_nama') || request()->has('tanggal_mulai')): ?>
                                    Tidak ada pesanan yang cocok dengan filter Anda.
                                <?php else: ?>
                                    Belum ada pesanan yang masuk.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel -->
        <div class="p-4 border-t border-gray-200">
            <!-- (Pagination bisa ditambahkan di sini nanti) -->
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/orders.blade.php ENDPATH**/ ?>