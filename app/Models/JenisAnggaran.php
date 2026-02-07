<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisAnggaran extends Model
{
    protected $table = 'jenis_anggaran';
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori',
        'nama_jenis',
    ];
}
