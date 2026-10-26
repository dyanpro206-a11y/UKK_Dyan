@extends('layouts.app')

@section('title', 'Edit Pengembalian')
@section('title_page', 'Edit Pengembalian')
@section('subtitle', 'Perbarui data pengembalian buku')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Form Edit Pengembalian (PJM-{{ str_pad($peminjaman->peminjaman_id, 4, '0', STR_PAD_LEFT) }})</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengembalian.update', $peminjaman->peminjaman_id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Buku</label>
                        <input type="text" class="form-control" value="{{ $peminjaman->buku?->judul }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Peminjam</label>
                        <input type="text" class="form-control" value="{{ $peminjaman->user?->nama_lengkap }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl. Peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_peminjaman" class="form-control @error('tgl_peminjaman') is-invalid @enderror" value="{{ old('tgl_peminjaman', $peminjaman->tgl_peminjaman?->format('Y-m-d')) }}" required>
                        @error('tgl_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Peminjaman <span class="text-danger">*</span></label>
                        <select name="status_peminjaman" id="statusPeminjaman" class="form-select @error('status_peminjaman') is-invalid @enderror" required>
                            <option value="Dikembalikan" {{ old('status_peminjaman', $peminjaman->status_peminjaman) == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="Dipinjam" {{ old('status_peminjaman', $peminjaman->status_peminjaman) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        </select>
                        <small class="text-muted">Jika diubah menjadi "Dipinjam", pengembalian dibatalkan.</small>
                        @error('status_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl. Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_pengembalian" id="tglPengembalian" class="form-control @error('tgl_pengembalian') is-invalid @enderror" value="{{ old('tgl_pengembalian', $peminjaman->tgl_pengembalian?->format('Y-m-d')) }}">
                        <small class="text-muted">Tanggal pengembalian minimal sama dengan tanggal peminjaman.</small>
                        @error('tgl_pengembalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('pengembalian.riwayat') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const status = document.getElementById('statusPeminjaman');
            const tgl = document.getElementById('tglPengembalian');

            function toggleTgl() {
                if (status.value === 'Dikembalikan') {
                    tgl.required = true;
                    tgl.disabled = false;
                } else {
                    tgl.required = false;
                    tgl.disabled = true;
                }
            }

            status.addEventListener('change', toggleTgl);
            toggleTgl();
        });
    </script>
@endpush