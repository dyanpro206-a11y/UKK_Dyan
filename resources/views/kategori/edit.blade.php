@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('title_page', 'Edit Kategori')
@section('subtitle', 'Perbarui nama kategori buku')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Form Edit Kategori</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('kategori-buku.update', $kategori->kategori_id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('kategori-buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection