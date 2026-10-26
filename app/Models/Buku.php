<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai ERD.
     */
    protected $table = 'buku';

    /**
     * Primary key tabel buku.
     */
    protected $primaryKey = 'buku_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kategori_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'cover',
    ];

    /**
     * Relasi: buku dimiliki satu kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Relasi: buku memiliki banyak peminjaman.
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id', 'buku_id');
    }
}