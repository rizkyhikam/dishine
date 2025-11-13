<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('page_title', 'Transaksi'); ?> - Dishine</title>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Warna latar dan teks default */
        body { font-family: 'Poppins', sans-serif; background-color: #f7f3f1; color: #3c2f2f; }
        
        /* Warna Utama Dishine (cokelat kemerahan) */
        .bg-dishine { background-color: #92584e; }
        .hover\:bg-dishine-dark:hover { background-color: #a56f66; }
        .text-dishine { color: #92584e; }
        
        /* Tombol Utama */
        .btn-dishine {
            @apply px-4 py-2 rounded-lg font-semibold transition duration-300;
        }
        
        /* Replikasi warna latar yang lebih terang di HiFi (Header/Footer Control) */
        .bg-hifi-light { background-color: #f0ebe8; }
        .bg-hifi-control { background-color: #f7f3f1; }
        .bg-hifi-dark-footer { background-color: #443c3a; }

        <?php echo $__env->yieldContent('styles'); ?>
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <nav class="bg-white shadow-sm">
            <div class="flex items-center space-x-5">
                <!-- Home -->
                <a href="/" class="flex items-center text-[#3c2f2f] hover:text-[#b48a60] space-x-1">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    <span>Beranda</span>
                </a>

                <!-- Catalog -->
                <a href="/katalog" class="flex items-center text-[#3c2f2f] hover:text-[#b48a60] space-x-1">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Katalog</span>
                </a>

                <!-- Auth Section -->
                <?php if(auth()->guard()->check()): ?>
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <img src="<?php echo e(Auth::user()->profile_photo_url ?? asset('images/default-user.jpg')); ?>"
                            alt="Profile" class="h-8 w-8 rounded-full object-cover border border-[#d6c3b3]">
                        <span class="text-[#3c2f2f] font-medium"><?php echo e(Auth::user()->name); ?></span>
                    </div>
                <?php else: ?>
                    <a href="/login" class="bg-[#b48a60] text-white px-4 py-2 rounded-md hover:bg-[#a07850] flex items-center space-x-1">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        <span>Login</span>
                    </a>
                <?php endif; ?>
    </nav>
    
    
    <header class="bg-hifi-light border-b border-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                
                <div class="flex items-center">
                    <a href="<?php echo e(url('/')); ?>" class="mr-6">
                        <img src="<?php echo e(asset('logodishine.png')); ?>" alt="Dishine" class="h-9">
                    </a>
                    <h2 class="text-xl font-bold text-gray-800">
                        <?php echo $__env->yieldContent('page_heading', 'Transaksi'); ?>
                    </h2>
                </div>
                
                
                <div class="relative w-72">
                    <input type="text" placeholder="Search" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-dishine pr-10">
                    <i data-lucide="search" class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>
        </div>
    </header>

    
    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
    
    
    <footer class="bg-hifi-dark-footer mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-end text-white text-opacity-70 text-sm">
            <div>
                
                <img src="<?php echo e(asset('img/logo-dishine-light.png')); ?>" alt="Dishine" class="h-8 opacity-70"> 
                <p class="mt-2 text-xs">Copyright 2022 Company Name.</p>
            </div>
            <div class="space-x-4 text-xs">
                <a href="#" class="hover:text-white">Privacy Policy</a>
                <a href="#" class="hover:text-white">Terms & Conditions</a>
                <a href="#" class="hover:text-white">Cookie Policy</a>
                <a href="#" class="hover:text-white">Contact</a>
            </div>
        </div>
    </footer>
    
    
    <script>
        lucide.createIcons();
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/layouts/transaction.blade.php ENDPATH**/ ?>