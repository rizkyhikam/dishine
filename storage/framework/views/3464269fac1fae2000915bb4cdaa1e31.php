<!-- 
    Product Card Component - Modern Design
-->
<div class="group bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1"
     data-aos="fade-up" 
     data-aos-delay="<?php echo e($delay ?? 0); ?>">
    
    <!-- Image Container with Overlay -->
    <a href="<?php echo e(url('/products/' . $product->id)); ?>" class="block relative overflow-hidden">
        <div class="relative aspect-square">
            <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" 
                 alt="<?php echo e($product->nama); ?>" 
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
            
            <!-- Gradient Overlay on Hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            
            <!-- Category Badge -->
            <div class="absolute top-3 left-3">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-white/90 backdrop-blur-sm text-[#8B6F47] shadow-md">
                    <i data-lucide="tag" class="w-3 h-3 mr-1"></i>
                    <?php echo e($product->category->name ?? 'N/A'); ?>

                </span>
            </div>

            <!-- Quick View Badge (appears on hover) -->
            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-[#8B6F47] text-white shadow-md">
                    <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                    Lihat Detail
                </span>
            </div>

            <?php if(Auth::check() && Auth::user()->role == 'reseller'): ?>
                <!-- Reseller Badge -->
                <div class="absolute bottom-3 right-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-400 to-yellow-500 text-yellow-900 shadow-md">
                        <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                        Harga Reseller
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </a>
    
    <!-- Content Section -->
    <div class="p-5">
        <!-- Product Name -->
        <a href="<?php echo e(url('/products/' . $product->id)); ?>" class="block group/title">
            <h3 class="text-lg font-bold text-[#3c2f2f] mb-2 line-clamp-2 group-hover/title:text-[#8B6F47] transition-colors min-h-[56px]">
                <?php echo e($product->nama); ?>

            </h3>
        </a>
        
        <!-- Description -->
        <p class="text-sm text-gray-600 mb-4 line-clamp-2 min-h-[40px] leading-relaxed">
            <?php echo e(Str::limit($product->deskripsi, 80)); ?>

        </p>
        
        <!-- Price Section -->
        <div class="mb-5">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->role == 'reseller'): ?>
                    <div class="space-y-1">
                        <div class="flex items-baseline space-x-2">
                            <span class="text-2xl font-bold text-[#d32f2f]">
                                Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?>

                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                Save <?php echo e(number_format((($product->harga_normal - $product->harga_reseller) / $product->harga_normal) * 100, 0)); ?>%
                            </span>
                        </div>
                        <div class="text-sm text-gray-400 line-through">
                            Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-2xl font-bold text-[#3c2f2f]">
                        Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-2xl font-bold text-[#3c2f2f]">
                    Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Stock Info -->
        <div class="mb-4 flex items-center text-sm text-gray-600">
            <i data-lucide="package" class="w-4 h-4 mr-1.5 text-[#8B6F47]"></i>
            <span>Stok: <span class="font-semibold text-[#8B6F47]"><?php echo e($product->stok ?? 0); ?></span></span>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <!-- Buy Now Button -->
            <form action="<?php echo e(route('cart.buyNow', $product->id)); ?>" method="POST" class="flex-1">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-[#8B6F47] to-[#6d5636] text-white px-4 py-3 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center justify-center space-x-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Beli Sekarang</span>
                </button>
            </form>

            <!-- Add to Cart Button -->
            <form action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                        class="p-3 rounded-xl border-2 border-[#8B6F47] text-[#8B6F47] hover:bg-[#8B6F47] hover:text-white transition-all hover:shadow-md">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/partials/product_card.blade.php ENDPATH**/ ?>