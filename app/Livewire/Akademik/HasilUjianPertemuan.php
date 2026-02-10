<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\NilaiSoalAnggota;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\DB;

class HasilUjianPertemuan extends Component
{
    #[Title('Hasil Ujian Pertemuan')]

    public $id_pertemuan = '0';
    public $pertemuans = [];
    public $hasil_ujian = [];
    public $pertemuanInfo;
    public $nilais = [], $inputan = [];

    public function mount()
    {
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
        $this->loadHasil();
        $this->dispatch('initSelect2');
    }

    public function loadHasil()
    {
        if ($this->id_pertemuan == '0') {
            $this->hasil_ujian = [];
            $this->pertemuanInfo = null;
            return;
        }

        $this->pertemuanInfo = DB::table('pertemuan')
            ->select(
                'pertemuan.judul_pertemuan',
                'pertemuan.nama_pemateri',
                'pertemuan.pertemuan_ke',
                DB::raw("(COALESCE(bank_soal_pertemuan.jml_pg, 0) + COALESCE(bank_soal_pertemuan.jml_kompleks, 0) + COALESCE(bank_soal_pertemuan.jml_jodohkan, 0) + COALESCE(bank_soal_pertemuan.jml_isian, 0) + COALESCE(bank_soal_pertemuan.jml_esai, 0)) as total_soal")
            )
            ->join('bank_soal_pertemuan', 'bank_soal_pertemuan.id_pertemuan', 'pertemuan.id')
            ->where('pertemuan.id', $this->id_pertemuan)
            ->first();

        $this->hasil_ujian = DB::table('nilai_soal_anggota')
            ->select(
                'nilai_soal_anggota.id_pertemuan',
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
            ->where('nilai_soal_anggota.id_pertemuan', $this->id_pertemuan)
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
            NilaiSoalAnggota::where('id_pertemuan', $this->id_pertemuan)->update(['dikoreksi' => '1']);
            $this->loadHasil();
            $this->dispatchAlert('success', 'Berhasil!', 'Semua anggota sudah ditandai sebagai dikoreksi.');
        } catch (\Exception $e) {
            $this->dispatchAlert('warning', 'Gagal!', 'Kesalahan: ' . $e->getMessage());
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
            ->where('id_pertemuan', $this->id_pertemuan)
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
        try {
            foreach ($this->inputan as $anggotaId => $nilaiData) {
                NilaiSoalAnggota::updateOrCreate(
                    ['id_anggota' => $anggotaId, 'id_pertemuan' => $this->id_pertemuan],
                    $nilaiData
                );
            }
            $this->loadHasil();
            $this->dispatchAlert('success', 'Berhasil!', 'Data nilai berhasil diubah.');
            $this->inputan = [];
        } catch (\Exception $e) {
            $this->dispatchAlert('warning', 'Gagal!', 'Kesalahan: ' . $e->getMessage());
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
