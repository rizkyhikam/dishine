<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Kategori</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded mb-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah Kategori -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Tambah Kategori Baru</h2>
                </div>
                <div class="card-body p-6">
                    <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="cth: Dress" required>
                        </div>
                        <button type="submit" class="w-full bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">Tambah Kategori</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 
        =================================
        DAFTAR KATEGORI (SUDAH DIPERBARUI)
        =================================
        -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-700">Daftar Kategori</h2>
                </div>
                <div class="card-body overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kategori</th>
                                
                                <!-- KOLOM BARU -->
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk di Kategori Ini</th>
                                
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo e($index + 1); ?></td>
                                    <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($category->name); ?></td>
                                    
                                    <!-- ISI KOLOM BARU -->
                                    <td class="px-6 py-4">
                                        <?php if($category->products->isEmpty()): ?>
                                            <span class="text-xs text-gray-500">Belum ada produk.</span>
                                        <?php else: ?>
                                            <!-- Tampilkan nama produk sebagai 'pills' -->
                                            <div class="flex flex-wrap gap-1">
                                                <?php $__currentLoopData = $category->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="bg-gray-200 text-gray-700 text-xs font-medium px-2 py-0.5 rounded-full">
                                                        <?php echo e($product->nama); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-sm">
                                        <form action="<?php echo e(route('admin.categories.delete', $category->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <!-- PERBAIKAN: ganti colspan="3" menjadi "4" -->
                                    <td colspan="4" class="text-center py-10 text-gray-500">Belum ada kategori.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/categories.blade.php ENDPATH**/ ?>