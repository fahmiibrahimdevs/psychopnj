<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function suratable()
    {
        return $this->morphTo();
    }
}
