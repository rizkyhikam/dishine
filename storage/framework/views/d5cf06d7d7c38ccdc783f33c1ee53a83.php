

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

    <!-- Form Edit Produk (VERSI UPGRADE) -->
    <div class="card mb-4">
        <div class="card-header">Edit Detail Produk</div>
        <div class="card-body">
            
            <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?> 

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Produk</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama', $product->nama)); ?>" required>
                    </div>
                    
                    <!-- DROPDOWN KATEGORI (BARU) -->
                    <div class="col-md-6">
                        <label>Kategori Produk</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>" 
                                    
                                    <?php echo e($product->category_id == $category->id ? 'selected' : ''); ?>>
                                    <?php echo e($category->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Harga Normal</label>
                        <input type="number" name="harga_normal" class="form-control" value="<?php echo e(old('harga_normal', $product->harga_normal)); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="form-control" value="<?php echo e(old('harga_reseller', $product->harga_reseller)); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?php echo e(old('stok', $product->stok)); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required><?php echo e(old('deskripsi', $product->deskripsi)); ?></textarea>
                </div>
                
                <!-- GAMBAR SAMPUL (COVER) -->
                <div class="mb-3">
                    <label>Gambar Sampul (Cover)</label>
                    <div class="mb-2">
                        <?php if($product->gambar): ?>
                            <!-- 
                                INI ADALAH BARIS 89 YANG SUDAH DIPERBAIKI
                                (typo ' ' ekstra sudah dihapus)
                            -->
                            <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="150" alt="Cover">
                        <?php else: ?>
                            <p>Tidak ada gambar sampul</p>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar sampul.</small>
                </div>

                <!-- GALERI FOTO (BARU) -->
                <div class="mb-3">
                    <label>Galeri Foto</label>
                    <div class="row g-2 mb-2">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col-auto">
                                <div class="card" style="width: 100px;">
                                    <img src="<?php echo e(asset('storage/' . $image->path)); ?>" class="card-img-top" alt="Gallery Image" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-1 text-center">
                                        
                                        <input type="checkbox" name="delete_images[]" value="<?php echo e($image->id); ?>" id="delete_img_<?php echo e($image->id); ?>">
                                        <label for="delete_img_<?php echo e($image->id); ?>" class="form-check-label small">Hapus</label>
                                    </div>
                                 </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12">
                                <p class="text-muted">Belum ada foto galeri.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <label>Tambah Foto Galeri Baru (Opsional)</label>
                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    <small class="form-text text-muted">Tahan Ctrl/Cmd untuk pilih banyak foto baru.</small>
                </div>

                <hr>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.products')); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products_edit.blade.php ENDPATH**/ ?>