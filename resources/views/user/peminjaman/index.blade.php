@extends('layouts.user')

@section('title', 'Peminjaman Saya')
@section('title_page', 'Peminjaman Saya')
@section('subtitle', 'Riwayat peminjaman buku Anda')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-hand-holding me-2 text-primary"></i> Daftar Peminjaman Saya</span>
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary-theme btn-sm">
                <i class="fas fa-plus me-1"></i> Pinjam Buku
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('user.peminjaman.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Dipinjam" {{ $status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ $status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-8 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Kategori</th>
                            <th>Tgl. Pinjam</th>
                            <th>Tgl. Kembali</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $item)
                            <tr>
                                <td>{{ $peminjaman->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $item->buku?->judul }}</td>
                                <td>{{ $item->buku?->kategori?->nama_kategori }}</td>
                                <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                                <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @if ($item->status_peminjaman === 'Dipinjam')
                                        <span class="badge badge-status bg-soft-warning">Dipinjam</span>
                                    @else
                                        <span class="badge badge-status bg-soft-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($item->status_peminjaman === 'Dipinjam')
                                        <a href="{{ route('user.peminjaman.edit', $item->peminjaman_id) }}" class="btn btn-sm btn-outline-primary" title="Edit peminjaman">
                                            <i class="fas fa-pen me-1"></i> Edit
                                        </a>
                                    @else
                                        <span class="text-muted">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-hand-holding"></i>
                                        <div>Anda belum memiliki riwayat peminjaman.</div>
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
