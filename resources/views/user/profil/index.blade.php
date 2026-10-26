@extends('layouts.user')

@section('title', 'Profil Saya')
@section('title_page', 'Profil Saya')
@section('subtitle', 'Kelola data akun Anda')

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="rounded-circle text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:90px;height:90px;background:linear-gradient(135deg,#111827,#2563eb);font-size:38px">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->nama_lengkap }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge bg-soft-primary">Anggota Perpustakaan</span>
                    <hr>
                    <div class="d-flex justify-content-around">
                        <div>
                            <div class="fw-bold fs-4">{{ $totalPeminjaman }}</div>
                            <small class="text-muted">Total Pinjam</small>
                        </div>
                        <div>
                            <div class="fw-bold fs-4">{{ $sedangDipinjam }}</div>
                            <small class="text-muted">Sedang Dipinjam</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-user-pen me-2 text-primary"></i> Ubah Profil</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('user.profil.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                                <small class="text-muted">Username tidak dapat diubah.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah" minlength="6">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $user->alamat) }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Simpan Perubahan</button>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
