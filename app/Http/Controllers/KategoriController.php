<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Daftar kategori buku.
     */
    public function index()
    {
        $kategori = KategoriBuku::withCount('buku')->latest()->get();

        return view('kategori.index', compact('kategori'));
    }

    /**
     * Form tambah kategori baru.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Simpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_buku,nama_kategori',
        ]);

        KategoriBuku::create($data);

        return redirect()->route('kategori-buku.index')->with('success', 'Kategori buku berhasil ditambahkan.');
    }

    /**
     * Form edit kategori.
     */
    public function edit(KategoriBuku $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Perbarui data kategori di database.
     */
    public function update(Request $request, KategoriBuku $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_buku,nama_kategori,' . $kategori->kategori_id . ',kategori_id',
        ]);

        $kategori->update($data);

        return redirect()->route('kategori-buku.index')->with('success', 'Kategori buku berhasil diperbarui.');
    }

    /**
     * Hapus kategori dari database.
     */
    public function destroy(KategoriBuku $kategori)
    {
        // Cegah penghapusan kategori yang masih memiliki buku.

        if ($kategori->buku()->exists()) {
            return redirect()->route('kategori-buku.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki buku.');
        }

        $kategori->delete();

        return redirect()->route('kategori-buku.index')->with('success', 'Kategori buku berhasil dihapus.');
    }
}