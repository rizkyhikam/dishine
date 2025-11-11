@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>🛒 Keranjang Belanja</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cartItems->isEmpty())
        <p>Keranjang kamu masih kosong 😅</p>
        <a href="{{ route('katalog') }}" class="btn btn-primary">Lihat Katalog</a>
    @else
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->nama }}</td>
                    <td>Rp {{ number_format($item->product->harga_normal, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ url('/cart/update/'.$item->id) }}" method="POST" class="d-flex">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control me-2" style="width:80px;">
                            <button class="btn btn-sm btn-secondary">Update</button>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item->product->harga_normal * $item->quantity, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ url('/cart/remove/'.$item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-4">
            <h4>Total: Rp {{ number_format($total, 0, ',', '.') }}</h4>
            <a href="{{ url('/checkout') }}" class="btn btn-success mt-2">Lanjut ke Checkout</a>
        </div>
    @endif
</div>
@endsection
