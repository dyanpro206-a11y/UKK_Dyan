<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Peminjaman;
use App\Models\Transaksi;
use Illuminate\Support\Facades\View;

View::share('errors', new Illuminate\Support\ViewErrorBag);

// Buat fixture transaksi (pakai peminjaman asli yang sudah ada, tanpa mengubahnya).
$p = Peminjaman::first();
$trx = Transaksi::create([
    'peminjaman_id' => $p->peminjaman_id,
    'user_id' => $p->user_id,
    'jenis_transaksi' => 'Peminjaman',
    'tgl_transaksi' => now(),
    'keterangan' => 'Sebelum edit.',
]);

$controller = new App\Http\Controllers\TransaksiController();

// ===== 1. Render form edit =====
$html = view('transaksi.edit', [
    'transaksi' => $trx->fresh(),
    'peminjaman' => Peminjaman::with(['buku', 'user'])->orderByDesc('peminjaman_id')->get(),
    'users' => App\Models\User::orderBy('nama_lengkap')->get(),
])->render();
echo '1. Form edit ter-render: ' . strlen($html) . ' karakter'
    . ' | berisi nilai lama: ' . (str_contains($html, 'Sebelum edit.') ? 'YA' : 'TIDAK')
    . ' | dropdown peminjaman: ' . (str_contains($html, 'Pilih Peminjaman') ? 'YA' : 'TIDAK')
    . ' | dropdown anggota: ' . (str_contains($html, 'Pilih Anggota') ? 'YA' : 'TIDAK') . PHP_EOL;

// ===== 2. Uji update dengan data valid =====
$request = Illuminate\Http\Request::create('/transaksi/' . $trx->transaksi_id, 'POST', [
    'peminjaman_id' => $p->peminjaman_id,
    'user_id' => $p->user_id,
    'jenis_transaksi' => 'Pengembalian',
    'tgl_transaksi' => '2026-09-15',
    'keterangan' => 'Sudah diedit via uji otomatis.',
]);
$request->setLaravelSession(app('session.store'));

// Jalankan validasi manual seperti di controller (update butuh Request dengan validasi).
$data = validator($request->all(), [
    'peminjaman_id' => 'required|exists:peminjaman,peminjaman_id',
    'user_id' => 'required|exists:users,user_id',
    'jenis_transaksi' => 'required|in:Peminjaman,Pengembalian',
    'tgl_transaksi' => 'required|date',
    'keterangan' => 'nullable|string|max:255',
])->validate();

$trx->update($data);
$trx->refresh();
echo '2. Update valid: jenis = ' . $trx->jenis_transaksi
    . ' | tgl = ' . $trx->tgl_transaksi
    . ' | ket = ' . $trx->keterangan . PHP_EOL;

// ===== 3. Uji validasi tolak data invalid =====
$invalid = Illuminate\Http\Request::create('/transaksi/' . $trx->transaksi_id, 'POST', [
    'peminjaman_id' => 99999,
    'user_id' => $p->user_id,
    'jenis_transaksi' => 'JenisNgawur',
    'tgl_transaksi' => 'bukan-tanggal',
]);
$ok = true;
try {
    validator($invalid->all(), [
        'peminjaman_id' => 'required|exists:peminjaman,peminjaman_id',
        'user_id' => 'required|exists:users,user_id',
        'jenis_transaksi' => 'required|in:Peminjaman,Pengembalian',
        'tgl_transaksi' => 'required|date',
        'keterangan' => 'nullable|string|max:255',
    ])->validate();
    $ok = false;
} catch (Illuminate\Validation\ValidationException $e) {
    $ok = true;
}
echo '3. Data invalid ditolak: ' . ($ok ? 'YA' : 'TIDAK') . PHP_EOL;

// ===== 4. Render index dengan tombol Edit =====
$html2 = view('transaksi.index', [
    'transaksi' => Transaksi::with(['peminjaman.buku.kategori', 'user'])->paginate(10),
    'jenis' => null,
    'search' => null,
])->render();
echo '4. Tombol Edit di index: ' . (str_contains($html2, 'transaksi/' . $trx->transaksi_id . '/edit') ? 'YA' : 'TIDAK') . PHP_EOL;

// ===== 5. Bersihkan fixture =====
$trx->delete();
echo '5. Fixture dihapus. Sisa transaksi: ' . Transaksi::count() . ' (harus tetap 1, milik user asli)' . PHP_EOL;
