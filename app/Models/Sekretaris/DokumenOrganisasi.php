<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'dokumen_organisasi';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(KategoriDokumen::class, 'id_kategori_dokumen');
    }

    public function tahunKepengurusan()
    {
        return $this->belongsTo(\App\Models\TahunKepengurusan::class, 'id_tahun_kepengurusan');
    }

    public function files()
    {
        return $this->morphMany(SuratFile::class, 'suratable');
    }
}
