<?php $__env->startSection('title', 'Tambah Slider - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="plus" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Tambah Slider Baru</h1>
                <p class="text-gray-600 mt-1">Tambahkan slider banner baru untuk tampilan website</p>
            </div>
        </div>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="mb-6 bg-gradient-to-r from-red-100 to-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
            <div class="flex items-center mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2 text-red-600"></i>
                <span class="font-medium">Ada kesalahan pada input:</span>
            </div>
            <ul class="list-disc list-inside text-sm ml-6">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
        
        <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Form Tambah Slider</h2>
            <p class="text-white text-opacity-90 mt-1">Isi form di bawah untuk menambah slider baru</p>
        </div>

        
        <div class="p-8">
            <form action="<?php echo e(route('admin.sliders.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i data-lucide="image" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                        Gambar Slider <span class="text-red-500">*</span>
                    </label>
                    
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center hover:border-[#D4BA98] transition-all bg-gray-50">
                        <div class="max-w-md mx-auto">
                            <i data-lucide="upload" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                            <p class="text-gray-600 font-medium mb-2">Klik untuk upload gambar</p>
                            <p class="text-gray-500 text-sm mb-4">Format: JPG, PNG, WEBP. Max: 2MB</p>
                            <input type="file" name="image" id="image" 
                                   class="hidden" 
                                   required 
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <label for="image" 
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl hover:shadow-lg font-semibold cursor-pointer transition-all">
                                <i data-lucide="folder-open" class="w-4 h-4 mr-2"></i>
                                Pilih Gambar
                            </label>
                        </div>
                    </div>
                    
                    
                    <div id="imagePreview" class="hidden mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <div class="relative inline-block">
                            <img id="preview" class="w-64 h-32 object-cover rounded-xl border-2 border-gray-200 shadow-sm">
                            <button type="button" onclick="removeImage()" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-all">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-3 flex items-center">
                        <i data-lucide="info" class="w-3 h-3 mr-1"></i>
                        Disarankan ukuran landscape (contoh: 1200×500px) untuk hasil terbaik
                    </p>
                </div>

                
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i data-lucide="message-square" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                        Alt Text (Opsional)
                    </label>
                    <input type="text" 
                           name="alt" 
                           id="alt" 
                           class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" 
                           placeholder="Masukkan deskripsi singkat untuk gambar...">
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i data-lucide="help-circle" class="w-3 h-3 mr-1"></i>
                        Alt text membantu SEO dan aksesibilitas untuk pengguna tunanetra
                    </p>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i data-lucide="navigation" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                            Posisi Urutan
                        </label>
                        <input type="number" 
                               name="position" 
                               id="position" 
                               value="0"
                               class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all">
                        <p class="text-xs text-gray-500 mt-2">Angka lebih kecil akan tampil lebih awal</p>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i data-lucide="power" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                            Status Slider
                        </label>
                        <div class="flex items-center space-x-3 bg-gray-50 rounded-xl p-4 border-2 border-gray-200">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active"
                                   value="1"
                                   class="w-4 h-4 text-[#CC8650] focus:ring-[#CC8650] rounded"
                                   checked>
                            <label for="is_active" class="text-gray-700 font-medium">
                                Aktifkan slider
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Slider akan langsung ditampilkan di website</p>
                    </div>
                </div>

                
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="<?php echo e(route('admin.sliders.index')); ?>" 
                       class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition-all">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl hover:shadow-lg font-semibold transition-all">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        Simpan Slider
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const input = document.getElementById('image');
            const previewContainer = document.getElementById('imagePreview');
            
            input.value = '';
            previewContainer.classList.add('hidden');
        }

        // Inisialisasi Lucide Icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/admin/sliders/create.blade.php ENDPATH**/ ?>