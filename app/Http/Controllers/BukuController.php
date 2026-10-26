<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Daftar buku dengan pencarian dan filter kategori.

     */
    public function index(Request $request)
    {
        $search = $request->query('cari');
        $kategoriId = $request->query('kategori_id');

        $buku = Buku::with('kategori')
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('penerbit', 'like', "%{$search}%");
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('buku.index', compact('buku', 'kategori', 'search', 'kategoriId'));
    }

    /**
     * Form tambah buku baru.

     */
    public function create()
    {
        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('buku.create', compact('kategori'));
    }

    /**
     * Simpan buku baru ke database.



     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori_buku,kategori_id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail buku.

     */
    public function show(Buku $buku)
    {
        $buku->load('kategori', 'peminjaman.user');

        return view('buku.show', compact('buku'));
    }

    /**
     * Form edit buku.



     */
    public function edit(Buku $buku)
    {
        $kategori = KategoriBuku::orderBy('nama_kategori')->get();

        return view('buku.edit', compact('buku', 'kategori'));
    }

    /**
     * Perbarui data buku di database.



     */
    public function update(Request $request, Buku $buku)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategori_buku,kategori_id',
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            // Hapus cover lama bila ada.
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }

            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku dari database.,

     */
    public function destroy(Buku $buku)
    {
        // Peminjaman terkait ikut terhapus kareena relasi cascade.,

        $buku->delete();

        // Hapus file cover bila ada.
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}