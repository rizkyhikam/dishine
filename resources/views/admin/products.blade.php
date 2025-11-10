@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Manajemen Produk</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Tambah Produk -->
    <div class="card mb-4">
        <div class="card-header">Tambah Produk Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.products') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Produk</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Harga Normal</label>
                        <input type="number" name="harga_normal" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label>Gambar Produk</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Produk</button>
            </form>
        </div>
    </div>

    <!-- Daftar Produk -->
    <div class="card">
        <div class="card-header">Daftar Produk</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga Normal</th>
                        <th>Harga Reseller</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->nama }}</td>
                            <td>Rp{{ number_format($product->harga_normal, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($product->harga_reseller, 0, ',', '.') }}</td>
                            <td>{{ $product->stok }}</td>
                            <td>
                                @if($product->gambar)
                                    <img src="{{ asset('storage/' . $product->gambar) }}" width="60">
                                @endif
                            </td>
                            <td>
                                <form action="{{ url('admin/products/'.$product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
