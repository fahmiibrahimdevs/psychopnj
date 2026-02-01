<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $table = "projects";
    protected $guarded = [];

    public function leader()
    {
        return $this->belongsTo(Anggota::class, 'id_leader');
    }

    public function tahun()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun');
    }
}
