<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    
    protected $fillable = [
        'nama',
        'id_user',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
