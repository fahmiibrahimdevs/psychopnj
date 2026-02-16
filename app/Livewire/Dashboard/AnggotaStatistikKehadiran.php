<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;

class AnggotaStatistikKehadiran extends Component
{
    #[Title('Statistik Kehadiran Saya')]

    public $anggota;
    public $statistics = [];
    public $attendanceHistory = [];

    public function mount()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            return;
        }

        $this->loadStatistics();
    }

    private function loadStatistics()
    {
        // Get all relevant meetings for the anggota
        $pertemuans = DB::table('pertemuan')
            ->join('program_pembelajaran', 'pertemuan.id_program', '=', 'program_pembelajaran.id')
            ->where('program_pembelajaran.id_tahun', $this->anggota->id_tahun)
            ->where('pertemuan.tanggal', '<=', now()->toDateString())
            ->select('pertemuan.id', 'pertemuan.jenis_presensi', 'pertemuan.judul_pertemuan', 'pertemuan.pertemuan_ke', 'pertemuan.tanggal', 'program_pembelajaran.nama_program')
            ->get()
            ->keyBy('id');

        // Filter only meetings where this anggota should attend based on their status
        $relevantPertemuans = $pertemuans->filter(function($p) {
            $jenis = $p->jenis_presensi ? explode(',', $p->jenis_presensi) : ['pengurus', 'anggota'];
            return in_array($this->anggota->status_anggota, $jenis);
        });

        $totalPertemuanWajib = $relevantPertemuans->count();
        $relevantPertemuanIds = $relevantPertemuans->keys()->toArray();

        // Get attendance records for this anggota
        $presensiData = DB::table('presensi_pertemuan')
            ->where('id_anggota', $this->anggota->id)
            ->whereIn('id_pertemuan', $relevantPertemuanIds)
            ->select('id_pertemuan', 'status', 'waktu', 'metode')
            ->get()
            ->keyBy('id_pertemuan');

        // Calculate statistics
        $totalHadir = $presensiData->where('status', 'hadir')->count();
        $totalIzin = $presensiData->where('status', 'izin')->count();
        $totalSakit = $presensiData->where('status', 'sakit')->count();
        $totalAlfa = $presensiData->where('status', 'alfa')->count();

        // Calculate missing meetings (no record)
        $attendedMeetingIds = $presensiData->keys()->toArray();
        $missingCount = 0;
        
        foreach ($relevantPertemuans as $rp) {
            if (!in_array($rp->id, $attendedMeetingIds)) {
                $missingCount++;
            }
        }

        $totalAlfaCombined = $totalAlfa + $missingCount;
        $percentage = $totalPertemuanWajib > 0 ? ($totalHadir / $totalPertemuanWajib) * 100 : 0;

        $this->statistics = [
            'hadir' => $totalHadir,
            'izin' => $totalIzin,
            'sakit' => $totalSakit,
            'alfa' => $totalAlfaCombined,
            'total_wajib' => $totalPertemuanWajib,
            'percentage' => round($percentage, 0)
        ];

        // Build attendance history grouped by program
        $history = [];
        
        foreach ($relevantPertemuans as $rp) {
            $status = 'tanpa_keterangan';
            $waktu = null;
            $metode = null;
            
            if (isset($presensiData[$rp->id])) {
                $status = $presensiData[$rp->id]->status;
                $waktu = $presensiData[$rp->id]->waktu;
                $metode = $presensiData[$rp->id]->metode;
            }
            
            if (!isset($history[$rp->nama_program])) {
                $history[$rp->nama_program] = [];
            }
            
            $history[$rp->nama_program][] = [
                'pertemuan' => $rp->judul_pertemuan . ' (Ke-' . $rp->pertemuan_ke . ')',
                'tanggal' => $rp->tanggal,
                'status' => $status,
                'waktu' => $waktu,
                'metode' => $metode,
                'order' => $rp->pertemuan_ke
            ];
        }

        // Sort each program's meetings by order
        foreach ($history as $program => $meetings) {
            usort($history[$program], function($a, $b) {
                return $a['order'] <=> $b['order'];
            });
        }

        $this->attendanceHistory = $history;
    }

    public function render()
    {
        return view('livewire.dashboard.anggota-statistik-kehadiran');
    }
}
