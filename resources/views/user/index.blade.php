@extends('layouts.app')

@section('title', 'Data User')
@section('title_page', 'Data User')
@section('subtitle', 'Kelola data pengguna perpustakaan')

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="card-title"><i class="fas fa-users me-2 text-primary"></i> Daftar User</span>
            <a href="{{ route('users.create') }}" class="btn btn-primary-theme btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah User
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                        <input type="text" name="cari" class="form-control" placeholder="Cari username, nama, atau email..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary-theme"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-rotate me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $item)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $item->username }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    @if ($item->role === 'admin')
                                        <span class="badge bg-soft-danger">Admin</span>
                                    @else
                                        <span class="badge bg-soft-info">User</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $item->user_id) }}" class="btn btn-sm btn-icon-sm btn-outline-warning" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-icon-sm btn-outline-danger btn-hapus"
                                        data-url="{{ route('users.destroy', $item->user_id) }}"
                                        data-nama="{{ $item->nama_lengkap }}" title="Hapus">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <div>Tidak ada data user yang ditemukan.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection