<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengadaanBarang extends Model
{
    protected $table = 'pengadaan_barang';
    
    protected $fillable = [
        'tahun_kepengurusan_id',
        'department_id',
        'project_id',
        'pengusul_id',
        'keuangan_id',
        'nama_barang',
        'jumlah',
        'harga',
        'total',
        'link_pembelian',
        'status',
        'catatan',
        'id_user',
    ];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'tahun_kepengurusan_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function pengusul()
    {
        return $this->belongsTo(Anggota::class, 'pengusul_id');
    }

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, 'keuangan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Auto calculate total
    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total = $model->jumlah * $model->harga;
        });
    }
}
