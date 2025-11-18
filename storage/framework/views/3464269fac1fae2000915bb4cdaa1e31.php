<!-- 
    File ini berisi 1 kartu produk.
-->
<div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300"
     data-aos="fade-up" 
     data-aos-delay="<?php echo e($delay ?? 0); ?>">
    
    <a href="<?php echo e(url('/products/' . $product->id)); ?>" class="block">     
        <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
             alt="<?php echo e($product->nama); ?>" 
             class="w-full h-64 object-cover">
    </a>
    
    <div class="p-4">
        <h3 class="text-lg font-semibold text-[#3c2f2f] mb-2 truncate"><?php echo e($product->nama); ?></h3>
        <p class="text-xs text-[#a07850] mb-2"><?php echo e($product->category->name ?? 'N/A'); ?></p>
        <p class="text-sm text-[#7a6a5a] mb-3 min-h-[48px]">
            <?php echo e(Str::limit($product->deskripsi, 60)); ?>

        </p>
        
        <div class="text-[#4a3b2f] font-bold mb-4">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->role == 'reseller'): ?>
                    Rp <?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                    <span class="text-xs text-red-600 line-through ml-1">Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?></span>
                <?php else: ?>
                    Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                <?php endif; ?>
            <?php else: ?>
                Rp <?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

            <?php endif; ?>
        </div>

        <!-- 
        =================================
        TOMBOL (SUDAH DIPERBAIKI)
        =================================
        -->
        <div class="flex items-center space-x-3 w-full">
            
            <!-- Tombol beli sekarang (SEKARANG MENJADI FORM) -->
            <form action="<?php echo e(route('cart.buyNow', $product->id)); ?>" method="POST" class="w-full">
                <?php echo csrf_field(); ?>
                <!-- 
                    Tidak ada input kuantitas di sini.
                    Controller (logicAddToCart) akan otomatis memakai
                    kuantitas minimum (1 untuk pelanggan, 5 untuk reseller)
                -->
                <button type="submit" class="bg-[#44351f] text-white w-full px-7 py-2 rounded-md hover:bg-[#a07850] transition text-center">
                    Beli Sekarang
                </button>
            </form>

            <!-- Tombol tambah ke keranjang (Sudah benar) -->
            <form action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                    class="text-[#b48a60] hover:text-[#a07850]">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </button>
            </form>
        </div>
        <!-- =============================== -->
    </div>
</div><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/partials/product_card.blade.php ENDPATH**/ ?>