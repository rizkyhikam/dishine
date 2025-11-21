

<?php $__env->startSection('title', 'Riwayat Pesanan - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-6xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-[#3c2f2f] mb-8">Riwayat Pesanan Anda</h1>

        <?php if($orders->isEmpty()): ?>
            <div class="bg-white p-8 rounded-xl shadow-md text-center">
                <p class="text-lg text-gray-500">Anda belum memiliki riwayat pesanan.</p>
                <a href="<?php echo e(url('/katalog')); ?>" class="mt-4 inline-block bg-[#44351f] text-white px-6 py-2 rounded-md hover:bg-[#a07850] transition">
                    Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden" data-aos="fade-up">
                        <div class="p-6">
                            <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-[#3c2f2f]">
                                        Pesanan #<?php echo e($order->id); ?>

                                    </h2>
                                    <p class="text-sm text-gray-500">
                                        Tanggal: <?php echo e(\Carbon\Carbon::parse($order->tanggal_pesan)->format('d F Y')); ?>

                                    </p>
                                </div>
                                
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                        <?php if($order->status == 'selesai'): ?> bg-green-100 text-green-800
                                        <?php elseif($order->status == 'dibatalkan'): ?> bg-red-100 text-red-800
                                        <?php elseif($order->status == 'dikirim'): ?> bg-blue-100 text-blue-800
                                        <?php elseif($order->status == 'diproses'): ?> bg-yellow-100 text-yellow-800
                                        <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>
                                    ">
                                        <?php echo e(ucwords(str_replace('_', ' ', $order->status))); ?>

                                    </span>
                                    <p class="text-lg font-bold text-[#4a3b2f] mt-1">
                                        Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?>

                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-4">
                                        <img src="<?php echo e(asset('storage/' . $item->product->gambar)); ?>" alt="<?php echo e($item->product->nama); ?>"
                                             class="w-16 h-16 object-cover rounded-md border">
                                        <div>
                                            <p class="font-semibold text-gray-800"><?php echo e($item->product->nama); ?></p>
                                            
                                            
                                            <?php if($item->deskripsi_varian): ?>
                                                <p class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mb-1">
                                                    <?php echo e($item->deskripsi_varian); ?>

                                                </p>
                                            <?php endif; ?>
                                            

                                            <p class="text-sm text-gray-500">
                                                <?php echo e($item->jumlah); ?> x Rp <?php echo e(number_format($item->harga_satuan, 0, ',', '.')); ?>

                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <a href="<?php echo e(url('/orders/' . $order->id)); ?>" 
                               class="bg-[#44351f] text-white px-5 py-2 rounded-md hover:bg-[#a07850] transition text-sm font-medium">
                                Lihat Detail Pesanan
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    if(window.lucide) {
        lucide.createIcons();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/orders/index.blade.php ENDPATH**/ ?>