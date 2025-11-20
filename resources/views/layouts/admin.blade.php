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
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #b48a60; border-radius: 10px; }
        .sidebar::-webkit-scrollbar-track { background: #e0d5ce; }
        
        /* Sembunyikan/Tampilkan Dropdown */
        .group:hover .group-hover\:block { display: block; }
    </style>
</head>
<body class="bg-gray-100">

    @php
        // Ambil notifikasi milik admin yang sedang login
        $unreadNotifications = Auth::user()->unreadNotifications;
        $readNotifications = Auth::user()->readNotifications->take(5); // Ambil 5 notif terakhir yg sudah dibaca
    @endphp

    <div class="flex h-screen bg-gray-100">
        
        <aside class="w-64 bg-[#f8f5f2] text-[#3c2f2f] flex flex-col fixed h-full sidebar overflow-y-auto border-r border-[#d6c3b3]">
            <div class="flex items-center justify-center h-20 border-b border-[#d6c3b3]">
                <img src="{{ asset('logo.png') }}" alt="Dishine Logo" class="h-12">
            </div>

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

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition
                          {{ request()->is('admin/users*') ? 'bg-[#b48a60] text-white' : '' }}">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                    <span>Manajemen Pengguna</span>
                </a>

                <!-- Manajemen Web (Dropdown) -->
<div class="group">
    <button class="flex items-center w-full px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition">
        <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
        <span>Manajemen Web</span>
        <i data-lucide="chevron-down" class="w-4 h-4 ml-auto"></i>
    </button>

    <div class="hidden group-hover:block ml-12 mt-1">
        <!-- Submenu Slider -->
        <a href="{{ route('admin.sliders.index') }}"
            class="flex items-center px-4 py-2 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black rounded-md transition
            {{ request()->is('admin/sliders*') ? 'bg-[#b48a60] text-white' : '' }}">
            <i data-lucide="image" class="w-4 h-4 mr-2"></i>
            <span>Slider</span>
        </a>
    </div>
</div>

            </nav>

            <div class="py-4 px-6 border-t border-[#d6c3b3]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                       class="w-full flex items-center px-6 py-3 text-[#3c2f2f] hover:bg-[#e0d5ce] hover:text-black transition rounded-md">
                        <i data-lucide="log-out" class="w-5 h-5 mr-3"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col ml-64">
            
            <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-end px-8">
                <div class="flex items-center space-x-6">

                    <div class="relative group">
                        <button class="text-gray-500 hover:text-gray-700 relative">
                            <i data-lucide="bell" class="w-6 h-6"></i>
                            @if($unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">
                                    {{ $unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl overflow-hidden z-20 hidden group-hover:block">
                            <div class="p-4 border-b">
                                <h4 class="font-semibold text-gray-800">Notifikasi</h4>
                            </div>
                            
                            <div class="divide-y max-h-96 overflow-y-auto">
                                
                                @forelse($unreadNotifications as $notification)
                                    <a href="{{ route('admin.notifications.read', $notification->id) }}" 
                                       class="block p-4 hover:bg-gray-50 bg-blue-50">
                                        <p class="font-semibold text-gray-800 text-sm">
                                            {{ $notification->data['message'] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Oleh: {{ $notification->data['user_name'] }} - {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </a>
                                @empty
                                    @endforelse

                                @foreach($readNotifications as $notification)
                                    <a href="{{ route('admin.orders.show', $notification->data['order_id']) }}" 
                                       class="block p-4 hover:bg-gray-50">
                                        <p class="font-medium text-gray-500 text-sm">
                                            {{ $notification->data['message'] }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }} (Dibaca)
                                        </p>
                                    </a>
                                @endforeach

                                @if($unreadNotifications->isEmpty() && $readNotifications->isEmpty())
                                    <p class="p-4 text-sm text-gray-500 text-center">Tidak ada notifikasi.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <button class="flex items-center space-x-2">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center bg-[#b48a60] text-white font-semibold">
                                {{ substr(Auth::user()->nama, 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-700">{{ Auth::user()->nama }}</span>
                        </button>
                    </div>
                </div>
            </header>
            
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