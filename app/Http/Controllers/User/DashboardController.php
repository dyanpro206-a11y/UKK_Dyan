<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard khusus user: ringkasan peminjaman miliknya.
     */
    public function index()
    {
        $user = Auth::user();

        $sedangDipinjam = $user->peminjaman()
            ->with('buku.kategori')
            ->where('status_peminjaman', 'Dipinjam')
            ->latest()
            ->get();

        $riwayat = $user->peminjaman()
            ->with('buku.kategori')
            ->where('status_peminjaman', 'Dikembalikan')
            ->latest()
            ->limit(5)
            ->get();

        $totalPeminjaman = $user->peminjaman()->count();
        $sedangDipinjamCount = $sedangDipinjam->count();
        $sudahDikembalikan = $user->peminjaman()->where('status_peminjaman', 'Dikembalikan')->count();

        // Buku yang sedang dipinjam orang lain (tidak tersedia).
        $bukuDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->pluck('buku_id');
        $bukuTersedia = Buku::whereNotIn('buku_id', $bukuDipinjam)->count();
        $totalBuku = Buku::count();

        return view('user.dashboard.index', compact(
            'sedangDipinjam',
            'riwayat',
            'totalPeminjaman',
            'sedangDipinjamCount',
            'sudahDikembalikan',
            'bukuTersedia',
            'totalBuku'
        ));
    }
}
