<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dishine - E-commerce Terpercaya'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f5f2; 
            color: #3c2f2f; 
            margin: 0;
            padding: 0;
        }
        .btn-primary { background-color: #b48a60; color: white; }
        .btn-primary:hover { background-color: #a07850; }
        
        /* Mobile Menu Animation */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .mobile-menu.active {
            max-height: 500px;
        }
    </style>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-[#f8f5f2] text-[#3c2f2f] flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full bg-gradient-to-r from-[#f8f5f2] to-[#f0e7e0] border-b-2 border-[#d6c3b3] shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            <div class="flex items-center justify-between h-16 lg:h-20">
                
                <!-- Logo (Kiri) -->
                <div class="flex items-center">
                    <a href="/" class="inline-block">
                        <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-10 lg:h-12 w-auto object-contain">
                    </a>
                </div>

                <!-- Desktop Navigation (Center-Right) -->
                <div class="hidden lg:flex items-center flex-1 justify-end space-x-6">
                    
                    <!-- Search Bar -->

                    <!-- Cart Icon -->
                    <a href="/cart" class="relative p-2 text-[#CC8650] hover:text-[#8B6F47] transition-colors hover:bg-white/50 rounded-lg">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <?php if(session('cart_count', 0) > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md">
                                <?php echo e(session('cart_count')); ?>

                            </span>
                        <?php endif; ?>
                    </a>

                    <form action="<?php echo e(url('/katalog')); ?>" method="GET" 
                        class="flex items-center border-2 border-[#d6c3b3] rounded-xl bg-white overflow-hidden shadow-sm w-80 hover:border-[#CC8650] transition-all">
                        <input type="text" 
                            name="q"
                            value="<?php echo e(request('q')); ?>"
                            placeholder="Cari produk impian Anda..."
                            class="w-full px-4 py-2.5 text-sm focus:outline-none">
                        <button type="submit" class="px-4 text-[#b48a60] hover:text-[#CC8650] transition-colors">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>
                    </form>

                    <!-- Menu Items -->
                    <a href="/" class="flex items-center text-[#3c2f2f] hover:text-[#CC8650] space-x-2 transition-colors px-3 py-2 rounded-lg hover:bg-white/50 <?php echo e(request()->is('/') ? 'text-[#CC8650] font-semibold bg-white/70' : ''); ?>">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Beranda</span>
                    </a>

                    <a href="/katalog" class="flex items-center text-[#3c2f2f] hover:text-[#CC8650] space-x-2 transition-colors px-3 py-2 rounded-lg hover:bg-white/50 <?php echo e(request()->is('katalog*') ? 'text-[#CC8650] font-semibold bg-white/70' : ''); ?>">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Katalog</span>
                    </a>

                    <?php if(auth()->guard()->check()): ?>
                        <!-- Pesanan -->
                        <a href="<?php echo e(route('orders.view')); ?>" class="flex items-center text-[#3c2f2f] hover:text-[#CC8650] space-x-2 transition-colors px-3 py-2 rounded-lg hover:bg-white/50 <?php echo e(request()->is('orders*') ? 'text-[#CC8650] font-semibold bg-white/70' : ''); ?>">
                            <i data-lucide="package" class="w-5 h-5"></i>
                            <span class="text-sm font-medium">Pesanan</span>
                        </a>

                        <!-- User Profile -->
                        <a href="/profil" class="flex items-center space-x-2 px-4 py-2 rounded-full bg-white border-2 border-[#d6c3b3] hover:border-[#CC8650] transition-all shadow-sm">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#CC8650] to-[#D4BA98] flex items-center justify-center text-white font-bold shadow-sm">
                                <?php echo e(substr(explode(' ', Auth::user()->nama)[0], 0, 1)); ?>

                            </div>
                            <span class="text-sm font-semibold text-[#3c2f2f]"><?php echo e(explode(' ', Auth::user()->nama)[0]); ?></span>
                        </a>
                    <?php else: ?>
                        <!-- Login Button -->
                        <a href="/login" class="bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white px-6 py-2.5 rounded-xl hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center space-x-2 font-semibold">
                            <i data-lucide="log-in" class="w-5 h-5"></i>
                            <span>Masuk</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex lg:hidden items-center space-x-3">
                    <!-- Cart Icon (Mobile) -->
                    <a href="/cart" class="relative p-2 text-[#CC8650]">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <?php if(session('cart_count', 0) > 0): ?>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                <?php echo e(session('cart_count')); ?>

                            </span>
                        <?php endif; ?>
                    </a>

                    <button id="mobileMenuToggle" class="p-2 text-[#3c2f2f] hover:text-[#CC8650] transition-colors">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="mobile-menu lg:hidden border-t border-[#d6c3b3] bg-white/50 backdrop-blur-sm">
                <div class="py-4 space-y-2 px-4">
                    <!-- Search Bar (Mobile) -->
                    <form action="<?php echo e(url('/katalog')); ?>" method="GET" 
                        class="flex items-center border-2 border-[#d6c3b3] rounded-xl bg-white overflow-hidden mb-4">
                        <input type="text" 
                            name="q"
                            value="<?php echo e(request('q')); ?>"
                            placeholder="Cari produk..."
                            class="w-full px-4 py-2 text-sm focus:outline-none">
                        <button type="submit" class="px-3 text-[#b48a60]">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </button>
                    </form>

                    <a href="/" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white transition-colors <?php echo e(request()->is('/') ? 'bg-white text-[#CC8650] font-semibold' : 'text-[#3c2f2f]'); ?>">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span>Beranda</span>
                    </a>

                    <a href="/katalog" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white transition-colors <?php echo e(request()->is('katalog*') ? 'bg-white text-[#CC8650] font-semibold' : 'text-[#3c2f2f]'); ?>">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                        <span>Katalog</span>
                    </a>

                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('orders.view')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white transition-colors <?php echo e(request()->is('orders*') ? 'bg-white text-[#CC8650] font-semibold' : 'text-[#3c2f2f]'); ?>">
                            <i data-lucide="package" class="w-5 h-5"></i>
                            <span>Pesanan Saya</span>
                        </a>

                        <a href="/profil" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-white transition-colors <?php echo e(request()->is('profil*') ? 'bg-white text-[#CC8650] font-semibold' : 'text-[#3c2f2f]'); ?>">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#CC8650] to-[#D4BA98] flex items-center justify-center text-white font-bold">
                                <?php echo e(substr(explode(' ', Auth::user()->nama)[0], 0, 1)); ?>

                            </div>
                            <span><?php echo e(explode(' ', Auth::user()->nama)[0]); ?></span>
                        </a>
                    <?php else: ?>
                        <a href="/login" class="flex items-center justify-center space-x-2 bg-gradient-to-r from-[#CC8650] to-[#D4BA98] text-white px-4 py-3 rounded-xl font-semibold shadow-md mx-4">
                            <i data-lucide="log-in" class="w-5 h-5"></i>
                            <span>Masuk / Daftar</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 w-full pt-16 lg:pt-20">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-[#5d4a42] to-[#4a3832] text-[#cbbbaa] py-8 lg:py-12 w-full mt-auto">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            <!-- Desktop Footer -->
            <div class="hidden lg:flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-10 brightness-200">
                    <div>
                        <p class="text-sm font-semibold text-white">Dishine</p>
                        <p class="text-xs">&copy; 2025 All rights reserved</p>
                    </div>
                </div>

                <div class="flex space-x-8 text-sm">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms & Conditions</a>
                    <a href="#" class="hover:text-white transition-colors">Contact Us</a>
                </div>
            </div>

            <!-- Mobile Footer -->
            <div class="lg:hidden text-center space-y-4">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="Dishine Logo" class="h-8 mx-auto brightness-200">
                <p class="text-xs">&copy; 2025 Dishine. All rights reserved</p>
                <div class="flex justify-center space-x-4 text-xs">
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <span>•</span>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                    <span>•</span>
                    <a href="#" class="hover:text-white transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <?php
        $firstName = auth()->check() ? explode(' ', auth()->user()->nama)[0] : 'pengunjung';
        $message = urlencode("Halo admin Dishine, saya $firstName ingin bertanya seputar produk Dishine.");
    ?>

    <a href="https://wa.me/6281291819276?text=<?php echo e($message); ?>"
       target="_blank"
       class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-2xl flex items-center justify-center z-50 transition-all transform hover:scale-110 hover:shadow-green-500/50">
        <i data-lucide="message-circle" class="w-6 h-6"></i>
    </a>

    <!-- Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuToggle?.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            const icon = mobileMenuToggle.querySelector('i');
            if (mobileMenu.classList.contains('active')) {
                icon.setAttribute('data-lucide', 'x');
            } else {
                icon.setAttribute('data-lucide', 'menu');
            }
            lucide.createIcons();
        });

        // Re-initialize icons after dynamic changes
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html><?php /**PATH C:\Aulia\dataD\KULIAH\PJBL\dishine\resources\views/layouts/app.blade.php ENDPATH**/ ?>