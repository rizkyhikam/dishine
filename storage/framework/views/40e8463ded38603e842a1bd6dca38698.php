<?php $__env->startSection('content'); ?>
<div class="bg-[#f3e8e3] py-12">
    
    <!-- Bagian Banner Atas (Sama seperti lama) -->
    <div class="text-center mb-10">
        <img src="<?php echo e(asset('modelkatalog.png')); ?>" 
             alt="Dishine Collection Models" 
             class="w-full h-64 md:h-80 lg:h-100 object-cover"
             data-aos="fade-up"> <br>
        <p class="text-[#6b5a4a]" data-aos="fade-up" data-aos-delay="80">Koleksi eksklusif Dishine untuk muslimah yang ingin tampil menawan tanpa meninggalkan kesederhanaan.</p>
    </div>

    <!-- 
        =================================
        KONTEN DINAMIS (BARU)
        =================================
    -->
    <div class="max-w-6xl mx-auto px-6">

        <?php if($is_search): ?>
            <!-- 
                =================================
                TAMPILAN JIKA SEDANG MENCARI
                =================================
            -->
            <h2 class="text-2xl font-bold text-[#3c2f2f] mb-6" data-aos="fade-up">
                Hasil pencarian untuk: <span class="text-[#a07850]">"<?php echo e($search_term); ?>"</span>
            </h2>

            <?php if($search_results->isEmpty()): ?>
                <div class="text-center text-[#a07850] font-medium" data-aos="fade-up">
                    Tidak ada produk yang cocok dengan pencarian Anda.
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    <?php $__currentLoopData = $search_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!-- Ini adalah Card Produk Anda (Tampilan Datar) -->
                        <?php echo $__env->make('partials.product_card', ['product' => $product, 'delay' => $loop->index * 30], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- 
                =================================
                TAMPILAN DEFAULT (BROWSE PER KATEGORI)
                (Sesuai Hi-Fi Anda)
                =================================
            -->
            <?php if($categories->isEmpty()): ?>
                <div class="text-center text-[#a07850] font-medium" data-aos="fade-up">
                    Belum ada produk yang tersedia.
                </div>
            <?php else: ?>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-12" data-aos="fade-up">
                        <!-- Judul Kategori (e.g., "Dress", "Hijab") -->
                        <h2 class="text-3xl font-bold text-[#3c2f2f] mb-6 border-b-2 border-[#d3c1b6] pb-2">
                            <?php echo e($category->name); ?>

                        </h2>
                        
                        <!-- Grid Produk per Kategori -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                            <?php $__currentLoopData = $category->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!-- Ini adalah Card Produk Anda -->
                                <?php echo $__env->make('partials.product_card', ['product' => $product, 'delay' => $loop->index * 30], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/katalog.blade.php ENDPATH**/ ?>