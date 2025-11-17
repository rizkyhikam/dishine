@extends('layouts.admin')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Produk: {{ $product->nama }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit Produk (VERSI UPGRADE TAILWIND) -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="card-header bg-gray-50 border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-700">Edit Detail Produk</h2>
        </div>
        
        <div class="card-body p-6">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- PENTING: Gunakan method PUT untuk update --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="nama" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('nama', $product->nama) }}" required>
                    </div>
                    
                    <!-- DROPDOWN KATEGORI -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal</label>
                        <input type="number" name="harga_normal" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('harga_normal', $product->harga_normal) }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Reseller</label>
                        <input type="number" name="harga_reseller" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('harga_reseller', $product->harga_reseller) }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stok" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('stok', $product->stok) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                </div>
                
                <!-- GAMBAR SAMPUL (COVER) -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Sampul (Cover)</label>
                    <div class="mb-2">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}" width="150" alt="Cover" class="rounded-md border">
                        @else
                            <p class="text-xs text-gray-500">Tidak ada gambar sampul</p>
                        @endif
                    </div>
                    <input type="file" name="gambar" class="w-full border border-gray-300 rounded px-3 py-2">
                    <small class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar sampul.</small>
                </div>

                <!-- GALERI FOTO -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Galeri Foto</label>
                    <div class="flex flex-wrap gap-3 mb-2">
                        {{-- Tampilkan semua foto galeri yang ada --}}
                        @forelse($product->images as $image)
                            <div class="relative w-24 h-24">
                                <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover rounded-md border" alt="Gallery Image">
                                <div class="absolute bottom-0 left-0 right-0 bg-black/60 p-1 text-center">
                                    {{-- Checkbox untuk HAPUS gambar --}}
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_img_{{ $image->id }}" class="form-checkbox h-4 w-4 text-red-600 border-gray-300 rounded">
                                    <label for="delete_img_{{ $image->id }}" class="ml-1 text-xs text-white">Hapus</label>
                                </div>
                             </div>
                        @empty
                            <div class="col-12">
                                <p class="text-xs text-gray-500">Belum ada foto galeri.</p>
                            </div>
                        @endforelse
                    </div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">Tambah Foto Galeri Baru (Opsional)</label>
                    <input type="file" name="gallery[]" class="w-full border border-gray-300 rounded px-3 py-2" accept="image/*" multiple>
                    <small class="text-xs text-gray-500">Tahan Ctrl/Cmd untuk pilih banyak foto baru.</small>
                </div>

                <hr class="my-6 border-gray-200">
                <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">Simpan Perubahan</button>
                <a href="{{ route('admin.products') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-md text-sm font-medium">Batal</a>
            </form>
        </div>
    </div>
@endsection