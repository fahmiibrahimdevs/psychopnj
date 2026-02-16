<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\NilaiSoalAnggota;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\DB;
use App\Traits\WithPermissionCache;

class HasilUjianPertemuan extends Component
{
    use WithPermissionCache;
    #[Title('Hasil Ujian Pertemuan')]

    public $id_part = '0';
    public $parts = [];
    public $hasil_ujian = [];
    public $partInfo;
    public $nilais = [], $inputan = [];

    public function mount()
    {
        $this->cacheUserPermissions();
        
        $data = DB::table('part_pertemuan')
            ->select(
                'part_pertemuan.id',
                'part_pertemuan.urutan',
                'part_pertemuan.nama_part',
                'pertemuan.judul_pertemuan',
                'pertemuan.pertemuan_ke',
                'program_pembelajaran.nama_program'
            )
            ->join('pertemuan', 'pertemuan.id', 'part_pertemuan.id_pertemuan')
            ->join('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->join('bank_soal_pertemuan', 'bank_soal_pertemuan.id_part', 'part_pertemuan.id')
            ->orderBy('pertemuan.tanggal', 'DESC')
            ->orderBy('part_pertemuan.urutan', 'ASC')
            ->get()
            ->map(function($item) {
                $item->display_name = "Pertemuan {$item->pertemuan_ke} - Part {$item->urutan}: {$item->nama_part}";
                return $item;
            });

        $this->parts = $data->groupBy('nama_program')->toArray();

        $this->dispatch('initSelect2');
    }

    public function updatedIdPart()
    {
        $this->loadHasil();
        $this->dispatch('initSelect2');
    }

    public function loadHasil()
    {
        if ($this->id_part == '0') {
            $this->hasil_ujian = [];
            $this->partInfo = null;
            return;
        }

        $this->partInfo = DB::table('part_pertemuan')
            ->select(
                'part_pertemuan.urutan',
                'part_pertemuan.nama_part',
                'pertemuan.judul_pertemuan',
                'pertemuan.nama_pemateri',
                'pertemuan.pertemuan_ke',
                DB::raw("(COALESCE(bank_soal_pertemuan.jml_pg, 0) + COALESCE(bank_soal_pertemuan.jml_kompleks, 0) + COALESCE(bank_soal_pertemuan.jml_jodohkan, 0) + COALESCE(bank_soal_pertemuan.jml_isian, 0) + COALESCE(bank_soal_pertemuan.jml_esai, 0)) as total_soal")
            )
            ->join('pertemuan', 'pertemuan.id', 'part_pertemuan.id_pertemuan')
            ->join('bank_soal_pertemuan', 'bank_soal_pertemuan.id_part', 'part_pertemuan.id')
            ->where('part_pertemuan.id', $this->id_part)
            ->first();

        $this->hasil_ujian = DB::table('nilai_soal_anggota')
            ->select(
                'nilai_soal_anggota.id_part',
                'anggota.id as id_anggota',
                'anggota.nama_lengkap',
                'anggota.jurusan_prodi_kelas',
                'anggota.status_anggota',
                DB::raw("TIME_FORMAT(nilai_soal_anggota.mulai, '%H:%i') as mulai"),
                'nilai_soal_anggota.lama_ujian',
                'nilai_soal_anggota.status',
                'nilai_soal_anggota.pg_benar',
                'nilai_soal_anggota.pg_salah',
                'nilai_soal_anggota.nilai_pg',
                'nilai_soal_anggota.nilai_pk',
                'nilai_soal_anggota.nilai_jo',
                'nilai_soal_anggota.nilai_is',
                'nilai_soal_anggota.nilai_es',
                'nilai_soal_anggota.dikoreksi',
            )
            ->leftJoin('anggota', 'anggota.id', 'nilai_soal_anggota.id_anggota')
            ->where('nilai_soal_anggota.id_part', $this->id_part)
            ->orderBy('anggota.nama_lengkap')
            ->get()
            ->map(function ($item) {
                $item->lama_ujian = $item->lama_ujian ? $this->formatDuration($item->lama_ujian) : '';
                $item->total_nilai = ($item->nilai_pg ?? 0) + ($item->nilai_pk ?? 0) + ($item->nilai_jo ?? 0) + ($item->nilai_is ?? 0) + ($item->nilai_es ?? 0);
                return $item;
            });
    }

    public function tandaiSemua()
    {
        try {
            NilaiSoalAnggota::where('id_part', $this->id_part)->update(['dikoreksi' => '1']);
            $this->loadHasil();
            $this->dispatchAlert('success', 'Berhasil!', 'Semua anggota sudah ditandai sebagai dikoreksi.');
        } catch (\Exception $e) {
            $this->dispatchAlert('warning', 'Gagal!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function inputNilai()
    {
        $this->nilais = NilaiSoalAnggota::select(
                'anggota.id',
                'anggota.nama_lengkap',
                'nilai_soal_anggota.nilai_pg',
                'nilai_soal_anggota.nilai_pk',
                'nilai_soal_anggota.nilai_jo',
                'nilai_soal_anggota.nilai_is',
                'nilai_soal_anggota.nilai_es',
            )
            ->leftJoin('anggota', 'anggota.id', 'nilai_soal_anggota.id_anggota')
            ->where('id_part', $this->id_part)
            ->get()
            ->map(function ($nilai) {
                $this->inputan[$nilai->id] = [
                    'nilai_pk' => $nilai->nilai_pk,
                    'nilai_jo' => $nilai->nilai_jo,
                    'nilai_is' => $nilai->nilai_is,
                    'nilai_es' => $nilai->nilai_es,
                ];
                return $nilai;
            });
    }

    public function updateNilai()
    {
        DB::beginTransaction();
        try {
            foreach ($this->inputan as $anggotaId => $nilaiData) {
                NilaiSoalAnggota::updateOrCreate(
                    ['id_anggota' => $anggotaId, 'id_part' => $this->id_part],
                    $nilaiData
                );
            }
            DB::commit();
            $this->loadHasil();
            $this->dispatchAlert('success', 'Berhasil!', 'Data nilai berhasil diubah.');
            $this->inputan = [];
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('warning', 'Gagal!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
            $this->inputan = [];
        }
    }

    public function refresh()
    {
        $this->loadHasil();
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

    public function render()
    {
        return view('livewire.akademik.hasil-ujian-pertemuan');
    }
}
