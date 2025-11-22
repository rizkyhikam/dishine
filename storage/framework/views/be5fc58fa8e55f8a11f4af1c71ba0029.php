<?php $__env->startSection('title', 'Home - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<!-- HERO / SLIDER -->
<div class="relative overflow-hidden bg-gray-100">
    <div id="slider" class="flex transition-transform duration-700 ease-in-out">
        <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="w-full flex-shrink-0 relative">
                <img src="<?php echo e(asset('storage/' . $slide->image)); ?>"
                     alt="<?php echo e($slide->alt ?? 'Dishine Slider'); ?>"
                     class="w-full h-[70vh] object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Navigation Dots -->
    <?php if($sliders->count() > 1): ?>
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button class="slider-dot w-3 h-3 rounded-full bg-white bg-opacity-50 transition-all duration-300 <?php echo e($index === 0 ? 'bg-opacity-100 w-8' : ''); ?>"
                        data-slide="<?php echo e($index); ?>"></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<?php if($sliders->isEmpty()): ?>
    <div class="w-full h-64 bg-gradient-to-r from-[#F0E7DB] to-[#EBE6E6] flex items-center justify-center">
        <div class="text-center">
            <i data-lucide="image" class="w-16 h-16 text-[#AE8B56] mx-auto mb-4"></i>
            <p class="text-gray-600 text-lg">Belum ada slider ditambahkan</p>
        </div>
    </div>
<?php endif; ?>

<!-- SLOGAN / HERO TEXT -->
<div class="py-20 bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6]">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <!-- Logo -->
        <div class="mb-8" data-aos="fade-up">
            <img src="<?php echo e(asset('logodishine.png')); ?>" 
                 alt="Dishine Logo" 
                 class="mx-auto h-32 mb-6 drop-shadow-lg">
        </div>

        <!-- Heading -->
        <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight" data-aos="fade-up" data-aos-delay="200">
            <span class="text-[#AE8B56]">Design with Quality,</span><br>
            <span class="text-[#3c2f2f]">Style with Faith</span>
        </h1>

        <!-- Description -->
        <p class="text-xl text-[#CC8650] mb-10 max-w-2xl mx-auto leading-relaxed" 
           data-aos="fade-up" data-aos-delay="300">
            Koleksi eksklusif Dishine untuk muslimah yang ingin tampil menawan 
            tanpa meninggalkan kesederhanaan dan nilai-nilai islami.
        </p>

        <!-- CTA Button -->
        <div data-aos="fade-up" data-aos-delay="400">
            <a href="<?php echo e(route('katalog')); ?>" 
               class="inline-flex items-center px-10 py-4 bg-[#CC8650] text-white text-lg font-semibold rounded-xl hover:bg-[#AE8B56] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i data-lucide="shopping-bag" class="w-5 h-5 mr-3"></i>
                Jelajahi Koleksi
            </a>
        </div>
    </div>
</div>

<!-- FEATURED PRODUCTS -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Section Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#F0E7DB] rounded-2xl mb-4">
                <i data-lucide="crown" class="w-8 h-8 text-[#CC8650]"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-800 mb-3">Produk Terlaris</h2>
            <p class="text-xl text-[#AE8B56] max-w-2xl mx-auto">
                Pilihan favorit pelanggan kami dengan kualitas terbaik
            </p>
        </div>
        
        <!-- Products Grid -->
        <?php if($featuredProducts->isEmpty()): ?>
            <div class="text-center py-12" data-aos="fade-up">
                <div class="bg-[#F0E7DB] p-8 rounded-2xl inline-block">
                    <i data-lucide="package" class="w-16 h-16 text-[#AE8B56] mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Produk Unggulan</h3>
                    <p class="text-gray-500">Silakan cek kembali nanti!</p>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.product_card', [
                        'product' => $product, 
                        'delay' => $loop->index * 100,
                        'show_badge' => true
                    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <!-- View All Button -->
        <div class="text-center mt-16" data-aos="fade-up">
            <a href="<?php echo e(route('katalog')); ?>" 
               class="inline-flex items-center px-8 py-3 border-2 border-[#CC8650] text-[#CC8650] rounded-xl hover:bg-[#CC8650] hover:text-white font-semibold transition-all duration-300 group">
                <span>Lihat Semua Koleksi</span>
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>

<!-- FEATURES SECTION -->
<div class="py-20 bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6]">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Mengapa Memilih Dishine?</h2>
            <p class="text-xl text-[#AE8B56]">Keunggulan yang membuat kami berbeda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-8 bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-[#CC8650] rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="award" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Kualitas Premium</h3>
                <p class="text-gray-600">Bahan pilihan dengan jahitan rapi untuk kenyamanan maksimal</p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center p-8 bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-[#CC8650] rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Syariah Compliant</h3>
                <p class="text-gray-600">Desain modis yang tetap menjaga nilai-nilai islami</p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center p-8 bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 bg-[#CC8650] rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="truck" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Pengiriman Cepat</h3>
                <p class="text-gray-600">Proses pengiriman aman dan cepat ke seluruh Indonesia</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Enhanced Slider Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('slider');
        const dots = document.querySelectorAll('.slider-dot');
        
        if (slider && slider.children.length > 1) {
            const totalSlides = slider.children.length;
            let index = 0;
            let slideInterval;

            function goToSlide(slideIndex) {
                index = slideIndex;
                slider.style.transform = `translateX(-${index * 100}%)`;
                
                // Update dots
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-opacity-100', i === index);
                    dot.classList.toggle('bg-opacity-50', i !== index);
                    dot.classList.toggle('w-8', i === index);
                    dot.classList.toggle('w-3', i !== index);
                });
            }

            function autoSlide() {
                index = (index + 1) % totalSlides;
                goToSlide(index);
            }

            // Click events for dots
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    goToSlide(i);
                    resetAutoSlide();
                });
            });

            function resetAutoSlide() {
                clearInterval(slideInterval);
                slideInterval = setInterval(autoSlide, 5000);
            }

            // Start auto slide
            slideInterval = setInterval(autoSlide, 5000);

            // Pause on hover
            slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
            slider.addEventListener('mouseleave', () => slideInterval = setInterval(autoSlide, 5000));
        }

        // Initialize Lucide icons
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/home.blade.php ENDPATH**/ ?>