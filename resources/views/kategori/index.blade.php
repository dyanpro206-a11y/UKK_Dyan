@extends('layouts.app')

@section('title', 'Kategori Buku')
@section('title_page', 'Kategori Buku')
@section('subtitle', 'Kelola kategori koleksi buku')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-tags me-2 text-primary"></i> Daftar Kategori</span>
            <a href="{{ route('kategori-buku.create') }}" class="btn btn-primary-theme btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Kategori
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Buku</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $item->nama_kategori }}</td>
                            <td>
                                <span class="badge bg-soft-primary">{{ $item->buku_count }} Buku</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('kategori-buku.edit', $item->kategori_id) }}" class="btn btn-sm btn-icon-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-icon-sm btn-outline-danger btn-hapus"
                                    data-url="{{ route('kategori-buku.destroy', $item->kategori_id) }}"
                                    data-nama="{{ $item->nama_kategori }}" title="Hapus">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="fas fa-tags"></i>
                                    <div>Belum ada kategori buku.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection