<?php $__env->startSection('title', 'Manajemen Slider - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="image" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Manajemen Slider Banner</h1>
                <p class="text-gray-600 mt-1">Kelola slider banner untuk tampilan website</p>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-6 bg-gradient-to-r from-green-100 to-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                <span class="font-medium"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
        
        
        <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Daftar Slider Banner</h2>
                    <p class="text-white text-opacity-90 mt-1">Kelola semua slider yang ditampilkan di website</p>
                </div>
                <a href="<?php echo e(route('admin.sliders.create')); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-white text-[#CC8650] rounded-xl hover:shadow-lg font-bold transition-all">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> 
                    Tambah Slider
                </a>
            </div>
        </div>

        
        <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-gray-700">
                    <i data-lucide="info" class="w-5 h-5 text-[#AE8B56]"></i>
                    <span class="text-sm font-semibold">Total Slider: <span class="text-[#CC8650] font-bold"><?php echo e($sliders->count()); ?></span></span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-green-200 rounded-full"></div>
                        <span>Aktif: <?php echo e($sliders->where('is_active', true)->count()); ?></span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-red-200 rounded-full"></div>
                        <span>Nonaktif: <?php echo e($sliders->where('is_active', false)->count()); ?></span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Gambar
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Alt Text
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Posisi
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gradient-to-r hover:from-[#F0E7DB] hover:to-transparent transition-all duration-150">
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-4">
                                    <div class="relative group">
                                        <img src="<?php echo e(asset('storage/' . $slider->image)); ?>" 
                                             width="80" 
                                             class="rounded-xl border-2 border-gray-200 shadow-sm group-hover:border-[#D4BA98] transition-all">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all"></div>
                                    </div>
                                </div>
                            </td>
                            
                            
                            <td class="px-8 py-5">
                                <div class="flex items-start max-w-xs">
                                    <i data-lucide="message-square" class="w-4 h-4 mr-2 text-[#AE8B56] mt-0.5 flex-shrink-0"></i>
                                    <span class="text-sm text-gray-700 font-medium">
                                        <?php echo e($slider->alt ?? 'Tidak ada alt text'); ?>

                                    </span>
                                </div>
                            </td>
                            
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <i data-lucide="navigation" class="w-4 h-4 mr-2 text-[#AE8B56]"></i>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#EBE6E6] text-[#AE8B56]">
                                        Posisi <?php echo e($slider->position); ?>

                                    </span>
                                </div>
                            </td>
                            
                            
                            <td class="px-8 py-5">
                                <?php if($slider->is_active): ?>
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-green-100 to-green-50 text-green-700 border border-green-200">
                                        <i data-lucide="check-circle" class="w-3 h-3 mr-1.5"></i>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-red-100 to-red-50 text-red-700 border border-red-200">
                                        <i data-lucide="x-circle" class="w-3 h-3 mr-1.5"></i>
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    
                                    <a href="<?php echo e(route('admin.sliders.edit', $slider->id)); ?>" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#D4BA98] to-[#D0B682] text-white rounded-xl hover:shadow-md font-semibold text-sm transition-all">
                                        <i data-lucide="edit" class="w-3 h-3 mr-1.5"></i>
                                        Edit
                                    </a>
                                    
                                    
                                    <form action="<?php echo e(route('admin.sliders.destroy', $slider->id)); ?>" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus slider ini?')"
                                          class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#AE8B56] to-[#CC8650] text-white rounded-xl hover:shadow-md font-semibold text-sm transition-all">
                                            <i data-lucide="trash-2" class="w-3 h-3 mr-1.5"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                        <i data-lucide="image" class="w-16 h-16 text-[#AE8B56]"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Slider</h3>
                                    <p class="text-gray-500 mb-6">Mulai dengan menambahkan slider banner pertama Anda.</p>
                                    <a href="<?php echo e(route('admin.sliders.create')); ?>" 
                                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl hover:shadow-lg font-semibold transition-all">
                                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                        Tambah Slider Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($sliders->hasPages()): ?>
            <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-bold text-[#CC8650]"><?php echo e($sliders->firstItem()); ?></span> - 
                        <span class="font-bold text-[#CC8650]"><?php echo e($sliders->lastItem()); ?></span> dari 
                        <span class="font-bold text-[#CC8650]"><?php echo e($sliders->total()); ?></span> slider
                    </div>
                    <div>
                        <?php echo e($sliders->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-[#CC8650]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Total Slider</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($sliders->count()); ?></p>
                </div>
                <div class="bg-[#F0E7DB] p-3 rounded-xl">
                    <i data-lucide="image" class="w-6 h-6 text-[#AE8B56]"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Slider Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($sliders->where('is_active', true)->count()); ?></p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Slider Nonaktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($sliders->where('is_active', false)->count()); ?></p>
                </div>
                <div class="bg-red-100 p-3 rounded-xl">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/sliders/index.blade.php ENDPATH**/ ?>