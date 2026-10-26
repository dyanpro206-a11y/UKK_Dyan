<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Daftar semua transaksi (peminjaman & pengembalian).
     */
    public function index(Request $request)
    {
        $jenis = $request->query('jenis');
        $search = $request->query('cari');

        $transaksi = Transaksi::with(['peminjaman.buku.kategori', 'user'])
            ->when($jenis, function ($query, $jenis) {
                $query->where('jenis_transaksi', $jenis);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('peminjaman.buku', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%");
                });
            })
            ->latest('tgl_transaksi')
            ->latest('transaksi_id')
            ->paginate(10)
            ->withQueryString();

        return view('transaksi.index', compact('transaksi', 'jenis', 'search'));
    }

    /**
     * Form edit transaksi.
     */
    public function edit(Transaksi $transaksi)
    {
        // Data peminjaman untuk dropdown (dengan info buku dan peminjamnya).
        $peminjaman = Peminjaman::with(['buku', 'user'])
            ->orderByDesc('peminjaman_id')
            ->get();

        // Data anggota untuk dropdown.
        $users = User::orderBy('nama_lengkap')->get();

        return view('transaksi.edit', compact('transaksi', 'peminjaman', 'users'));
    }

    /**
     * Perbarui data transaksi di database.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $data = $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,peminjaman_id',
            'user_id' => 'required|exists:users,user_id',
            'jenis_transaksi' => 'required|in:Peminjaman,Pengembalian',
            'tgl_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $transaksi->update($data);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }
}
