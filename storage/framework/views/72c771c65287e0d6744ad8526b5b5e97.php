<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'DISHINE Admin'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #F7F8FC; 
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { 
            background: linear-gradient(to bottom, #CC8650, #D4BA98); 
            border-radius: 10px; 
        }
        .sidebar::-webkit-scrollbar-track { background: #e0d5ce; }
        
        /* Dropdown Animation */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .dropdown-content.active {
            max-height: 500px;
        }

        /* Mobile Menu Overlay */
        .mobile-menu-overlay {
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="bg-gray-100">

    <?php
        $unreadNotifications = Auth::user()->unreadNotifications;
        $readNotifications = Auth::user()->readNotifications->take(5);
    ?>

    <div class="flex h-screen bg-gray-100 overflow-hidden">
        
        <!-- Mobile Menu Overlay -->
        <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden mobile-menu-overlay lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gradient-to-b from-[#f8f5f2] to-[#f0e7e0] text-[#3c2f2f] flex flex-col fixed h-full sidebar overflow-y-auto border-r-2 border-[#d6c3b3] z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl">
            
            <!-- Logo Section -->
            <div class="flex items-center justify-between h-20 px-6 border-b-2 border-[#d6c3b3] bg-white bg-opacity-50">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-12 w-auto object-contain">
                <!-- Close Button (Mobile Only) -->
                <button id="closeSidebar" class="lg:hidden text-[#3c2f2f] hover:text-[#CC8650] transition">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 mt-6 px-3">
                <!-- Dashboard -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" 
                   class="flex items-center px-4 py-3 mb-1 text-[#3c2f2f] hover:bg-gradient-to-r hover:from-[#CC8650] hover:to-[#D4BA98] hover:text-white rounded-xl transition-all duration-200 <?php echo e(request()->is('admin/dashboard*') ? 'bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white shadow-md' : ''); ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>
                
                <!-- Manajemen Pesanan -->
                <a href="<?php echo e(route('admin.orders')); ?>" 
                   class="flex items-center px-4 py-3 mb-1 text-[#3c2f2f] hover:bg-gradient-to-r hover:from-[#CC8650] hover:to-[#D4BA98] hover:text-white rounded-xl transition-all duration-200 <?php echo e(request()->is('admin/orders*') ? 'bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white shadow-md' : ''); ?>">
                    <i data-lucide="shopping-bag" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold">Pesanan</span>
                </a>
                
                <!-- Manajemen Produk -->
                <a href="<?php echo e(route('admin.products')); ?>" 
                   class="flex items-center px-4 py-3 mb-1 text-[#3c2f2f] hover:bg-gradient-to-r hover:from-[#CC8650] hover:to-[#D4BA98] hover:text-white rounded-xl transition-all duration-200 <?php echo e(request()->is('admin/products*') ? 'bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white shadow-md' : ''); ?>">
                    <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold">Produk</span>
                </a>
                
                <!-- Manajemen Kategori -->
                <a href="<?php echo e(route('admin.categories')); ?>" 
                   class="flex items-center px-4 py-3 mb-1 text-[#3c2f2f] hover:bg-gradient-to-r hover:from-[#CC8650] hover:to-[#D4BA98] hover:text-white rounded-xl transition-all duration-200 <?php echo e(request()->is('admin/categories*') ? 'bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white shadow-md' : ''); ?>">
                    <i data-lucide="layers" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold">Kategori</span>
                </a>

                <!-- Manajemen Pengguna -->
                <a href="<?php echo e(route('admin.users')); ?>" 
                   class="flex items-center px-4 py-3 mb-1 text-[#3c2f2f] hover:bg-gradient-to-r hover:from-[#CC8650] hover:to-[#D4BA98] hover:text-white rounded-xl transition-all duration-200 <?php echo e(request()->is('admin/users*') ? 'bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white shadow-md' : ''); ?>">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                    <span class="font-semibold">Pengguna</span>
                </a>

                <!-- Manajemen Web (Dropdown) -->
                <div class="mb-1">
                    <button id="webManagementToggle" class="flex items-center w-full px-4 py-3 text-[#3c2f2f] hover:bg-gradient-to-r hover:from-[#CC8650] hover:to-[#D4BA98] hover:text-white rounded-xl transition-all duration-200 <?php echo e(request()->is('admin/sliders*') ? 'bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white shadow-md' : ''); ?>">
                        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                        <span class="font-semibold flex-1 text-left">Kelola Web</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" id="webManagementChevron"></i>
                    </button>

                    <div id="webManagementDropdown" class="dropdown-content ml-4 mt-1">
                        <!-- Submenu Slider -->
                        <a href="<?php echo e(route('admin.sliders.index')); ?>"
                            class="flex items-center px-4 py-2 mb-1 text-[#3c2f2f] hover:bg-[#e0d5ce] rounded-lg transition <?php echo e(request()->is('admin/sliders*') ? 'bg-[#e0d5ce]' : ''); ?>">
                            <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                            <span class="text-sm font-medium">Slider</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t-2 border-[#d6c3b3] bg-white bg-opacity-30">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" 
                       class="w-full flex items-center px-4 py-3 text-[#3c2f2f] hover:bg-red-500 hover:text-white rounded-xl transition-all duration-200 font-semibold">
                        <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-64">
            
            <!-- Header/Navbar -->
            <header class="h-16 lg:h-20 bg-gradient-to-r from-[#f8f5f2] to-[#f0e7e0] border-b-2 border-[#d6c3b3] flex items-center justify-between px-4 lg:px-8 shadow-md sticky top-0 z-20">
                
                <!-- Mobile Menu Button -->
                <button id="openSidebar" class="lg:hidden text-[#3c2f2f] hover:text-[#CC8650] transition">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Logo (Mobile Only) -->
                <div class="lg:hidden">
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-8 w-auto object-contain">
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4 ml-auto">
                    <!-- Notification Bell -->
                    <div class="relative group">
                        <button class="relative p-2 text-[#3c2f2f] hover:bg-[#e0d5ce] rounded-full transition">
                            <i data-lucide="bell" class="w-5 h-5 lg:w-6 lg:h-6"></i>
                            <?php if($unreadNotifications->count() > 0): ?>
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-md">
                                    <?php echo e($unreadNotifications->count()); ?>

                                </span>
                            <?php endif; ?>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl overflow-hidden z-30 hidden group-hover:block border border-[#d6c3b3]">
                            <div class="p-4 bg-gradient-to-r from-[#CC8650] to-[#D4BA98]">
                                <h4 class="font-bold text-white flex items-center">
                                    <i data-lucide="bell" class="w-4 h-4 mr-2"></i>
                                    Notifikasi
                                </h4>
                            </div>
                            
                            <div class="divide-y max-h-96 overflow-y-auto">
                                <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e(route('admin.notifications.read', $notification->id)); ?>" 
                                       class="block p-4 hover:bg-[#f0e7e0] bg-blue-50 transition">
                                        <p class="font-semibold text-gray-800 text-sm">
                                            <?php echo e($notification->data['message']); ?>

                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i data-lucide="user" class="w-3 h-3 inline mr-1"></i>
                                            <?php echo e($notification->data['user_name']); ?> • <?php echo e($notification->created_at->diffForHumans()); ?>

                                        </p>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>

                                <?php $__currentLoopData = $readNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('admin.orders.show', $notification->data['order_id'])); ?>" 
                                       class="block p-4 hover:bg-[#f0e7e0] transition">
                                        <p class="font-medium text-gray-500 text-sm">
                                            <?php echo e($notification->data['message']); ?>

                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            <?php echo e($notification->created_at->diffForHumans()); ?> (Dibaca)
                                        </p>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($unreadNotifications->isEmpty() && $readNotifications->isEmpty()): ?>
                                    <div class="p-8 text-center">
                                        <i data-lucide="inbox" class="w-12 h-12 mx-auto text-gray-300 mb-2"></i>
                                        <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="flex items-center space-x-2 lg:space-x-3 bg-white bg-opacity-50 rounded-full px-2 lg:px-3 py-1.5 border border-[#d6c3b3]">
                        <div class="h-8 w-8 lg:h-10 lg:w-10 rounded-full flex items-center justify-center bg-gradient-to-br from-[#CC8650] to-[#D4BA98] text-white font-bold shadow-md">
                            <?php echo e(substr(Auth::user()->nama, 0, 1)); ?>

                        </div>
                        <span class="hidden sm:block font-semibold text-[#3c2f2f] text-sm lg:text-base"><?php echo e(Auth::user()->nama); ?></span>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 p-4 lg:p-8 overflow-y-auto">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Mobile Menu Toggle
        const sidebar = document.getElementById('sidebar');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');
        const overlay = document.getElementById('mobileMenuOverlay');

        openSidebar?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeSidebar?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Dropdown Toggle
        const webManagementToggle = document.getElementById('webManagementToggle');
        const webManagementDropdown = document.getElementById('webManagementDropdown');
        const webManagementChevron = document.getElementById('webManagementChevron');

        webManagementToggle?.addEventListener('click', () => {
            webManagementDropdown.classList.toggle('active');
            webManagementChevron.classList.toggle('rotate-180');
        });

        // Auto-open dropdown if currently on slider page
        if (window.location.pathname.includes('/admin/sliders')) {
            webManagementDropdown?.classList.add('active');
            webManagementChevron?.classList.add('rotate-180');
        }

        // Re-initialize icons after any dynamic content changes
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/layouts/admin.blade.php ENDPATH**/ ?>