<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresensiPertemuan extends Model
{
    use HasFactory;
    protected $table = "presensi_pertemuan";
    protected $guarded = [];
}
