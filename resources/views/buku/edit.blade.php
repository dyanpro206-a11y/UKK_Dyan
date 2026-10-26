@extends('layouts.app')

@section('title', 'Edit Buku')
@section('title_page', 'Edit Buku')
@section('subtitle', 'Perbarui informasi buku')

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-pen me-2 text-primary"></i> Form Edit Buku</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('buku.update', $buku->buku_id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $buku->judul) }}" required>
                        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->kategori_id }}" {{ old('kategori_id', $buku->kategori_id) == $kat->kategori_id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penulis <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis', $buku->penulis) }}" required>
                        @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penerbit <span class="text-danger">*</span></label>
                        <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror" value="{{ old('penerbit', $buku->penerbit) }}" required>
                        @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                        <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                        @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cover Buku</label>
                        <div class="d-flex align-items-start gap-3">
                            <img id="cover-preview" src="{{ $buku->cover ? asset('storage/' . $buku->cover) : '' }}" alt="Preview Cover" class="rounded border {{ $buku->cover ? '' : 'd-none' }}" style="width:80px;height:112px;object-fit:cover;">
                            <div class="flex-grow-1">
                                <input type="file" name="cover" id="cover-input" class="form-control @error('cover') is-invalid @enderror" accept="image/jpeg,image/png,image/gif,image/webp">
                                <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF, WEBP (maks. 2MB). Kosongkan jika tidak ingin mengubah cover.</small>
                                @error('cover') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-floppy-disk me-1"></i> Perbarui</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('cover-input').addEventListener('change', function () {
            const file = this.files[0];
            const preview = document.getElementById('cover-preview');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
@endsection