<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Perpustakaan Dyann</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--sb1:#16213e;--sb2:#0f3460}
        *{font-family:'Poppins',sans-serif}
        body{background-color:#f2f6fc;min-height:100vh}
        .sidebar{position:fixed;top:0;left:0;width:260px;height:100vh;background:linear-gradient(180deg,var(--sb1),var(--sb2));color:#fff;z-index:1040;overflow-y:auto}
        .sidebar-header{padding:22px 20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:12px}
        .sidebar-header .logo-icon{width:44px;height:44px;background:rgba(255,255,255,.12);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#ffc107}
        .sidebar-header h5{font-size:16px;font-weight:700;margin:0;letter-spacing:.5px}
        .sidebar-header small{color:rgba(255,255,255,.6);font-size:11px}
        .sidebar .nav-link{color:rgba(255,255,255,.72);padding:12px 22px;margin:3px 12px;border-radius:10px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:12px;transition:all .2s}
        .sidebar .nav-link i{width:22px;text-align:center;font-size:15px}
        .sidebar .nav-link:hover{background:rgba(255,255,255,.08);color:#fff}
        .sidebar .nav-link.active{background:#ffc107;color:#16213e;font-weight:600}
        .sidebar .nav-link.logout-link{color:#fda4af}
        .sidebar-section-title{padding:14px 24px 6px;font-size:10px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);font-weight:600}
.main-content{margin-left:260px;min-height:100vh;display:flex;flex-direction:column}
        .topbar{background:#fff;padding:14px 24px;box-shadow:0 2px 10px rgba(0,0,0,.05);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:1030}
        .topbar .page-title h4{font-weight:700;color:#16213e;margin:0;font-size:20px}
        .topbar .page-title small{color:#6c757d;font-size:12px}
        .content-wrapper{padding:24px;flex:1}
        .stat-card{border:none;border-radius:16px;box-shadow:0 4px 14px rgba(15,52,96,.08);transition:transform .2s}
        .stat-card:hover{transform:translateY(-4px)}
        .stat-card .icon-box{width:54px;height:54px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff}
        .stat-card .stat-value{font-size:28px;font-weight:700;color:#16213e}
        .stat-card .stat-label{font-size:13px;color:#6c757d;font-weight:500}
        .bg-soft-primary{background-color:#e8f0fe;color:#0f3460}
        .bg-soft-warning{background-color:#fff3cd;color:#997404}
        .bg-soft-success{background-color:#d1e7dd;color:#146c43}
        .bg-soft-info{background-color:#cff4fc;color:#055160}
        .bg-soft-danger{background-color:#f8d7da;color:#842029}
        .bg-soft-secondary{background-color:#e2e3e5;color:#41464b}
        .card{border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,.05)}
        .card-header{background-color:#fff;border-bottom:1px solid #eef1f7;padding:16px 20px;font-weight:600}
        .card-header .card-title{font-weight:700;color:#16213e;font-size:16px}
        .table thead th{background-color:#f6f9ff;color:#16213e;font-weight:600;font-size:13px;white-space:nowrap;border-bottom:none}
        .table td{font-size:13px;vertical-align:middle}
        .badge-status{padding:6px 12px;border-radius:30px;font-size:12px;font-weight:600}
        .btn-primary-theme{background-color:#0f3460;border-color:#0f3460;color:#fff}
        .btn-primary-theme:hover{background-color:#0a2749;border-color:#0a2749;color:#fff}
        .form-label{font-weight:500;font-size:13px;color:#495057}
        .form-control:focus,.form-select:focus{border-color:#86b7fe;box-shadow:0 0 0 .2rem rgba(15,52,96,.15)}
        .book-cover{width:100%;height:250px;background:linear-gradient(135deg,#0f3460,#1f5f9e);border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:60px;position:relative;overflow:hidden}
        .book-cover img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:inherit}
        .info-item{padding:10px 0;border-bottom:1px dashed #dee2e6}
        .info-item .label{font-size:12px;color:#6c757d}
        .info-item .value{font-weight:600;color:#16213e}
        .login-page{min-height:100vh;background:linear-gradient(135deg,#0a1128 0%,#101d3d 55%,#0d2c52 100%);display:flex;align-items:center;justify-content:center;padding:20px}
        .login-card{background:#fff;border-radius:20px;box-shadow:0 20px 50px rgba(0,0,0,.45);border:none;overflow:hidden;max-width:440px;width:100%}
        .login-card .login-header{background:linear-gradient(135deg,#0f3460,#1a56a6);padding:34px 30px;text-align:center;color:#fff;border-bottom:4px solid #ffc107}
        .login-card .login-header .logo{width:70px;height:70px;background:rgba(255,255,255,.15);border-radius:18px;display:inline-flex;align-items:center;justify-content:center;font-size:34px;color:#ffc107;margin-bottom:14px}
        .login-card .login-body{padding:30px;background:#fff}
        .form-check-input:checked{background-color:#0f3460;border-color:#0f3460}
        .pagination .page-link{color:#0f3460}
        .pagination .active>.page-link{background-color:#0f3460;border-color:#0f3460}
        .empty-state{padding:40px;text-align:center;color:#6c757d}
        .empty-state i{font-size:48px;color:#c8d4e8;margin-bottom:14px}
        @media print{.sidebar,.topbar,.no-print{display:none!important}.main-content{margin-left:0!important}.content-wrapper{padding:0}}
    </style>
    @stack('styles')
</head>
<body>
<div class="login-page d-none"></div>
    @if (auth()->check() && !request()->is('login*') && !request()->is('daftar*'))
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <h5 class="mb-0">Perpustakaan</h5>
                    <small>Digital Dyann</small>
                </div>
            </div>
            <div class="sidebar-section-title">Menu Utama</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-gauge-high"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
                        <i class="fas fa-book"></i> Data Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kategori-buku.*') ? 'active' : '' }}" href="{{ route('kategori-buku.index') }}">
                        <i class="fas fa-tags"></i> Kategori Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> Data User
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                        <i class="fas fa-hand-holding"></i> Peminjaman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                        <i class="fas fa-rotate-left"></i> Pengembalian
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}">
                        <i class="fas fa-right-left"></i> Transaksi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                        <i class="fas fa-file-lines"></i> Laporan
                    </a>
                </li>
            </ul>
            <div class="sidebar-section-title">Akun</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link logout-link w-100 border-0 bg-transparent text-start">
                            <i class="fas fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <div class="main-content">
            <nav class="topbar">
                <div class="page-title">
                    <h4>@yield('title_page', 'Dashboard')</h4>
                    <small>@yield('subtitle', 'Panel administrasi perpustakaan digital')</small>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-semibold small">{{ auth()->user()->nama_lengkap ?? 'Admin' }}</div>
                        <div class="text-muted" style="font-size:12px">{{ auth()->user()->username ?? '' }}</div>
                    </div>
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width:42px;height:42px;background-color:#0f3460">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </nav>
<main class="content-wrapper">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-circle-check me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="fas fa-circle-exclamation me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-triangle-exclamation me-2"></i> Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endif
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px">
                <div class="modal-body text-center p-4">
                    <div class="rounded-circle bg-soft-danger d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;font-size:30px">
                        <i class="fas fa-trash-can text-danger"></i>
                    </div>
                    <h5 class="fw-bold">Konfirmasi Hapus</h5>
                    <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus
                        <strong class="text-danger" id="namaItemHapus"></strong>?
                        Data yang sudah dihapus tidak dapat dikembalikan.
                    </p>
                    <form method="POST" id="formHapus">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fas fa-trash-can me-1"></i> Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px">
                <div class="modal-body text-center p-4">
                    <div class="rounded-circle bg-soft-primary d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;font-size:30px">
                        <i class="fas fa-circle-plus text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Konfirmasi Tambah</h5>
                    <p class="text-muted mb-4">Apakah Anda yakin ingin menambahkan
                        <strong class="text-primary" id="namaItemTambah"></strong>?
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="btnYaTambah" class="btn btn-primary-theme px-4">
                            <i class="fas fa-circle-plus me-1"></i> Ya, Tambahkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalTambah = document.getElementById('modalTambah');
            const btnYaTambah = document.getElementById('btnYaTambah');
            const namaItemTambah = document.getElementById('namaItemTambah');
            let formTambahAktif = null;

            if (!modalTambah) { return; }

            document.querySelectorAll('form.form-tambah').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.confirmed === 'true') { return; }
                    event.preventDefault();
                    formTambahAktif = form;
                    if (namaItemTambah) {
                        namaItemTambah.textContent = form.getAttribute('data-nama') || 'data baru ini';
                    }
                    bootstrap.Modal.getOrCreateInstance(modalTambah).show();
                });
            });

            if (btnYaTambah) {
                btnYaTambah.addEventListener('click', function () {
                    if (!formTambahAktif) { return; }
                    const form = formTambahAktif;
                    formTambahAktif = null;
                    form.dataset.confirmed = 'true';
                    bootstrap.Modal.getOrCreateInstance(modalTambah).hide();
                    form.submit();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalHapus = document.getElementById('modalHapus');
            const formHapus = document.getElementById('formHapus');
            const namaItemHapus = document.getElementById('namaItemHapus');

            // Pengaman: form hapus tidak boleh dikirim tanpa URL tujuan yang jelas,
            // kalau tidak ia akan mengirim DELETE ke halaman yang sedang dibuka
            // (misal DELETE /peminjaman/create) dan memicu error MethodNotAllowed.
            formHapus.addEventListener('submit', function (event) {
                const action = formHapus.getAttribute('action');
                if (!action) {
                    event.preventDefault();
                    bootstrap.Modal.getOrCreateInstance(modalHapus).hide();
                }
            });

            document.querySelectorAll('.btn-hapus').forEach(function (btn) {
                btn.addEventListener('click', function (event) {
                    event.preventDefault();
                    const url = btn.getAttribute('data-url');
                    if (!url) { return; }
                    const nama = btn.getAttribute('data-nama');
                    formHapus.setAttribute('action', url);
                    namaItemHapus.textContent = nama;
                    const modal = bootstrap.Modal.getOrCreateInstance(modalHapus);
                    modal.show();
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>