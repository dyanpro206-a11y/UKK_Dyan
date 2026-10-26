@extends('layouts.app')

@section('title', 'Detail Buku')
@section('title_page', 'Detail Buku')
@section('subtitle', 'Informasi lengkap buku')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="book-cover mb-4">
                        @if ($buku->cover)
                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" onerror="this.style.display='none'">
                        @else
                            <img src="https://picsum.photos/seed/buku-{{ $buku->buku_id }}/400/560" alt="Cover {{ $buku->judul }}" onerror="this.style.display='none'">
                        @endif
                        <i class="fas fa-book"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-center">{{ $buku->judul }}</h5>
                    <p class="text-muted text-center mb-0">{{ $buku->penulis }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-circle-info me-2 text-primary"></i> Informasi Buku</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="label">Judul Buku</div>
                                <div class="value">{{ $buku->judul }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="label">Kategori</div>
                                <div class="value">{{ $buku->kategori?->nama_kategori }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="label">Penulis</div>
                                <div class="value">{{ $buku->penulis }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="label">Penerbit</div>
                                <div class="value">{{ $buku->penerbit }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="label">Tahun Terbit</div>
                                <div class="value">{{ $buku->tahun_terbit }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="label">Dibuat Pada</div>
                                <div class="value">{{ $buku->created_at?->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('buku.edit', $buku->buku_id) }}" class="btn btn-warning"><i class="fas fa-pen me-1"></i> Edit</a>
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-clock-rotate-left me-2 text-primary"></i> Riwayat Peminjaman</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Tgl. Peminjaman</th>
                                <th>Tgl. Pengembalian</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($buku->peminjaman as $pj)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pj->user?->nama_lengkap }}</td>
                                    <td>{{ $pj->tgl_peminjaman?->format('d M Y') }}</td>
                                    <td>{{ $pj->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        @if ($pj->status_peminjaman === 'Dipinjam')
                                            <span class="badge badge-status bg-soft-warning">Dipinjam</span>
                                        @else
                                            <span class="badge badge-status bg-soft-success">Dikembalikan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <div>Buku ini belum pernah dipinjam.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection