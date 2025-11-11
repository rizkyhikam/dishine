<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dishine - E-commerce Terpercaya')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f5f2; color: #3c2f2f; }
        .btn-primary { background-color: #b48a60; color: white; }
        .btn-primary:hover { background-color: #a07850; }
    </style>
</head>
<body class="bg-[#f8f5f2] text-[#3c2f2f]">
    <!-- Navbar -->
    <nav class="bg-[#f8f5f2] border-b border-[#d6c3b3] py-3 pl-6 pr-6">
        <div class="flex justify-between items-center">

            <!-- Left: Logo -->
            <div class="flex items-center space-x-2">
                <img src="{{ asset('logo.png') }}" alt="Dishine Logo" class="h-12">
            </div>

            <!-- Center: Search Bar + Cart -->
            <div class="flex items-center space-x-3 w-1/2">
                <!-- Cart icon -->
                <a href="/cart" class="p-2 text-[#b48a60] hover:text-[#a07850] relative">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    @if(session('cart_count', 0) > 0)
                        <span class="absolute top-1 right-1 bg-red-500 text-white text-xs rounded-full px-1.5">
                            {{ session('cart_count') }}
                        </span>
                    @endif
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
                    <span>Home</span>
                </a>

                <!-- Catalog -->
                <a href="/catalog" class="flex items-center text-[#3c2f2f] hover:text-[#b48a60] space-x-1">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Catalog</span>
                </a>

                <!-- Auth Section -->
                @auth
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <!-- Profile picture -->
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default-user.jpg') }}"
                            alt="Profile" class="h-8 w-8 rounded-full object-cover border border-[#d6c3b3]">
                        <!-- Username -->
                        <span class="text-[#3c2f2f] font-medium">{{ Auth::user()->name }}</span>
                    </div>
                @else
                    <a href="/login" class="bg-[#b48a60] text-white px-4 py-2 rounded-md hover:bg-[#a07850] flex items-center space-x-1">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Aktifkan ikon -->
    <script>
        lucide.createIcons();
    </script>


    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-center space-x-6">
                <a href="https://instagram.com/dishine" class="text-[#3c2f2f] hover:text-[#b48a60]">Instagram</a>
                <a href="https://tiktok.com/@dishine" class="text-[#3c2f2f] hover:text-[#b48a60]">TikTok</a>
                <a href="https://shopee.co.id/dishine" class="text-[#3c2f2f] hover:text-[#b48a60]">Shopee</a>
            </div>
            <p class="text-center text-sm mt-4">&copy; 2023 Dishine. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>