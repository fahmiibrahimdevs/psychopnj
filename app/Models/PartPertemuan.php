<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartPertemuan extends Model
{
    use HasFactory;
    
    protected $table = 'part_pertemuan';
    protected $guarded = [];
    
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }
    
    public function files()
    {
        return $this->hasMany(PartFile::class, 'id_part');
    }
    
    public function bankSoal()
    {
        return $this->hasOne(BankSoalPertemuan::class, 'id_part');
    }
    
    public function nilaiSoalAnggota()
    {
        return $this->hasMany(NilaiSoalAnggota::class, 'id_part');
    }
}
