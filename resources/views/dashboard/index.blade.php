@extends('layouts.app')

@section('title', 'Dashboard')
@section('title_page', 'Dashboard')
@section('subtitle', 'Ringkasan data perpustakaan digital')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-primary"><i class="fas fa-book"></i></div>
                    <div>
                        <div class="stat-value">{{ $jumlahBuku }}</div>
                        <div class="stat-label">Total Buku</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-warning"><i class="fas fa-tags"></i></div>
                    <div>
                        <div class="stat-value">{{ $jumlahKategori }}</div>
                        <div class="stat-label">Kategori Buku</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-info"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-value">{{ $jumlahUser }}</div>
                        <div class="stat-label">Total User</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-secondary"><i class="fas fa-arrows-rotate"></i></div>
                    <div>
                        <div class="stat-value">{{ $jumlahPeminjaman }}</div>
                        <div class="stat-label">Total Peminjaman</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-warning"><i class="fas fa-book-open-reader"></i></div>
                    <div>
                        <div class="stat-value">{{ $jumlahDipinjam }}</div>
                        <div class="stat-label">Sedang Dipinjam</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 col-xl-2">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-success"><i class="fas fa-book-bookmark"></i></div>
                    <div>
                        <div class="stat-value">{{ $jumlahDikembalikan }}</div>
                        <div class="stat-label">Sudah Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title"><i class="fas fa-clock-rotate-left me-2 text-primary"></i> Peminjaman Terbaru</span>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-primary-theme">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Peminjam</th>
                        <th>Tgl. Peminjaman</th>
                        <th>Tgl. Pengembalian</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjamanTerbaru as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->buku->judul }}</div>
                                <small class="text-muted">{{ $item->buku->penulis }}</small>
                            </td>
                            <td>{{ $item->user->nama_lengkap }}</td>
                            <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                            <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                            <td>
                                @if ($item->status_peminjaman === 'Dipinjam')
                                    <span class="badge badge-status bg-soft-warning"><i class="fas fa-hourglass-half me-1"></i> Dipinjam</span>
                                @else
                                    <span class="badge badge-status bg-soft-success"><i class="fas fa-circle-check me-1"></i> Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <div>Belum ada data peminjaman.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection