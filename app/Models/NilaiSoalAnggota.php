<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiSoalAnggota extends Model
{
    use HasFactory;

    protected $table = 'nilai_soal_anggota';
    protected $guarded = [];

    public function bankSoal()
    {
        return $this->belongsTo(BankSoalPertemuan::class, 'id_bank_soal');
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function soalAnggota()
    {
        return $this->hasMany(SoalAnggota::class, 'id_nilai_soal');
    }
}
