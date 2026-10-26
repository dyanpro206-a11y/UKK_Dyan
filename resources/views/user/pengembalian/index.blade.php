@extends('layouts.user')

@section('title', 'Pengembalian Buku')
@section('title_page', 'Pengembalian Buku')
@section('subtitle', 'Kembalikan buku yang sedang Anda pinjam')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-rotate-left me-2 text-primary"></i> Buku yang Sedang Anda Pinjam</span>
            <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary-theme btn-sm">
                <i class="fas fa-plus me-1"></i> Pinjam Buku
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('user.pengembalian.index') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="cari" class="form-control" placeholder="Cari judul buku..." value="{{ $search }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-magnifying-glass me-1"></i> Cari</button>
                    <a href="{{ route('user.pengembalian.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
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
                                <td><span class="badge badge-status bg-soft-warning">Dipinjam</span></td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('user.pengembalian.kembalikan', $item) }}"
                                          onsubmit="return confirm('Kembalikan buku \'{{ $item->buku?->judul }}\' sekarang?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-rotate-left me-1"></i> Kembalikan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-rotate-left"></i>
                                        <div>Tidak ada buku yang sedang Anda pinjam.</div>
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

    <div class="card mb-4">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-clock-rotate-left me-2 text-primary"></i> Riwayat Pengembalian Anda</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Tgl. Pinjam</th>
                            <th>Tgl. Kembali</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $item)
                            <tr>
                                <td>{{ $riwayat->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $item->buku?->judul }}</td>
                                <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                                <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                                <td><span class="badge badge-status bg-soft-success">Dikembalikan</span></td>
                                <td class="text-end">
                                    <a href="{{ route('user.pengembalian.edit', $item->peminjaman_id) }}" class="btn btn-sm btn-outline-primary" title="Edit tanggal pengembalian">
                                        <i class="fas fa-pen me-1"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
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
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
@endsection
