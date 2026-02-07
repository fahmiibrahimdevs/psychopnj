<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramKegiatan extends Model
{
    use HasFactory;
    protected $table = "program_pembelajaran";
    protected $guarded = [];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun');
    }
}
