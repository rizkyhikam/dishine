<?php $__env->startSection('title', 'Profil Saya - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gradient-to-br from-[#F0E7DB] to-[#EBE6E6] min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Header Section -->
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#CC8650] rounded-2xl mb-4">
                <i data-lucide="user" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Profil Saya</h1>
            <p class="text-lg text-[#AE8B56]">Kelola informasi akun Anda</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-[#CC8650] font-bold text-xl">
                                <?php echo e(substr($user->nama, 0, 1)); ?>

                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white"><?php echo e($user->nama); ?></h2>
                            <p class="text-white text-opacity-90"><?php echo e(ucfirst($user->role)); ?> Account</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="button" id="toggleUbahBtn" 
                                class="inline-flex items-center px-5 py-2.5 bg-white text-[#CC8650] rounded-xl hover:bg-opacity-90 font-semibold transition-all">
                            <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                            Edit Profil
                        </button>
                        
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" 
                                    class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition-all">
                                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-8">
                <?php if(session('success')): ?>
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                            <span class="font-medium"><?php echo e(session('success')); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if($errors->any() && !session('success')): ?>
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
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

                <!-- DATA DISPLAY -->
                <div id="dataDisplay">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Info -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#F0E7DB] rounded-lg flex items-center justify-center">
                                    <i data-lucide="user" class="w-5 h-5 text-[#AE8B56]"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                    <p class="text-lg font-semibold text-gray-800"><?php echo e($user->nama ?? '-'); ?></p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#F0E7DB] rounded-lg flex items-center justify-center">
                                    <i data-lucide="mail" class="w-5 h-5 text-[#AE8B56]"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-lg font-semibold text-gray-800"><?php echo e($user->email ?? '-'); ?></p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#F0E7DB] rounded-lg flex items-center justify-center">
                                    <i data-lucide="shield" class="w-5 h-5 text-[#AE8B56]"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Role</p>
                                    <p class="text-lg font-semibold text-gray-800 capitalize"><?php echo e($user->role ?? 'guest'); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-6">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-[#F0E7DB] rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-[#AE8B56]"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500">Alamat Lengkap</p>
                                    <p class="text-lg font-semibold text-gray-800 whitespace-pre-line"><?php echo e($user->alamat ?? 'Belum diatur'); ?></p>
                                    <?php if(!$user->alamat): ?>
                                        <p class="text-xs text-red-500 mt-1">⚠️ Penting untuk proses pengiriman</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#F0E7DB] rounded-lg flex items-center justify-center">
                                    <i data-lucide="phone" class="w-5 h-5 text-[#AE8B56]"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nomor WhatsApp</p>
                                    <p class="text-lg font-semibold text-gray-800"><?php echo e($user->no_hp ?? 'Belum diatur'); ?></p>
                                    <?php if(!$user->no_hp): ?>
                                        <p class="text-xs text-red-500 mt-1">⚠️ Penting untuk konfirmasi pesanan</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT FORM -->
                <div id="dataForm" class="hidden">
                    <form action="<?php echo e(route('profil.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="user" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                    Nama Lengkap
                                </label>
                                <input type="text" id="nama" name="nama" 
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" 
                                       value="<?php echo e(old('nama', $user->nama ?? '')); ?>" 
                                       required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="mail" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                    Email
                                </label>
                                <input type="email" id="email" name="email" 
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" 
                                       value="<?php echo e(old('email', $user->email ?? '')); ?>" 
                                       required>
                            </div>

                            <!-- Nomor WhatsApp -->
                            <div>
                                <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="phone" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                    Nomor WhatsApp
                                </label>
                                <input type="text" id="no_hp" name="no_hp" 
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all" 
                                       value="<?php echo e(old('no_hp', $user->no_hp ?? '')); ?>"
                                       placeholder="Contoh: 08123456789">
                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <i data-lucide="info" class="w-3 h-3 mr-1"></i>
                                    Untuk konfirmasi pesanan dan notifikasi
                                </p>
                            </div>

                            <!-- Role (Readonly) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="shield" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                    Role
                                </label>
                                <input type="text" 
                                       class="w-full border-2 border-gray-200 bg-gray-50 text-gray-600 rounded-xl px-4 py-3" 
                                       value="<?php echo e(ucfirst($user->role ?? 'guest')); ?>" 
                                       readonly>
                            </div>

                            <!-- Alamat (Full Width) -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 inline mr-1 text-[#AE8B56]"></i>
                                    Alamat Lengkap
                                </label>
                                <textarea id="alamat" name="alamat" rows="4"
                                          class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-[#CC8650] focus:ring-2 focus:ring-[#CC8650] focus:ring-opacity-20 transition-all"
                                          placeholder="Jl. Mawar No. 10, RT 01/RW 02, Kelurahan, Kecamatan, Kota, Kode Pos"><?php echo e(old('alamat', $user->alamat ?? '')); ?></textarea>
                                <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i>
                                    Wajib diisi untuk proses pengiriman barang
                                </p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                            <button type="button" id="batalUbahBtn" 
                                    class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition-all">
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                Batal
                            </button>
                            <button type="submit" 
                                    class="inline-flex items-center px-8 py-3 bg-[#CC8650] text-white rounded-xl hover:bg-[#AE8B56] font-semibold transition-all">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataDisplay = document.getElementById('dataDisplay');
        const dataForm = document.getElementById('dataForm');
        const toggleUbahBtn = document.getElementById('toggleUbahBtn');
        const batalUbahBtn = document.getElementById('batalUbahBtn');

        // Toggle edit mode
        toggleUbahBtn.addEventListener('click', function () {
            dataDisplay.classList.add('hidden');
            dataForm.classList.remove('hidden');
        });

        // Cancel edit
        batalUbahBtn.addEventListener('click', function () {
            dataForm.classList.add('hidden');
            dataDisplay.classList.remove('hidden');
        });

        // Show form if there are validation errors
        <?php if($errors->any() && !session('success')): ?>
            dataDisplay.classList.add('hidden');
            dataForm.classList.remove('hidden');
        <?php endif; ?>

        // Initialize icons
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/profil.blade.php ENDPATH**/ ?>