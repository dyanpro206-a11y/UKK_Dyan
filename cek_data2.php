<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\Transaksi::with('peminjaman.buku', 'user')->get() as $t) {
    echo "TRX #{$t->transaksi_id} [{$t->jenis_transaksi}] buku={$t->peminjaman?->buku?->judul} user={$t->user?->username} peminjaman_ada=" . (App\Models\Peminjaman::find($t->peminjaman_id) ? 'YA' : 'TIDAK') . " ket={$t->keterangan}" . PHP_EOL;
}
