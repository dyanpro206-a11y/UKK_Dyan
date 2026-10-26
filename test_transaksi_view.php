<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Peminjaman::first();
if (! $p) {
    echo "Tidak ada data peminjaman untuk uji.\n";
    exit;
}

$t = App\Models\Transaksi::create([
    'peminjaman_id' => $p->peminjaman_id,
    'user_id' => $p->user_id,
    'jenis_transaksi' => 'Peminjaman',
    'tgl_transaksi' => now(),
    'keterangan' => 'Uji render halaman transaksi.',
]);

$html = view('transaksi.index', [
    'transaksi' => App\Models\Transaksi::with(['peminjaman.buku.kategori', 'user'])
        ->paginate(10),
    'jenis' => null,
    'search' => null,
])->render();

echo 'Halaman ter-render: ' . strlen($html) . ' karakter.' . PHP_EOL;
echo 'Berisi kode TRX: ' . (str_contains($html, 'TRX-') ? 'YA' : 'TIDAK') . PHP_EOL;
echo 'Berisi menu Transaksi di sidebar: ' . (str_contains($html, '>Transaksi</a>') ? 'YA' : 'TIDAK') . PHP_EOL;

$t->delete();
echo 'Data uji dihapus. Sisa transaksi: ' . App\Models\Transaksi::count() . PHP_EOL;
