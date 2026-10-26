<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Tampilkan dan ubah profil user yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        $totalPeminjaman = $user->peminjaman()->count();
        $sedangDipinjam = $user->peminjaman()->where('status_peminjaman', 'Dipinjam')->count();

        return view('user.profil.index', compact('user', 'totalPeminjaman', 'sedangDipinjam'));
    }

    /**
     * Perbarui profil user yang sedang login.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'alamat' => 'nullable|string',
            'password' => 'nullable|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Username dan role tidak boleh diubah oleh user sendiri.
        $user->update($data);

        return redirect()->route('user.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
