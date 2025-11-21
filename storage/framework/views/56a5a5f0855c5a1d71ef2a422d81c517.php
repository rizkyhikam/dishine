<?php $__env->startSection('content'); ?>
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-6xl mx-auto px-6">
        
        
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="font-medium"><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="font-medium"><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php
            // Safety guard
            $variantData      = $variantData      ?? collect();
            $defaultSizesData = $defaultSizesData ?? collect();

            $hasVariants    = $variantData->count() > 0;
            $hasDefaultSize = !$hasVariants && $defaultSizesData->count() > 0;
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            
            
            
            <div data-aos="fade-right">
                <div class="mb-4 rounded-xl overflow-hidden shadow-md">
                    <img
                        id="main-image"
                        src="<?php echo e(asset('storage/' . $product->gambar)); ?>"
                        alt="<?php echo e($product->nama); ?>"
                        class="w-full h-auto object-cover transition-all duration-300 aspect-square"
                    >
                </div>

                <div class="flex space-x-3 overflow-x-auto pb-2">
                    
                    <div class="w-24 h-24 flex-shrink-0">
                        <img
                            src="<?php echo e(asset('storage/' . $product->gambar)); ?>"
                            alt="Thumbnail (Cover)"
                            class="thumbnail-image w-full h-full object-cover rounded-md cursor-pointer border-2 border-[#a07850]"
                        >
                    </div>

                    
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="w-24 h-24 flex-shrink-0">
                            <img
                                src="<?php echo e(asset('storage/' . $image->path)); ?>"
                                alt="Thumbnail Galeri"
                                class="thumbnail-image w-full h-full object-cover rounded-md cursor-pointer border-2 border-transparent hover:border-[#a07850]"
                            >
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            
            
            <div data-aos="fade-left" data-aos-delay="100">
                
                <p class="text-sm text-[#a07850] font-semibold mb-2">
                    Kategori: <?php echo e($product->category->name ?? 'N/A'); ?>

                </p>

                
                <h1 class="text-3xl lg:text-4xl font-bold text-[#3c2f2f] mb-3">
                    <?php echo e($product->nama); ?>

                </h1>
                
                
                <div class="text-2xl font-bold text-[#4a3b2f] mb-4">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->role === 'reseller'): ?>
                            Rp <?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                            <span class="text-lg text-red-600 line-through ml-2">
                                Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                            </span>
                        <?php else: ?>
                            Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                        <?php endif; ?>
                    <?php else: ?>
                        Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                    <?php endif; ?>
                </div>

                
                <p class="text-sm text-[#7a6a5a] mb-4">
                    Stok Tersedia: <span class="font-semibold"><?php echo e($product->stok); ?></span>
                </p>

                
                
                
                <form method="POST">
                    <?php echo csrf_field(); ?>

                    
                    <input type="hidden" name="variant_size_id" id="variant_size_id">
                    
                    
                    <input type="hidden" name="variant_id" id="variant_id"> 
                    <input type="hidden" name="size_id" id="size_id">

                    
                    
                    
                    <?php if($hasVariants): ?>
                        
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-[#3c2f2f] mb-2">Warna:</p>
                            <div id="warnaContainer" class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $variantData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($variant['stok'] > 0): ?>
                                        <button
                                            type="button"
                                            class="warna-btn px-3 py-1 rounded-md border border-[#b48a60] text-sm text-[#3c2f2f] bg-white hover:bg-[#f3e8e3]"
                                            data-variant-id="<?php echo e($variant['id']); ?>"
                                        >
                                            <?php echo e($variant['warna']); ?>

                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-[#3c2f2f] mb-2">Ukuran:</p>
                            <div id="sizeContainer" class="flex flex-wrap gap-2">
                                
                            </div>
                            <p id="sizeStockInfo" class="text-xs text-[#7a6a5a] mt-1"></p>
                        </div>

                    <?php elseif($hasDefaultSize): ?>
                        
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-[#3c2f2f] mb-2">Ukuran:</p>
                            <div id="sizeContainer" class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $defaultSizesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($row['stok'] > 0): ?>
                                        <button
                                            type="button"
                                            class="size-btn px-3 py-1 rounded-md border border-[#b48a60] text-sm text-[#3c2f2f] bg-white hover:bg-[#f3e8e3]"
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
                            <p id="sizeStockInfo" class="text-xs text-[#7a6a5a] mt-1"></p>
                        </div>
                    <?php endif; ?>

                    
                    <div class="w-24 mb-4">
                        <label for="quantity" class="text-sm font-medium text-[#6b5a4a]">Jumlah:</label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            class="w-full border border-[#b48a60] rounded-md p-2 text-center"
                            value="<?php echo e($minQuantity); ?>"
                            min="<?php echo e($minQuantity); ?>"
                            max="<?php echo e($product->stok); ?>"
                        >
                    </div>

                    
                    <?php if($isReseller): ?>
                        <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 text-xs p-3 rounded-md mb-4">
                            Minimal pembelian untuk Reseller adalah <?php echo e($minQuantity); ?> item.
                        </div>
                    <?php endif; ?>

                    
                    <div class="flex items-center space-x-3 w-full">
                        
                        <button
                            type="submit"
                            formaction="<?php echo e(route('cart.buyNow', $product->id)); ?>"
                            class="flex-1 bg-[#44351f] text-white px-6 py-2.5 rounded-md hover:bg-[#a07850] transition text-center flex items-center justify-center"
                        >
                            Beli Sekarang
                        </button>
                        
                        
                        <button
                            type="submit"
                            formaction="<?php echo e(route('cart.add', $product->id)); ?>"
                            class="p-3 rounded-md border border-[#b48a60] text-[#b48a60] hover:bg-[#b48a60] hover:text-white transition"
                        >
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        </button>
                    </div>
                </form>

                
                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-[#3c2f2f] mb-2">Deskripsi Produk</h4>
                    <div class="text-[#7a6a5a] space-y-2">
                        <?php echo nl2br(e($product->deskripsi)); ?>

                    </div>
                </div>
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
                    t.classList.remove('border-[#a07850]', 'border-2');
                    t.classList.add('border-transparent');
                });
                this.classList.add('border-[#a07850]', 'border-2');
                this.classList.remove('border-transparent');
            });
        });

        // ==================== DATA VARIAN / SIZE ====================
        const variants     = <?php echo json_encode($variantData ?? [], 15, 512) ?>;
        const defaultSizes = <?php echo json_encode($defaultSizesData ?? [], 15, 512) ?>; 
        const hasVariants  = variants.length > 0;
        const minQty       = <?php echo e((int) $minQuantity); ?>;

        const sizeContainer         = document.getElementById('sizeContainer');
        const sizeStockInfo         = document.getElementById('sizeStockInfo');
        
        // KRITIS: input untuk menyimpan ID BARIS STOK
        const inputVariantSizeId    = document.getElementById('variant_size_id');
        const inputSizeId           = document.getElementById('size_id');
        const inputVariantId        = document.getElementById('variant_id'); 
        const qtyInput              = document.getElementById('quantity');
        
        function setQuantityLimit(stockTotal) {
            if (!qtyInput) return;

            qtyInput.max = stockTotal;

            const current = parseInt(qtyInput.value || 0);
            if (current > stockTotal) {
                qtyInput.value = Math.max(minQty, Math.min(stockTotal, current));
            }

            qtyInput.min = stockTotal >= minQty ? minQty : 1;
        }


        // --- FUNGSI UTAMA UNTUK MENGISI INPUT DARI KLIK ---
        function attachSizeListeners(buttons) {
            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    // 1. Highlight tombol
                    buttons.forEach(b => {
                        b.classList.remove('bg-[#b48a60]', 'text-white');
                        b.classList.add('bg-white', 'text-[#3c2f2f]');
                    });
                    this.classList.add('bg-[#b48a60]', 'text-white');

                    // 2. KRITIS: Simpan ID BARIS STOK
                    const rowId = this.dataset.rowId; 
                    inputVariantSizeId.value = rowId; // <-- MENGISI INPUT FINAL

                    // Simpan size_id lama (opsional)
                    inputSizeId.value = this.dataset.sizeId;

                    // 3. Logika Stok
                    const stok = parseInt(this.dataset.stock || 0);
                    sizeStockInfo.textContent = 'Stok untuk ukuran ini: ' + stok;
                    setQuantityLimit(stok);
                });
            });
            
            // Auto pilih tombol pertama
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
                inputVariantSizeId.value = ''; // Reset ID stok saat ganti warna

                const variant = variants.find(v => v.id == variantId);
                if (!variant) return;

                // inputVariantId digunakan di kode Anda untuk menyimpan ID varian utama
                inputVariantId.value = variantId; 
                
                // highlight warna
                warnaButtons.forEach(btn => {
                    if (btn.dataset.variantId == variantId) {
                        btn.classList.add('bg-[#b48a60]', 'text-white');
                        btn.classList.remove('bg-white', 'text-[#3c2f2f]');
                    } else {
                        btn.classList.remove('bg-[#b48a60]', 'text-white');
                        btn.classList.add('bg-white', 'text-[#3c2f2f]');
                    }
                });

                // tombol ukuran per variant
                const sizeButtonsArray = [];
                (variant.sizes || []).forEach(function (s) {
                    if (s.stok <= 0) return;

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = s.name;
                    btn.className = 'size-btn px-3 py-1 rounded-md border border-[#b48a60] text-sm text-[#3c2f2f] bg-white hover:bg-[#f3e8e3]';
                    btn.dataset.sizeId   = s.id;
                    btn.dataset.stock    = s.stok;
                    btn.dataset.sizeName = s.name;
                    btn.dataset.rowId    = s.row_id; // <-- ID Baris Stok (variant_sizes.id)

                    sizeContainer.appendChild(btn);
                    sizeButtonsArray.push(btn);
                });
                
                // Pasang listener dan klik tombol pertama
                attachSizeListeners(sizeButtonsArray);
            }

            // auto pilih warna pertama
            if (warnaButtons.length > 0) {
                renderSizesForVariant(warnaButtons[0].dataset.variantId);
            }

            warnaButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    renderSizesForVariant(this.dataset.variantId);
                });
            });

        // ========== KASUS 2: TANPA VARIAN, ADA DEFAULT SIZE (KASUS GAMIS MUSLIMAH) ==========
        } else if (!hasVariants && defaultSizes.length > 0 && sizeContainer) {
            
            // Tombol size-btn sudah di-render di HTML (Blade), kita ambil dari DOM
            const sizeButtons = sizeContainer.querySelectorAll('.size-btn');

            // Pasang listener dan panggil click() pada tombol pertama
            attachSizeListeners(sizeButtons);

        // ========== KASUS 3: BENAR-BENAR TANPA VARIAN & SIZE ==========
        } else {
            setQuantityLimit(<?php echo e((int) $product->stok); ?>);
        }

        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/detail_produk.blade.php ENDPATH**/ ?>