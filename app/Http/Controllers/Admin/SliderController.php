<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace sesuai folder

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class SliderController extends Controller
{
    public function index()
    {
        // Ambil slider urut berdasarkan posisi
        $sliders = Slider::orderBy('position')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // Max 2MB
            'alt' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            // 'is_active' tidak perlu divalidasi required, karena checkbox
        ]);

        // Simpan gambar ke folder 'public/sliders'
        // Hasilnya: 'sliders/namafile.jpg'
        $path = $request->file('image')->store('sliders', 'public');

        Slider::create([
            'image' => $path, // Simpan path-nya saja
            'alt' => $request->alt,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'), // True jika dicentang
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|max:2048',
            'alt' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
        ]);

        $data = [
            'alt' => $request->alt,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        // Jika ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }

            // Upload gambar baru
            $path = $request->file('image')->store('sliders', 'public');
            $data['image'] = $path;
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil diupdate!');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        // Hapus gambar dari storage
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil dihapus!');
    }
}