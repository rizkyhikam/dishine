@extends('layouts.app')

@section('title', 'Login - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10 flex justify-center">
    <div class="bg-white rounded-lg shadow-md p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Login ke Dishine</h1>
        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">Email:</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2">Password:</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="btn-primary w-full py-2 rounded">Login</button>
        </form>
        <p class="text-center mt-4">Belum punya akun? <a href="/register" class="text-[#b48a60] hover:underline">Daftar di sini</a></p>
    </div>
</div>
@endsection