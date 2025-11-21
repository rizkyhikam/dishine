<?php $__env->startSection('title', 'Manajemen Pengguna - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] p-4 rounded-2xl shadow-lg">
                <i data-lucide="users" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Manajemen Pengguna</h1>
                <p class="text-gray-600 mt-1">Kelola data pengguna dan reseller toko Anda</p>
            </div>
        </div>
    </div>

    <!-- Card Putih untuk Tabel -->
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        
        <!-- Form Filter dengan Gradient Header -->
        <div class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] px-8 py-6">
            
            <form action="<?php echo e(route('admin.users')); ?>" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    
                    <!-- Filter Nama / No HP -->
                    <div class="md:col-span-3">
                        <label for="search" class="block text-sm font-semibold text-white mb-2">Cari Nama atau No. HP</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="text" name="search" id="search" 
                                   class="w-full border-2 border-white border-opacity-30 bg-white rounded-xl pl-10 pr-4 py-3 text-sm focus:border-white focus:ring focus:ring-white focus:ring-opacity-30 transition-all" 
                                   placeholder="Ketik nama atau nomor HP..."
                                   value="<?php echo e($filters['search'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="flex space-x-2">
                        <button type="submit" class="flex-1 bg-white text-[#CC8650] px-4 py-3 rounded-xl hover:shadow-lg font-bold text-sm flex items-center justify-center transition-all">
                            <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                            Cari
                        </button>
                        <a href="<?php echo e(route('admin.users')); ?>" class="flex-1 bg-[#8B6F47] text-white px-4 py-3 rounded-xl hover:bg-[#725a38] font-semibold text-sm flex items-center justify-center transition-all">
                            <i data-lucide="x" class="w-4 h-4 mr-1"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Bar -->
        <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-gray-700">
                    <i data-lucide="info" class="w-5 h-5 text-[#AE8B56]"></i>
                    <span class="text-sm font-semibold">Total Pengguna: <span class="text-[#CC8650] font-bold"><?php echo e($users->total()); ?></span></span>
                </div>
                <?php if(request()->has('search')): ?>
                    <span class="text-sm text-gray-600">
                        <i data-lucide="filter" class="w-4 h-4 inline"></i>
                        Filter aktif: <span class="font-semibold">"<?php echo e(request('search')); ?>"</span>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabel Pengguna -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Pengguna
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Role
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gradient-to-r hover:from-[#F0E7DB] hover:to-transparent transition-all duration-150">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-full flex items-center justify-center bg-gradient-to-br from-[#CC8650] to-[#D4BA98] text-white font-bold text-lg mr-4 shadow-md">
                                        <?php echo e(substr($user->nama, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-gray-900"><?php echo e($user->nama); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i data-lucide="mail" class="w-4 h-4 mr-2 text-[#AE8B56]"></i>
                                        <span class="font-medium"><?php echo e($user->email); ?></span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i data-lucide="phone" class="w-4 h-4 mr-2 text-[#AE8B56]"></i>
                                        <span class="font-medium"><?php echo e($user->no_hp ?? '-'); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <?php if($user->alamat): ?>
                                    <div class="flex items-start max-w-xs">
                                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-[#AE8B56] mt-0.5 flex-shrink-0"></i>
                                        <span class="text-sm text-gray-700 line-clamp-2" title="<?php echo e($user->alamat); ?>">
                                            <?php echo e($user->alamat); ?>

                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400 italic">Belum diisi</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-5 whitespace-nowrap">
                                <?php if($user->role == 'reseller'): ?>
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-[#D4BA98] to-[#D0B682] text-white shadow-sm">
                                        <i data-lucide="store" class="w-3 h-3 mr-1.5"></i>
                                        Reseller
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-full bg-gradient-to-r from-[#AE8B56] to-[#CC8650] text-white shadow-sm">
                                        <i data-lucide="user" class="w-3 h-3 mr-1.5"></i>
                                        <?php echo e(ucfirst($user->role)); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <?php if(request()->has('search')): ?>
                                        <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                            <i data-lucide="search-x" class="w-16 h-16 text-[#AE8B56]"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Hasil</h3>
                                        <p class="text-gray-500 mb-4">Tidak ada pengguna yang cocok dengan pencarian Anda.</p>
                                        <a href="<?php echo e(route('admin.users')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                                            <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                            Hapus Filter
                                        </a>
                                    <?php else: ?>
                                        <div class="bg-[#F0E7DB] p-6 rounded-full mb-4">
                                            <i data-lucide="users-2" class="w-16 h-16 text-[#AE8B56]"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Pengguna</h3>
                                        <p class="text-gray-500">Belum ada data pengguna terdaftar.</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel (Pagination) -->
        <?php if($users->hasPages()): ?>
            <div class="bg-gradient-to-r from-[#EBE6E6] to-[#F0E7DB] px-8 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Menampilkan <span class="font-bold text-[#CC8650]"><?php echo e($users->firstItem()); ?></span> - <span class="font-bold text-[#CC8650]"><?php echo e($users->lastItem()); ?></span> dari <span class="font-bold text-[#CC8650]"><?php echo e($users->total()); ?></span> pengguna
                    </div>
                    <div>
                        <?php echo e($users->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Script untuk inisialisasi Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/users.blade.php ENDPATH**/ ?>