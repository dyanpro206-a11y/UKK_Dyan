<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\User\PengembalianController as UserPengembalianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\BukuController as UserBukuController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PeminjamanController as UserPeminjamanController;
use App\Http\Controllers\User\ProfilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Berisi rute untuk website perpustakaan digital.
| Panel admin dan panel user dipisahkan: admin memakai tampilan biru,
| user memakai tampilan hijau dengan menu yang berbeda.
|
*/

// Halaman utama -> diarahkan sesuai status login dan role.
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->role === 'admin'
        ? redirect()->route('dashboard')
        : redirect()->route('user.dashboard');
});

// Autentikasi (login admin / login user terpisah).
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login/admin', [AuthController::class, 'showAdminLoginForm'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin.proses');
Route::get('/login/user', [AuthController::class, 'showUserLoginForm'])->name('login.user');
Route::post('/login/user', [AuthController::class, 'loginUser'])->name('login.user.proses');
Route::get('/daftar', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/daftar', [RegisterController::class, 'register'])->name('register.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Panel Admin (tampilan biru, menu lengkap)
| Hanya bisa diakses setelah login sebagai admin.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Buku (CRUD + detail)
    Route::resource('buku', BukuController::class);

    // Kategori Buku (CRUD)
    Route::resource('kategori-buku', KategoriController::class)
        ->except(['show'])
        ->parameters(['kategori-buku' => 'kategori']);

    // Data User (CRUD)
    Route::resource('users', UserController::class)->except(['show']);

    // Peminjaman (daftar + tambah + edit)
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'edit', 'update']);

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}/kembalikan', [PengembalianController::class, 'kembalikan'])->name('pengembalian.kembalikan');
    Route::get('/pengembalian/{peminjaman}/edit', [PengembalianController::class, 'edit'])->name('pengembalian.edit');
    Route::put('/pengembalian/{peminjaman}', [PengembalianController::class, 'update'])->name('pengembalian.update');
    Route::get('/pengembalian/riwayat', [PengembalianController::class, 'riwayat'])->name('pengembalian.riwayat');

    // Transaksi (riwayat aktivitas peminjaman & pengembalian)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});

/*
|--------------------------------------------------------------------------
| Panel User (tampilan hijau, menu khusus anggota)
| Hanya bisa diakses setelah login sebagai user biasa.
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {

    // Dashboard user (halaman utama anggota)
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Katalog buku (hanya lihat, tanpa CRUD)
    Route::get('/buku', [UserBukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{buku}', [UserBukuController::class, 'show'])->name('buku.show');

    // Peminjaman milik user yang sedang login
    Route::get('/peminjaman', [UserPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [UserPeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [UserPeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}/edit', [UserPeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{peminjaman}', [UserPeminjamanController::class, 'update'])->name('peminjaman.update');
    // Pengembalian buku oleh anggota sendiri
    Route::get('/pengembalian', [UserPengembalianController::class, 'index'])->name('pengembalian.index');
    Route::post('/pengembalian/{peminjaman}/kembalikan', [UserPengembalianController::class, 'kembalikan'])->name('pengembalian.kembalikan');
    Route::get('/pengembalian/{peminjaman}/edit', [UserPengembalianController::class, 'edit'])->name('pengembalian.edit');
    Route::put('/pengembalian/{peminjaman}', [UserPengembalianController::class, 'update'])->name('pengembalian.update');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});
