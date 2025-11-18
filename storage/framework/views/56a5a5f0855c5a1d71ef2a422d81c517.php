

<?php $__env->startSection('content'); ?>
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <!-- KOLOM KIRI: GALERI FOTO -->
            <div data-aos="fade-right">
                <!-- ... (Galeri foto Anda, tidak perlu diubah) ... -->
                <div class="mb-4 rounded-xl overflow-hidden shadow-md">
                    <img id="main-image" 
                         src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
                         alt="<?php echo e($product->nama); ?>" 
                         class="w-full h-auto object-cover transition-all duration-300 aspect-square">
                </div>
                <div class="flex space-x-3 overflow-x-auto pb-2">
                    <div class="w-24 h-24 flex-shrink-0">
                        <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
                             alt="Thumbnail (Cover)" 
                             class="thumbnail-image w-full h-full object-cover rounded-md cursor-pointer border-2 border-[#a07850]">
                    </div>
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="w-24 h-24 flex-shrink-0">
                            <img src="<?php echo e(asset('storage/' . $image->path)); ?>" 
                                 alt="Thumbnail Galeri" 
                                 class="thumbnail-image w-full h-full object-cover rounded-md cursor-pointer border-2 border-transparent hover:border-[#a07850]">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- KOLOM KANAN: INFO PRODUK -->
            <div data-aos="fade-left" data-aos-delay="100">
                <p class="text-sm text-[#a07850] font-semibold mb-2">
                    Kategori: <?php echo e($product->category->name ?? 'N/A'); ?>

                </p>

                <h1 class="text-3xl lg:text-4xl font-bold text-[#3c2f2f] mb-3">
                    <?php echo e($product->nama); ?>

                </h1>
                
                <!-- 
                =================================
                HARGA (SUDAH DIPERBAIKI)
                =================================
                -->
                <div class="text-2xl font-bold text-[#4a3b2f] mb-4">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(Auth::user()->role == 'reseller'): ?>
                            <!-- Tampilkan Harga Reseller -->
                            Rp <?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                            <span class="text-lg text-red-600 line-through ml-2">Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?></span>
                        <?php else: ?>
                            <!-- Tampilkan Harga Normal -->
                            Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Tampilkan Harga Normal (jika belum login) -->
                        Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                    <?php endif; ?>
                </div>
                <!-- =============================== -->

                <p class="text-sm text-[#7a6a5a] mb-4">
                    Stok Tersedia: <span class="font-semibold"><?php echo e($product->stok); ?></span>
                </p>

                <!-- Form Keranjang -->
                <form action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="flex items-center space-x-4 mb-6">
                        <!-- Kuantitas -->
                        <div class="w-24">
                            <label for="quantity" class="text-sm font-medium text-[#6b5a4a]">Jumlah:</label>
                            
                            <!-- 
                            =================================
                            ATURAN KUANTITAS (BARU)
                            =================================
                            -->
                            <?php
                                $minQuantity = 1;
                                if(Auth::check() && Auth::user()->role == 'reseller') {
                                    $minQuantity = 5; // Reseller minimal 5
                                }
                            ?>
                            
                            <input type="number" id="quantity" name="quantity" 
                                   class="w-full border border-[#b48a60] rounded-md p-2 text-center" 
                                   value="<?php echo e($minQuantity); ?>" 
                                   min="<?php echo e($minQuantity); ?>" 
                                   max="<?php echo e($product->stok); ?>">
                        </div>

                        <!-- Tombol Keranjang -->
                        <div class="flex-1">
                            <label class="text-sm invisible">_</label> <!-- (untuk perataan) -->
                            <button type="submit" 
                                    class="w-full bg-[#44351f] text-white px-6 py-2.5 rounded-md hover:bg-[#a07850] transition text-center flex items-center justify-center space-x-2">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </div>
                    </div>
                    
                    <?php if($minQuantity > 1): ?>
                    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 text-sm p-3 rounded-md -mt-2 mb-4">
                        Minimal pembelian untuk Reseller adalah 5 item.
                    </div>
                    <?php endif; ?>
                    <!-- =============================== -->

                </form>

                <!-- Deskripsi -->
                <div class="mt-6">
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
    // ... (JavaScript galeri Anda, tidak perlu diubah) ...
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('main-image');
        const thumbnails = document.querySelectorAll('.thumbnail-image');

        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function () {
                mainImage.src = this.src;
                thumbnails.forEach(t => t.classList.remove('border-[#a07850]', 'border-2'));
                thumbnails.forEach(t => t.classList.add('border-transparent'));
                this.classList.add('border-[#a07850]', 'border-2');
                this.classList.remove('border-transparent');
            });
        });
        
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/detail_produk.blade.php ENDPATH**/ ?>