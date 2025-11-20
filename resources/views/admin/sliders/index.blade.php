@extends('layouts.admin')

@section('title', 'Manajemen Slider')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Slider Banner</h2>

    <a href="{{ route('admin.sliders.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block">
        <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i> Tambah Slider
    </a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Gambar</th>
                    <th class="py-3 px-6 text-left">Alt Text</th>
                    <th class="py-3 px-6 text-center">Posisi</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse($sliders as $slider)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <img src="{{ asset('storage/' . $slider->image) }}" width="120" class="rounded">
                        </td>
                        <td class="py-3 px-6 text-left">{{ $slider->alt ?? '-' }}</td>
                        <td class="py-3 px-6 text-center">{{ $slider->position }}</td>
                        <td class="py-3 px-6 text-center">
                            @if($slider->is_active)
                                <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Aktif</span>
                            @else
                                <span class="bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="w-4 h-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                    <i data-lucide="edit"></i>
                                </a>
                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-4 h-4 transform hover:text-red-500 hover:scale-110">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Belum ada slider.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection