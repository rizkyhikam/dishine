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
    const totalSlides = slider.children.length;
    let index = 0;

    function autoSlide() {
        index = (index + 1) % totalSlides;
        slider.style.transform = `translateX(-${index * 100}%)`;
    }

    setInterval(autoSlide, 4000); // ganti setiap 4 detik
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

<!-- PRODUK UNGGULAN -->
<div class="container mx-auto px-4 py-10">
    <h2 class="text-3xl font-bold mb-6 text-center" data-aos="fade-up">Produk Unggulan</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php $__currentLoopData = $featuredProducts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow-md p-4" data-aos="fade-up" data-aos-delay="100">
            <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
                 alt="<?php echo e($product->nama); ?>" 
                 class="w-full h-48 object-cover rounded">
            <h3 class="text-xl font-semibold mt-4"><?php echo e($product->nama); ?></h3>
            <p class="text-[#b48a60] font-bold">Rp <?php echo e(number_format($product->harga_normal)); ?></p>
            <a href="<?php echo e(route('katalog.show', $product->id)); ?>" 
               class="btn-primary block text-center mt-4 px-4 py-2 rounded">
               Lihat Detail
            </a>
            <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-primary mt-2 block text-center">
               Lihat Keranjang
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/home.blade.php ENDPATH**/ ?>