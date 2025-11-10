@extends('layouts.app')

@section('title', 'Kelola Pengguna - Admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Kelola Pengguna</h1>
    <table class="w-full bg-white shadow-md rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-4">Nama</th>
                <th class="p-4">Email</th>
                <th class="p-4">Role</th>
                <th class="p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="p-4">{{ $user->nama }}</td>
                <td class="p-4">{{ $user->email }}</td>
                <td class="p-4">{{ $user->role }}</td>
                <td class="p-4">
                    <button class="btn-primary px-4 py-2 rounded">Edit</button