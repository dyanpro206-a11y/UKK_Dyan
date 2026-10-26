@extends('layouts.app')

@section('title', 'Data Buku')
@section('title_page', 'Data Buku')
@section('subtitle', 'Kelola koleksi buku perpustakaan')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-book me-2 text-primary"></i> Daftar Buku</span>
            <a href="{{ route('buku.create') }}" class="btn btn-primary-theme btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Buku
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari judul, penulis, atau penerbit..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->kategori_id }}" {{ $kategoriId == $kat->kategori_id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th class="text-center">Cover</th>
                            <th>Judul Buku</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($buku as $item)
                            <tr>
                                <td>{{ $buku->firstItem() + $loop->index }}</td>
                                <td class="text-center">
                                    @if ($item->cover)
                                        <img src="{{ asset('storage/' . $item->cover) }}" alt="Cover {{ $item->judul }}" class="rounded" style="width:40px;height:56px;object-fit:cover;">
                                    @else
                                        <span class="badge bg-soft-secondary"><i class="fas fa-image"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $item->judul }}</td>
                                <td><span class="badge bg-soft-primary">{{ $item->kategori?->nama_kategori }}</span></td>
                                <td>{{ $item->penulis }}</td>
                                <td>{{ $item->penerbit }}</td>
                                <td>{{ $item->tahun_terbit }}</td>
                                <td class="text-center">
                                    <a href="{{ route('buku.show', $item->buku_id) }}" class="btn btn-sm btn-icon-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('buku.edit', $item->buku_id) }}" class="btn btn-sm btn-icon-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon-sm btn-outline-danger btn-hapus"
                                        data-url="{{ route('buku.destroy', $item->buku_id) }}"
                                        data-nama="{{ $item->judul }}" title="Hapus">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-book"></i>
                                        <div>Tidak ada data buku yang ditemukan.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $buku->links() }}
            </div>
        </div>
    </div>
@endsection