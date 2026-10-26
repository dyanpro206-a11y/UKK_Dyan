<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman pemilihan login (admin atau user).
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Tampilkan halaman login khusus admin.
     */
    public function showAdminLoginForm()
    {
        return view('auth.login-admin');
    }

    /**
     * Proses login admin.
     */
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Hanya akun dengan role admin yang boleh masuk panel ini.
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun ini tidak memiliki akses admin.'])->withInput();
            }

            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Selamat datang, ' . Auth::user()->nama_lengkap . '!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Tampilkan halaman login khusus user.
     */
    public function showUserLoginForm()
    {
        return view('auth.login-user');
    }

    /**
     * Proses login user biasa.
     */
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Hanya akun dengan role user yang boleh masuk melalui pintu ini.
            if (Auth::user()->role !== 'user') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun ini tidak memiliki akses user.'])->withInput();
            }

            $request->session()->regenerate();

            return redirect()->route('user.dashboard')->with('success', 'Selamat datang, ' . Auth::user()->nama_lengkap . '!');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}