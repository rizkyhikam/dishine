<?php $__env->startSection('title', 'Checkout - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>
    <form action="/orders" method="POST">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Detail Pengiriman</h2>
            <label class="block mb-2">Alamat Pengiriman:</label>
            <textarea name="alamat_pengiriman" class="w-full p-2 border rounded" required></textarea>
            <label class="block mb-2 mt-4">Ongkir:</label>
            <input type="number" name="ongkir" class="w-full p-2 border rounded" value="0">
        </div>
        <button type="submit" class="btn-primary px-6 py-3 rounded">Buat Pesanan</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/checkout/index.blade.php ENDPATH**/ ?>