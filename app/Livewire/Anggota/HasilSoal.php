<?php

namespace App\Livewire\Anggota;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use App\Models\NilaiSoalAnggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HasilSoal extends Component
{
    #[Title('Hasil Soal Saya')]

    public $anggota;
    public $detailSoal = [];
    public $selectedHasil;

    public function mount()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }
    }

    public function showDetail($id)
    {
        $this->selectedHasil = NilaiSoalAnggota::find($id);

        if (!$this->selectedHasil || $this->selectedHasil->id_anggota != $this->anggota->id) {
            return;
        }

        $soalAnggotaList = \App\Models\SoalAnggota::select([
                'soal_anggota.*',
                'soal_pertemuan.soal',
                'soal_pertemuan.jenis',
                'soal_pertemuan.jawaban as kunci_jawaban',
                'soal_pertemuan.opsi_a',
                'soal_pertemuan.opsi_b',
                'soal_pertemuan.opsi_c',
                'soal_pertemuan.opsi_d',
                'soal_pertemuan.opsi_e',
            ])
            ->join('soal_pertemuan', 'soal_anggota.id_soal', '=', 'soal_pertemuan.id')
            ->where('soal_anggota.id_nilai_soal', $id)
            ->orderBy('soal_anggota.no_soal_alias', 'ASC')
            ->get();

        $this->detailSoal = $soalAnggotaList->map(function ($item) {
            // Map alias to original option text
            $optionsMap = [
                'A' => $item->opsi_a,
                'B' => $item->opsi_b,
                'C' => $item->opsi_c,
                'D' => $item->opsi_d,
                'E' => $item->opsi_e,
            ];

            // Format answers based on type
            $formattedJawaban = $item->jawaban_anggota;
            $formattedKunci = $item->kunci_jawaban;
            $optionsPgKompleks = [];

            if ($item->jenis == '2') { // PG Kompleks
                $formattedJawaban = json_decode($item->jawaban_anggota, true) ?? [];
                $formattedKunci = json_decode($item->kunci_jawaban, true) ?? [];
                $optionsPgKompleks = json_decode($item->opsi_a, true) ?? [];
            } elseif ($item->jenis == '3') { // Jodohkan
                $formattedJawaban = $this->parseJodohkan($item->jawaban_anggota);
                $formattedKunci = $this->parseJodohkan($item->kunci_jawaban);
            }

            return [
                'no_soal' => $item->no_soal_alias,
                'soal' => $item->soal,
                'jenis' => $item->jenis,
                'jawaban_anggota' => $item->jawaban_anggota,
                'kunci_jawaban' => $item->kunci_jawaban,
                'formatted_jawaban' => $formattedJawaban,
                'formatted_kunci' => $formattedKunci,
                'options_pg_kompleks' => $optionsPgKompleks,
                'opsi_display' => [
                    'A' => $optionsMap[$item->opsi_alias_a] ?? null,
                    'B' => $optionsMap[$item->opsi_alias_b] ?? null,
                    'C' => $optionsMap[$item->opsi_alias_c] ?? null,
                    'D' => $optionsMap[$item->opsi_alias_d] ?? null,
                    'E' => $optionsMap[$item->opsi_alias_e] ?? null,
                ],
                'alias_map' => [
                    'A' => $item->opsi_alias_a,
                    'B' => $item->opsi_alias_b,
                    'C' => $item->opsi_alias_c,
                    'D' => $item->opsi_alias_d,
                    'E' => $item->opsi_alias_e,
                ]
            ];
        })->toArray();
    }

    private function parseJodohkan($json)
    {
        if (!$json) return [];
        
        $data = json_decode($json, true);
        if (!isset($data['jawaban']) || !isset($data['links'])) return [];

        // jawaban[0] = Left side, jawaban[1] = Right side
        // Usually index 0 in these arrays is a header/placeholder like "#"
        $lefts = $data['jawaban'][0] ?? [];
        $rights = $data['jawaban'][1] ?? [];

        $pairs = [];
        
        // Iterate links. links index maps to lefts index.
        foreach ($data['links'] as $lbIndex => $targets) {
            if (!$targets) continue;
            
            $leftText = $lefts[$lbIndex] ?? '-';
            $rightTexts = [];

            foreach ($targets as $targetKey) {
                // targetKey is usually "A", "B", ...
                // Map "A" -> Index 1, "B" -> Index 2, assuming row 0 is header
                $idx = ord($targetKey) - 65 + 1; 
                $rightText = $rights[$idx] ?? $targetKey;
                $rightTexts[] = $rightText;
            }

            $pairs[] = [
                'left' => $leftText,
                'right' => implode(', ', $rightTexts)
            ];
        }

        return $pairs;
    }

    public function closeDetail()
    {
        $this->selectedHasil = null;
        $this->detailSoal = [];
    }

    public function render()
    {
        $hasilList = NilaiSoalAnggota::select(
                'nilai_soal_anggota.*',
                'pertemuan.judul_pertemuan',
                'pertemuan.pertemuan_ke',
                'pertemuan.tanggal',
                'program_pembelajaran.nama_program',
                'part_pertemuan.urutan as part_urutan',
                'part_pertemuan.nama_part',
                DB::raw("(COALESCE(nilai_pg, 0) + COALESCE(nilai_pk, 0) + COALESCE(nilai_jo, 0) + COALESCE(nilai_is, 0) + COALESCE(nilai_es, 0)) as total_nilai"),
                DB::raw("(COALESCE(bank_soal_pertemuan.bobot_pg, 0) + COALESCE(bank_soal_pertemuan.bobot_kompleks, 0) + COALESCE(bank_soal_pertemuan.bobot_jodohkan, 0) + COALESCE(bank_soal_pertemuan.bobot_isian, 0) + COALESCE(bank_soal_pertemuan.bobot_esai, 0)) as total_bobot")
            )
            ->leftJoin('part_pertemuan', 'part_pertemuan.id', 'nilai_soal_anggota.id_part')
            ->leftJoin('pertemuan', 'pertemuan.id', 'part_pertemuan.id_pertemuan')
            ->leftJoin('program_pembelajaran', 'program_pembelajaran.id', 'pertemuan.id_program')
            ->leftJoin('bank_soal_pertemuan', 'bank_soal_pertemuan.id', 'nilai_soal_anggota.id_bank_soal')
            ->where('nilai_soal_anggota.id_anggota', $this->anggota->id)
            ->where('nilai_soal_anggota.status', '1')
            ->orderBy('nilai_soal_anggota.tanggal', 'DESC')
            ->get()
            ->groupBy(function($item) {
                return $item->nama_program . '|' . $item->pertemuan_ke . '|' . $item->judul_pertemuan . '|' . $item->tanggal;
            })
            ->groupBy(function($item, $key) {
                // Extract nama_program from the key
                return explode('|', $key)[0];
            });

        return view('livewire.anggota.hasil-soal', [
            'hasilList' => $hasilList
        ]);
    }
}
