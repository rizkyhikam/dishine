@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Manajemen Produk</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Form Tambah Produk -->
<!-- Form Tambah Produk (VERSI UPGRADE) -->
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
                            <!-- DROPDOWN KATEGORI (BARU) -->
                            <label>Kategori Produk</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Harga Normal</label>
                            <input type="number" name="harga_normal" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Harga Reseller</label>
                            <input type="number" name="harga_reseller" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Stok</label>
                            <input type="number" name="stok" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <!-- INPUT GAMBAR SAMPUL (Ganti Label) -->
                        <div class="col-md-6">
                            <label>Gambar Sampul (Cover)</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*" required>
                            <small class="form-text text-muted">Ini adalah foto utama di katalog.</small>
                        </div>
                        
                        <!-- INPUT GALERI FOTO (BARU) -->
                        <div class="col-md-6">
                            <label>Galeri Foto (Opsional)</label>
                            <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                            <small class="form-text text-muted">Tahan Ctrl/Cmd untuk pilih banyak foto.</small>
                        </div>
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
            
            <!-- KEPALA TABEL (<thead>) YANG SUDAH BENAR -->
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kategori</th> <!-- <-- Kolom baru -->
                    <th>Harga Normal</th>
                    <th>Harga Reseller</th>
                    <th>Stok</th>
                    <th>Gambar (Sampul)</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <!-- ISI TABEL (<tbody>) YANG SUDAH BENAR -->
            <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->nama }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td> <!-- <-- Kolom baru -->
                        <td>Rp{{ number_format($product->harga_normal, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($product->harga_reseller, 0, ',', '.') }}</td>
                        <td>{{ $product->stok }}</td>
                        <td>
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" width="60" alt="{{ $product->nama }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning me-1 mb-1">Edit</a>
                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger mb-1" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada produk.</td>
                    </tr>
                @endforelse
                <!-- SAYA SUDAH HAPUS KATA 'Category' YANG NYASAR DARI SINI -->
            </tbody>
        </table>
    </div>
</div>
@endsection