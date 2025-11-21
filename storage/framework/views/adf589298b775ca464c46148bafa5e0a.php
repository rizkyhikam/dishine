<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#b48a60] to-[#d4a574] p-4 rounded-2xl shadow-lg">
                <i data-lucide="list" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Manajemen Kategori</h1>
                <p class="text-gray-600 mt-1">Kelola kategori produk toko Anda</p>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center shadow-sm">
            <div class="bg-green-100 p-2 rounded-lg mr-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <p class="font-semibold">Berhasil!</p>
                <p class="text-sm"><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-start">
                <div class="bg-red-100 p-2 rounded-lg mr-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Terjadi Kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah Kategori -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-[#b48a60] to-[#d4a574] px-6 py-5">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                            <i data-lucide="plus-circle" class="w-5 h-5 text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">Tambah Kategori</h2>
                    </div>
                </div>
                <div class="p-6">
                    <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Nama Kategori
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="tag" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-[#b48a60] focus:ring focus:ring-[#b48a60] focus:ring-opacity-20 transition-all" 
                                    placeholder="Contoh: Dress, Blouse, Pants" 
                                    required>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Masukkan nama kategori yang deskriptif</p>
                        </div>
                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-[#b48a60] to-[#d4a574] text-white px-5 py-3 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 font-semibold flex items-center justify-center space-x-2">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            <span>Tambah Kategori</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Kategori -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#b48a60] to-[#d4a574] px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                                <i data-lucide="layers" class="w-6 h-6 text-white"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Daftar Kategori</h2>
                        </div>
                        <span class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-full text-sm font-semibold">
                            <?php echo e($categories->count()); ?> Kategori
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-16">No</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Produk di Kategori</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[#b48a60] bg-opacity-10 text-[#b48a60] font-bold">
                                            <?php echo e($index + 1); ?>

                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="bg-[#b48a60] bg-opacity-10 p-2 rounded-lg mr-3">
                                                <i data-lucide="tag" class="w-5 h-5 text-[#b48a60]"></i>
                                            </div>
                                            <div>
                                                <p class="text-base font-bold text-gray-900"><?php echo e($category->name); ?></p>
                                                <p class="text-xs text-gray-500">Kategori Produk</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <?php if($category->products->isEmpty()): ?>
                                            <div class="flex items-center text-gray-400">
                                                <i data-lucide="inbox" class="w-4 h-4 mr-2"></i>
                                                <span class="text-sm italic">Belum ada produk</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex flex-wrap gap-2">
                                                <?php $__currentLoopData = $category->products->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="inline-flex items-center bg-[#c9a36d] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                                        <i data-lucide="package" class="w-3 h-3 mr-1"></i>
                                                        <?php echo e(Str::limit($product->nama, 15)); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($category->products->count() > 5): ?>
                                                    <span class="inline-flex items-center bg-[#8b6f47] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                                        +<?php echo e($category->products->count() - 5); ?> lainnya
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="text-xs text-gray-700 font-semibold mt-2">
                                                Total: <?php echo e($category->products->count()); ?> produk
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <form action="<?php echo e(route('admin.categories.delete', $category->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Produk yang terkait tidak akan terhapus.')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button 
                                                type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-lg hover:bg-red-100 transition-all duration-200 border border-red-200">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-16">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-gray-100 p-6 rounded-full mb-4">
                                                <i data-lucide="inbox" class="w-16 h-16 text-gray-400"></i>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Kategori</h3>
                                            <p class="text-gray-500">Silakan tambahkan kategori pertama Anda menggunakan form di sebelah kiri.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk inisialisasi Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/categories.blade.php ENDPATH**/ ?>