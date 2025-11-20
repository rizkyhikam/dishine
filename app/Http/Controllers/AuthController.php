<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

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
                return redirect()->intended('/admin/dashboard'); 
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * 1. Tampilkan form lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * 2. Kirim link reset password ke email
     */
    public function sendResetLink(Request $request)
{
    // Validasi awal
    $request->validate([
        'email' => 'required|email',
    ]);

    // 1️⃣ Cek apakah email ada di database
    $user = User::where('email', $request->email)->first();

    if (! $user) {
        // Kalau email tidak ditemukan
        return back()->withErrors([
            'email' => 'Email ini tidak terdaftar di sistem kami.'
        ]);
    }

    // 2️⃣ Jika email ada, lanjut kirim link reset password
    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return back()->with(['status' => __($status)]);
    }

    return back()->withErrors(['email' => __($status)]);
}


    /**
     * 3. Tampilkan form reset password (setelah klik link di email)
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * 4. Proses reset password yang sebenarnya
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}