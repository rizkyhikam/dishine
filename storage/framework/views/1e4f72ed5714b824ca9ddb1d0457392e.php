

<?php $__env->startSection('title', 'Tambah Slider'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Slider Baru</h2>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="<?php echo e(route('admin.sliders.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Gambar Slider (Wajib)
                </label>
                <input type="file" name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required accept="image/*">
                <p class="text-gray-600 text-xs italic mt-1">Format: JPG, PNG, WEBP. Max: 2MB. Disarankan ukuran landscape (e.g 1200x500px).</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="alt">
                    Alt Text (Opsional)
                </label>
                <input type="text" name="alt" id="alt" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Deskripsi singkat gambar">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                    Posisi Urutan
                </label>
                <input type="number" name="position" id="position" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan
                </button>
                <a href="<?php echo e(route('admin.sliders.index')); ?>" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/sliders/create.blade.php ENDPATH**/ ?>