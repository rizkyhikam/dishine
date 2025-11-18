<?php $__env->startSection('title', 'Manajemen Pengguna - DISHINE Admin'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Pengguna</h1>

    <!-- Card Putih untuk Tabel -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        
        <!-- Form Filter (Search Bar) -->
        <form action="<?php echo e(route('admin.users')); ?>" method="GET">
            <div class="p-4 border-b border-gray-200 bg-gray-50 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <!-- Filter Nama / No HP -->
                <div class="md:col-span-3">
                    <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Cari Nama atau No. HP</label>
                    <input type="text" name="search" id="search" 
                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm" 
                           placeholder="Ketik nama atau nomor HP..."
                           value="<?php echo e($filters['search'] ?? ''); ?>">
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex space-x-2">
                    <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium flex items-center justify-center">
                        <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                        Cari
                    </button>
                    <a href="<?php echo e(route('admin.users')); ?>" class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-sm font-medium flex items-center justify-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <!-- =============================== -->


        <!-- Tabel Pengguna -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. HP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-medium text-gray-900"><?php echo e($user->nama); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($user->email); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?php echo e($user->no_hp ?? '-'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="<?php echo e($user->alamat); ?>">
                                <?php echo e($user->alamat ? Str::limit($user->alamat, 50) : '-'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <!-- Role Pill -->
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    <?php if($user->role == 'reseller'): ?> bg-green-200 text-green-800
                                    <?php else: ?> bg-blue-200 text-blue-800 <?php endif; ?>
                                ">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">
                                <?php if(request()->has('search')): ?>
                                    Tidak ada pengguna yang cocok dengan pencarian Anda.
                                <?php else: ?>
                                    Belum ada data pengguna.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel (Pagination) -->
        <div class="p-4 border-t border-gray-200">
            <?php echo e($users->links()); ?>

        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/admin/users.blade.php ENDPATH**/ ?>