@extends('layouts.admin')

@section('title', 'Kelola Pesanan - Admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8">Kelola Pesanan</h1>
    <table class="w-full bg-white shadow-md rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-4">ID Pesanan</th>
                <th class="p-4">Pengguna</th>
                <th class="p-4">Total</th>
                <th class="p-4">Status</th>
                <th class="p-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td class="p-4">{{ $order->id }}</td>
                <td class="p-4">{{ $order->user->nama }}</td>
                <td class="p-4">Rp {{ number_format($order->total) }}</td>
                <td class="p-4">{{ $order->status }}</td>
                <td class="p-4">
                    <button class="btn-primary px-4 py-2 rounded">Lihat Detail</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection