<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranKasPeriode extends Model
{
    use HasFactory;

    protected $table = 'iuran_kas_periode';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function tahun()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun');
    }
}
