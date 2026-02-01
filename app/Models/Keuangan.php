<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';

    protected $fillable = [
        'id_tahun',
        'tanggal',
        'jenis',
        'kategori',
        'id_department',
        'id_project',
        'deskripsi',
        'nominal',
        'bukti',
        'id_user',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'id_project');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function iuranKas()
    {
        return $this->hasMany(IuranKas::class, 'id_keuangan');
    }
}
