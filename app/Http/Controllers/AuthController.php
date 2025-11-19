<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ... method register biarkan saja seperti sebelumnya ...
    public function register(Request $request)
    {
        // Kode register Anda tetap sama, tidak perlu diubah
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,reseller,pelanggan',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);
        
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // === INI BAGIAN YANG DIUBAH ===
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil data user yang sedang login
            $user = Auth::user();

            // Cek Role
            if ($user->role === 'admin') {
                // Jika admin, arahkan ke dashboard admin
                // Pastikan Anda sudah punya route '/admin/dashboard' atau sesuaikan url-nya
                return redirect()->intended('/admin/dashboard'); 
            }

            // Jika Reseller (Opsional, jika ingin dibedakan juga)
            //if ($user->role === 'reseller') {
                 //return redirect()->intended('/reseller/dashboard');
            //}

            // Jika user biasa (pelanggan), arahkan ke home
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
    // === AKHIR PERUBAHAN ===

    public function logout(Request $request)
    {
        Auth::logout();
        
        // Perbaikan sedikit: Logout biasanya redirect, bukan response JSON 
        // kecuali ini API. Jika web biasa, gunakan redirect.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}