<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    /**
     * Daftar semua peminjaman.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $peminjaman = Peminjaman::with(['buku.kategori', 'user'])
            ->when($status, function ($query, $status) {
                $query->where('status_peminjaman', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjaman', 'status'));
    }

    /**
     * Form tambah peminjaman baru.


     */
                            public function create()
    {
        // Hanya tampilkan buku yang sedang tidak dipinjam (tersedia).
        $bukuDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->pluck('buku_id');
        $buku = Buku::with('kategori')
            ->whereNotIn('buku_id', $bukuDipinjam)
            ->orderBy('judul')
            ->get();

        // Daftar anggota (user) yang dapat dipilih admin sebagai peminjam.
        $users = User::orderBy('nama_lengkap')->get();

        return view('peminjaman.create', compact('buku', 'users'));
    }

    /**
     * Simpan peminjaman baru ke database.



     */
    public function store(Request $request)
    {
                        $data = $request->validate([
            'buku_id' => 'required|exists:buku,buku_id',
            'user_id' => 'required|exists:users,user_id',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'nullable|date|after_or_equal:tgl_peminjaman',
            'status_peminjaman' => 'required|in:Dipinjam,Dikembalikan',
        ]);

        // Peminjam dipilih admin lewat dropdown pada form,
        // sehingga admin bisa meminjamkan buku atas nama user lain.
        $peminjam = User::findOrFail($data['user_id']);

        // Pastikan buku tidak sedang dipinjam oleh peminjaman lain.,

        $sedangDipinjam = Peminjaman::where('buku_id', $data['buku_id'])
            ->where('status_peminjaman', 'Dipinjam')
            ->exists();

        if ($sedangDipinjam) {
            return back()->withErrors(['buku_id' => 'Buku ini sedang dipinjam oleh anggota lain.'])->withInput();
        }

        // Jika status langsung Dikembalikan, isi tanggal pengembalian.,

        if ($data['status_peminjaman'] === 'Dikembalikan' && empty($data['tgl_pengembalian'])) {

            $data['tgl_pengembalian'] = date('Y-m-d');
        }

        $peminjaman = Peminjaman::create($data);

        // Catat transaksi peminjaman otomatis.
        Transaksi::create([
            'peminjaman_id' => $peminjaman->peminjaman_id,
            'user_id' => $peminjaman->user_id,
            'jenis_transaksi' => 'Peminjaman',
            'tgl_transaksi' => $peminjaman->tgl_peminjaman,
            'keterangan' => 'Peminjaman buku "' . $peminjaman->buku->judul . '" oleh ' . $peminjam->nama_lengkap . '.',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    /**
     * Form edit peminjaman.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $peminjaman->load(['buku.kategori', 'user']);

        // Buku yang bisa dipilih: buku tersedia + buku milik peminjaman ini sendiri.
        $bukuDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')
            ->where('peminjaman_id', '!=', $peminjaman->peminjaman_id)
            ->pluck('buku_id');

        $buku = Buku::with('kategori')
            ->whereNotIn('buku_id', $bukuDipinjam)
            ->orderBy('judul')
            ->get();

        // Daftar anggota (user) yang dapat dipilih admin sebagai peminjam.
        $users = User::orderBy('nama_lengkap')->get();

        return view('peminjaman.edit', compact('peminjaman', 'buku', 'users'));
    }

    /**
     * Simpan perubahan data peminjaman.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $data = $request->validate([
            'buku_id' => 'required|exists:buku,buku_id',
            'user_id' => 'required|exists:users,user_id',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'nullable|date|after_or_equal:tgl_peminjaman',
            'status_peminjaman' => 'required|in:Dipinjam,Dikembalikan',
        ]);

        // Pastikan buku tidak sedang dipinjam oleh peminjaman lain
        // (peminjaman ini sendiri dikecualikan dari pengecekan).
        $sedangDipinjam = Peminjaman::where('buku_id', $data['buku_id'])
            ->where('status_peminjaman', 'Dipinjam')
            ->where('peminjaman_id', '!=', $peminjaman->peminjaman_id)
            ->exists();

        if ($sedangDipinjam) {
            return back()->withErrors(['buku_id' => 'Buku ini sedang dipinjam oleh anggota lain.'])->withInput();
        }

        // Jika status Dikembalikan, isi tanggal pengembalian bila kosong.
        // Jika status kembali Dipinjam, kosongkan tanggal pengembalian.
        if ($data['status_peminjaman'] === 'Dikembalikan' && empty($data['tgl_pengembalian'])) {
            $data['tgl_pengembalian'] = date('Y-m-d');
        } elseif ($data['status_peminjaman'] === 'Dipinjam') {
            $data['tgl_pengembalian'] = null;
        }

        $peminjaman->update($data);

        // Sinkronkan transaksi peminjaman terkait (bila ada) agar datanya tetap konsisten.
        $peminjaman->transaksi()
            ->where('jenis_transaksi', 'Peminjaman')
            ->update([
                'user_id' => $data['user_id'],
                'tgl_transaksi' => $data['tgl_peminjaman'],
            ]);

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }
}