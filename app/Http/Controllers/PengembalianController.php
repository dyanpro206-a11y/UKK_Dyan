<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    /**
     * Tampilkan buku yang sedang dipinjam untuk dikembalikan.,

     */
    public function index(Request $request)
    {
        $search = $request->query('cari');

        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->where('status_peminjaman', 'Dipinjam')
            ->when($search, function ($query, $search) {
                $query->whereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pengembalian.index', compact('peminjaman', 'search'));
    }

    /**
     * Proses pengembalian buku: ubah status dan simpan tanggal pengembalian.,

     */
    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'Dipinjam') {
            return redirect()->route('pengembalian.index')->with('error', 'Peminjaman ini sudah dikembalikan.');
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
            'keterangan' => 'Pengembalian buku "' . $peminjaman->buku->judul . '".',
        ]);

        return redirect()->route('pengembalian.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Tampilkan form edit data pengembalian.
     */
    public function edit(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_peminjaman !== 'Dikembalikan') {
            return redirect()->route('pengembalian.index')
                ->with('error', 'Hanya peminjaman berstatus Dikembalikan yang dapat diedit.');
        }

        return view('pengembalian.edit', compact('peminjaman'));
    }

    /**
     * Perbarui data pengembalian (tanggal & status).
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $data = $request->validate([
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'nullable|date|after_or_equal:tgl_peminjaman',
            'status_peminjaman' => 'required|in:Dipinjam,Dikembalikan',
        ]);

        if ($data['status_peminjaman'] === 'Dikembalikan') {
            // Jika tanggal pengembalian dikosongkan, pertahankan nilai lama atau pakai hari ini.
            if (empty($data['tgl_pengembalian'])) {
                $data['tgl_pengembalian'] = $peminjaman->tgl_pengembalian ?? now()->toDateString();
            }
        } else {
            // Jika status kembali Dipinjam, kosongkan tanggal pengembalian.
            $data['tgl_pengembalian'] = null;
        }

        $peminjaman->update($data);

        // Sinkronkan transaksi pengembalian yang tercatat otomatis.
        if ($data['status_peminjaman'] === 'Dikembalikan') {
            Transaksi::where('peminjaman_id', $peminjaman->peminjaman_id)
                ->where('jenis_transaksi', 'Pengembalian')
                ->update([
                    'tgl_transaksi' => $data['tgl_pengembalian'],
                    'keterangan' => 'Pengembalian buku "' . $peminjaman->buku->judul . '".',
                ]);
        } else {
            // Pengembalian dibatalkan: hapus catatan transaksi pengembalian.
            Transaksi::where('peminjaman_id', $peminjaman->peminjaman_id)
                ->where('jenis_transaksi', 'Pengembalian')
                ->delete();
        }

        return redirect()->route('pengembalian.riwayat')
            ->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    /**
     * Riwayat pengembalian (buku yang sudah dikembalikan.,

     */
    public function riwayat(Request $request)
    {
        $search = $request->query('cari');

        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->where('status_peminjaman', 'Dikembalikan')
            ->when($search, function ($query, $search) {
                $query->whereHas('buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pengembalian.riwayat', compact('peminjaman', 'search'));
    }
}