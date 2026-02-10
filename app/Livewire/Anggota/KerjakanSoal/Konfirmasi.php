<?php

namespace App\Livewire\Anggota\KerjakanSoal;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use App\Models\Pertemuan;
use App\Models\BankSoalPertemuan;
use App\Models\SoalPertemuan;
use App\Models\NilaiSoalAnggota;
use App\Models\SoalAnggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class Konfirmasi extends Component
{
    #[Title('Konfirmasi Soal')]

    public $pertemuanId;
    public $pertemuan;
    public $bankSoal;
    public $anggota;
    public $totalSoal = 0;

    public function mount($pertemuanId = '')
    {
        try {
            $this->pertemuanId = Crypt::decryptString($pertemuanId);
        } catch (\Throwable $th) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            return redirect('/anggota/daftar-pertemuan');
        }

        // Check if already completed
        $checkStatus = NilaiSoalAnggota::where([
            ['id_pertemuan', $this->pertemuanId],
            ['id_anggota', $this->anggota->id],
            ['status', '1']
        ])->exists();

        if ($checkStatus) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $this->pertemuan = Pertemuan::with('program')
            ->findOrFail($this->pertemuanId);

        $this->bankSoal = BankSoalPertemuan::where('id_pertemuan', $this->pertemuanId)->first();

        if (!$this->bankSoal) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $this->totalSoal = $this->bankSoal->jml_pg + $this->bankSoal->jml_kompleks + $this->bankSoal->jml_jodohkan + $this->bankSoal->jml_isian + $this->bankSoal->jml_esai;
    }

    public function mulaiKerjakan()
    {
        $checkAda = NilaiSoalAnggota::where([
            ['id_bank_soal', $this->bankSoal->id],
            ['id_pertemuan', $this->pertemuanId],
            ['id_anggota', $this->anggota->id],
        ])->exists();

        if (!$checkAda) {
            // Load soal
            $soals = SoalPertemuan::where([
                ['id_bank_soal', $this->bankSoal->id],
                ['tampilkan', '1']
            ])
            ->orderBy('jenis', 'ASC')
            ->orderBy('nomor_soal', 'ASC')
            ->get();

            DB::transaction(function () use ($soals) {
                $nilaiSoal = NilaiSoalAnggota::create([
                    'tanggal'       => date('Y-m-d'),
                    'id_bank_soal'  => $this->bankSoal->id,
                    'id_pertemuan'  => $this->pertemuanId,
                    'id_anggota'    => $this->anggota->id,
                    'mulai'         => now(),
                ]);

                $data = [];
                foreach ($soals as $index => $soal) {
                    $jwbAnggota = '';
                    if ($soal->jenis == '3') {
                        $jwbJodoh = json_decode($soal->jawaban, true);
                        if (isset($jwbJodoh['links']) && is_array($jwbJodoh['links'])) {
                            foreach ($jwbJodoh['links'] as &$link) {
                                if (is_array($link)) {
                                    $link = [];
                                }
                            }
                        }
                        $jwbAnggota = json_encode($jwbJodoh, JSON_UNESCAPED_SLASHES);
                    }

                    $pointSoal = 0;
                    $pointEssai = 0;

                    // Check if all bobot are zero (not configured)
                    $totalBobot = (float)$this->bankSoal->bobot_pg + (float)$this->bankSoal->bobot_kompleks 
                        + (float)$this->bankSoal->bobot_jodohkan + (float)$this->bankSoal->bobot_isian 
                        + (float)$this->bankSoal->bobot_esai;
                    $bobotNotSet = $totalBobot <= 0;

                    if ($soal->jenis == '5') {
                        if ($bobotNotSet) {
                            $pointEssai = $this->totalSoal > 0 ? 100 / $this->totalSoal : 0;
                        } else {
                            $pointEssai = (float)$this->bankSoal->jml_esai > 0 
                                ? (float)$this->bankSoal->bobot_esai / (float)$this->bankSoal->jml_esai 
                                : 0;
                        }
                    } else {
                        $jmlField = match($soal->jenis) {
                            '1' => 'jml_pg',
                            '2' => 'jml_kompleks',
                            '3' => 'jml_jodohkan',
                            '4' => 'jml_isian',
                            default => 'jml_pg',
                        };
                        $bobotField = match($soal->jenis) {
                            '1' => 'bobot_pg',
                            '2' => 'bobot_kompleks',
                            '3' => 'bobot_jodohkan',
                            '4' => 'bobot_isian',
                            default => 'bobot_pg',
                        };
                        if ($bobotNotSet) {
                            // Fallback: distribute 100 equally across all questions
                            $pointSoal = $this->totalSoal > 0 ? 100 / $this->totalSoal : 0;
                        } else {
                            $pointSoal = (float)$this->bankSoal->{$jmlField} > 0 
                                ? (float)$this->bankSoal->{$bobotField} / (float)$this->bankSoal->{$jmlField} 
                                : 0;
                        }
                    }

                    $data[] = [
                        'id_nilai_soal'   => $nilaiSoal->id,
                        'id_soal'         => $soal->id,
                        'jenis_soal'      => $soal->jenis,
                        'no_soal_alias'   => $index + 1,
                        'opsi_alias_a'    => $soal->jenis == '1' ? 'A' : ($soal->jenis == '2' ? $soal->opsi_a : ''),
                        'opsi_alias_b'    => $soal->jenis == '1' ? 'B' : '',
                        'opsi_alias_c'    => $soal->jenis == '1' ? 'C' : '',
                        'opsi_alias_d'    => $soal->jenis == '1' ? 'D' : '',
                        'opsi_alias_e'    => $soal->jenis == '1' ? 'E' : '',
                        'jawaban_alias'   => $soal->jawaban,
                        'jawaban_anggota' => $soal->jenis == '2' ? '[]' : ($soal->jenis == '3' ? $jwbAnggota : ''),
                        'jawaban_benar'   => $soal->jawaban,
                        'point_soal'      => $pointSoal,
                        'point_essai'     => $pointEssai,
                        'nilai_koreksi'   => 0,
                        'nilai_otomatis'  => 0,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }

                DB::table('soal_anggota')->insert($data);
            });
        }

        return redirect('/anggota/mengerjakan/' . Crypt::encryptString($this->pertemuanId));
    }

    public function render()
    {
        return view('livewire.anggota.kerjakan-soal.konfirmasi');
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
