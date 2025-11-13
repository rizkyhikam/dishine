<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dishine - E-commerce Terpercaya'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f5f2; color: #3c2f2f; }
        .btn-primary { background-color: #b48a60; color: white; }
        .btn-primary:hover { background-color: #a07850; }
    </style>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-[#f8f5f2] text-[#3c2f2f]">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full bg-[#f8f5f2] border-b border-[#d6c3b3] py-3 pl-6 pr-6 z-50">
        <div class="flex justify-between items-center">

            <!-- Left: Logo -->
            <div class="flex items-center space-x-2">
                <a href="/" class="inline-block">
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-12">
                </a>
            </div>

            <!-- Center: Search Bar + Cart -->
            <div class="flex items-center space-x-3 w-1/2">
                <!-- Cart icon -->
                <a href="/cart" class="p-2 text-[#b48a60] hover:text-[#a07850] relative">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    <?php if(session('cart_count', 0) > 0): ?>
                        <span class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full px-1.5">
                            <?php echo e(session('cart_count')); ?>

                        </span>
                    <?php endif; ?>
                </a>

                <!-- Search -->
                <div class="flex items-center border border-[#d6c3b3] rounded-md w-full bg-white">
                    <input type="text" placeholder="Search"
                        class="w-full px-3 py-2 text-sm focus:outline-none">
                    <button class="px-3 text-[#b48a60] hover:text-[#a07850]">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <!-- Right: Menu -->
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
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-[#5d4a42] text-[#cbbbaa] py-6 mt-10">
        <div class="flex justify-between items-start px-10 md:px-16 lg:px-24">
            
            <!-- Left: Logo dan Copyright -->
            <div class="flex flex-col items-start space-y-2">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-12">
                <p class="text-sm">&copy; 2025 Dishine. All rights reserved.</p>
            </div>

            <!-- Right: Links -->
            <div class="flex space-x-6 text-sm mt-2">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms & Conditions</a>
                <a href="#" class="hover:text-white transition">Cookie Policy</a>
                <a href="#" class="hover:text-white transition">Contact</a>
            </div>
        </div>
    </footer>


    <!-- Aktifkan ikon -->
    <script>
        lucide.createIcons();
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800, // Durasi animasi dalam milidetik (800ms = 0.8 detik)
            once: true     // Animasi hanya berjalan sekali saat elemen pertama kali terlihat
        });
    </script>
</body>
</html><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/layouts/app.blade.php ENDPATH**/ ?>