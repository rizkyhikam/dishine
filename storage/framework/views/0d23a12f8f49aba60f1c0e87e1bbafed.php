<?php $__env->startSection('content'); ?>
<div class="bg-[#f3e8e3] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        
        <?php if(session('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-sm" role="alert">
                <div class="flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold"><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6 shadow-sm" role="alert">
                <div class="flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold"><?php echo e(session('error')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php
            $variantData      = $variantData      ?? collect();
            $defaultSizesData = $defaultSizesData ?? collect();
            $hasVariants    = $variantData->count() > 0;
            $hasDefaultSize = !$hasVariants && $defaultSizesData->count() > 0;
        ?>

        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                
                
                
                
                <div class="p-8" data-aos="fade-right">
                    <div class="mb-6 rounded-2xl overflow-hidden shadow-lg border-4 border-[#b48a60]">
                        <img
                            id="main-image"
                            src="<?php echo e(asset('storage/' . $product->gambar)); ?>"
                            alt="<?php echo e($product->nama); ?>"
                            class="w-full h-auto object-cover transition-all duration-300 aspect-square"
                        >
                    </div>

                    <div class="flex space-x-3 overflow-x-auto pb-2">
                        
                        <div class="w-20 h-20 flex-shrink-0">
                            <img
                                src="<?php echo e(asset('storage/' . $product->gambar)); ?>"
                                alt="Thumbnail"
                                class="thumbnail-image w-full h-full object-cover rounded-xl cursor-pointer border-3 border-[#b48a60] hover:scale-105 transition-transform shadow-md"
                            >
                        </div>

                        
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="w-20 h-20 flex-shrink-0">
                                <img
                                    src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                    alt="Thumbnail"
                                    class="thumbnail-image w-full h-full object-cover rounded-xl cursor-pointer border-2 border-gray-300 hover:border-[#b48a60] hover:scale-105 transition-all shadow-md"
                                >
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                
                
                <div class="p-8 bg-gradient-to-br from-white to-[#faf8f6]" data-aos="fade-left" data-aos-delay="100">
                    
                    <h1 class="text-3xl lg:text-4xl font-bold text-[#3c2f2f] mb-3">
                        <?php echo e($product->nama); ?>

                    </h1>

                    
                    <div class="mb-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-[#b48a60] bg-opacity-20 text-[#8B6F47]">
                            <i data-lucide="tag" class="w-4 h-4 mr-2"></i>
                            <?php echo e($product->category->name ?? 'N/A'); ?>

                        </span>
                    </div>
                    
                    
                    <div class="mb-8">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(Auth::user()->role === 'reseller'): ?>
                                <div class="flex items-baseline space-x-3">
                                    <span class="text-4xl font-bold text-[#d32f2f]">
                                        Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                                    </span>
                                    <span class="text-xl text-gray-400 line-through">
                                        Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="text-4xl font-bold text-[#d32f2f]">
                                    Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                                </span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-4xl font-bold text-[#d32f2f]">
                                Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <hr class="border-gray-300 mb-6">

                    
                    
                    
                    <form method="POST">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="variant_size_id" id="variant_size_id">
                        <input type="hidden" name="variant_id" id="variant_id"> 
                        <input type="hidden" name="size_id" id="size_id">

                        
                        <?php if($hasVariants): ?>
                            <div class="mb-6">
                                <p class="text-base font-bold text-[#3c2f2f] mb-3">Warna:</p>
                                <div id="warnaContainer" class="flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $variantData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($variant['stok'] > 0): ?>
                                            <button
                                                type="button"
                                                class="warna-btn px-5 py-2.5 rounded-lg border-2 border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:border-[#b48a60] hover:bg-[#f3e8e3] transition-all"
                                                data-variant-id="<?php echo e($variant['id']); ?>"
                                            >
                                                <?php echo e($variant['warna']); ?>

                                            </button>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            
                            <div class="mb-6">
                                <p class="text-base font-bold text-[#3c2f2f] mb-3">Ukuran:</p>
                                <div id="sizeContainer" class="flex flex-wrap gap-2">
                                    
                                </div>
                                <p id="sizeStockInfo" class="text-xs text-gray-500 mt-2"></p>
                            </div>

                        <?php elseif($hasDefaultSize): ?>
                            
                            <div class="mb-6">
                                <p class="text-base font-bold text-[#3c2f2f] mb-3">Ukuran:</p>
                                <div id="sizeContainer" class="flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $defaultSizesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($row['stok'] > 0): ?>
                                            <button
                                                type="button"
                                                class="size-btn px-5 py-2.5 rounded-lg border-2 border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:border-[#b48a60] hover:bg-[#f3e8e3] transition-all"
                                                data-size-id="<?php echo e($row['id']); ?>"
                                                data-size-name="<?php echo e($row['name']); ?>"
                                                data-stock="<?php echo e($row['stok']); ?>"
                                                data-row-id="<?php echo e($row['row_id']); ?>"
                                            >
                                                <?php echo e($row['name']); ?>

                                            </button>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <p id="sizeStockInfo" class="text-xs text-gray-500 mt-2"></p>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($isReseller): ?>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 px-4 py-3 rounded-lg mb-6 flex items-start">
                                <i data-lucide="alert-triangle" class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0"></i>
                                <span class="text-sm">Minimal pembelian untuk Reseller adalah <strong><?php echo e($minQuantity); ?> item</strong>.</span>
                            </div>
                        <?php endif; ?>

                        
                        <div class="flex items-center space-x-4 mb-8">
                            
                            <div class="flex items-center border-2 border-gray-300 rounded-lg overflow-hidden">
                                <button type="button" onclick="decreaseQty()" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 transition">
                                    <i data-lucide="minus" class="w-5 h-5"></i>
                                </button>
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    class="w-20 text-center border-0 focus:outline-none font-bold text-lg"
                                    value="<?php echo e($minQuantity); ?>"
                                    min="<?php echo e($minQuantity); ?>"
                                    max="<?php echo e($product->stok); ?>"
                                    readonly
                                >
                                <button type="button" onclick="increaseQty()" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 transition">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                </button>
                            </div>

                            
                            <button
                                type="submit"
                                formaction="<?php echo e(route('cart.add', $product->id)); ?>"
                                class="flex-1 flex items-center justify-center space-x-2 px-6 py-4 rounded-xl border-2 border-[#8B6F47] text-[#8B6F47] hover:bg-[#8B6F47] hover:text-white transition-all font-bold"
                            >
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                <span>Keranjang</span>
                            </button>
                        </div>

                        
                        <button
                            type="submit"
                            formaction="<?php echo e(route('cart.buyNow', $product->id)); ?>"
                            class="w-full bg-gradient-to-r from-[#8B6F47] to-[#6d5636] text-white px-6 py-4 rounded-xl hover:shadow-xl transform hover:-translate-y-0.5 transition-all font-bold text-lg flex items-center justify-center space-x-2"
                        >
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            <span>Beli Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        
        
        <div class="bg-white rounded-2xl shadow-xl p-8" data-aos="fade-up">
            <h2 class="text-2xl font-bold text-[#3c2f2f] mb-6 flex items-center">
                <i data-lucide="file-text" class="w-6 h-6 mr-3 text-[#b48a60]"></i>
                Deskripsi Produk
            </h2>
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                <?php echo nl2br(e($product->deskripsi)); ?>

            </div>
        </div>

    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // ==================== GALERI FOTO ====================
        const mainImage = document.getElementById('main-image');
        const thumbnails = document.querySelectorAll('.thumbnail-image');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function () {
                mainImage.src = this.src;
                thumbnails.forEach(t => {
                    t.classList.remove('border-[#b48a60]', 'border-3');
                    t.classList.add('border-gray-300', 'border-2');
                });
                this.classList.add('border-[#b48a60]', 'border-3');
                this.classList.remove('border-gray-300');
            });
        });

        // ==================== QUANTITY CONTROLS ====================
        const qtyInput = document.getElementById('quantity');
        const minQty = <?php echo e((int) $minQuantity); ?>;

        window.decreaseQty = function() {
            let current = parseInt(qtyInput.value);
            let min = parseInt(qtyInput.min);
            if (current > min) {
                qtyInput.value = current - 1;
            }
        };

        window.increaseQty = function() {
            let current = parseInt(qtyInput.value);
            let max = parseInt(qtyInput.max);
            if (current < max) {
                qtyInput.value = current + 1;
            }
        };

        // ==================== DATA VARIAN / SIZE ====================
        const variants     = <?php echo json_encode($variantData ?? [], 15, 512) ?>;
        const defaultSizes = <?php echo json_encode($defaultSizesData ?? [], 15, 512) ?>; 
        const hasVariants  = variants.length > 0;

        const sizeContainer         = document.getElementById('sizeContainer');
        const sizeStockInfo         = document.getElementById('sizeStockInfo');
        const inputVariantSizeId    = document.getElementById('variant_size_id');
        const inputSizeId           = document.getElementById('size_id');
        const inputVariantId        = document.getElementById('variant_id'); 
        
        function setQuantityLimit(stockTotal) {
            if (!qtyInput) return;
            qtyInput.max = stockTotal;
            const current = parseInt(qtyInput.value || 0);
            if (current > stockTotal) {
                qtyInput.value = Math.max(minQty, Math.min(stockTotal, current));
            }
            qtyInput.min = stockTotal >= minQty ? minQty : 1;
        }

        function attachSizeListeners(buttons) {
            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    buttons.forEach(b => {
                        b.classList.remove('bg-[#b48a60]', 'text-white', 'border-[#b48a60]');
                        b.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                    });
                    this.classList.add('bg-[#b48a60]', 'text-white', 'border-[#b48a60]');
                    this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');

                    const rowId = this.dataset.rowId; 
                    inputVariantSizeId.value = rowId;
                    inputSizeId.value = this.dataset.sizeId;

                    const stok = parseInt(this.dataset.stock || 0);
                    sizeStockInfo.textContent = 'Stok tersedia: ' + stok + ' item';
                    setQuantityLimit(stok);
                });
            });
            
            if (buttons.length > 0) {
                buttons[0].click();
            }
        }
        
        // ========== KASUS 1: MODE DENGAN VARIAN WARNA ==========
        if (hasVariants) {
            const warnaButtons = document.querySelectorAll('.warna-btn');

            function renderSizesForVariant(variantId) {
                sizeContainer.innerHTML = '';
                sizeStockInfo.textContent = '';
                inputVariantSizeId.value = '';

                const variant = variants.find(v => v.id == variantId);
                if (!variant) return;

                inputVariantId.value = variantId; 
                
                warnaButtons.forEach(btn => {
                    if (btn.dataset.variantId == variantId) {
                        btn.classList.add('bg-[#b48a60]', 'text-white', 'border-[#b48a60]');
                        btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    } else {
                        btn.classList.remove('bg-[#b48a60]', 'text-white', 'border-[#b48a60]');
                        btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                    }
                });

                const sizeButtonsArray = [];
                (variant.sizes || []).forEach(function (s) {
                    if (s.stok <= 0) return;

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = s.name;
                    btn.className = 'size-btn px-5 py-2.5 rounded-lg border-2 border-gray-300 text-sm font-semibold text-gray-700 bg-white hover:border-[#b48a60] hover:bg-[#f3e8e3] transition-all';
                    btn.dataset.sizeId   = s.id;
                    btn.dataset.stock    = s.stok;
                    btn.dataset.sizeName = s.name;
                    btn.dataset.rowId    = s.row_id;

                    sizeContainer.appendChild(btn);
                    sizeButtonsArray.push(btn);
                });
                
                attachSizeListeners(sizeButtonsArray);
            }

            if (warnaButtons.length > 0) {
                renderSizesForVariant(warnaButtons[0].dataset.variantId);
            }

            warnaButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    renderSizesForVariant(this.dataset.variantId);
                });
            });

        } else if (!hasVariants && defaultSizes.length > 0 && sizeContainer) {
            const sizeButtons = sizeContainer.querySelectorAll('.size-btn');
            attachSizeListeners(sizeButtons);
        } else {
            setQuantityLimit(<?php echo e((int) $product->stok); ?>);
        }

        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/detail_produk.blade.php ENDPATH**/ ?>