<?php $__env->startSection('title', 'Home - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<!-- HERO / SLIDER -->
<div class="relative overflow-hidden">
    <div id="slider" class="flex transition-transform duration-700 ease-in-out">
        <img src="<?php echo e(asset('modelhome.png')); ?>" 
             alt="Dishine 1" 
             class="w-full h-auto object-cover max-h-[550px] flex-shrink-0"
             data-aos="fade-in"
             data-aos-duration="1000">
        <img src="<?php echo e(asset('modelhome2.jpg')); ?>" 
             alt="Dishine 2" 
             class="w-full h-auto object-cover max-h-[550px] flex-shrink-0">
        <img src="<?php echo e(asset('modelhome3.jpg')); ?>" 
             alt="Dishine 3" 
             class="w-full h-auto object-cover max-h-[550px] flex-shrink-0">
    </div>
</div>

<!-- Script auto-slide -->
<script>
    const slider = document.getElementById('slider');
    // Periksa apakah slider ada sebelum melanjutkan
    if (slider) {
        const totalSlides = slider.children.length;
        let index = 0;

        function autoSlide() {
            index = (index + 1) % totalSlides;
            slider.style.transform = `translateX(-${index * 100}%)`;
        }

        if (totalSlides > 1) { // Hanya jalankan jika ada lebih dari 1 slide
            setInterval(autoSlide, 4000); // ganti setiap 4 detik
        }
    }
</script>


<!-- SLOGAN / HERO TEXT -->
<div class="text-center py-20" style="background-color: #f8f5f2;" data-aos="fade-up">
    <img src="<?php echo e(asset('logodishine.png')); ?>" 
         alt="Dishine Logo" 
         class="mx-auto h-40 mb-6"
         data-aos="fade-up">

    <h1 class="text-4xl font-serif font-extrabold mb-4" style="color: #AE8B56;"
        data-aos="fade-up" data-aos-delay="200">
        Design with Quality, <br>
        <span style="color: #3c2f2f;">Style with Faith</span>
    </h1>

    <p class="mb-8 max-w-lg mx-auto text-sm" style="color: #CC8550;" data-aos="fade-up" data-aos-delay="300">
        Koleksi eksklusif Dishine untuk muslimah yang ingin tampil menawan tanpa meninggalkan kesederhanaan.
    </p> 

    <a href="<?php echo e(route('katalog')); ?>" 
       class="inline-block px-8 py-3 rounded-md shadow-lg text-white font-medium" 
       style="background-color: #3c2f2f;" data-aos="fade-zoom-in" data-aos-delay="400">
        Order
    </a>
</div>

<!-- 
=================================
PRODUK UNGGULAN (VERSI UPGRADE)
=================================
Menggunakan desain yang sama dengan 'katalog.blade.php'
-->
<div class="bg-[#f3e8e3] py-20"> <!-- Ganti background agar senada -->
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-[#3c2f2f]">Produk Terlaris</h2>
            <p class="text-lg text-[#a07850] mt-1">Pilihan favorit pelanggan kami.</p>
        </div>
        
        <?php if($featuredProducts->isEmpty()): ?>
            <div class="text-center text-[#a07850] font-medium" data-aos="fade-up">
                <p>Belum ada produk unggulan untuk ditampilkan.</p>
                <p class="text-sm">Silakan cek kembali nanti!</p>
            </div>
        <?php else: ?>
            <!-- Kita panggil 'product_card' yang sudah kita buat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- Ini akan otomatis menampilkan harga reseller/normal -->
                    <?php echo $__env->make('partials.product_card', ['product' => $product, 'delay' => $loop->index * 100], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-12" data-aos="fade-up">
            <a href="<?php echo e(route('katalog')); ?>" 
               class="inline-block px-8 py-3 rounded-md shadow-lg text-white font-medium bg-[#44351f] hover:bg-[#a07850] transition">
                Lihat Semua Koleksi
            </a>
        </div>
    </div>
</div>
<!-- =============================== -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/home.blade.php ENDPATH**/ ?>