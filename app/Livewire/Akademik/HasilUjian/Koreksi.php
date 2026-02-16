<?php

namespace App\Livewire\Akademik\HasilUjian;

use App\Models\NilaiSoalAnggota;
use App\Models\SoalAnggota;
use Exception;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Traits\WithPermissionCache;

class Koreksi extends Component
{
    use WithPermissionCache;
    #[Title('Koreksi')]

    protected $listeners = ['updatePoint' => 'updateP'];

    public $id_part, $id_anggota;
    public $detail, $soals;
    public $point = [];

    public function mount($id_part = '0', $id_anggota = '0')
    {
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        try {
            $this->id_part = $id_part;
            $this->id_anggota = $id_anggota;

            $this->detailData();
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function updateP()
    {
        $id = key($this->point);
        $value = reset($this->point);

        $soalAnggota = SoalAnggota::select('id_nilai_soal', 'jenis_soal', 'nilai_koreksi')
            ->where('id', $id)
            ->first();

        if (!$soalAnggota) {
            return;
        }

        $nsa = NilaiSoalAnggota::select('nilai_pk', 'nilai_jo', 'nilai_is', 'nilai_es')
            ->where('id', $soalAnggota->id_nilai_soal)
            ->first();

        if (!$nsa) {
            return;
        }

        $kolom = "";
        $valueNSA = 0;

        switch ($soalAnggota->jenis_soal) {
            case "2":
                $kolom = "nilai_pk";
                $valueNSA = $nsa->nilai_pk;
                break;
            case "3":
                $kolom = "nilai_jo";
                $valueNSA = $nsa->nilai_jo;
                break;
            case "4":
                $kolom = "nilai_is";
                $valueNSA = $nsa->nilai_is;
                break;
            case "5":
                $kolom = "nilai_es";
                $valueNSA = $nsa->nilai_es;
                break;
            default:
                return;
        }

        $totalAlt = (float)$value - (float)$soalAnggota->nilai_koreksi;

        if ((float)$value >= 0) {
            NilaiSoalAnggota::where('id', $soalAnggota->id_nilai_soal)
                ->update([$kolom => (float)$valueNSA + $totalAlt]);
            $this->point = [];
            $this->dispatchAlert('success', 'Berhasil!', 'Poin berhasil diubah.');
        } else {
            $this->point = [];
            $this->dispatchAlert('warning', 'Perhatian!', 'Nilai tidak boleh minus!');
            return;
        }

        SoalAnggota::where('id', $id)
            ->update(['nilai_koreksi' => $value, 'nilai_otomatis' => '1']);
    }

    public function sudahDikoreksi()
    {
        try {
            $nsa = NilaiSoalAnggota::where([
                ['id_part', $this->id_part],
                ['id_anggota', $this->id_anggota],
            ])->update(["dikoreksi" => "1"]);

            if ($nsa) {
                $this->dispatchAlert('success', 'Berhasil!', 'Anggota telah ditandai dikoreksi.');
            } else {
                $this->dispatchAlert('warning', 'Gagal!', 'Kesalahan di database, hubungi programmer.');
            }
        } catch (Exception $e) {
            $this->dispatchAlert('warning', 'Gagal!', $e->getMessage());
        }
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type'    => $type,
            'message' => $message,
            'text'    => $text,
        ]);
    }

    public function detailData()
    {
        $this->detail = DB::table('nilai_soal_anggota')
            ->select(
                'nilai_soal_anggota.id as id_nsa',
                'bank_soal_pertemuan.opsi',
                'anggota.nama_lengkap',
                'anggota.jurusan_prodi_kelas',
                'anggota.status_anggota',
                'program_pembelajaran.nama_program',
                'pertemuan.judul_pertemuan',
                'pertemuan.pertemuan_ke',
                'pertemuan.nama_pemateri',
                'part_pertemuan.urutan as part_urutan',
                'part_pertemuan.nama_part',
                'nilai_soal_anggota.nilai_pg',
                'nilai_soal_anggota.nilai_pk',
                'nilai_soal_anggota.nilai_jo',
                'nilai_soal_anggota.nilai_is',
                'nilai_soal_anggota.nilai_es',
                'nilai_soal_anggota.dikoreksi',
            )
            ->leftJoin('anggota', 'anggota.id', 'nilai_soal_anggota.id_anggota')
            ->leftJoin('part_pertemuan', 'part_pertemuan.id', 'nilai_soal_anggota.id_part')
            ->leftJoin('pertemuan', 'pertemuan.id', 'part_pertemuan.id_pertemuan')
            ->leftJoin('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->leftJoin('bank_soal_pertemuan', 'bank_soal_pertemuan.id_part', 'part_pertemuan.id')
            ->where([
                ['nilai_soal_anggota.id_part', $this->id_part],
                ['nilai_soal_anggota.id_anggota', $this->id_anggota],
            ])
            ->first();

        if (!$this->detail) {
            return;
        }

        $this->soals = DB::table('soal_anggota')
            ->select(
                'soal_anggota.id',
                'soal_anggota.jenis_soal',
                'soal_pertemuan.soal',
                'soal_anggota.no_soal_alias',
                'soal_anggota.opsi_alias_a',
                'soal_anggota.opsi_alias_b',
                'soal_anggota.opsi_alias_c',
                'soal_anggota.opsi_alias_d',
                'soal_anggota.opsi_alias_e',
                'soal_anggota.jawaban_alias',
                'soal_anggota.jawaban_anggota',
                'soal_anggota.ragu',
                'soal_pertemuan.opsi_a',
                'soal_pertemuan.opsi_b',
                'soal_pertemuan.opsi_c',
                'soal_pertemuan.opsi_d',
                'soal_pertemuan.opsi_e',
                'soal_anggota.point_soal',
                'soal_anggota.point_essai',
                'soal_anggota.nilai_koreksi',
                'soal_anggota.nilai_otomatis',
            )
            ->leftJoin('soal_pertemuan', 'soal_pertemuan.id', 'soal_anggota.id_soal')
            ->where('soal_anggota.id_nilai_soal', $this->detail->id_nsa)
            ->orderBy('soal_anggota.no_soal_alias')
            ->get();

        foreach ($this->soals as &$item) {
            $optionsMap = [
                'A' => $item->opsi_a,
                'B' => $item->opsi_b,
                'C' => $item->opsi_c,
                'D' => $item->opsi_d,
                'E' => $item->opsi_e,
            ];

            $item->opsi_alias_a = $optionsMap[$item->opsi_alias_a] ?? null;
            $item->opsi_alias_b = $optionsMap[$item->opsi_alias_b] ?? null;
            $item->opsi_alias_c = $optionsMap[$item->opsi_alias_c] ?? null;
            $item->opsi_alias_d = $optionsMap[$item->opsi_alias_d] ?? null;
            $item->opsi_alias_e = $optionsMap[$item->opsi_alias_e] ?? null;
        }
    }

    public function render()
    {
        $this->detailData();

        return view('livewire.akademik.hasil-ujian.koreksi');
    }
}
