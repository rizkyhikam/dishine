<?php $__env->startSection('title', 'Kelola Pesanan - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Kelola Pesanan</h1>
    <table class="w-full bg-white shadow-md rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-4">ID Pesanan</th>
                <th class="p-4">Pengguna</th>
                <th class="p-4">Total</th>
                <th class="p-4">Status</th>
                <th class="p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="p-4"><?php echo e($order->id); ?></td>
                <td class="p-4"><?php echo e($order->user->nama); ?></td>
                <td class="p-4">Rp <?php echo e(number_format($order->total)); ?></td>
                <td class="p-4"><?php echo e($order->status); ?></td>
                <td class="p-4">
                    <button class="btn-primary px-4 py-2 rounded">Lihat Detail</button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/orders.blade.php ENDPATH**/ ?>