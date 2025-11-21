<div class="grid grid-cols-7 items-center py-4 border-b bg-white">

    
    <div class="col-span-3 flex items-center space-x-4">
        <input type="checkbox" checked class="w-5 h-5">

        <img src="<?php echo e(asset('storage/' . $item->product->gambar)); ?>" 
             class="w-20 h-20 object-cover rounded">

        <div>
            <span class="font-semibold text-[#3c2f2f] block">
                <?php echo e($item->product->nama); ?>

            </span>
            
            
            <?php if($item->variant_size_id): ?>
                <?php
                    $stokRow = App\Http\Controllers\CartController::findStokRow($item->variant_size_id);
                    $detailText = null;

                    if ($stokRow) {
                        // Cek apakah ini VariantSize (Varian Penuh: Warna + Ukuran)
                        if ($stokRow instanceof App\Models\VariantSize) {
                            // Relasi sudah dimuat di controller!
                            $warna = $stokRow->productVariant->warna ?? 'N/A';
                            $ukuran = $stokRow->size->name ?? 'N/A';
                            
                            $detailText = "Varian: {$warna}, Ukuran: {$ukuran}";
                        } 
                        // Cek apakah ini DefaultProductSize (Non-Varian/Hanya Ukuran)
                        elseif ($stokRow instanceof App\Models\DefaultProductSize) {
                            // Relasi sudah dimuat di controller!
                            $ukuran = $stokRow->size->name ?? 'N/A';
                            
                            // HANYA tampilkan ukuran tanpa label "Varian:"
                            $detailText = "Ukuran: {$ukuran}"; 
                        }
                    }
                ?>
                
                <?php if($detailText): ?>
                    <p class="text-sm text-gray-500 mt-1">
                        <?php echo $detailText; ?>

                    </p>
                <?php else: ?>
                    <p class="text-sm text-red-500 mt-1">
                        Detail Varian Hilang.
                    </p>
                <?php endif; ?>
                
            <?php else: ?>
                <p class="text-sm text-gray-500 mt-1">
                    Produk Tanpa Opsi Ukuran.
                </p>
            <?php endif; ?>
            

        </div>
    </div>

    
    <div class="text-center text-[#3c2f2f]">
        
        <?php
            $currentPrice = (Auth::check() && Auth::user()->role == 'reseller') ? $item->product->harga_reseller : $item->product->harga_normal;
        ?>
        Rp <?php echo e(number_format($currentPrice, 0, ',', '.')); ?>

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
            Rp <?php echo e(number_format($currentPrice * $item->quantity, 0, ',', '.')); ?>

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
</div><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/cart/partials/item.blade.php ENDPATH**/ ?>