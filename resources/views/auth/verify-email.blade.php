<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Dishine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; background-color: #f8f5f2; } </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg border border-[#d6c3b3] text-center">
        <div class="mb-6">
            <img src="{{ asset('logo.png') }}" alt="Dishine Logo" class="h-12 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-[#3c2f2f]">Verifikasi Email Anda</h2>
        </div>

        <p class="text-gray-600 mb-6">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan ke email Anda.
        </p>
        
        <p class="text-sm text-gray-500 mb-6">
            Tidak menerima email? Cek folder spam Anda.
        </p>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <!-- Tombol Kirim Ulang -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-[#b48a60] text-white font-semibold py-2.5 rounded-lg hover:bg-[#a07850] transition duration-200">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-[#3c2f2f] underline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>