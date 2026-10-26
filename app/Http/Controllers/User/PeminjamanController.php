<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Daftar peminjaman milik user yang sedang login.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $peminjaman = Auth::user()->peminjaman()
            ->with('buku.kategori')
            ->when($status, function ($query, $status) {
                $query->where('status_peminjaman', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('user.peminjaman.index', compact('peminjaman', 'status'));
    }

    /**
     * Form peminjaman buku (hanya buku yang tersedia).
     */
    public function create(Request $request)
    {
        $bukuDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->pluck('buku_id');

        $buku = Buku::with('kategori')
            ->whereNotIn('buku_id', $bukuDipinjam)
            ->orderBy('judul')
            ->get();

        // Buku terpilih (jika datang dari halaman detail/katalog).
        $bukuTerpilih = $request->query('buku');

        return view('user.peminjaman.create', compact('buku', 'bukuTerpilih'));
    }

    /**
     * Simpan peminjaman baru atas nama user yang sedang login.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'buku_id' => 'required|exists:buku,buku_id',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'nullable|date|after_or_equal:tgl_peminjaman',
        ]);

        // Peminjam otomatis = user yang sedang login.
        $data['user_id'] = Auth::id();
        $data['status_peminjaman'] = 'Dipinjam';

        // Pastikan buku tidak sedang dipinjam orang lain.
        $sedangDipinjam = Peminjaman::where('buku_id', $data['buku_id'])
            ->where('status_peminjaman', 'Dipinjam')
            ->exists();

        if ($sedangDipinjam) {
            return back()->withErrors(['buku_id' => 'Buku ini sedang dipinjam oleh anggota lain.'])->withInput();
        }

        // Satu user tidak boleh meminjam buku yang sama dua kali bersamaan.
        $sudahPinjam = Peminjaman::where('buku_id', $data['buku_id'])
            ->where('user_id', Auth::id())
            ->where('status_peminjaman', 'Dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->withErrors(['buku_id' => 'Anda sedang meminjam buku ini.'])->withInput();
        }

        $peminjaman = Peminjaman::create($data);

        // Catat transaksi peminjaman otomatis.
        Transaksi::create([
            'peminjaman_id' => $peminjaman->peminjaman_id,
            'user_id' => $peminjaman->user_id,
            'jenis_transaksi' => 'Peminjaman',
            'tgl_transaksi' => $peminjaman->tgl_peminjaman,
            'keterangan' => 'Peminjaman buku "' . $peminjaman->buku->judul . '" oleh anggota.',
        ]);

        return redirect()->route('user.peminjaman.index')->with('success', 'Peminjaman berhasil dicatat. Silakan kembalikan tepat waktu!');
    }

    /**
     * Form edit peminjaman yang masih berstatus Dipinjam.
     */
    public function edit(Peminjaman $peminjaman)
    {
        // Pastikan peminjaman ini milik user yang sedang login.
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Anda tidak berhak mengubah peminjaman ini.');
        }

        // Hanya peminjaman yang masih berjalan yang dapat diedit.
        if ($peminjaman->status_peminjaman !== 'Dipinjam') {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Hanya buku yang sedang dipinjam yang dapat diedit.');
        }

        // Daftar buku yang tersedia + buku yang sedang dipinjam ini sendiri.
        $bukuDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')
            ->where('peminjaman_id', '!=', $peminjaman->peminjaman_id)
            ->pluck('buku_id');

        $buku = Buku::with('kategori')
            ->whereNotIn('buku_id', $bukuDipinjam)
            ->orderBy('judul')
            ->get();

        return view('user.peminjaman.edit', compact('peminjaman', 'buku'));
    }

    /**
     * Perbarui peminjaman yang masih berstatus Dipinjam.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        // Pastikan peminjaman ini milik user yang sedang login.
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Anda tidak berhak mengubah peminjaman ini.');
        }

        if ($peminjaman->status_peminjaman !== 'Dipinjam') {
            return redirect()->route('user.peminjaman.index')
                ->with('error', 'Hanya buku yang sedang dipinjam yang dapat diedit.');
        }

        $data = $request->validate([
            'buku_id' => 'required|exists:buku,buku_id',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'nullable|date|after_or_equal:tgl_peminjaman',
        ]);

        // Buku tidak boleh sedang dipinjam oleh peminjaman lain.
        $sedangDipinjam = Peminjaman::where('buku_id', $data['buku_id'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('peminjaman_id', '!=', $peminjaman->peminjaman_id)
            ->exists();

        if ($sedangDipinjam) {
            return back()->withErrors(['buku_id' => 'Buku ini sedang dipinjam oleh anggota lain.'])->withInput();
        }

        $judulLama = $peminjaman->buku->judul;
        $peminjaman->update($data);
        $peminjaman->refresh();

        // Sinkronkan transaksi peminjaman yang tercatat otomatis.
        Transaksi::where('peminjaman_id', $peminjaman->peminjaman_id)
            ->where('jenis_transaksi', 'Peminjaman')
            ->update([
                'tgl_transaksi' => $peminjaman->tgl_peminjaman,
                'keterangan' => 'Peminjaman buku "' . $peminjaman->buku->judul . '" oleh anggota.',
            ]);

        $pesan = $peminjaman->buku->judul !== $judulLama
            ? 'Peminjaman berhasil diperbarui (buku diganti ke "' . $peminjaman->buku->judul . '").'
            : 'Peminjaman berhasil diperbarui.';

        return redirect()->route('user.peminjaman.index')->with('success', $pesan);
    }
}
