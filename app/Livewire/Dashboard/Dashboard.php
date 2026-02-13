<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Anggota;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    #[Title('Dashboard')]

    // Anggota dashboard properties
    public $anggota;
    public $totalPertemuan = 0;
    public $persentaseHadir = 0;
    public $ujianSelesai = 0;
    public $iuranLunas = 0;
    public $iuranTotal = 0;
    public $pertemuanMendatang = [];
    public $hasilUjianTerakhir = [];
    
    public function render()
    {
        $user = User::find(Auth::user()->id);
        
        if($user->hasRole(['chairman', 'admin_media', 'admin_pengajaran', 'admin_keuangan', 'admin_inventaris', 'admin_sekretaris', 'admin_project', 'super_admin'])) {
            return view('livewire.dashboard.dashboard-pengurus');
        } else if ($user->hasRole('anggota')) {
            $this->loadAnggotaDashboard();
            return view('livewire.dashboard.dashboard-anggota');
        }

        return view('livewire.dashboard.dashboard');
    }

    private function loadAnggotaDashboard()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            return;
        }

        // Total pertemuan yang sudah berlangsung
        $this->totalPertemuan = DB::table('pertemuan')
            ->join('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->where('program_pembelajaran.id_tahun', $this->anggota->id_tahun)
            ->where('program_pembelajaran.untuk_anggota', true)
            ->where('pertemuan.tanggal', '<=', now()->toDateString())
            ->count();

        // Persentase kehadiran
        $totalPresensi = DB::table('presensi_pertemuan')
            ->where('id_anggota', $this->anggota->id)
            ->count();
        $hadirCount = DB::table('presensi_pertemuan')
            ->where('id_anggota', $this->anggota->id)
            ->where('status', 'hadir')
            ->count();
        $this->persentaseHadir = $totalPresensi > 0 ? round(($hadirCount / $totalPresensi) * 100) : 0;

        // Ujian selesai
        $this->ujianSelesai = DB::table('nilai_soal_anggota')
            ->where('id_anggota', $this->anggota->id)
            ->where('status', '1')
            ->count();

        // Iuran kas
        $this->iuranTotal = DB::table('iuran_kas_periode')
            ->where('id_tahun', $this->anggota->id_tahun)
            ->count();
        $this->iuranLunas = DB::table('iuran_kas')
            ->where('id_anggota', $this->anggota->id)
            ->where('id_tahun', $this->anggota->id_tahun)
            ->where('status', 'lunas')
            ->count();

        // Pertemuan mendatang (max 5)
        $this->pertemuanMendatang = DB::table('pertemuan')
            ->select('pertemuan.judul_pertemuan', 'pertemuan.pertemuan_ke', 'pertemuan.tanggal', 'program_pembelajaran.nama_program')
            ->join('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->where('program_pembelajaran.id_tahun', $this->anggota->id_tahun)
            ->where('program_pembelajaran.untuk_anggota', true)
            ->where('pertemuan.tanggal', '>=', now()->toDateString())
            ->orderBy('pertemuan.tanggal')
            ->limit(5)
            ->get();

        // Hasil ujian terakhir (max 5)
        $this->hasilUjianTerakhir = DB::table('nilai_soal_anggota')
            ->select(
                'pertemuan.judul_pertemuan',
                'pertemuan.pertemuan_ke',
                'nilai_soal_anggota.nilai_pg',
                'nilai_soal_anggota.nilai_pk',
                'nilai_soal_anggota.nilai_jo',
                'nilai_soal_anggota.nilai_is',
                'nilai_soal_anggota.nilai_es',
                'nilai_soal_anggota.status'
            )
            ->join('pertemuan', 'pertemuan.id', 'nilai_soal_anggota.id_pertemuan')
            ->where('nilai_soal_anggota.id_anggota', $this->anggota->id)
            ->orderByDesc('nilai_soal_anggota.created_at')
            ->limit(5)
            ->get();
    }
}
