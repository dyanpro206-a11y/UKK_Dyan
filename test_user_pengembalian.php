<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$controller = new App\Http\Controllers\User\PengembalianController();

// Ambil user anggota dan buku yang tidak sedang dipinjam (fixture).
$anggota = User::where('role', '!=', 'admin')->first();
$sedangDipinjam = Peminjaman::where('status_peminjaman', 'Dipinjam')->pluck('buku_id');
$buku = Buku::whereNotIn('buku_id', $sedangDipinjam)->first();

if (! $anggota || ! $buku) {
    echo "Data tidak cukup untuk membuat fixture.\n";
    exit(1);
}

Auth::login($anggota);

// ===== 1. Uji proteksi kepemilikan (pakai peminjaman milik orang lain, tanpa mengubahnya) =====
$peminjamanOrang = Peminjaman::where('user_id', '!=', $anggota->user_id)
    ->where('status_peminjaman', 'Dipinjam')->first();
if ($peminjamanOrang) {
    $resp = $controller->kembalikan($peminjamanOrang);
    $peminjamanOrang->refresh();
    echo '1. Proteksi kepemilikan: ditolak = '
        . (str_contains($resp->getSession()->get('error') ?? '', 'berhak') && $peminjamanOrang->status_peminjaman === 'Dipinjam' ? 'YA' : 'TIDAK') . PHP_EOL;
}

// ===== 2. Buat fixture peminjaman milik anggota lalu kembalikan =====
$fixture = Peminjaman::create([
    'buku_id' => $buku->buku_id,
    'user_id' => $anggota->user_id,
    'tgl_peminjaman' => now()->toDateString(),
    'status_peminjaman' => 'Dipinjam',
]);

$resp = $controller->kembalikan($fixture);
$fixture->refresh();
$trx = Transaksi::where('peminjaman_id', $fixture->peminjaman_id)->latest()->first();

echo '2. Kembalikan buku sendiri: status = ' . $fixture->status_peminjaman
    . ' | tgl_pengembalian = ' . $fixture->tgl_pengembalian
    . ' | transaksi = ' . ($trx ? 'YA [' . $trx->jenis_transaksi . '] ' . $trx->keterangan : 'TIDAK') . PHP_EOL;

// ===== 3. Kembalikan dua kali harus ditolak =====
$resp2 = $controller->kembalikan($fixture);
echo '3. Kembalikan dua kali ditolak: '
    . (str_contains($resp2->getSession()->get('error') ?? '', 'sudah') ? 'YA' : 'TIDAK') . PHP_EOL;

// ===== 4. Bersihkan fixture =====
Transaksi::where('peminjaman_id', $fixture->peminjaman_id)->delete();
$fixture->delete();
echo '4. Fixture dihapus. Verifikasi: transaksi tersisa = ' . Transaksi::count()
    . ' (harus tetap 1, milik user asli)' . PHP_EOL;
