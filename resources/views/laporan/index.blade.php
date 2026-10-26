@extends('layouts.app')

@section('title', 'Laporan Peminjaman')
@section('title_page', 'Laporan Peminjaman')
@section('subtitle', 'Rekap data peminjaman perpustakaan')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-lines me-2 text-primary"></i> Filter Laporan</span>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Jenis Laporan</label>
                    <select name="jenis" class="form-select">
                        <option value="semua" {{ $jenis == 'semua' ? 'selected' : '' }}>Semua Peminjaman</option>
                        <option value="dipinjam" {{ $jenis == 'dipinjam' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="dikembalikan" {{ $jenis == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tgl_awal" class="form-control" value="{{ $tglAwal }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tgl_akhir" class="form-control" value="{{ $tglAkhir }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Tampilkan</button>
                    <a href="{{ route('laporan.cetak') }}?{{ http_build_query(request()->query()) }}" target="_blank" class="btn btn-danger">
                        <i class="fas fa-print me-1"></i> Cetak Laporan
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="card-title"><i class="fas fa-table-list me-2 text-primary"></i> Hasil Laporan</span>
            <span class="badge bg-soft-primary">{{ $peminjaman->count() }} Data</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
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
                    @forelse ($peminjaman as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-file-lines"></i>
                                    <div>Tidak ada data laporan dengan filter tersebut.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection