<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoalAnggota extends Model
{
    use HasFactory;

    protected $table = 'soal_anggota';
    protected $guarded = [];

    public function nilaiSoal()
    {
        return $this->belongsTo(NilaiSoalAnggota::class, 'id_nilai_soal');
    }

    public function soalPertemuan()
    {
        return $this->belongsTo(SoalPertemuan::class, 'id_soal');
    }
}
