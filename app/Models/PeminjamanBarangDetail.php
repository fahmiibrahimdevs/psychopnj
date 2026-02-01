<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanBarangDetail extends Model
{
    protected $table = 'peminjaman_barang_detail';
    
    protected $fillable = [
        'peminjaman_barang_id',
        'barang_id',
        'jumlah',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanBarang::class, 'peminjaman_barang_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
