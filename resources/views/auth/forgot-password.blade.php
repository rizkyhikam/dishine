<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Dishine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8f5f2; } </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-[#d6c3b3]">
        <div class="text-center mb-6">
            {{-- Ganti logo.png dengan path logomu --}}
            <img src="{{ asset('logodishine.png') }}" alt="Dishine Logo" class="h-20 mx-auto mb-4 object-contain">
            <h2 class="text-2xl font-bold text-[#3c2f2f]">Lupa Password?</h2>
            <p class="text-sm text-gray-600 mt-2">Masukkan email Anda, kami akan mengirimkan link untuk reset password.</p>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <!-- Notifikasi Error -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-[#6b5a4a] mb-1">Email Terdaftar</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border border-[#d6c3b3] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#b48a60] focus:border-transparent">
            </div>

            <button type="submit" class="w-full bg-[#b48a60] text-white font-semibold py-2.5 rounded-lg hover:bg-[#a07850] transition duration-200">
                Kirim Link Reset Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-[#b48a60] hover:underline">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>