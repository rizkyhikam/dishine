

<?php $__env->startSection('title', 'Manajemen Slider'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Slider Banner</h2>

    <a href="<?php echo e(route('admin.sliders.create')); ?>" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">
        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Tambah Slider
    </a>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Gambar</th>
                    <th class="py-3 px-6 text-left">Alt Text</th>
                    <th class="py-3 px-6 text-center">Posisi</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php $__empty_1 = true; $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <img src="<?php echo e(asset('storage/' . $slider->image)); ?>" width="120" class="rounded">
                        </td>
                        <td class="py-3 px-6 text-left"><?php echo e($slider->alt ?? '-'); ?></td>
                        <td class="py-3 px-6 text-center"><?php echo e($slider->position); ?></td>
                        <td class="py-3 px-6 text-center">
                            <?php if($slider->is_active): ?>
                                <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Aktif</span>
                            <?php else: ?>
                                <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="<?php echo e(route('admin.sliders.edit', $slider->id)); ?>" class="w-4 h-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                    <i data-lucide="edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.sliders.destroy', $slider->id)); ?>" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-4 h-4 transform hover:text-red-500 hover:scale-110">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada slider.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/sliders/index.blade.php ENDPATH**/ ?>