<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai ERD.
     */
    protected $table = 'kategori_buku';

    /**
     * Primary key tabel kategori_buku.
     */
    protected $primaryKey = 'kategori_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi: kategori_buku memiliki banyak buku.
     */
    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id', 'kategori_id');
    }
}