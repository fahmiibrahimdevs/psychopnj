<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PertemuanGaleri extends Model
{
    use HasFactory;
    
    protected $table = "pertemuan_galeri";
    protected $guarded = [];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }
}
