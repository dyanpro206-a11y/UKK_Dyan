@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('title_page', 'Tambah Kategori')
@section('subtitle', 'Tambahkan kategori buku baru')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-tag me-2 text-primary"></i> Form Tambah Kategori</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('kategori-buku.store') }}" class="form-tambah" data-nama="{{ old('nama_kategori') ?: 'kategori baru' }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori') }}" placeholder="Contoh: Fiksi, Pendidikan, Teknologi" required>
                    @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Simpan</button>
                    <a href="{{ route('kategori-buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection