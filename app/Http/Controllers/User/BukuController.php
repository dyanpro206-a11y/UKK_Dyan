<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    /**
     * Katalog buku untuk user (hanya melihat, dengan pencarian & filter).
     */
    public function index(Request $request)
    {
        $search = $request->query('cari');
        $kategoriId = $request->query('kategori_id');

        $bukuDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->pluck('buku_id');

        $buku = Buku::with('kategori')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->orderBy('judul')
            ->paginate(12)
            ->withQueryString();

        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('user.buku.index', compact('buku', 'kategori', 'search', 'kategoriId', 'bukuDipinjam'));
    }

    /**
     * Detail buku untuk user.
     */
    public function show(Buku $buku)
    {
        $buku->load('kategori');

        // Apakah buku ini sedang dipinjam siapa pun / oleh user ini?
        $sedangDipinjam = Peminjaman::where('buku_id', $buku->buku_id)
            ->where('status_peminjaman', 'Dipinjam')
            ->exists();

        $dipinjamOlehSaya = Peminjaman::where('buku_id', $buku->buku_id)
            ->where('user_id', Auth::id())
            ->where('status_peminjaman', 'Dipinjam')
            ->exists();

        return view('user.buku.show', compact('buku', 'sedangDipinjam', 'dipinjamOlehSaya'));
    }
}
