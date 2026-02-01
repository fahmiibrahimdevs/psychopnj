<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranKasPeriode extends Model
{
    use HasFactory;

    protected $table = 'iuran_kas_periodes';
    protected $guarded = ['id'];

    public function tahun()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun');
    }
}
