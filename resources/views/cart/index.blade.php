@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h2>🛒 Keranjang Belanja</h2>

    {{-- ✅ Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ✅ Cek apakah cart kosong --}}
    @if($cartItems->isEmpty())
        <div class="text-center mt-4">
            <p class="fs-5 text-muted">Keranjang kamu masih kosong 😅</p>
            <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">
                🛍️ Lihat Katalog
            </a>
        </div>
    @else
        <div class="table-responsive mt-4">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
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
                                <input 
                                    type="number" 
                                    name="quantity" 
                                    value="{{ $item->quantity }}" 
                                    min="1" 
                                    class="form-control me-2" 
                                    style="width:80px;"
                                >
                                <button class="btn btn-sm btn-secondary">Update</button>
                            </form>
                        </td>
                        <td>
                            Rp {{ number_format($item->product->harga_normal * $item->quantity, 0, ',', '.') }}
                        </td>
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
        </div>

        {{-- ✅ Total dan tombol checkout --}}
        <div class="text-end mt-4">
            <h4><strong>Total:</strong> Rp {{ number_format($total, 0, ',', '.') }}</h4>
            <form action="{{ url('/checkout') }}" method="GET" class="d-inline">
                @csrf
                <button class="btn btn-success btn-lg mt-2">
                    ✅ Lanjut ke Checkout
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
