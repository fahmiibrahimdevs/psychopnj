<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pertemuan extends Model
{
    use HasFactory;
    protected $table = "pertemuan";
    protected $guarded = [];

    public function galeri()
    {
        return $this->hasMany(PertemuanGaleri::class, 'id_pertemuan');
    }

    public function program()
    {
        return $this->belongsTo(ProgramKegiatan::class, 'id_program');
    }
}
