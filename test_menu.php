<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Illuminate\Support\Facades\View::share('errors', new Illuminate\Support\ViewErrorBag);
Illuminate\Support\Facades\Auth::login(App\Models\User::first());
$h = view('layouts.app')->render();

echo (str_contains($h, route('transaksi.index')) ? 'Link menu transaksi ADA: ' . route('transaksi.index') : 'Link menu transaksi TIDAK ADA') . PHP_EOL;
echo (str_contains($h, '> Transaksi</a>') ? 'Teks menu "Transaksi" ADA' : 'Teks menu "Transaksi" TIDAK ADA') . PHP_EOL;
