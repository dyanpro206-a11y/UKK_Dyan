<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Peminjaman::first();
if (! $p) {
    echo "Tidak ada data peminjaman untuk uji relasi.\n";
    exit;
}

$t = App\Models\Transaksi::create([
    'peminjaman_id' => $p->peminjaman_id,
    'user_id' => $p->user_id,
    'jenis_transaksi' => 'Peminjaman',
    'tgl_transaksi' => now(),
    'keterangan' => 'tes',
]);

echo 'Dibuat transaksi_id: ' . $t->transaksi_id
    . ' | relasi peminjaman: ' . $t->peminjaman->peminjaman_id
    . ' | relasi user: ' . $t->user->name . PHP_EOL;

$t->delete();
echo 'Data uji dihapus. Sisa transaksi: ' . App\Models\Transaksi::count() . PHP_EOL;
