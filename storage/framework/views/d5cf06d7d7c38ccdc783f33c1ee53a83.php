

<?php $__env->startSection('content'); ?>
    <h1 class="mb-4">Edit Produk: <?php echo e($product->nama); ?></h1>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Edit Produk -->
    <div class="card mb-4">
        <div class="card-header">Edit Detail Produk</div>
        <div class="card-body">
            
            
            <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?> 

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Produk</label>
                        
                        <input type="text" name="nama" class="form-control" value="<?php echo e($product->nama); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Harga Normal</label>
                        <input type="number" name="harga_normal" class="form-control" value="<?php echo e($product->harga_normal); ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="form-control" value="<?php echo e($product->harga_reseller); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?php echo e($product->stok); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required><?php echo e($product->deskripsi); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label>Gambar Produk Saat Ini</label>
                    <div class="mb-2">
                        <?php if($product->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="150">
                        <?php else: ?>
                            <p>Tidak ada gambar</p>
                        <?php endif; ?>
                    </div>
                    <label>Upload Gambar Baru (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.products')); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products_edit.blade.php ENDPATH**/ ?>