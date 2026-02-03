<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    protected $table = 'peminjaman_barang';
    
    protected $fillable = [
        'tahun_kepengurusan_id',
        'pencatat_id',
        'nama_peminjam',
        'kontak_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan',
        'status',
        'catatan',
        'id_user',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'tahun_kepengurusan_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(Anggota::class, 'pencatat_id');
    }

    public function details()
    {
        return $this->hasMany(PeminjamanBarangDetail::class, 'peminjaman_barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
