<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpenRecruitment extends Model
{
    use HasFactory;
    
    protected $table = "open_recruitment";
    protected $guarded = [];
    public $timestamps = false;
}
