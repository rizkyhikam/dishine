<?php $__env->startSection('title', 'Edit Slider'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="image" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Edit Slider</h1>
                <p class="text-gray-600 mt-1">Perbarui gambar slider untuk halaman utama</p>
            </div>
        </div>
        <a href="<?php echo e(route('admin.sliders.index')); ?>" 
           class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-semibold transition-all space-x-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-2xl shadow-md overflow-hidden max-w-4xl">
    <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
        <div class="flex items-center space-x-3">
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <i data-lucide="edit" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">Form Edit Slider</h2>
                <p class="text-white text-sm opacity-90 mt-1">Ubah informasi slider yang sudah ada</p>
            </div>
        </div>
    </div>

    <div class="p-8">
        <form action="<?php echo e(route('admin.sliders.update', $slider->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Preview Gambar Saat Ini -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                    Gambar Slider Saat Ini
                </label>
                <div class="relative group">
                    <img src="<?php echo e(asset('storage/' . $slider->image)); ?>" 
                         class="w-full max-w-2xl rounded-2xl border-4 border-[#D4BA98] shadow-lg">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-2xl flex items-center justify-center">
                        <span class="text-white font-bold opacity-0 group-hover:opacity-100 transition-all">
                            <i data-lucide="zoom-in" class="w-8 h-8"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Upload Gambar Baru -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-3" for="image">
                    <i data-lucide="upload" class="w-4 h-4 inline mr-1"></i>
                    Ganti Gambar <span class="text-gray-500 text-xs font-normal">(Opsional - Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <div class="border-2 border-dashed border-[#D4BA98] rounded-xl p-6 hover:border-[#CC8650] transition-all bg-[#F0E7DB] bg-opacity-30">
                    <div class="flex items-center space-x-4">
                        <div class="bg-[#CC8650] bg-opacity-10 p-4 rounded-lg">
                            <i data-lucide="image-plus" class="w-8 h-8 text-[#CC8650]"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#CC8650] file:text-white hover:file:bg-[#AE8B56] transition-all" 
                                   accept="image/*"
                                   onchange="previewImage(event)">
                            <p class="text-xs text-gray-500 mt-2">
                                <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                                Format: JPG, PNG, GIF | Maksimal: 2MB | Rekomendasi: 1920x600px
                            </p>
                        </div>
                    </div>
                    
                    <!-- Preview Upload Baru -->
                    <div id="imagePreview" class="hidden mt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Preview Gambar Baru:</p>
                        <img id="previewImg" src="" class="w-full max-w-xl rounded-xl border-2 border-[#CC8650] shadow-md">
                    </div>
                </div>
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-600 mt-2 flex items-center">
                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Alt Text -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="alt">
                    <i data-lucide="type" class="w-4 h-4 inline mr-1"></i>
                    Alt Text
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="tag" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="alt" 
                           id="alt" 
                           value="<?php echo e(old('alt', $slider->alt)); ?>" 
                           class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                           placeholder="Contoh: Banner Promo Lebaran 2024">
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                    Teks alternatif untuk SEO dan aksesibilitas
                </p>
                <?php $__errorArgs = ['alt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-600 mt-1 flex items-center">
                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Posisi Urutan -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="position">
                    <i data-lucide="list-ordered" class="w-4 h-4 inline mr-1"></i>
                    Posisi Urutan
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="hash" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="number" 
                           name="position" 
                           id="position" 
                           value="<?php echo e(old('position', $slider->position)); ?>" 
                           class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-[#CC8650] focus:ring focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                           min="0"
                           placeholder="Contoh: 1">
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                    Slider dengan posisi lebih kecil akan ditampilkan lebih dulu
                </p>
                <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-red-600 mt-1 flex items-center">
                        <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Status Aktif -->
            <div class="mb-8">
                <div class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] border-2 border-[#D4BA98] rounded-xl p-6">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" 
                               name="is_active" 
                               class="w-6 h-6 text-[#CC8650] rounded-lg focus:ring-[#CC8650] focus:ring-2 transition-all" 
                               <?php echo e(old('is_active', $slider->is_active) ? 'checked' : ''); ?>>
                        <div class="ml-4 flex items-center space-x-3">
                            <div>
                                <span class="text-gray-800 font-bold text-lg block">Aktifkan Slider Ini</span>
                                <span class="text-gray-600 text-sm">Centang untuk menampilkan slider di halaman utama</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="<?php echo e(route('admin.sliders.index')); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-semibold transition-all space-x-2">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <span>Batal</span>
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all font-bold space-x-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    <span>Update Slider</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Preview Image -->
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }
    
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/sliders/edit.blade.php ENDPATH**/ ?>