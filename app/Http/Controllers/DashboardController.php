<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan statistik.
     */
    public function index()
    {
        $jumlahBuku = Buku::count();
        $jumlahKategori = KategoriBuku::count();
        $jumlahUser = User::count();
        $jumlahPeminjaman = Peminjaman::count();
        $jumlahDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->count();
        $jumlahDikembalikan = Peminjaman::where('status_peminjaman', 'Dikembalikan')->count();

        $peminjamanTerbaru = Peminjaman::with(['buku.kategori', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'jumlahBuku',
            'jumlahKategori',
            'jumlahUser',
            'jumlahPeminjaman',
            'jumlahDipinjam',
            'jumlahDikembalikan',
            'peminjamanTerbaru'
        ));
    }
}