<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TahunKepengurusan;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';
    protected $guarded = [];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(\App\Models\TahunKepengurusan::class, 'id_tahun_kepengurusan');
    }

    public function files()
    {
        return $this->morphMany(SuratFile::class, 'suratable');
    }
}
