<?php $__env->startSection('content'); ?>
<h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Produk</h1>

<?php if(session('success')): ?>
    <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded mb-4"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<!-- Form Tambah Produk (Di dalam Card) -->
<div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
    <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-700">Tambah Produk Baru</h2>
    </div>
    <div class="card-body p-6">
        <form action="<?php echo e(route('admin.products.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal</label>
                    <input type="number" name="harga_normal" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Reseller</label>
                    <input type="number" name="harga_reseller" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" name="stok" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Sampul (Cover)</label>
                    <input type="file" name="gambar" class="w-full border border-gray-300 rounded px-3 py-2" accept="image/*" required>
                    <small class="text-xs text-gray-500">Ini adalah foto utama di katalog.</small>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Galeri Foto (Opsional)</label>
                    <input type="file" name="gallery[]" class="w-full border border-gray-300 rounded px-3 py-2" accept="image/*" multiple>
                    <small class="text-xs text-gray-500">Tahan Ctrl/Cmd untuk pilih banyak foto.</small>
                </div>
            </div>
            <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">Tambah Produk</button>
        </form>
    </div>
</div>

<!-- Daftar Produk (Di dalam Card) -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Produk</h2>
    </div>
    <div class="card-body overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Normal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar (Sampul)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($product->nama); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($product->category->name ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500">Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($product->stok); ?></td>
                        <td class="px-6 py-4">
                            <?php if($product->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="60" alt="<?php echo e($product->nama); ?>" class="rounded-md">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            <form action="<?php echo e(route('admin.products.delete', $product->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="text-red-600 hover:text-red-900 font-medium ml-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500">Belum ada produk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products.blade.php ENDPATH**/ ?>