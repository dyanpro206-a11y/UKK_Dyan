@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('title_page', 'Edit Transaksi')
@section('subtitle', 'Perbarui data transaksi')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Form Edit Transaksi (TRX-{{ str_pad($transaksi->transaksi_id, 4, '0', STR_PAD_LEFT) }})</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('transaksi.update', $transaksi->transaksi_id) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Peminjaman Terkait <span class="text-danger">*</span></label>
                        <select name="peminjaman_id" class="form-select @error('peminjaman_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Peminjaman --</option>
                            @foreach ($peminjaman as $item)
                                <option value="{{ $item->peminjaman_id }}" {{ old('peminjaman_id', $transaksi->peminjaman_id) == $item->peminjaman_id ? 'selected' : '' }}>
                                    #{{ $item->peminjaman_id }} &mdash; {{ $item->buku?->judul }} ({{ $item->user?->nama_lengkap }})
                                </option>
                            @endforeach
                        </select>
                        @error('peminjaman_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anggota <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->user_id }}" {{ old('user_id', $transaksi->user_id) == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->nama_lengkap }} ({{ '@' . $user->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Transaksi <span class="text-danger">*</span></label>
                        <select name="jenis_transaksi" class="form-select @error('jenis_transaksi') is-invalid @enderror" required>
                            <option value="Peminjaman" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) === 'Peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                            <option value="Pengembalian" {{ old('jenis_transaksi', $transaksi->jenis_transaksi) === 'Pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                        </select>
                        @error('jenis_transaksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_transaksi" class="form-control @error('tgl_transaksi') is-invalid @enderror" value="{{ old('tgl_transaksi', $transaksi->tgl_transaksi?->format('Y-m-d')) }}" required>
                        @error('tgl_transaksi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan transaksi (opsional)...">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
