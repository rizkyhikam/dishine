<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Dummy user untuk FE sementara
        $user = User::first(); // ambil user pertama aja dulu
        return view('profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::first(); // nanti diganti Auth::user()

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
