<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        // UBAH INI SAJA: ganti get() menjadi paginate()
        $sliders = Slider::orderBy('position')->paginate(10); // ← PERUBAHAN DI SINI
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'alt' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('uploads/sliders', 'public');

        Slider::create([
            'image' => $path,
            'alt' => $request->alt,
            'position' => $request->position ?? 0,
            'is_active' => $request->has('is_active'),
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

        if ($request->hasFile('image')) {
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }

            $path = $request->file('image')->store('uploads/sliders', 'public');
            $data['image'] = $path;
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil diupdate!');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider berhasil dihapus!');
    }
}