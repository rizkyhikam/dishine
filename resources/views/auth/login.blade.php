@extends('layouts.auth')

@section('title', 'Login - Dishine')

@section('content')
<div class="container mx-auto px-4 py-10 flex justify-center">
    <div class="w-full max-w-sm py-8">
        <h1 class="text-3xl font-serif font-extrabold text-center mb-6" style="color: #AE8B56;">
            Login
        </h1>
        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Email:</label>
                <input type="email" name="email" id="email" class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100" 
                style="border-color: #CC8550; color: #AE8B56;" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-2" style="color: #AE8B56;">Password:</label>
                <input type="password" name="password" id="password" 
                class="w-full p-2 border rounded focus:ring-0 focus:border-opacity-100" 
                style="border-color: #CC8550; color: #AE8B56;" required>
            </div>
            <button type="submit" class="btn-primary w-full py-2 rounded">Login</button>
        </form>
        <p class="text-center mt-4">Belum punya akun? <a href="/register" class="text-[#b48a60] hover:underline">Daftar di sini</a></p>
            <div class="text-center mt-12"> 
                <a href="/" class="inline-block">
                <img src="{{ asset('logodishine.png') }}" 
                alt="Dishine Logo - Design with Quality" 
                class="mx-auto h-35 mb-6 hover:opacity-90 transition duration-200" 
                style="max-width: 200px;">
                </a>
            </div>
    </div>
</div>
@endsection