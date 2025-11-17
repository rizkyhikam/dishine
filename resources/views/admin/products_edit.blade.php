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

    <!-- Form Edit Produk (VERSI UPGRADE) -->
    <div class="card mb-4">
        <div class="card-header">Edit Detail Produk</div>
        <div class="card-body">
            
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- PENTING: Gunakan method PUT untuk update --}}

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Produk</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $product->nama) }}" required>
                    </div>
                    
                    <!-- DROPDOWN KATEGORI (BARU) -->
                    <div class="col-md-6">
                        <label>Kategori Produk</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{-- Ini untuk memilih kategori yang sudah tersimpan --}}
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Harga Normal</label>
                        <input type="number" name="harga_normal" class="form-control" value="{{ old('harga_normal', $product->harga_normal) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="form-control" value="{{ old('harga_reseller', $product->harga_reseller) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="{{ old('stok', $product->stok) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                </div>
                
                <!-- GAMBAR SAMPUL (COVER) -->
                <div class="mb-3">
                    <label>Gambar Sampul (Cover)</label>
                    <div class="mb-2">
                        @if($product->gambar)
                            <!-- 
                                INI ADALAH BARIS 89 YANG SUDAH DIPERBAIKI
                                (typo ' ' ekstra sudah dihapus)
                            -->
                            <img src="{{ asset('storage/' . $product->gambar) }}" width="150" alt="Cover">
                        @else
                            <p>Tidak ada gambar sampul</p>
                        @endif
                    </div>
                    <input type="file" name="gambar" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar sampul.</small>
                </div>

                <!-- GALERI FOTO (BARU) -->
                <div class="mb-3">
                    <label>Galeri Foto</label>
                    <div class="row g-2 mb-2">
                        {{-- Tampilkan semua foto galeri yang ada --}}
                        @forelse($product->images as $image)
                            <div class="col-auto">
                                <div class="card" style="width: 100px;">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top" alt="Gallery Image" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-1 text-center">
                                        {{-- Checkbox untuk HAPUS gambar --}}
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_img_{{ $image->id }}">
                                        <label for="delete_img_{{ $image->id }}" class="form-check-label small">Hapus</label>
                                    </div>
                                 </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">Belum ada foto galeri.</p>
                            </div>
                        @endforelse
                    </div>

                    <label>Tambah Foto Galeri Baru (Opsional)</label>
                    <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    <small class="form-text text-muted">Tahan Ctrl/Cmd untuk pilih banyak foto baru.</small>
                </div>

                <hr>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route('admin.products') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection