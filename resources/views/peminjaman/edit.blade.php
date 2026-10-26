@extends('layouts.app')

@section('title', 'Edit Peminjaman')
@section('title_page', 'Edit Peminjaman')
@section('subtitle', 'Perbarui data peminjaman buku')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Form Edit Peminjaman (PJM-{{ str_pad($peminjaman->peminjaman_id, 4, '0', STR_PAD_LEFT) }})</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('peminjaman.update', $peminjaman->peminjaman_id) }}" class="form-tambah" data-nama="peminjaman">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Buku <span class="text-danger">*</span></label>
                        <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->buku_id }}" {{ old('buku_id', $peminjaman->buku_id) == $b->buku_id ? 'selected' : '' }}>
                                    {{ $b->judul }} ({{ $b->kategori?->nama_kategori }})
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pilih Peminjam <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Peminjam --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->user_id }}" {{ old('user_id', $peminjaman->user_id) == $u->user_id ? 'selected' : '' }}>
                                    {{ $u->nama_lengkap }} ({{ $u->username }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Peminjaman dicatat atas nama anggota yang dipilih.</small>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl.peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_peminjaman" class="form-control @error('tgl_peminjaman') is-invalid @enderror" value="{{ old('tgl_peminjaman', $peminjaman->tgl_peminjaman?->format('Y-m-d')) }}" required>
                        @error('tgl_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl.pengembalian</label>
                        <input type="date" name="tgl_pengembalian" id="tglPengembalian" class="form-control @error('tgl_pengembalian') is-invalid @enderror" value="{{ old('tgl_pengembalian', $peminjaman->tgl_pengembalian?->format('Y-m-d')) }}">
                        @error('tgl_pengembalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Peminjaman <span class="text-danger">*</span></label>
                        <select name="status_peminjaman" id="statusPeminjaman" class="form-select @error('status_peminjaman') is-invalid @enderror" required>
                            <option value="Dipinjam" {{ old('status_peminjaman', $peminjaman->status_peminjaman) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ old('status_peminjaman', $peminjaman->status_peminjaman) == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                        @error('status_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
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