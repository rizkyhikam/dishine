<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // WAJIB ADA UNTUK RESET PASSWORD MANUAL
use Illuminate\Support\Str;

// IMPORT PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class AuthController extends Controller
{
    // --- FUNGSI BANTUAN UNTUK KIRIM EMAIL (AGAR TIDAK REPETITIF) ---
    private function sendEmail($toEmail, $toName, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mochammadalthofsr@gmail.com'; // EMAIL ASLI KAMU
            $mail->Password   = 'ggyg nugu eyrj tfzr';         // APP PASSWORD ASLI KAMU
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('noreply@dishine.com', 'Dishine Admin');
            $mail->addAddress($toEmail, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false; // Gagal kirim
        }
    }

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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. BUAT TOKEN & SIMPAN USER
        $token_verifikasi = Str::random(64);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
            'email_verified_at' => null,
            'remember_token' => $token_verifikasi,
        ]);

        // 3. KIRIM EMAIL REGISTER
        $link = url('/verify-email/' . $token_verifikasi);
        $body = "
            <h3>Halo, {$request->nama}!</h3>
            <p>Terima kasih telah mendaftar. Klik tombol di bawah untuk verifikasi akun Anda:</p>
            <a href='{$link}' style='background-color: #AE8B56; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display:inline-block;'>Verifikasi Akun</a>
            <br><br>
            <small>Atau copy link ini: {$link}</small>
        ";

        $emailSent = $this->sendEmail($request->email, $request->nama, 'Verifikasi Akun Dishine', $body);

        if ($emailSent) {
            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan cek inbox email Anda untuk verifikasi.');
        } else {
            return redirect('/login')->with('success', 'Akun dibuat, tapi gagal mengirim email verifikasi.');
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
            
            // Cek Verifikasi Email
            if ($user->email_verified_at == null) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda belum diverifikasi. Cek email Anda.']);
            }

            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard'); 
            }
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- FITUR LUPA PASSWORD MANUAL (DENGAN PHPMAILER) ---
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // 1. Buat Token Manual
        $token = Str::random(64);

        // 2. Simpan ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // 3. Kirim Email Manual pakai PHPMailer
        $link = route('password.reset', ['token' => $token, 'email' => $request->email]); // Perhatikan parameter route ini
        $body = "
            <h3>Permintaan Reset Password</h3>
            <p>Seseorang meminta reset password untuk akun Dishine Anda.</p>
            <p>Klik tombol di bawah untuk membuat password baru:</p>
            <a href='{$link}' style='background-color: #AE8B56; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display:inline-block;'>Reset Password</a>
            <br><br>
            <small>Abaikan jika ini bukan Anda.</small>
        ";

        // Kita kirim email (Nama penerima kita set 'User' karena kita cuma butuh email)
        $emailSent = $this->sendEmail($request->email, 'User', 'Reset Password Dishine', $body);

        if ($emailSent) {
            return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
        } else {
            return back()->withErrors(['email' => 'Gagal mengirim email reset. Silakan coba lagi.']);
        }
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token, 
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // 1. Cek Token di Database
        $resetRecord = DB::table('password_reset_tokens')->where([
            ['email', $request->email],
            ['token', $request->token]
        ])->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        // 2. Update Password User
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // 3. Hapus Token Bekas
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');
    }
}