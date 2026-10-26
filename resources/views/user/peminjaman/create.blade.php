@extends('layouts.user')

@section('title', 'Pinjam Buku')
@section('title_page', 'Pinjam Buku')
@section('subtitle', 'Ajukan peminjaman buku atas nama Anda')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-hand-holding me-2 text-primary"></i> Form Peminjaman Buku</span>
        </div>
        <div class="card-body">
            @if ($buku->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-circle-info me-2"></i> Saat ini tidak ada buku yang tersedia untuk dipinjam
                    (semua buku sedang dipinjam).
                    <a href="{{ route('user.buku.index') }}" class="alert-link">Lihat Katalog</a>
                </div>
            @endif

            <form method="POST" action="{{ route('user.peminjaman.store') }}" class="form-tambah" data-nama="pengajuan peminjaman">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Buku <span class="text-danger">*</span></label>
                        <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->buku_id }}" {{ (old('buku_id') ?: $bukuTerpilih) == $b->buku_id ? 'selected' : '' }}>
                                    {{ $b->judul }} ({{ $b->kategori?->nama_kategori }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl. Peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_peminjaman" class="form-control @error('tgl_peminjaman') is-invalid @enderror" value="{{ old('tgl_peminjaman', date('Y-m-d')) }}" required>
                        @error('tgl_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tgl. Pengembalian</label>
                        <input type="date" name="tgl_pengembalian" class="form-control @error('tgl_pengembalian') is-invalid @enderror" value="{{ old('tgl_pengembalian') }}">
                        @error('tgl_pengembalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Kosongkan jika belum menentukan batas waktu.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Peminjam</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->nama_lengkap }} (Anda)" disabled>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Ajukan Pinjaman</button>
                    <a href="{{ route('user.buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
