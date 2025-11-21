<?php $__env->startSection('title', 'Detail Pesanan ORD-' . $order->id . ' - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    
    <a href="<?php echo e(route('admin.orders')); ?>" class="flex items-center text-gray-600 hover:text-gray-800 font-medium mb-4">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Kembali ke Daftar Pesanan
    </a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Pesanan ORD-<?php echo e($order->id); ?></h1>
                        <p class="text-sm text-gray-500">
                            Tanggal: <?php echo e(\Carbon\Carbon::parse($order->tanggal_pesan)->format('d F Y, H:i')); ?>

                        </p>
                    </div>
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full
                        <?php if($order->status == 'selesai'): ?> bg-green-200 text-green-800
                        <?php elseif($order->status == 'dibatalkan'): ?> bg-red-200 text-red-800
                        <?php elseif($order->status == 'dikirim'): ?> bg-blue-200 text-blue-800
                        <?php elseif($order->status == 'diproses'): ?> bg-yellow-200 text-yellow-800
                        <?php else: ?> bg-gray-200 text-gray-800 <?php endif; ?>
                    ">
                        <?php echo e(ucwords(str_replace('_', ' ', $order->status))); ?>

                    </span>
                </div>

                <hr class="my-4">

                <h3 class="text-lg font-semibold text-gray-700 mb-4">Produk yang Dipesan</h3>
                <div class="space-y-4">
                    <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between items-start border-b pb-4 last:border-0 last:pb-0">
                        <div class="flex items-start space-x-4">
                            <img src="<?php echo e(asset('storage/' . $item->product->gambar)); ?>" alt="<?php echo e($item->product->nama); ?>"
                                 class="w-16 h-16 object-cover rounded-md border">
                            <div>
                                <p class="font-semibold text-gray-800"><?php echo e($item->product->nama); ?></p>
                                
                                
                                <?php if($item->deskripsi_varian): ?>
                                    <p class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mb-1">
                                        <?php echo e($item->deskripsi_varian); ?>

                                    </p>
                                <?php endif; ?>
                                

                                <p class="text-sm text-gray-500 mt-1">
                                    <?php echo e($item->jumlah); ?> x Rp <?php echo e(number_format($item->harga_satuan, 0, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-800 whitespace-nowrap">
                            Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Rincian Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal Produk:</span>
                        <span class="font-medium text-gray-800">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pengiriman (<?php echo e(strtoupper($order->kurir)); ?> - <?php echo e($order->layanan_kurir); ?>):</span>
                        <span class="font-medium text-gray-800">Rp <?php echo e(number_format($order->ongkir, 0, ',', '.')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Layanan:</span>
                        <span class="font-medium text-gray-800">Rp <?php echo e(number_format($order->biaya_layanan, 0, ',', '.')); ?></span>
                    </div>
                    <hr class="my-2 border-gray-200">
                    <div class="flex justify-between text-base font-bold text-gray-800">
                        <span>Total Bayar:</span>
                        <span>Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Aksi Pesanan</h3>
                <form action="<?php echo e(route('admin.orders.update', $order->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Status Pesanan</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 mb-3 bg-gray-50">
                        <option value="menunggu_verifikasi" <?php echo e($order->status == 'menunggu_verifikasi' ? 'selected' : ''); ?>>Menunggu Verifikasi</option>
                        <option value="diproses" <?php echo e($order->status == 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                        <option value="dikirim" <?php echo e($order->status == 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                        <option value="selesai" <?php echo e($order->status == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                        <option value="dibatalkan" <?php echo e($order->status == 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                    </select>
                    <button type="submit" class="w-full bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium transition">
                        Update Status
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Detail Pelanggan</h3>
                <p class="text-sm text-gray-600 mb-1"><strong>Nama:</strong> <?php echo e($order->user->nama ?? 'User Terhapus'); ?></p>
                <p class="text-sm text-gray-600 mb-1"><strong>Email:</strong> <?php echo e($order->user->email ?? '-'); ?></p>
                <p class="text-sm text-gray-600"><strong>No. HP:</strong> <?php echo e($order->user->no_hp ?? '-'); ?></p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Alamat Pengiriman</h3>
                <div class="text-sm text-gray-600 leading-relaxed">
                    <p class="font-bold text-gray-800 mb-1"><?php echo e($order->nama_penerima); ?></p>
                    <p class="mb-1"><?php echo e($order->no_hp); ?></p>
                    <p><?php echo e($order->alamat_pengiriman); ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Bukti Pembayaran</h3>
                <?php if($order->payment): ?>
                    <a href="<?php echo e(asset('storage/' . $order->payment->bukti_transfer)); ?>" target="_blank" rel="noopener noreferrer" class="block group relative overflow-hidden rounded-md">
                        <img src="<?php echo e(asset('storage/' . $order->payment->bukti_transfer)); ?>" 
                             alt="Bukti Transfer" class="w-full rounded-md shadow-sm transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 font-medium text-sm bg-black bg-opacity-50 px-3 py-1 rounded">Lihat Full</span>
                        </div>
                    </a>
                <?php else: ?>
                    <div class="text-center py-8 bg-gray-50 rounded border border-dashed border-gray-300">
                        <p class="text-sm text-gray-500">Belum ada bukti pembayaran.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/orders_show.blade.php ENDPATH**/ ?>