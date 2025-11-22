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

// IMPORT PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. VALIDASI
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,reseller,pelanggan',
            'no_hp' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // 2. BUAT TOKEN & SIMPAN USER
        // Kita buat token random untuk verifikasi
        $token_verifikasi = Str::random(64);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'email_verified_at' => null, // Set null karena belum verifikasi
            'remember_token' => $token_verifikasi, // Simpan token di sini sementara
        ]);

        // 3. KIRIM EMAIL DENGAN PHPMAILER
        $mail = new PHPMailer(true);

        try {
            // --- Konfigurasi Server SMTP ---
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Nyalakan ini jika ingin melihat error log detail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Ganti dengan host SMTP kamu
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mochammadalthofsr@gmail.com'; // MASUKKAN EMAIL KAMU DISINI
            $mail->Password   = 'ggyg nugu eyrj tfzr'; // MASUKKAN APP PASSWORD DISINI
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // --- Pengirim & Penerima ---
            $mail->setFrom('noreply@dishine.com', 'Dishine Admin');
            $mail->addAddress($request->email, $request->nama);

            // --- Konten Email ---
            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Akun Dishine';
            
            // Link verifikasi mengarah ke route /verify-email/{token}
            $link = url('/verify-email/' . $token_verifikasi);

            $mail->Body    = "
                <h3>Halo, {$request->nama}!</h3>
                <p>Terima kasih telah mendaftar. Klik tombol di bawah untuk verifikasi akun Anda:</p>
                <a href='{$link}' style='background-color: #AE8B56; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display:inline-block;'>Verifikasi Akun</a>
                <br><br>
                <small>Atau copy link ini: {$link}</small>
            ";
            
            $mail->AltBody = 'Verifikasi akun anda di link berikut: ' . $link;

            $mail->send();
            
            // Redirect sukses dengan pesan cek email
            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan cek inbox email Anda untuk verifikasi.');

        } catch (Exception $e) {
            // Jika email gagal, user tetap terbuat tapi beri notifikasi error
            return redirect('/login')->with('success', 'Akun dibuat, tapi gagal mengirim email verifikasi. Error: ' . $mail->ErrorInfo);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Opsional: Cek apakah sudah verifikasi email
            if ($user->email_verified_at == null) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda belum diverifikasi. Cek email Anda.']);
            }

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

    // --- FITUR LUPA PASSWORD (DEFAULT LARAVEL) ---
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Cek user manual agar pesan error lebih jelas
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Email ini tidak terdaftar di sistem kami.']);
        }

        // Kirim link (Ini menggunakan setting .env Laravel, bukan PHPMailer manual di atas)
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status)]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

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