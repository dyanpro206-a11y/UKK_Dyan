@extends('layouts.user')

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
                                <div class="label">Ketersediaan</div>
                                <div class="value">
                                    @if ($dipinjamOlehSaya)
                                        <span class="badge badge-status bg-soft-warning">Anda sedang meminjam</span>
                                    @elseif ($sedangDipinjam)
                                        <span class="badge badge-status bg-soft-danger">Sedang dipinjam anggota lain</span>
                                    @else
                                        <span class="badge badge-status bg-soft-success">Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        @if (! $sedangDipinjam)
                            <a href="{{ route('user.peminjaman.create', ['buku' => $buku->buku_id]) }}" class="btn btn-primary-theme">
                                <i class="fas fa-hand-holding me-1"></i> Pinjam Buku Ini
                            </a>
                        @endif
                        <a href="{{ route('user.buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
