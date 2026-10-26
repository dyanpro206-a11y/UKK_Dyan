<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar user (anggota perpustakaan).
     */
    public function index(Request $request)
    {
        $search = $request->query('cari');

        $users = User::when($search, function ($query, $search) {
                $query->where('username', 'like', "%{$search}%")
                      ->orWhere('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('user.index', compact('users', 'search'));
    }

    /**
     * Form tambah user baru.


     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Simpan user baru ke database.



     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|min:6',
            'email' => 'required|email|max:255|unique:users,email',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'role' => 'required|in:admin,user',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user.



     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Perbarui data user di database.



     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:6',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user dari database.



     */
    public function destroy(User $user)
    {
        // Cegah penghapusan akun yang sedang login.,

        if (auth()->id() === $user->user_id) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}