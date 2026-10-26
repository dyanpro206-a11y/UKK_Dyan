@extends('layouts.app')

@section('title', 'Transaksi')
@section('title_page', 'Transaksi')
@section('subtitle', 'Riwayat transaksi peminjaman dan pengembalian buku')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-right-left me-2 text-primary"></i> Daftar Transaksi</span>
            <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-file-lines me-1"></i> Lihat Laporan
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('transaksi.index') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="jenis" class="form-select">
                        <option value="">-- Semua Jenis --</option>
                        <option value="Peminjaman" {{ $jenis == 'Peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                        <option value="Pengembalian" {{ $jenis == 'Pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="cari" class="form-control" placeholder="Cari judul buku atau nama peminjam..." value="{{ $search }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Jenis Transaksi</th>
                            <th>Buku</th>
                            <th>Anggota</th>
                            <th>Tgl. Transaksi</th>
                            <th>Keterangan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $item)
                            <tr>
                                <td>{{ $transaksi->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">TRX-{{ str_pad($item->transaksi_id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if ($item->jenis_transaksi === 'Peminjaman')
                                        <span class="badge badge-status bg-soft-info">Peminjaman</span>
                                    @else
                                        <span class="badge badge-status bg-soft-success">Pengembalian</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->peminjaman?->buku?->judul }}</div>
                                    <small class="text-muted">{{ $item->peminjaman?->buku?->kategori?->nama_kategori }}</small>
                                </td>
                                <td>{{ $item->user?->nama_lengkap }}</td>
                                <td>{{ $item->tgl_transaksi?->format('d M Y') }}</td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('transaksi.edit', $item) }}" class="btn btn-outline-primary btn-sm" title="Edit transaksi">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fas fa-right-left"></i>
                                        <div>Tidak ada data transaksi.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>
@endsection
