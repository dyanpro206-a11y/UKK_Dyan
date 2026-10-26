@extends('layouts.user')

@section('title', 'Katalog Buku')
@section('title_page', 'Katalog Buku')
@section('subtitle', 'Jelajahi koleksi buku perpustakaan')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('user.buku.index') }}" class="row g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari judul, penulis, atau penerbit..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="kategori_id" class="form-select">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->kategori_id }}" {{ $kategoriId == $kat->kategori_id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('user.buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse ($buku as $item)
            @php
                $tersedia = ! $bukuDipinjam->contains($item->buku_id);
            @endphp
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="book-cover mb-3" style="height:160px;font-size:42px">
                            @if ($item->cover)
                                <img src="{{ asset('storage/' . $item->cover) }}" alt="Cover {{ $item->judul }}" onerror="this.style.display='none'">
                            @else
                                <img src="https://picsum.photos/seed/buku-{{ $item->buku_id }}/400/560" alt="Cover {{ $item->judul }}" onerror="this.style.display='none'">
                            @endif
                            <i class="fas fa-book"></i>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $item->judul }}</h6>
                        <small class="text-muted d-block mb-2">{{ $item->penulis }}</small>
                        <span class="badge bg-soft-primary mb-3">{{ $item->kategori?->nama_kategori }}</span>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('user.buku.show', $item->buku_id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                            @if ($tersedia)
                                <a href="{{ route('user.peminjaman.create', ['buku' => $item->buku_id]) }}" class="btn btn-sm btn-primary-theme">
                                    <i class="fas fa-hand-holding me-1"></i> Pinjam
                                </a>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="fas fa-ban me-1"></i> Dipinjam
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="empty-state">
                        <i class="fas fa-book"></i>
                        <div>Tidak ada buku yang ditemukan.</div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $buku->links() }}
    </div>
@endsection
