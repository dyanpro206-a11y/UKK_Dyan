<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed data contoh untuk aplikasi perpustakaan.
     */
    public function run(): void
    {
        // ===== User =====
        $admin = User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'email' => 'admin@gmail.com',
            'nama_lengkap' => 'Administrator Perpustakaan',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'role' => 'admin',
        ]);

        $users = [];
        $users[] = User::create([
            'username' => 'dyann',
            'password' => Hash::make('dyann123'),
            'email' => 'dyann@example.com',
            'nama_lengkap' => 'Dyann Putri Maharani',
            'alamat' => 'Jl. Melati No. 8, Bandung',
            'role' => 'user',
        ]);
        $users[] = User::create([
            'username' => 'budi',
            'password' => Hash::make('budi123'),
            'email' => 'budi@example.com',
            'nama_lengkap' => 'Budi Santoso',
            'alamat' => 'Jl. Kenanga No. 12, Yogyakarta',
            'role' => 'user',
        ]);
        $users[] = User::create([
            'username' => 'siti',
            'password' => Hash::make('siti123'),
            'email' => 'siti@example.com',
            'nama_lengkap' => 'Siti Rahayu',
            'alamat' => 'Jl. Anggrek No. 5, Surabaya',
            'role' => 'user',
        ]);

        // ===== Kategori Buku =====
        $fiksi = KategoriBuku::create(['nama_kategori' => 'Fiksi']);
        $pendidikan = KategoriBuku::create(['nama_kategori' => 'Pendidikan']);
        $teknologi = KategoriBuku::create(['nama_kategori' => 'Teknologi']);
        $sejarah = KategoriBuku::create(['nama_kategori' => 'Sejarah']);
        $anak = KategoriBuku::create(['nama_kategori' => 'Buku Anak']);

        // ===== Buku =====
        $bukuLaskar = Buku::create([
            'kategori_id' => $fiksi->kategori_id,
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'tahun_terbit' => 2005,
        ]);
        $bukuBumi = Buku::create([
            'kategori_id' => $fiksi->kategori_id,
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'penerbit' => 'Hasta Mitra',
            'tahun_terbit' => 1980,
        ]);
        $bukuPemrograman = Buku::create([
            'kategori_id' => $teknologi->kategori_id,
            'judul' => 'Pemrograman Web dengan Laravel',
            'penulis' => 'Rian Firmansyah',
            'penerbit' => 'Informatika',
            'tahun_terbit' => 2021,
        ]);
        $bukuJaringan = Buku::create([
            'kategori_id' => $teknologi->kategori_id,
            'judul' => 'Jaringan Komputer Dasar',
            'penulis' => 'Taufik Hidayat',
            'penerbit' => 'Andi Offset',
            'tahun_terbit' => 2019,
        ]);
        $bukuMatematika = Buku::create([
            'kategori_id' => $pendidikan->kategori_id,
            'judul' => 'Matematika untuk SMA Kelas X',
            'penulis' => 'Sutarto Hp',
            'penerbit' => 'Erlangga',
            'tahun_terbit' => 2017,
        ]);
        $bukuSejarah = Buku::create([
            'kategori_id' => $sejarah->kategori_id,
            'judul' => 'Sejarah Indonesia Modern',
            'penulis' => 'M.C. Ricklefs',
            'penerbit' => 'Serambi',
            'tahun_terbit' => 2008,
        ]);
        $bukuCergam = Buku::create([
            'kategori_id' => $anak->kategori_id,
            'judul' => 'Si Kancil dan Buaya',
            'penulis' => 'Kak Rara',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2015,
        ]);

        // ===== Peminjaman =====
        Peminjaman::create([
            'buku_id' => $bukuLaskar->buku_id,
            'user_id' => $users[0]->user_id,
            'tgl_peminjaman' => now()->subDays(5)->toDateString(),
            'tgl_pengembalian' => now()->addDays(9)->toDateString(),
            'status_peminjaman' => 'Dipinjam',
        ]);
        Peminjaman::create([
            'buku_id' => $bukuPemrograman->buku_id,
            'user_id' => $users[1]->user_id,
            'tgl_peminjaman' => now()->subDays(2)->toDateString(),
            'tgl_pengembalian' => now()->addDays(12)->toDateString(),
            'status_peminjaman' => 'Dipinjam',
        ]);
        Peminjaman::create([
            'buku_id' => $bukuBumi->buku_id,
            'user_id' => $users[2]->user_id,
            'tgl_peminjaman' => now()->subDays(20)->toDateString(),
            'tgl_pengembalian' => now()->subDays(8)->toDateString(),
            'status_peminjaman' => 'Dikembalikan',
        ]);
        Peminjaman::create([
            'buku_id' => $bukuMatematika->buku_id,
            'user_id' => $users[0]->user_id,
            'tgl_peminjaman' => now()->subDays(30)->toDateString(),
            'tgl_pengembalian' => now()->subDays(15)->toDateString(),
            'status_peminjaman' => 'Dikembalikan',
        ]);
    }
}
