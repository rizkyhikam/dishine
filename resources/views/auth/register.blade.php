@extends('layouts.app')

@section('title', 'Register - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10 flex justify-center">
    <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Daftar Akun Dishine</h1>
        @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

        <form action="/register" method="POST">
            @csrf
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium mb-2">Nama:</label>
                <input type="text" name="nama" id="nama" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email:</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Password:</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium mb-2">Konfirmasi Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium mb-2">Role:</label>
                <select name="role" id="role" class="w-full p-2 border rounded" required>
                    <option value="pelanggan">Pelanggan</option>
                    <option value="reseller">Reseller</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium mb-2">Alamat:</label>
                <textarea name="alamat" id="alamat" class="w-full p-2 border rounded" required></textarea>
            </div>
            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium mb-2">No. HP:</label>
                <input type="text" name="no_hp" id="no_hp" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="btn-primary w-full py-2 rounded">Daftar</button>
        </form>
        <p class="text-center mt-4">Sudah punya akun? <a href="/login" class="text-[#b48a60] hover:underline">Login di sini</a></p>
    </div>
</div>
@endsection