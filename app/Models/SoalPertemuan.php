<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoalPertemuan extends Model
{
    use HasFactory;
    
    protected $table = 'soal_pertemuan';
    protected $guarded = [];
    
    public function bankSoal()
    {
        return $this->belongsTo(BankSoalPertemuan::class, 'id_bank_soal');
    }
}
