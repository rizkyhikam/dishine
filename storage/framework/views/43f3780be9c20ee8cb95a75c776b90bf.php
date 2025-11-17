<?php $__env->startSection('title', 'Keranjang Belanja - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-10">

    
    <h1 class="text-3xl font-bold mb-8 text-[#3c2f2f]">Keranjang Belanja</h1>

    
    <?php if($cartItems->isEmpty()): ?>
        <div class="text-center bg-[#f8f5f2] py-10 rounded-lg">
            <p class="text-xl text-gray-500">Keranjang kamu kosong</p>
            <a href="/katalog" class="btn-primary mt-4 inline-block px-4 py-2 rounded">
                Lihat Katalog
            </a>
        </div>
    <?php else: ?>

        
        <div class="grid grid-cols-7 font-semibold border-b pb-3 text-[#3c2f2f]">
            <div class="col-span-3">Produk</div>
            <div class="text-center">Harga</div>
            <div class="text-center">Kuantitas</div>
            <div class="text-center">Subtotal</div>
        </div>

        
        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('cart.partials.item', ['item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="mt-8 p-4 bg-[#ececec] rounded-lg flex justify-between items-center">
            <div>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="selectAll" class="w-5 h-5" />
                    <span>Pilih Semua (<?php echo e(count($cartItems)); ?>)</span>
                </label>
            </div>

            <div class="flex items-center space-x-6">
                <span class="font-bold text-lg">Total</span>
                <span class="font-bold text-xl">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>

                <a href="/checkout" 
                   class="px-6 py-2 bg-[#92584e] text-white rounded-lg hover:bg-[#7d493f]">
                    Checkout
                </a>
            </div>
        </div>

    <?php endif; ?>
</div>

<script>
document.querySelectorAll(".minus-btn").forEach(btn => {
    btn.onclick = () => {
        const id = btn.dataset.target;
        const input = document.getElementById(id);
        if (input.value > 1) {
            input.value--;
            input.dispatchEvent(new Event('change'));
        }
    };
});

document.querySelectorAll(".plus-btn").forEach(btn => {
    btn.onclick = () => {
        const id = btn.dataset.target;
        const input = document.getElementById(id);
        input.value++;
        input.dispatchEvent(new Event('change'));
    };
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/cart/index.blade.php ENDPATH**/ ?>