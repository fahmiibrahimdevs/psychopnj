<?php

namespace App\Livewire\Anggota;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatPresensi extends Component
{
    #[Title('Riwayat Presensi')]

    public $anggota;
    public $filterProgram = '';
    public $presensiList = [];
    public $statistik = [
        'total'     => 0,
        'hadir'     => 0,
        'izin'      => 0,
        'sakit'     => 0,
        'alfa'      => 0,
    ];

    public function mount()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }

        $this->loadPresensi();
    }

    public function updatedFilterProgram()
    {
        $this->loadPresensi();
    }

    public function loadPresensi()
    {
        $query = DB::table('pertemuan')
            ->select(
                'presensi_pertemuan.status',
                'presensi_pertemuan.waktu',
                'pertemuan.judul_pertemuan',
                'pertemuan.pertemuan_ke',
                'pertemuan.tanggal',
                'program_pembelajaran.nama_program'
            )
            ->join('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->leftJoin('presensi_pertemuan', function($join) {
                $join->on('pertemuan.id', '=', 'presensi_pertemuan.id_pertemuan')
                     ->where('presensi_pertemuan.id_anggota', '=', $this->anggota->id);
            })
            ->where('program_pembelajaran.id_tahun', $this->anggota->id_tahun);

        if ($this->filterProgram) {
            $query->where('pertemuan.id_program', $this->filterProgram);
        }

        $this->presensiList = $query->orderBy('pertemuan.tanggal', 'DESC')->get();

        // Calculate statistics
        $now = now()->format('Y-m-d');
        $totalPast = $this->presensiList->filter(function ($item) use ($now) {
            return $item->tanggal <= $now;
        })->count();

        $hadir = $this->presensiList->where('status', 'hadir')->count();

        $this->statistik = [
            'total' => $this->presensiList->count(),
            'hadir' => $hadir,
            'izin'  => $this->presensiList->where('status', 'izin')->count(),
            'sakit' => $this->presensiList->where('status', 'sakit')->count(),
            'alfa'  => $this->presensiList->where('status', 'alfa')->count(),
            'belum_hadir' => $this->presensiList->whereNull('status')->count(),
            'persentase' => $totalPast > 0 ? round(($hadir / $totalPast) * 100) : 0,
        ];
    }

    public function render()
    {
        $programs = DB::table('program_pembelajaran')
            ->where('id_tahun', $this->anggota->id_tahun)
            ->orderBy('nama_program')
            ->get();

        return view('livewire.anggota.riwayat-presensi', [
            'programs' => $programs,
        ]);
    }
}
