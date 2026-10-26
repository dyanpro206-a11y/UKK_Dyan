@extends('layouts.app')

@section('title', 'Pendaftaran')
@section('title_page', 'Pendaftaran')
@section('subtitle', 'Buat akun anggota perpustakaan')

@section('content')
    <div class="login-page">
        <div class="login-card" style="max-width:520px">
            <div class="login-header">
                <div class="logo"><i class="fas fa-user-plus"></i></div>
                <h4 class="fw-bold mb-1">Pendaftaran Anggota</h4>
                <p class="mb-0 small" style="color:rgba(255,255,255,.75)">Perpustakaan Digital Dyann</p>
            </div>
            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong><i class="fas fa-triangle-exclamation me-2"></i> Periksa kembali isian Anda:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.proses') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required autofocus>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap" value="{{ old('nama_lengkap') }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="contoh@email.com" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ulangi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" minlength="6" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat tempat tinggal (opsional)">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-theme btn-lg w-100 fw-semibold mt-4">
                        <i class="fas fa-user-plus me-2"></i> Daftar
                    </button>
                </form>

                <p class="text-center text-muted small mt-3 mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="fw-semibold">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
@endsection