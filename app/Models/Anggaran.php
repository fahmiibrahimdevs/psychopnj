<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    protected $table = 'anggaran';

    protected $fillable = [
        'id_tahun',
        'kategori',
        'jenis',
        'id_department',
        'id_project',
        'nama',
        'nominal',
        'id_user',
    ];

    protected $casts = [
        'nominal' => 'integer',
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
}
