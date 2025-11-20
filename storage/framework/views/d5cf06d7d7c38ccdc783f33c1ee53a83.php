<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Produk: <?php echo e($product->nama); ?></h1>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form Edit Produk (VERSI UPGRADE TAILWIND) -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-700">Edit Detail Produk</h2>
        </div>
        
        <div class="card-body p-6">
            <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?> 

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="nama" class="w-full border border-gray-300 rounded px-3 py-2" value="<?php echo e(old('nama', $product->nama)); ?>" required>
                    </div>
                    
                    <!-- DROPDOWN KATEGORI -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal</label>
                        <input type="number" name="harga_normal" class="w-full border border-gray-300 rounded px-3 py-2" value="<?php echo e(old('harga_normal', $product->harga_normal)); ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="w-full border border-gray-300 rounded px-3 py-2" value="<?php echo e(old('harga_reseller', $product->harga_reseller)); ?>" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stok" class="w-full border border-gray-300 rounded px-3 py-2" value="<?php echo e(old('stok', $product->stok)); ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" required><?php echo e(old('deskripsi', $product->deskripsi)); ?></textarea>
                </div>
                <!-- VARIAN PRODUK -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Varian Produk</h2>

                    <!-- FORM TAMBAH VARIAN -->
                    <form action="<?php echo e(route('admin.variants.store', $product->id)); ?>" method="POST" class="mb-6">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Warna</label>
                                <input type="text" name="warna" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Stok</label>
                                <input type="number" name="stok" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Harga (Opsional)</label>
                                <input type="number" name="harga" class="w-full border border-gray-300 rounded px-3 py-2">
                            </div>
                        </div>

                        <button class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                            Tambah Varian
                        </button>
                    </form>


                    <!-- LIST VARIAN -->
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Daftar Varian</h3>

                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border rounded-lg p-4 flex items-center justify-between bg-gray-50">
                                
                                <form action="<?php echo e(route('admin.variants.update', $variant->id)); ?>" method="POST" class="flex items-center gap-3 w-full">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <input type="text" name="warna" value="<?php echo e($variant->warna); ?>"
                                        class="border border-gray-300 rounded px-2 py-1 w-32">

                                    <input type="number" name="stok" value="<?php echo e($variant->stok); ?>"
                                        class="border border-gray-300 rounded px-2 py-1 w-20">

                                    <input type="number" name="harga" value="<?php echo e($variant->harga); ?>"
                                        class="border border-gray-300 rounded px-2 py-1 w-28">

                                    <button class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                                        Update
                                    </button>
                                </form>

                                <!-- HAPUS -->
                                <form action="<?php echo e(route('admin.variants.delete', $variant->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-gray-500">Belum ada varian.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- GAMBAR SAMPUL (COVER) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Sampul (Cover)</label>
                    <div class="mb-2">
                        <?php if($product->gambar): ?>
                            <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="150" alt="Cover" class="rounded-md border">
                        <?php else: ?>
                            <p class="text-xs text-gray-500">Tidak ada gambar sampul</p>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="gambar" class="w-full border border-gray-300 rounded px-3 py-2">
                    <small class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar sampul.</small>
                </div>

                <!-- GALERI FOTO -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Galeri Foto</label>
                    <div class="flex flex-wrap gap-3 mb-2">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="relative w-24 h-24">
                                <img src="<?php echo e(asset('storage/' . $image->path)); ?>" class="w-full h-full object-cover rounded-md border" alt="Gallery Image">
                                <div class="absolute bottom-0 left-0 right-0 bg-black/60 p-1 text-center">
                                    
                                    <input type="checkbox" name="delete_images[]" value="<?php echo e($image->id); ?>" id="delete_img_<?php echo e($image->id); ?>" class="form-checkbox h-4 w-4 text-red-600 border-gray-300 rounded">
                                    <label for="delete_img_<?php echo e($image->id); ?>" class="ml-1 text-xs text-white">Hapus</label>
                                </div>
                             </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12">
                                <p class="text-xs text-gray-500">Belum ada foto galeri.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">Tambah Foto Galeri Baru (Opsional)</label>
                    <input type="file" name="gallery[]" class="w-full border border-gray-300 rounded px-3 py-2" accept="image/*" multiple>
                    <small class="text-xs text-gray-500">Tahan Ctrl/Cmd untuk pilih banyak foto baru.</small>
                </div>

                <hr class="my-6 border-gray-200">
                <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.products')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-md text-sm font-medium">Batal</a>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products_edit.blade.php ENDPATH**/ ?>