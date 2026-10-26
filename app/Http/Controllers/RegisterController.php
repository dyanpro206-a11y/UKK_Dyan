<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman pendaftaran user
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran user baru
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'email' => 'required|email|max:255|unique:users,email',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        User::create($data);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil. Silakan login dengan akun baru Anda.');
    }
}