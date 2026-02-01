<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs';
    
    protected $fillable = [
        'kategori_barang_id',
        'kode',
        'nama',
        'nama_barang',
        'jumlah',
        'satuan',
        'jenis',
        'kondisi',
        'lokasi',
        'foto',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }

    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanBarangDetail::class, 'barang_id');
    }

    // Hitung jumlah yang sedang dipinjam
    public function getJumlahDipinjamAttribute()
    {
        return $this->peminjamanDetails()
            ->whereHas('peminjaman', function ($query) {
                $query->where('status', 'dipinjam');
            })
            ->sum('jumlah');
    }

    // Hitung stok tersedia
    public function getStokTersediaAttribute()
    {
        return $this->jumlah - $this->jumlah_dipinjam;
    }

    // Generate kode otomatis
    public static function generateKode()
    {
        $lastBarang = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastBarang ? intval(substr($lastBarang->kode, 4)) : 0;
        return 'PLG-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}
