<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartFile extends Model
{
    use HasFactory;
    
    protected $table = 'part_file';
    protected $guarded = [];
    
    public function part()
    {
        return $this->belongsTo(PartPertemuan::class, 'id_part');
    }
}
