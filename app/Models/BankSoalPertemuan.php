<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankSoalPertemuan extends Model
{
    use HasFactory;
    
    protected $table = 'bank_soal_pertemuan';
    protected $guarded = [];
    
    public function part()
    {
        return $this->belongsTo(PartPertemuan::class, 'id_part');
    }
    
    public function pertemuan()
    {
        // Accessor to get pertemuan through part
        return $this->hasOneThrough(
            Pertemuan::class,
            PartPertemuan::class,
            'id', // Foreign key on part_pertemuan table
            'id', // Foreign key on pertemuan table
            'id_part', // Local key on bank_soal_pertemuan table
            'id_pertemuan' // Local key on part_pertemuan table
        );
    }
    
    public function soal()
    {
        return $this->hasMany(SoalPertemuan::class, 'id_bank_soal');
    }
}
