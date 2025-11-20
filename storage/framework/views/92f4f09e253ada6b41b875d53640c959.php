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
                <label class="block text-sm font-medium">Nama Produk</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Kategori Produk</label>
                <select name="category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
            <div>
                <label class="block text-sm font-medium">Harga Normal</label>
                <input type="number" name="harga_normal" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Harga Reseller</label>
                <input type="number" name="harga_reseller" class="w-full border rounded px-3 py-2" required>
            </div>

            
            <div id="normalStock">
                <label class="block text-sm font-medium">Stok</label>
                <input type="number" name="stok" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        
        <div class="mb-4">
            <label class="block text-sm font-medium">Deskripsi</label>
            <textarea name="deskripsi" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
        </div>

        
        <label class="flex items-center gap-2 mb-3">
            <input type="checkbox" id="useVariants" name="use_variants" value="1">
            Gunakan varian warna
        </label>

        
        <div id="variantTable" style="display:none;">
            <table class="w-full mt-3">
                <thead>
                    <tr class="text-left font-semibold">
                        <th>Warna</th>
                        <th>Stok</th>
                        <th>Harga (opsional)</th>
                    </tr>
                </thead>
                <tbody id="variantRows"></tbody>
            </table>

            <button type="button" id="addVariantRow"
                class="mt-3 bg-gray-300 px-3 py-1 rounded">
                + Tambah Varian
            </button>
        </div>

        <br>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block text-sm font-medium">Gambar Sampul</label>
                <input type="file" name="gambar" class="w-full border rounded px-3 py-2" accept="image/*" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Galeri Foto</label>
                <input type="file" name="gallery[]" multiple class="w-full border rounded px-3 py-2" accept="image/*">
            </div>
        </div>

        
        <button type="submit"
            class="bg-gray-800 text-white px-5 py-2 rounded hover:bg-gray-700">
            Tambah Produk
        </button>
    </form>

    
    <script>
    document.getElementById('useVariants').addEventListener('change', function () {
        const use = this.checked;
        document.getElementById('variantTable').style.display = use ? 'block' : 'none';
        document.getElementById('normalStock').style.display = use ? 'none' : 'block';
    });

    document.getElementById('addVariantRow').addEventListener('click', function () {
        const row = `
            <tr>
                <td><input type="text" name="variant_warna[]" class="border px-2 py-1 rounded"></td>
                <td><input type="number" name="variant_stok[]" class="border px-2 py-1 rounded"></td>
                <td><input type="number" name="variant_harga[]" class="border px-2 py-1 rounded"></td>
            </tr>`;
        document.getElementById('variantRows').insertAdjacentHTML('beforeend', row);
    });
    </script>

    </div>
</div>

<!-- 
=================================
Form Filter Produk (BARU)
=================================
-->
<div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
    <div class="card-body p-6">
        <form action="<?php echo e(route('admin.products')); ?>" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Input Nama Produk -->
                <div class="md:col-span-3">
                    <label for="search_nama" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Produk</label>
                    <input type="text" name="search_nama" id="search_nama" 
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                           placeholder="Ketik nama produk..."
                           value="<?php echo e($filters['search_nama'] ?? ''); ?>">
                </div>
                <!-- Tombol Aksi -->
                <div class="flex space-x-2">
                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium flex items-center justify-center">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                        Cari
                    </button>
                    <a href="<?php echo e(route('admin.products')); ?>" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-sm font-medium flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </div>
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
                    
                    <!-- INI KOLOM BARU YANG HILANG -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Reseller</th>
                    
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar (Sampul)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo e($product->nama); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($product->category->name ?? 'N/A'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp<?php echo e(number_format($product->harga_normal, 0, ',', '.')); ?></td>
                        
                        <!-- INI ISI KOLOM BARU YANG HILANG -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp<?php echo e(number_format($product->harga_reseller, 0, ',', '.')); ?></td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($product->stok); ?></td>
                        <td class="px-6 py-4">
                            <?php if($product->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" width="60" alt="<?php echo e($product->nama); ?>" class="rounded-md">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                        <!-- Colspan diperbarui menjadi 9 -->
                        <td colspan="9" class="text-center py-10 text-gray-500">
                            <?php if(request()->has('search_nama')): ?>
                                Tidak ada produk yang cocok dengan pencarian Anda.
                            <?php else: ?>
                                Belum ada produk.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/products.blade.php ENDPATH**/ ?>