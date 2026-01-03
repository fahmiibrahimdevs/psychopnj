<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunKepengurusan extends Model
{
    use HasFactory;
    protected $table = "tahun_kepengurusan";
    protected $guarded = [];
    
    public $timestamps = false;

    public function ketua()
    {
        return $this->hasOne(Anggota::class, 'id_tahun', 'id')
            ->where('nama_jabatan', 'Ketua')
            ->where('status_aktif', 'aktif');
    }

    public function wakilKetua()
    {
        return $this->hasOne(Anggota::class, 'id_tahun', 'id')
            ->where('nama_jabatan', 'Wakil Ketua')
            ->where('status_aktif', 'aktif');
    }
}
