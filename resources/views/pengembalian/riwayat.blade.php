@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')
@section('title_page', 'Riwayat Pengembalian')
@section('subtitle', 'Daftar buku yang sudah dikembalikan')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-clock-rotate-left me-2 text-primary"></i> Riwayat Pengembalian</span>
            <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-rotate-left me-1"></i> Buku Sedang Dipinjam
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('pengembalian.riwayat') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari judul buku atau nama peminjam..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('pengembalian.riwayat') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl. Peminjaman</th>
                            <th>Tgl. Pengembalian</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $item)
                            <tr>
                                <td>{{ $peminjaman->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->buku?->judul }}</div>
                                    <small class="text-muted">{{ $item->buku?->penulis }}</small>
                                </td>
                                <td>{{ $item->user?->nama_lengkap }}</td>
                                <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                                <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-status bg-soft-success">
                                        <i class="fas fa-circle-check me-1"></i> Dikembalikan
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pengembalian.edit', $item->peminjaman_id) }}" class="btn btn-sm btn-outline-primary" title="Edit pengembalian">
                                        <i class="fas fa-pen me-1"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <div>Belum ada riwayat pengembalian.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $peminjaman->links() }}
            </div>
        </div>
    </div>
@endsection