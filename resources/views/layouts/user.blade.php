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
        /* ===== TEMA USER (PUTIH - BIRU - HITAM) ===== */
        :root{--us1:#111827;--us2:#2563eb;--us-accent:#2563eb}
        *{font-family:'Poppins',sans-serif}
        body{background-color:#ffffff;min-height:100vh}
        .sidebar{position:fixed;top:0;left:0;width:260px;height:100vh;background:linear-gradient(180deg,#0b0d12,#181c26);color:#fff;z-index:1040;overflow-y:auto}
        .sidebar-header{padding:22px 20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:12px}
        .sidebar-header .logo-icon{width:44px;height:44px;background:rgba(255,255,255,.12);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#60a5fa}
        .sidebar-header h5{font-size:16px;font-weight:700;margin:0;letter-spacing:.5px}
        .sidebar-header small{color:rgba(255,255,255,.6);font-size:11px}
        .sidebar .nav-link{color:rgba(255,255,255,.72);padding:12px 22px;margin:3px 12px;border-radius:10px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:12px;transition:all .2s}
        .sidebar .nav-link i{width:22px;text-align:center;font-size:15px}
        .sidebar .nav-link:hover{background:rgba(255,255,255,.08);color:#fff}
        .sidebar .nav-link.active{background:var(--us-accent);color:#fff;font-weight:600}
        .sidebar .nav-link.logout-link{color:#fda4af}
        .sidebar-section-title{padding:14px 24px 6px;font-size:10px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.4);font-weight:600}
        .main-content{margin-left:260px;min-height:100vh;display:flex;flex-direction:column}
        .topbar{background:#fff;padding:14px 24px;box-shadow:0 2px 10px rgba(0,0,0,.05);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:1030}
        .topbar .page-title h4{font-weight:700;color:var(--us1);margin:0;font-size:20px}
        .topbar .page-title small{color:#6c757d;font-size:12px}
        .content-wrapper{padding:24px;flex:1}
        .stat-card{border:none;border-radius:16px;box-shadow:0 4px 14px rgba(17,24,39,.08);transition:transform .2s}
        .stat-card:hover{transform:translateY(-4px)}
        .stat-card .icon-box{width:54px;height:54px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px}
        .stat-card .stat-value{font-size:28px;font-weight:700;color:var(--us1)}
        .stat-card .stat-label{font-size:13px;color:#6c757d;font-weight:500}
        .bg-soft-primary{background-color:#dbeafe;color:#1e40af}
        .bg-soft-warning{background-color:#fff3cd;color:#997404}
        .bg-soft-success{background-color:#d1e7dd;color:#146c43}
        .bg-soft-info{background-color:#cff4fc;color:#055160}
        .bg-soft-danger{background-color:#f8d7da;color:#842029}
        .bg-soft-secondary{background-color:#e2e3e5;color:#41464b}
        .card{border:none;border-radius:16px;box-shadow:0 2px 10px rgba(0,0,0,.05)}
        .card-header{background-color:#fff;border-bottom:1px solid #eef2f7;padding:16px 20px;font-weight:600}
        .card-header .card-title{font-weight:700;color:var(--us1);font-size:16px}
        .table thead th{background-color:#f1f5f9;color:var(--us1);font-weight:600;font-size:13px;white-space:nowrap;border-bottom:none}
        .table td{font-size:13px;vertical-align:middle}
        .badge-status{padding:6px 12px;border-radius:30px;font-size:12px;font-weight:600}
        .btn-primary-theme{background-color:var(--us2);border-color:var(--us2)}
        .btn-primary-theme:hover{background-color:#1d4ed8;border-color:#1d4ed8}
        .form-label{font-weight:500;font-size:13px;color:#495057}
        .form-control:focus,.form-select:focus{border-color:#93c5fd;box-shadow:0 0 0 .2rem rgba(37,99,235,.2)}
        .book-cover{width:100%;height:250px;background:linear-gradient(135deg,#111827,#2563eb);border-radius:16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:60px;position:relative;overflow:hidden}
        .book-cover img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:inherit}
        .info-item{padding:10px 0;border-bottom:1px dashed #dee2e6}
        .info-item .label{font-size:12px;color:#6c757d}
        .info-item .value{font-weight:600;color:var(--us1)}
        .pagination .page-link{color:var(--us2)}
        .pagination .active>.page-link{background-color:var(--us2);border-color:var(--us2)}
        .empty-state{padding:40px;text-align:center;color:#6c757d}
        .empty-state i{font-size:48px;color:#cbd5e1;margin-bottom:14px}
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon"><i class="fas fa-book-open-reader"></i></div>
            <div>
                <h5 class="mb-0">Perpustakaan</h5>
                <small>Anggota &mdash; {{ auth()->user()->username }}</small>
            </div>
        </div>
        <div class="sidebar-section-title">Menu Utama</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                    <i class="fas fa-house"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.buku.*') ? 'active' : '' }}" href="{{ route('user.buku.index') }}">
                    <i class="fas fa-book"></i> Katalog Buku
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.peminjaman.*') ? 'active' : '' }}" href="{{ route('user.peminjaman.index') }}">
                    <i class="fas fa-hand-holding"></i> Peminjaman Saya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.pengembalian.*') ? 'active' : '' }}" href="{{ route('user.pengembalian.index') }}">
                    <i class="fas fa-rotate-left"></i> Pengembalian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.profil') ? 'active' : '' }}" href="{{ route('user.profil') }}">
                    <i class="fas fa-user-pen"></i> Profil Saya
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
                <small>@yield('subtitle', 'Area anggota perpustakaan digital')</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-semibold small">{{ auth()->user()->nama_lengkap }}</div>
                    <div class="text-muted" style="font-size:12px">{{ auth()->user()->email }}</div>
                </div>
                <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width:42px;height:42px;background-color:#111827">
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
    @stack('scripts')
</body>
</html>
