@extends('layouts.app')

@section('title', 'Login User')
@section('title_page', 'Login User')
@section('subtitle', 'Login sebagai pengunjung perpustakaan')

@section('content')
    <div class="login-page">
        <div class="login-card" style="max-width:420px">
            <div class="login-header">
                <div class="logo"><i class="fas fa-user"></i></div>
                <h4 class="fw-bold mb-1">Login User</h4>
                <p class="mb-0 small" style="color:rgba(255,255,255,.75)">Perpustakaan Digital Dyann</p>
            </div>
            <div class="login-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong><i class="fas fa-triangle-exclamation me-2"></i></strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.user.proses') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary-theme btn-lg w-100 fw-semibold">
                        <i class="fas fa-right-to-bracket me-2"></i> Masuk sebagai User
                    </button>
                </form>

                <p class="text-center text-muted small mt-3 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-semibold">Daftar di sini</a>
                </p>
                <div class="text-center mt-2">
                    <a href="{{ route('login') }}" class="small text-muted fw-semibold">
                        <i class="fas fa-chevron-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection