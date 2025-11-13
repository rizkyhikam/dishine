<?php $__env->startSection('content'); ?>
<div class="container mt-4 mb-5">

    
    <div class="d-flex justify-content-between align-items-center py-3 mb-4" style="background-color: #f7f3f1;">
        <div class="input-group" style="width: 300px;">
            <input type="text" class="form-control" placeholder="Search" aria-label="Search" style="border-right: none;">
            <button class="btn btn-outline-secondary" type="button" style="border-left: none;">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>
    

    
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    
    <?php if($cartItems->isEmpty()): ?>
        <div class="text-center mt-5 py-5" style="background-color: #f7f3f1; border-radius: 8px;">
            <p class="fs-4 text-muted">Keranjang kamu masih kosong 😅</p>
            <a href="<?php echo e(route('katalog')); ?>" class="btn btn-dark mt-3 px-4 py-2">
                🛍️ Lihat Katalog
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-12">
                
                <div class="row align-items-center text-muted fw-bold pb-2" style="border-bottom: 1px solid #ccc; font-size: 0.9em;">
                    <div class="col-6">Produk</div>
                    <div class="col-2 text-end">Harga Satuan</div>
                    <div class="col-2 text-center">Kuantitas</div>
                    <div class="col-2 text-end">Sub Total</div>
                </div>

                
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <div class="row align-items-center py-3 my-2" style="border-bottom: 1px solid #f0f0f0; background-color: #fff;">
                        
                        
                        <div class="col-6 d-flex align-items-center">
                            
                            <div class="form-check me-3">
                                <input class="form-check-input" type="checkbox" value="<?php echo e($item->id); ?>" id="item-<?php echo e($item->id); ?>" checked style="width: 20px; height: 20px;">
                            </div>
                            
                            
                            <img src="<?php echo e(asset('storage/' . $item->product->gambar)); ?>" alt="<?php echo e($item->product->nama); ?>" class="me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            
                            
                            <span class="fw-semibold"><?php echo e($item->product->nama); ?></span>
                        </div>
                        
                        
                        <div class="col-2 text-end">
                            Rp <?php echo e(number_format($item->product->harga_normal, 0, ',', '.')); ?>

                        </div>
                        
                        
                        <div class="col-2 text-center">
                            <form action="<?php echo e(url('/cart/update/'.$item->id)); ?>" method="POST" class="d-inline-flex align-items-center">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="input-group input-group-sm" style="width: 120px;">
                                    
                                    <button class="btn btn-outline-secondary btn-qty" type="button" data-type="minus" data-field="quantity-<?php echo e($item->id); ?>">-</button>
                                    
                                    
                                    <input 
                                        type="number" 
                                        name="quantity" 
                                        id="quantity-<?php echo e($item->id); ?>"
                                        value="<?php echo e($item->quantity); ?>" 
                                        min="1" 
                                        class="form-control text-center quantity-input" 
                                        style="max-width: 40px;"
                                        onchange="this.form.submit()" 
                                    >
                                    
                                    
                                    <button class="btn btn-outline-secondary btn-qty" type="button" data-type="plus" data-field="quantity-<?php echo e($item->id); ?>">+</button>
                                </div>
                            </form>
                        </div>

                        
                        <div class="col-2 text-end d-flex justify-content-end align-items-center">
                            <strong class="me-3">
                                Rp <?php echo e(number_format($item->product->harga_normal * $item->quantity, 0, ',', '.')); ?>

                            </strong>
                            
                            
                            <form action="<?php echo e(url('/cart/remove/'.$item->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-link text-danger p-0" title="Hapus">
                                    <i class="bi bi-trash"></i> 
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                

                
                <div class="row mt-4 p-3 align-items-center" style="background-color: #f7f3f1; border-radius: 8px;">
                    
                    <div class="col-6 d-flex align-items-center">
                        <div class="form-check me-4">
                            <input class="form-check-input" type="checkbox" id="selectAll" style="width: 20px; height: 20px;">
                            <label class="form-check-label ms-1" for="selectAll">
                                Pilih Semua (<?php echo e(count($cartItems)); ?>)
                            </label>
                        </div>
                        <button class="btn btn-link text-danger p-0" id="bulkDeleteBtn">Hapus Produk dari Keranjang</button>
                    </div>

                    
                    <div class="col-6 text-end d-flex justify-content-end align-items-center">
                        <span class="text-uppercase fw-bold me-3">Total</span>
                        <h4 class="fw-bold text-dark me-4 mb-0">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></h4>
                        
                        <form action="<?php echo e(url('/checkout')); ?>" method="GET" class="d-inline">
                            
                            <button class="btn btn-success btn-lg px-4" style="background-color: #92584e; border-color: #92584e;">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-qty').forEach(button => {
            button.addEventListener('click', function (e) {
                const type = this.dataset.type;
                const fieldId = this.dataset.field;
                const input = document.getElementById(fieldId);
                let currentVal = parseInt(input.value);

                if (type === 'minus') {
                    if (currentVal > input.min) {
                        input.value = currentVal - 1;
                        input.dispatchEvent(new Event('change')); // Memicu event change agar form submit
                    }
                } else if (type === 'plus') {
                    input.value = currentVal + 1;
                    input.dispatchEvent(new Event('change')); // Memicu event change agar form submit
                }
            });
        });
        
        // Logika Pilih Semua (Sederhana)
        const selectAll = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.form-check-input[type="checkbox"]:not(#selectAll)');

        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/cart/index.blade.php ENDPATH**/ ?>