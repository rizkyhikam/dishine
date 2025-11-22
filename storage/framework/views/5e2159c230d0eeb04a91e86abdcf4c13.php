<?php $__env->startSection('title', 'Katalog Produk - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] min-h-screen py-12">
    
    <!-- Header Section -->
    <div class="relative overflow-hidden mb-12">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="mb-6" data-aos="fade-up">
                <img src="<?php echo e(asset('modelkatalog.png')); ?>" 
                     alt="Dishine Collection Models" 
                     class="w-full h-64 md:h-80 lg:h-96 object-cover rounded-2xl shadow-lg mx-auto">
            </div>
            
            <div data-aos="fade-up" data-aos-delay="100">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Koleksi Eksklusif
                </h1>
                <p class="text-xl text-[#AE8B56] max-w-2xl mx-auto leading-relaxed">
                    Temukan gaya muslimah modern dengan kualitas terbaik dari Dishine
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6">
        <?php if($is_search): ?>
            <!-- Search Results -->
            <div class="mb-8" data-aos="fade-up">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">
                            Hasil Pencarian
                        </h2>
                        <p class="text-[#AE8B56] mt-2">
                            Untuk: <span class="font-semibold">"<?php echo e($search_term); ?>"</span>
                        </p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Ditemukan <?php echo e($search_results->count()); ?> produk
                    </div>
                </div>

                <?php if($search_results->isEmpty()): ?>
                    <div class="text-center py-16" data-aos="fade-up">
                        <div class="bg-white p-8 rounded-2xl shadow-sm inline-block">
                            <i data-lucide="search-x" class="w-16 h-16 text-[#AE8B56] mx-auto mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Hasil</h3>
                            <p class="text-gray-500 mb-4">Tidak ada produk yang cocok dengan pencarian Anda.</p>
                            <a href="<?php echo e(route('katalog')); ?>" 
                               class="inline-flex items-center px-6 py-2 bg-[#CC8650] text-white rounded-lg hover:bg-[#AE8B56] transition-all">
                                Lihat Semua Produk
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php $__currentLoopData = $search_results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('partials.product_card', [
                                'product' => $product, 
                                'delay' => $loop->index * 50
                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- Categories View -->
            <?php if($categories->isEmpty()): ?>
                <div class="text-center py-16" data-aos="fade-up">
                    <div class="bg-white p-8 rounded-2xl shadow-sm inline-block">
                        <i data-lucide="package" class="w-16 h-16 text-[#AE8B56] mx-auto mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Produk</h3>
                        <p class="text-gray-500">Silakan cek kembali nanti!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-16" data-aos="fade-up">
                        <!-- Category Header -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-4">
                                <div class="w-2 h-8 bg-[#CC8650] rounded-full"></div>
                                <h2 class="text-3xl font-bold text-gray-800">
                                    <?php echo e($category->name); ?>

                                </h2>
                            </div>
                            <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full">
                                <?php echo e($category->products->count()); ?> produk
                            </span>
                        </div>
                        
                        <!-- Products Grid -->
                        <?php if($category->products->isEmpty()): ?>
                            <div class="text-center py-8 text-gray-500">
                                <i data-lucide="package-x" class="w-8 h-8 mx-auto mb-2"></i>
                                <p>Belum ada produk dalam kategori ini</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                <?php $__currentLoopData = $category->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('partials.product_card', [
                                        'product' => $product, 
                                        'delay' => $loop->index * 50
                                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <!-- CTA Section -->
    <?php if(!$is_search && $categories->isNotEmpty()): ?>
    <div class="max-w-4xl mx-auto px-6 mt-16 text-center" data-aos="fade-up">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-[#D4BA98]">
            <i data-lucide="sparkles" class="w-12 h-12 text-[#CC8650] mx-auto mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Tidak Menemukan yang Dicari?</h3>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                Hubungi kami untuk informasi produk custom atau koleksi terbaru
            </p>
            <a href="https://wa.me/6281291819276?text=Halo%20Dishine,%20saya%20ingin%20bertanya%20tentang%20produk%20kustom"
               target="_blank"
               class="inline-flex items-center px-6 py-3 bg-[#CC8650] text-white rounded-xl hover:bg-[#AE8B56] transition-all font-semibold">
                <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                Konsultasi via WhatsApp
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/katalog.blade.php ENDPATH**/ ?>