<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{
    use HasFactory;
    protected $table = "anggota";
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }
}