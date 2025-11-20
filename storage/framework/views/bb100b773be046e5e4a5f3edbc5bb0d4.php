<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- === INI BARIS YANG SAYA TAMBAHKAN (WAJIB ADA) === -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- ================================================= -->

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
    <nav class="fixed top-0 left-0 w-full bg-[#f8f5f2] border-b border-[#d6c3b3] py-3 px-8 z-50">
        <div class="grid grid-cols-3 items-center">
            <!-- Left: Logo -->
            <div class="flex justify-start">
                <a href="/" class="inline-block">
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-10">
                </a>
            </div>

            <!-- Center: Search Bar + Cart -->
            <div class="flex items-center justify-center space-x-3">
                <!-- Cart icon -->
                <a href="/cart" class="p-2 text-[#b48a60] hover:text-[#a07850] relative flex-shrink-0">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <?php if(session('cart_count', 0) > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 min-w-[20px] h-5 flex items-center justify-center">
                            <?php echo e(session('cart_count')); ?>

                        </span>
                    <?php endif; ?>
                </a>

                <!-- Search Bar -->
                <form action="<?php echo e(url('/katalog')); ?>" method="GET" 
                    class="flex items-center border border-[#d6c3b3] rounded-md bg-white overflow-hidden shadow-sm w-96">
                    <input type="text" 
                        name="q"
                        value="<?php echo e(request('q')); ?>"
                        placeholder="Cari dress, kerudung..."
                        class="w-full px-3 py-1.5 text-sm focus:outline-none">
                    <button type="submit" class="px-3 text-[#b48a60] hover:text-[#a07850] flex-shrink-0">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <!-- Right: Menu -->
            <div class="flex items-center justify-end space-x-4">
                <!-- Home -->
                <a href="/" class="flex items-center text-[#3c2f2f] hover:text-[#b48a60] space-x-1.5 
                                pb-1 border-b-2 transition-colors
                                <?php echo e(request()->is('/') ? 'border-[#3c2f2f]' : 'border-transparent'); ?>">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Beranda</span>
                </a>

                <!-- Catalog -->
                <a href="/katalog" class="flex items-center text-[#3c2f2f] hover:text-[#b48a60] space-x-1.5 
                                    pb-1 border-b-2 transition-colors
                                    <?php echo e(request()->is('katalog*') ? 'border-[#3c2f2f]' : 'border-transparent'); ?>">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">Katalog</span>
                </a>

                <!-- Auth Section -->
                <?php if(auth()->guard()->check()): ?>
                    <!-- Link ke Riwayat Pesanan -->
                    <a href="<?php echo e(route('orders.view')); ?>" class="flex items-center text-[#3c2f2f] hover:text-[#b48a60] space-x-1.5 
                                                        pb-1 border-b-2 transition-colors
                                                        <?php echo e(request()->is('orders*') ? 'border-[#3c2f2f]' : 'border-transparent'); ?>">
                        <i data-lucide="archive" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Pesanan</span>
                    </a>

                    <!-- Link Profil -->
                    <a href="/profil" class="flex items-center text-[#3c2f2f] hover:text-[#a07850] space-x-2">
                        <div class="h-7 w-7 rounded-full border border-[#d6c3b3] flex items-center justify-center bg-white">
                            <i data-lucide="user" class="w-4 h-4 text-[#6b5a4a]"></i>
                        </div>
                        <span class="text-sm font-medium"><?php echo e(Auth::user()->nama); ?></span>
                    </a>
                
                <?php else: ?>
                    <!-- Link Login -->
                    <a href="/login" class="bg-[#b48a60] text-white px-4 py-1.5 rounded-md hover:bg-[#a07850] flex items-center space-x-1.5 transition-colors shadow-sm">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span class="text-sm font-medium">Login</span>
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

    <?php
        $username = auth()->check() ? auth()->user()->nama : 'pengunjung';
        $message = urlencode("Halo admin Dishine, saya $username ingin bertanya seputar produk Dishine.");
    ?>

    <a href="https://wa.me/6281291819276?text=<?php echo e($message); ?>"
    target="_blank"
    class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg 
            flex items-center justify-center z-50 transition-all">
        <i data-lucide="message-circle" class="w-6 h-6"></i>
    </a>

    <!-- Scripts -->
    <script>
        lucide.createIcons();
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html><?php /**PATH C:\Users\althof\Documents\KULYEAH\SEMESTER 3\pjbl lagi\dishine\resources\views/layouts/app.blade.php ENDPATH**/ ?>