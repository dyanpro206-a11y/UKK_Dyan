<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Halaman laporan peminjaman dengan filter.,

     */
    public function index(Request $request)
    {
        [$peminjaman, $jenis, $tglAwal, $tglAkhir] = $this->filter($request);

        return view('laporan.index', compact('peminjaman', 'jenis', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Cetak laporan (versi cetak/,print-friendly/.,

     */
    public function cetak(Request $request)
    {
        [$peminjaman, $jenis, $tglAwal, $tglAkhir] = $this->filter($request);

        return view('laporan.cetak', compact('peminjaman', 'jenis', 'tglAwal', 'tglAkhir'));
    }

    /**
     * Query peminjaman berdasarkan filter jenis dan rentang tanggal.,

     */
    private function filter(Request $request)
    {
        $jenis = $request->query('jenis', 'semua');
        $tglAwal = $request->query('tgl_awal');
        $tglAkhir = $request->query('tgl_akhir');

        $query = Peminjaman::with(['buku.kategori', 'user'])->latest();

        if ($jenis === 'dipinjam') {
            $query->where('status_peminjaman', 'Dipinjam');
        } elseif ($jenis === 'dikembalikan') {
            $query->where('status_peminjaman', 'Dikembalikan');
        }

        if ($tglAwal) {
            $query->whereDate('tgl_peminjaman', '>=', $tglAwal);
        }

        if ($tglAkhir) {
            $query->whereDate('tgl_peminjaman', '<=', $tglAkhir);
        }

        $peminjaman = $query->get();

        return [$peminjaman, $jenis, $tglAwal, $tglAkhir];
    }
}