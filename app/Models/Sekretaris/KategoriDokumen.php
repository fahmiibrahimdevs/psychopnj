<?php

namespace App\Models\Sekretaris;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriDokumen extends Model
{
    use HasFactory;

    protected $table = 'kategori_dokumen';
    protected $guarded = [];

    public function dokumen()
    {
        return $this->hasMany(DokumenOrganisasi::class, 'id_kategori_dokumen');
    }
}
