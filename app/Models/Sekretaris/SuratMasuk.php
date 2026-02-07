<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TahunKepengurusan;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';
    protected $guarded = [];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun_kepengurusan');
    }

    public function files()
    {
        return $this->morphMany(SuratFile::class, 'suratable');
    }
}
