@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Edit Produk: {{ $product->nama }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit Produk -->
    <div class="card mb-4">
        <div class="card-header">Edit Detail Produk</div>
        <div class="card-body">
            
            {{-- Form ini akan mengarah ke rute 'admin.products.update' --}}
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- PENTING: Gunakan method PUT untuk update --}}

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Produk</label>
                        {{-- Tampilkan data lama di 'value' --}}
                        <input type="text" name="nama" class="form-control" value="{{ $product->nama }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Harga Normal</label>
                        <input type="number" name="harga_normal" class="form-control" value="{{ $product->harga_normal }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="form-control" value="{{ $product->harga_reseller }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ $product->stok }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ $product->deskripsi }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label>Gambar Produk Saat Ini</label>
                    <div class="mb-2">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" width="150">
                        @else
                            <p>Tidak ada gambar</p>
                        @endif
                    </div>
                    <label>Upload Gambar Baru (Opsional)</label>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route('admin.products') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection