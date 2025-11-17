<div class="grid grid-cols-7 items-center py-4 border-b bg-white">

    
    <div class="col-span-3 flex items-center space-x-4">
        <input type="checkbox" checked class="w-5 h-5">

        <img src="<?php echo e(asset('storage/' . $item->product->gambar)); ?>" 
             class="w-20 h-20 object-cover rounded">

        <span class="font-semibold text-[#3c2f2f]">
            <?php echo e($item->product->nama); ?>

        </span>
    </div>

    
    <div class="text-center text-[#3c2f2f]">
        Rp <?php echo e(number_format($item->product->harga_normal, 0, ',', '.')); ?>

    </div>

    
    <div class="text-center">

        <form action="<?php echo e(route('cart.update', $item->id)); ?>" 
            method="POST"
            class="inline-flex items-center space-x-1">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <button type="button" 
                    class="px-2 py-1 border rounded minus-btn"
                    data-target="qty-<?php echo e($item->id); ?>">-</button>

            <input id="qty-<?php echo e($item->id); ?>" 
                type="number" name="quantity"
                value="<?php echo e($item->quantity); ?>" 
                min="1"
                class="w-12 text-center border rounded"
                onchange="this.form.submit()">

            <button type="button" 
                    class="px-2 py-1 border rounded plus-btn"
                    data-target="qty-<?php echo e($item->id); ?>">+</button>
        </form>

    </div>


    
    <div class="text-center">
        <strong>
            Rp <?php echo e(number_format($item->product->harga_normal * $item->quantity, 0, ',', '.')); ?>

        </strong>
    </div>

    <div class="text-center">
        <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <button class="px-3 text-[#b48a60] hover:text-[#a07850]">
            <i data-lucide="trash" class="w-5 h-5"></i>
        </button>
        </form>
    </div>
</div>
<?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/cart/partials/item.blade.php ENDPATH**/ ?>