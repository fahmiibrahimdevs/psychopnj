<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\NilaiSoalAnggota;
use App\Models\SoalAnggota;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\DB;
use App\Traits\WithPermissionCache;

class StatusAnggotaUjian extends Component
{
    use WithPermissionCache;
    #[Title('Status Anggota Ujian')]

    protected $listeners = ['terapkanAksi'];

    public $id_pertemuan = '0';
    public $pertemuans = [];
    public $data = [];
    public $paksa_selesai = [], $ulang = [];

    public function mount()
    {
        $this->cacheUserPermissions();
        
        $data = DB::table('pertemuan')
            ->select('pertemuan.id', 'pertemuan.judul_pertemuan', 'pertemuan.pertemuan_ke', 'program_pembelajaran.nama_program')
            ->join('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->join('bank_soal_pertemuan', 'bank_soal_pertemuan.id_pertemuan', 'pertemuan.id')
            ->where('pertemuan.has_bank_soal', true)
            ->orderBy('pertemuan.tanggal', 'DESC')
            ->get();

        $this->pertemuans = $data->groupBy('nama_program')->toArray();

        $this->dispatch('initSelect2');
    }

    public function updatedIdPertemuan()
    {
        $this->loadData();
        $this->dispatch('initSelect2');
    }

    public function loadData()
    {
        if ($this->id_pertemuan == '0') {
            $this->data = [];
            return;
        }

        $this->data = DB::table('nilai_soal_anggota')
            ->select(
                'anggota.id as id_anggota',
                'anggota.nama_lengkap',
                'anggota.jurusan_prodi_kelas',
                'anggota.status_anggota',
                'nilai_soal_anggota.status',
                DB::raw("TIME_FORMAT(nilai_soal_anggota.mulai, '%H:%i') as mulai"),
                'nilai_soal_anggota.lama_ujian',
                'sessions.user_id',
            )
            ->leftJoin('anggota', 'anggota.id', 'nilai_soal_anggota.id_anggota')
            ->leftJoin('sessions', 'sessions.user_id', 'anggota.id_user')
            ->where('nilai_soal_anggota.id_pertemuan', $this->id_pertemuan)
            ->distinct()
            ->get()
            ->map(function ($item) {
                $item->lama_ujian = $item->lama_ujian ? $this->formatDuration($item->lama_ujian) : '';
                return $item;
            });
    }

    public function render()
    {
        $this->loadData();
        return view('livewire.akademik.status-anggota-ujian');
    }

    public function terapkanAksiConfirm()
    {
        $this->dispatch('swal:confirm:aksi', [
            'type'    => 'warning',
            'message' => 'Perhatian!',
            'text'    => 'Apakah anda yakin ingin menerapkan aksi?',
        ]);
    }

    public function terapkanAksi()
    {
        try {
            DB::transaction(function () {
                if (!empty($this->paksa_selesai)) {
                    $this->paksaSelesai();
                }

                if (!empty($this->ulang)) {
                    $this->ulangUjian();
                }
            });

            $this->paksa_selesai = [];
            $this->ulang = [];
            $this->dispatchAlert('success', 'Berhasil!', 'Aksi berhasil diterapkan.');
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Gagal!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    private function paksaSelesai()
    {
        $nssIds = NilaiSoalAnggota::whereIn('id_anggota', $this->paksa_selesai)
            ->where([
                ['id_pertemuan', $this->id_pertemuan],
                ['status', '0'],
            ])
            ->pluck('id')
            ->toArray();

        if (!empty($nssIds)) {
            foreach ($nssIds as $idNilaiSoal) {
                $totalPoinPerJenis = DB::table(DB::raw("(SELECT 1 AS jenis_soal UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) AS all_jenis_soal"))
                    ->leftJoin(DB::raw("(SELECT jenis_soal, SUM(point_soal) AS total_point FROM soal_anggota WHERE id_nilai_soal = $idNilaiSoal AND jawaban_alias = jawaban_anggota GROUP BY jenis_soal) AS TotalPoin"), 'all_jenis_soal.jenis_soal', '=', 'TotalPoin.jenis_soal')
                    ->select('all_jenis_soal.jenis_soal', DB::raw('COALESCE(TotalPoin.total_point, 0) AS total_point'))
                    ->orderBy('all_jenis_soal.jenis_soal')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->jenis_soal => $item->total_point];
                    })
                    ->toArray();

                $cekPg = DB::table('soal_anggota')
                    ->selectRaw('SUM(CASE WHEN jawaban_alias = jawaban_anggota THEN 1 ELSE 0 END) AS pg_benar, SUM(CASE WHEN jawaban_alias <> jawaban_anggota THEN 1 ELSE 0 END) AS pg_salah')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', 1)
                    ->first();

                $nilaiSoal = NilaiSoalAnggota::find($idNilaiSoal);
                $lamaUjian = '00:00:00';
                if ($nilaiSoal && $nilaiSoal->mulai) {
                    $waktuMulai = \Carbon\Carbon::parse($nilaiSoal->mulai);
                    $waktuSelesai = \Carbon\Carbon::now();
                    $lamaUjian = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');
                }

                NilaiSoalAnggota::where('id', $idNilaiSoal)
                    ->update([
                        'pg_benar'   => $cekPg->pg_benar ?? 0,
                        'pg_salah'   => $cekPg->pg_salah ?? 0,
                        'nilai_pg'   => $totalPoinPerJenis[1] ?? 0,
                        'nilai_pk'   => $totalPoinPerJenis[2] ?? 0,
                        'nilai_jo'   => $totalPoinPerJenis[3] ?? 0,
                        'nilai_is'   => $totalPoinPerJenis[4] ?? 0,
                        'nilai_es'   => $totalPoinPerJenis[5] ?? 0,
                        'status'     => '1',
                        'dikoreksi'  => '0',
                        'selesai'    => now(),
                        'lama_ujian' => $lamaUjian,
                    ]);
            }
        }
    }

    private function ulangUjian()
    {
        $nss = NilaiSoalAnggota::whereIn('id_anggota', $this->ulang)
            ->where('id_pertemuan', $this->id_pertemuan);

        $idNss = $nss->pluck('id')->toArray();

        SoalAnggota::whereIn('id_nilai_soal', $idNss)->delete();
        $nss->delete();
    }

    public function refresh()
    {
        $this->paksa_selesai = [];
        $this->ulang = [];
        $this->loadData();
    }

    public function formatDuration($duration)
    {
        list($hours, $minutes, $seconds) = explode(':', $duration);
        $hours = (int) $hours;
        $minutes = (int) $minutes;

        $result = [];
        if ($hours > 0) {
            $result[] = "{$hours} j";
        }
        if ($minutes >= 0) {
            $result[] = "{$minutes} mnt";
        }
        return implode(' ', $result);
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type'    => $type,
            'message' => $message,
            'text'    => $text,
        ]);
    }
}
