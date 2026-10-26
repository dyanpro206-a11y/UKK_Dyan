@extends('layouts.user')

@section('title', 'Edit Peminjaman')
@section('title_page', 'Edit Peminjaman')
@section('subtitle', 'Perbarui data peminjaman buku Anda')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Form Edit Peminjaman</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.peminjaman.update', $peminjaman->peminjaman_id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Buku <span class="text-danger">*</span></label>
                        <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->buku_id }}" {{ old('buku_id', $peminjaman->buku_id) == $b->buku_id ? 'selected' : '' }}>
                                    {{ $b->judul }} ({{ $b->kategori?->nama_kategori }}){{ $b->buku_id === $peminjaman->buku_id ? ' &mdash; buku saat ini' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Buku yang sedang dipinjam anggota lain tidak tersedia untuk dipilih.</small>
                        @error('buku_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl. Peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_peminjaman" class="form-control @error('tgl_peminjaman') is-invalid @enderror" value="{{ old('tgl_peminjaman', $peminjaman->tgl_peminjaman?->format('Y-m-d')) }}" required>
                        @error('tgl_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rencana Tgl. Pengembalian</label>
                        <input type="date" name="tgl_pengembalian" class="form-control @error('tgl_pengembalian') is-invalid @enderror" value="{{ old('tgl_pengembalian', $peminjaman->tgl_pengembalian?->format('Y-m-d')) }}">
                        <small class="text-muted">Kosongkan jika belum menentukan batas waktu.</small>
                        @error('tgl_pengembalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Peminjam</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->nama_lengkap }} (Anda)" disabled>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection