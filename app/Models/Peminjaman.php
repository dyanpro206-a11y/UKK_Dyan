<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai ERD.
     */
    protected $table = 'peminjaman';

    /**
     * Primary key tabel peminjaman.
     */
    protected $primaryKey = 'peminjaman_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buku_id',
        'user_id',
        'tgl_peminjaman',
        'tgl_pengembalian',
        'status_peminjaman',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_peminjaman' => 'date',
        'tgl_pengembalian' => 'date',
    ];

    /**
     * Relasi: peminjaman dimiliki satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi: buku memiliki banyak peminjaman.
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'buku_id');
    }

    /**
     * Relasi: peminjaman memiliki banyak transaksi.
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'peminjaman_id', 'peminjaman_id');
    }
}