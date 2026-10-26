@extends('layouts.app')

@section('title', 'Tambah Peminjaman')
@section('title_page', 'Tambah Peminjaman')
@section('subtitle', 'Catat transaksi peminjaman buku')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-hand-holding me-2 text-primary"></i> Form Tambah Peminjaman</span>
        </div>
        <div class="card-body">
            @if ($buku->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-circle-info me-2"></i> Saat ini tidak ada buku yang tersedia untuk dipinjam
                    (semua buku sedang dipinjam atau belum ada buku).
                    <a href="{{ route('buku.index') }}" class="alert-link">Kelola Buku</a>
                </div>
            @endif

            <form method="POST" action="{{ route('peminjaman.store') }}" class="form-tambah" data-nama="peminjaman baru">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Pilih Buku <span class="text-danger">*</span></label>
                        <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Buku --</option>
                            @foreach ($buku as $b)
                                <option value="{{ $b->buku_id }}" {{ old('buku_id') == $b->buku_id ? 'selected' : '' }}>
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
                                <option value="{{ $u->user_id }}" {{ old('user_id') == $u->user_id ? 'selected' : '' }}>
                                    {{ $u->nama_lengkap }} ({{ $u->username }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Peminjaman dicatat atas nama anggota yang dipilih.</small>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl.peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_peminjaman" class="form-control @error('tgl_peminjaman') is-invalid @enderror" value="{{ old('tgl_peminjaman', date('Y-m-d')) }}" required>
                        @error('tgl_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tgl.pengembalian</label>
                        <input type="date" name="tgl_pengembalian" id="tglPengembalian" class="form-control @error('tgl_pengembalian')is-invalid @enderror" value="{{ old('tgl_pengembalian') }}">
                        @error('tgl_pengembalian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status Peminjaman <span class="text-danger">*</span></label>
                        <select name="status_peminjaman" id="statusPeminjaman" class="form-select @error('status_peminjaman')is-invalid @enderror" required>
                            <option value="Dipinjam" {{ old('status_peminjaman') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ old('status_peminjaman') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                        @error('status_peminjaman') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-circle-plus me-1"></i> Tambahkan</button>
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