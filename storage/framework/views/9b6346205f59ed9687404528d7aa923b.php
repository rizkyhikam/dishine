

<?php $__env->startSection('title', 'Profil Saya - Dishine'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f3e8e3] py-12">
    <div class="max-w-2xl mx-auto px-6">
        <h1 class="text-3xl font-bold text-[#3c2f2f] mb-8 text-center">Profil Pengguna</h1>

        <!-- Card Putih -->
        <div class="bg-white p-8 rounded-xl shadow-md">

            <?php if(session('success')): ?>
                <!-- Notifikasi Sukses (Versi Tailwind) -->
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
                    <span class="font-medium"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($errors->any() && !session('success')): ?>
                <!-- Notifikasi Error (Versi Tailwind) -->
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                    <p class="font-bold mb-2">Oops! Ada kesalahan:</p>
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- 
            =================================
            TAMPILAN DATA (DEFAULT)
            =================================
            -->
            <div id="dataDisplay">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-2xl font-semibold text-[#3c2f2f]">Data Diri</h2>
                    
                    <!-- Grup Tombol Aksi -->
                    <div class="flex items-center space-x-3">
                        <button type="button" id="toggleUbahBtn" class="bg-[#44351f] text-white px-4 py-2 rounded-md hover:bg-[#a07850] text-sm font-medium">
                            Ubah Data
                        </button>
                        
                        <!-- 
                        --- TOMBOL LOGOUT BARU ---
                        Ini adalah form yang terlihat seperti tombol
                        -->
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-800 text-sm font-medium">
                                Logout
                            </button>
                        </form>
                        <!-- --- AKHIR TOMBOL LOGOUT --- -->
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#6b5a4a]">Nama Lengkap</label>
                        <p class="text-lg text-gray-800"><?php echo e($user->nama ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6b5a4a]">Email</label>
                        <p class="text-lg text-gray-800"><?php echo e($user->email ?? '-'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6b5a4a]">Alamat Lengkap</label>
                        <p class="text-lg text-gray-800 whitespace-pre-line"><?php echo e($user->alamat ?? 'Belum diatur'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6b5a4a]">Nomor HP (WhatsApp)</label>
                        <p class="text-lg text-gray-800"><?php echo e($user->no_hp ?? 'Belum diatur'); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#6b5a4a]">Role</label>
                        <p class="text-lg text-gray-800"><?php echo e(ucfirst($user->role ?? 'guest')); ?></p>
                    </div>
                </div>
            </div>

            <!-- 
            =================================
            FORM EDIT (AWALNYA HIDDEN)
            =================================
            -->
            <div id="dataForm" class="hidden">
                <h2 class="text-2xl font-semibold text-[#3c2f2f] mb-4">Ubah Data Diri</h2>
                
                <form action="<?php echo e(route('profil.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-5">
                        
                        <!-- Nama Lengkap (Diganti ke 'nama') -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-[#6b5a4a] mb-1">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" 
                                   class="w-full border border-[#d6c3b3] rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#b48a60]" 
                                   value="<?php echo e(old('nama', $user->nama ?? '')); ?>" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-[#6b5a4a] mb-1">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="w-full border border-[#d6c3b3] rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#b48a60]" 
                                   value="<?php echo e(old('email', $user->email ?? '')); ?>" required>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-[#6b5a4a] mb-1">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                      class="w-full border border-[#d6c3b3] rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#b48a60]"
                                      placeholder="Contoh: Jl. Mawar No. 10, RT 01/RW 02, Kel. Sukamaju, Kec. Beji, Kota Depok, 16421"><?php echo e(old('alamat', $user->alamat ?? '')); ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Sangat penting untuk diisi agar bisa checkout.</p>
                        </div>

                        <!-- Nomor Telepon (Diganti ke 'no_hp') -->
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-[#6b5a4a] mb-1">Nomor HP (WhatsApp)</label>
                            <input type="text" id="no_hp" name="no_hp" 
                                   class="w-full border border-[#d6c3b3] rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#b48a60]" 
                                   value="<?php echo e(old('no_hp', $user->no_hp ?? '')); ?>"
                                   placeholder="Contoh: 08123456789">
                            <p class="text-xs text-gray-500 mt-1">Sangat penting untuk diisi agar bisa checkout.</p>
                        </div>

                        <!-- Role (Readonly) -->
                        <div>
                            <label class="block text-sm font-medium text-[#6b5a4a] mb-1">Role</label>
                            <input type="text" 
                                   class="w-full border border-gray-200 bg-gray-100 text-gray-500 rounded-md px-3 py-2" 
                                   value="<?php echo e(ucfirst($user->role ?? 'guest')); ?>" readonly>
                        </div>

                        <!-- Tombol Form -->
                        <div class="flex items-center space-x-3 pt-4">
                            <button type="submit" 
                                    class="bg-[#44351f] text-white px-7 py-3 rounded-md hover:bg-[#a07850] transition text-center font-semibold">
                                Simpan Perubahan
                            </button>
                            <button type="button" id="batalUbahBtn" 
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-7 py-3 rounded-md transition text-center font-semibold">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div> <!-- End dataForm -->

        </div>
    </div>
</div>

<!-- 
=================================
JAVASCRIPT UNTUK TOGGLE
=================================
-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dataDisplay = document.getElementById('dataDisplay');
        const dataForm = document.getElementById('dataForm');
        const toggleUbahBtn = document.getElementById('toggleUbahBtn');
        const batalUbahBtn = document.getElementById('batalUbahBtn');

        // Saat tombol "Ubah Data" diklik
        toggleUbahBtn.addEventListener('click', function () {
            dataDisplay.classList.add('hidden');
            dataForm.classList.remove('hidden');
        });

        // Saat tombol "Batal" diklik
        batalUbahBtn.addEventListener('click', function () {
            dataForm.classList.add('hidden');
            dataDisplay.classList.remove('hidden');
        });

        // Jika ada error validasi, langsung tunjukkan form-nya
        <?php if($errors->any() && !session('success')): ?>
            dataDisplay.classList.add('hidden');
            dataForm.classList.remove('hidden');
        <?php endif; ?>

        // Panggil lucide
        lucide.createIcons();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/profil.blade.php ENDPATH**/ ?>