<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();
        return view('profil', compact('user'));
    }

    /**
     * Meng-update data profil user
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi data
        $request->validate([
            // 'nama' (bukan 'name')
            'nama' => 'required|string|max:255',
            
            // Pastikan email unik, KECUALI untuk user ini sendiri
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            
            'alamat' => 'nullable|string|max:500',
            
            // 'no_hp' (bukan 'telepon')
            'no_hp' => 'nullable|string|max:20', 
        ]);

        // 2. Simpan data
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->alamat = $request->alamat;
        $user->no_hp = $request->no_hp;
        
        $user->save();

        // 3. Kembali dengan pesan sukses
        return redirect()->route('profil')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}