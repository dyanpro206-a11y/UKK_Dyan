@extends('layouts.app')

@section('title', 'Pengembalian Buku')
@section('title_page', 'Pengembalian Buku')
@section('subtitle', 'Proses pengembalian buku yang sedang dipinjam')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-rotate-left me-2 text-primary"></i> Buku Sedang Dipinjam</span>
            <a href="{{ route('pengembalian.riwayat') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-clock-rotate-left me-1"></i> Riwayat Pengembalian
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('pengembalian.index') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari judul buku atau nama peminjam..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Peminjam</th>
                            <th>Tgl. Peminjaman</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $item)
                            <tr>
                                <td>{{ $peminjaman->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $item->buku?->judul }}</div>
                                    <small class="text-muted">{{ $item->buku?->penulis }}</small>
                                </td>
                                <td>{{ $item->user?->nama_lengkap }}</td>
                                <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-kembalikan"
                                        data-url="{{ route('pengembalian.kembalikan', $item->peminjaman_id) }}"
                                        data-nama="{{ $item->buku?->judul }}">
                                        <i class="fas fa-check me-1"></i> Kembalikan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-face-smile"></i>
                                        <div>Tidak ada buku yang sedang dipinjam.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $peminjaman->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-kembalikan').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const url = btn.getAttribute('data-url');
                    const nama = btn.getAttribute('data-nama');
                    if (confirm('Kembalikan buku "' + nama + '"? Buku akan ditandai sebagai Dikembalikan.')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;
                        form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name="csrf-token"]').content + '">';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush