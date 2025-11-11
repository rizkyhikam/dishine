<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dishine - E-commerce Terpercaya')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f5f2; color: #3c2f2f; }
        .btn-primary { background-color: #b48a60; color: white; }
        .btn-primary:hover { background-color: #a07850; }
    </style>
</head>
<body class="bg-[#f8f5f2] text-[#3c2f2f]">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-[#3c2f2f]">Dishine</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-[#3c2f2f] hover:text-[#b48a60]">Home</a>
                    <a href="/katalog" class="text-[#3c2f2f] hover:text-[#b48a60]">Katalog</a>
                    <a href="/faq" class="text-[#3c2f2f] hover:text-[#b48a60]">FAQ</a>
                    @auth
                        <a href="/profil" class="text-[#3c2f2f] hover:text-[#b48a60]">Profil</a>
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-[#3c2f2f] hover:text-[#b48a60]">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="btn-primary px-4 py-2 rounded">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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