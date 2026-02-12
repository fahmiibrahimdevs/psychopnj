<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDoorLock extends Model
{
    use HasFactory;
    
    protected $table = 'history_door_locks';
    protected $guarded = [];

    protected $casts = [
        'waktu_akses' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
