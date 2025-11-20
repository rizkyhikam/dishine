@extends('layouts.admin')

@section('title', 'Edit Slider')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Slider</h2>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Saat Ini</label>
                <img src="{{ asset('storage/' . $slider->image) }}" class="w-64 rounded border shadow-sm mb-2">
                
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Ganti Gambar (Opsional)
                </label>
                <input type="file" name="image" id="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="alt">Alt Text</label>
                <input type="text" name="alt" id="alt" value="{{ $slider->alt }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="position">Posisi Urutan</label>
                <input type="number" name="position" id="position" value="{{ $slider->position }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600" {{ $slider->is_active ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Aktifkan Slider Ini</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
