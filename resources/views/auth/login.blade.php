@extends('layouts.app')

@section('title', 'Login')
@section('title_page', 'Login')
@section('subtitle', 'Pilihperan akun untuk masuk')

@section('content')
    <div class="login-page">
        <div class="login-card" style="max-width:480px">
            <div class="login-header">
                <div class="logo"><i class="fas fa-book-open"></i></div>
                <h4 class="fw-bold mb-1">Perpustakaan Dyann</h4>
                <p class="mb-0 small" style="color:rgba(255,255,255,.75)">Perpustakaan Digital</p>
            </div>
            <div class="login-body text-center">
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

                <p class="text-muted mb-4 small">
                    Pilih peran akun Anda untuk masuk ke sistem perpustakaan.
                </p>

                <div class="d-grid gap-3">
                    <a href="{{ route('login.admin') }}" class="btn btn-primary-theme btn-lg fw-semibold">
                        <i class="fas fa-right-to-bracket me-2"></i> Login sebagai Admin
                    </a>
                    <a href="{{ route('login.user') }}" class="btn btn-outline-primary btn-lg fw-semibold">
                        <i class="fas fa-user me-2"></i> Login sebagai User
                    </a>
                </div>

                <p class="text-center text-muted small mt-4 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-semibold">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>
@endsection