<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DISHINE Admin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F7F8FC; }
        /* Scrollbar kustom untuk sidebar */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #b48a60; border-radius: 10px; }
        .sidebar::-webkit-scrollbar-track { background: #e0d5ce; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen bg-gray-100">
        
        <!-- 
        =================================
        SIDEBAR (WARNA BARU)
        =================================
        -->
        <aside class="w-64 bg-[#f8f5f2] text-[#3c2f2f] flex flex-col fixed h-full sidebar overflow-y-auto border-r border-[#d6c3b3]">
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-[#d6c3b3]">
                <img src="{{ asset('logo.png') }}" alt="Dishine Logo" class="h-12">
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 mt-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition
                          {{ request()->is('admin/dashboard*') ? 'bg-[#b48a60] text-white' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.orders') }}" 
                   class="flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition
                          {{ request()->is('admin/orders*') ? 'bg-[#b48a60] text-white' : '' }}">
                    <i data-lucide="archive" class="w-5 h-5 mr-3"></i>
                    <span>Manajemen Pesanan</span>
                </a>
                
                <a href="{{ route('admin.products') }}" 
                   class="flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition
                          {{ request()->is('admin/products*') ? 'bg-[#b48a60] text-white' : '' }}">
                    <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                    <span>Manajemen Produk</span>
                </a>
                
                <a href="{{ route('admin.categories') }}" 
                   class="flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition
                          {{ request()->is('admin/categories*') ? 'bg-[#b48a60] text-white' : '' }}">
                    <i data-lucide="list" class="w-5 h-5 mr-3"></i>
                    <span>Manajemen Kategori</span>
                </a>

                <a href="{{ route('admin.faq') }}" 
                   class="flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition
                          {{ request()->is('admin/faq*') ? 'bg-[#b48a60] text-white' : '' }}">
                    <i data-lucide="help-circle" class="w-5 h-5 mr-3"></i>
                    <span>Manajemen FAQ</span>
                </a>
            </nav>

            <!-- User/Keluar -->
            <div class="py-4 px-6 border-t border-[#d6c3b3]">
                <!-- Form Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type-="submit" 
                       class="w-full flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition rounded-md">
                        <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- 
        =================================
        KONTEN UTAMA (KANAN)
        =================================
        -->
        <div class="flex-1 flex flex-col ml-64">
            
            <!-- Navbar Atas (Tetap putih) -->
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-end px-8">
                <div class="flex items-center space-x-6">
                    <button class="text-gray-500 hover:text-gray-700 relative">
                        <i data-lucide="bell" class="w-6 h-6"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">3</span>
                    </button>
                    
                    <div class="relative">
                        <button class="flex items-center space-x-2">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center bg-[#b48a60] text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Area Konten Halaman -->
            <main class="flex-1 p-8 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>