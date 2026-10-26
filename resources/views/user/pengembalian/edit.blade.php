@extends('layouts.user')

@section('title', 'Edit Pengembalian')
@section('title_page', 'Edit Pengembalian')
@section('subtitle', 'Perbarui tanggal pengembalian buku Anda')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Edit Tanggal Pengembalian</span>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Buku</label>
                    <input type="text" class="form-control" value="{{ $peminjaman->buku?->judul }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tgl. Peminjaman</label>
                    <input type="text" class="form-control" value="{{ $peminjaman->tgl_peminjaman?->format('d M Y') }}" disabled>
                </div>
            </div>
            <form method="POST" action="{{ route('user.pengembalian.update', $peminjaman->peminjaman_id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tgl. Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_pengembalian" class="form-control @error('tgl_pengembalian') is-invalid @enderror" value="{{ old('tgl_pengembalian', $peminjaman->tgl_pengembalian?->format('Y-m-d')) }}" required>
                        <small class="text-muted">Tanggal pengembalian minimal sama dengan tanggal peminjaman.</small>
                        @error('tgl_pengembalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('user.pengembalian.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection