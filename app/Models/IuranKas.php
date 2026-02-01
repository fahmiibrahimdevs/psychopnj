<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IuranKas extends Model
{
    protected $table = 'iuran_kas';

    protected $fillable = [
        'id_tahun',
        'id_anggota',
        'periode',
        'nominal',
        'status',
        'tanggal_bayar',
        'id_keuangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function tahunKepengurusan()
    {
        return $this->belongsTo(TahunKepengurusan::class, 'id_tahun');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, 'id_keuangan');
    }
}
