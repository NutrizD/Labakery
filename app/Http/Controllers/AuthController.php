<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Gagal login: kembalikan pesan & isi email
        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->only('email'));
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Form registrasi (khusus yang berhak).
     */
    public function showRegistrationForm()
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            return redirect()->route('login')
                ->with('error', 'Hanya Super Admin yang dapat mengakses halaman registrasi.');
        }

        return view('auth.register');
    }

    /**
     * Proses registrasi (khusus yang berhak).
     */
    public function register(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            return redirect()->route('login')
                ->with('error', 'Hanya Super Admin yang dapat melakukan registrasi.');
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', Rule::in(['admin', 'kasir', 'super_admin'])],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat dan ditambahkan ke sistem.');
    }

    /**
     * Form lupa password.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/'],
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem.'])
                    ->withInput($request->only('email'));
            }

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with(['status' => 'Link reset password telah dikirim ke email Anda.']);
            }

            return back()->withErrors(['email' => 'Gagal mengirim link reset password. Silakan coba lagi.'])
                ->withInput($request->only('email'));
        } catch (\Exception $e) {
            Log::error('Reset Password Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Terjadi kesalahan saat mengirim email: ' . $e->getMessage()])
                ->withInput($request->only('email'));
        }
    }

    /**
     * Form reset password.
     */
    public function showResetPasswordForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Proses reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]])->withInput($request->only('email'));
    }
}
