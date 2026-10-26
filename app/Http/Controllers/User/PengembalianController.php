<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    /**
     * Daftar buku milik user yang sedang dipinjam, siap dikembalikan.
     */
    public function index(Request $request)
    {
        $search = $request->query('cari');

        $peminjaman = Auth::user()->peminjaman()
            ->with('buku.kategori')
            ->where('status_peminjaman', 'Dipinjam')
            ->when($search, function ($query, $search) {
                $query->whereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Riwayat pengembalian milik user (untuk kartu kedua di halaman).
        $riwayat = Auth::user()->peminjaman()
            ->with('buku.kategori')
            ->where('status_peminjaman', 'Dikembalikan')
            ->latest('tgl_pengembalian')
            ->paginate(5, ['*'], 'hal_riwayat')
            ->withQueryString();

        return view('user.pengembalian.index', compact('peminjaman', 'riwayat', 'search'));
    }

    /**
     * Anggota mengembalikan buku miliknya sendiri.
     */
    public function kembalikan(Peminjaman $peminjaman)
    {
        // Pastikan peminjaman ini milik user yang sedang login.
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Anda tidak berhak mengembalikan peminjaman ini.');
        }

        if ($peminjaman->status_peminjaman !== 'Dipinjam') {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        $peminjaman->update([
            'status_peminjaman' => 'Dikembalikan',
            'tgl_pengembalian' => now()->toDateString(),
        ]);

        // Catat transaksi pengembalian otomatis.
        Transaksi::create([
            'peminjaman_id' => $peminjaman->peminjaman_id,
            'user_id' => $peminjaman->user_id,
            'jenis_transaksi' => 'Pengembalian',
            'tgl_transaksi' => now()->toDateString(),
            'keterangan' => 'Pengembalian buku "' . $peminjaman->buku->judul . '" oleh anggota.',
        ]);

        return redirect()->route('user.pengembalian.index')
            ->with('success', 'Buku "' . $peminjaman->buku->judul . '" berhasil dikembalikan. Terima kasih!');
    }

    /**
     * Tampilkan form edit tanggal pengembalian milik anggota.
     */
    public function edit(Peminjaman $peminjaman)
    {
        // Pastikan peminjaman ini milik user yang sedang login.
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Anda tidak berhak mengubah peminjaman ini.');
        }

        if ($peminjaman->status_peminjaman !== 'Dikembalikan') {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Hanya buku yang sudah dikembalikan yang dapat diedit.');
        }

        return view('user.pengembalian.edit', compact('peminjaman'));
    }

    /**
     * Perbarui tanggal pengembalian milik anggota sendiri.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        // Pastikan peminjaman ini milik user yang sedang login.
        if ($peminjaman->user_id !== Auth::id()) {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Anda tidak berhak mengubah peminjaman ini.');
        }

        if ($peminjaman->status_peminjaman !== 'Dikembalikan') {
            return redirect()->route('user.pengembalian.index')
                ->with('error', 'Hanya buku yang sudah dikembalikan yang dapat diedit.');
        }

        $data = $request->validate([
            'tgl_pengembalian' => [
                'required',
                'date',
                'after_or_equal:' . $peminjaman->tgl_peminjaman?->format('Y-m-d'),
            ],
        ]);

        $peminjaman->update($data);

        // Sinkronkan tanggal pada transaksi pengembalian yang tercatat otomatis.
        Transaksi::where('peminjaman_id', $peminjaman->peminjaman_id)
            ->where('jenis_transaksi', 'Pengembalian')
            ->update(['tgl_transaksi' => $data['tgl_pengembalian']]);

        return redirect()->route('user.pengembalian.index')
            ->with('success', 'Tanggal pengembalian berhasil diperbarui.');
    }
}
