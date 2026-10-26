<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\Transaksi::with('peminjaman.buku', 'user')->get() as $t) {
    echo "TRX #{$t->transaksi_id} [{$t->jenis_transaksi}] buku={$t->peminjaman?->buku?->judul} user={$t->user?->username} tgl={$t->tgl_transaksi} ket={$t->keterangan}" . PHP_EOL;
}
echo 'Total peminjaman: ' . App\Models\Peminjaman::count() . PHP_EOL;
echo 'Peminjaman Dipinjam: ' . App\Models\Peminjaman::where('status_peminjaman', 'Dipinjam')->count() . PHP_EOL;
echo 'Total buku: ' . App\Models\Buku::count() . PHP_EOL;
echo 'Total user: ' . App\Models\User::count() . PHP_EOL;
