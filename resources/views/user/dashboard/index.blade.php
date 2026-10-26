@extends('layouts.user')

@section('title', 'Dashboard')
@section('title_page', 'Dashboard Anggota')
@section('subtitle', 'Ringkasan aktivitas pinjam Anda')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-primary"><i class="fas fa-book"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalBuku }}</div>
                        <div class="stat-label">Koleksi Buku</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-info"><i class="fas fa-hand-holding"></i></div>
                    <div>
                        <div class="stat-value">{{ $sedangDipinjamCount }}</div>
                        <div class="stat-label">Sedang Saya Pinjam</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-success"><i class="fas fa-circle-check"></i></div>
                    <div>
                        <div class="stat-value">{{ $sudahDikembalikan }}</div>
                        <div class="stat-label">Sudah Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card p-3 h-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-box bg-soft-warning"><i class="fas fa-clock-rotate-left"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalPeminjaman }}</div>
                        <div class="stat-label">Total Riwayat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title"><i class="fas fa-hand-holding me-2 text-primary"></i> Buku yang Sedang Saya Pinjam</span>
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-sm btn-primary-theme">
                <i class="fas fa-plus me-1"></i> Pinjam Buku
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Tgl. Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sedangDipinjam as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->buku?->judul }}</div>
                                <small class="text-muted">{{ $item->buku?->penulis }}</small>
                            </td>
                            <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                            <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                            <td>
                                <span class="badge badge-status bg-soft-warning"><i class="fas fa-hourglass-half me-1"></i> Dipinjam</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-book-open-reader"></i>
                                    <div>Anda belum meminjam buku. <a href="{{ route('user.buku.index') }}">Lihat katalog</a></div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-clock-rotate-left me-2 text-primary"></i> Riwayat Terakhir</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Buku</th>
                        <th>Tgl. Pinjam</th>
                        <th>Tgl. Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $item->buku?->judul }}</td>
                            <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                            <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                            <td>
                                <span class="badge badge-status bg-soft-success"><i class="fas fa-circle-check me-1"></i> Dikembalikan</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
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
    </div>
@endsection
