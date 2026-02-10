<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankSoalPertemuan extends Model
{
    use HasFactory;
    
    protected $table = 'bank_soal_pertemuan';
    protected $guarded = [];
    
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }
    
    public function soal()
    {
        return $this->hasMany(SoalPertemuan::class, 'id_bank_soal');
    }
}
