@extends('layouts.app')

@section('title', 'Peminjaman')
@section('title_page', 'Peminjaman')
@section('subtitle', 'Kelola transaksi peminjaman buku')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-hand-holding me-2 text-primary"></i> Daftar Peminjaman</span>
            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary-theme btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Peminjaman
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('peminjaman.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Dipinjam" {{ $status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ $status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl.peminjaman</th>
                            <th>Tgl.pengembalian</th>
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
                                    <small class="text-muted">{{ $item->buku?->kategori?->nama_kategori }}</small>
                                </td>
                                <td>{{ $item->user?->nama_lengkap }}</td>
                                <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                                <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @if ($item->status_peminjaman === 'Dipinjam')
                                        <span class="badge badge-status bg-soft-warning">Dipinjam</span>
                                    @else
                                        <span class="badge badge-status bg-soft-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('peminjaman.edit', $item->peminjaman_id) }}" class="btn btn-sm btn-outline-primary" title="Edit peminjaman">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-hand-holding"></i>
                                        <div>Tidak ada data peminjaman.</div>
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