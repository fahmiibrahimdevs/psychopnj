<?php

namespace App\Livewire\Anggota\KerjakanSoal;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use App\Models\PartPertemuan;
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

    public $partId;
    public $bankSoalId;
    public $totalSoal = 0;

    // Non-persistent view data (loaded in render)
    public $part;
    public $bankSoal;
    public $anggota;

    public function mount($partId = '')
    {
        try {
            $this->partId = Crypt::decryptString($partId);
        } catch (\Throwable $th) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$anggota) {
            return redirect('/anggota/daftar-pertemuan');
        }

        // Check if already completed
        $checkStatus = NilaiSoalAnggota::where([
            ['id_part', $this->partId],
            ['id_anggota', $anggota->id],
            ['status', '1']
        ])->exists();

        if ($checkStatus) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $bankSoal = BankSoalPertemuan::where('id_part', $this->partId)->first();

        if (!$bankSoal) {
            return redirect('/anggota/daftar-pertemuan');
        }

        // Simpan hanya scalar ID agar bisa di-serialize Livewire
        $this->bankSoalId = $bankSoal->id;
        $this->totalSoal = $bankSoal->jml_pg + $bankSoal->jml_kompleks + $bankSoal->jml_jodohkan + $bankSoal->jml_isian + $bankSoal->jml_esai;
    }

    public function mulaiKerjakan()
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $bankSoal = BankSoalPertemuan::find($this->bankSoalId);
        $part = PartPertemuan::find($this->partId);

        if (!$anggota || !$bankSoal || !$part) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $existingNilai = NilaiSoalAnggota::where([
            ['id_bank_soal', $bankSoal->id],
            ['id_part', $this->partId],
            ['id_anggota', $anggota->id],
            ['status', '0'],
        ])->latest('id')->first();

        // Jika ada record tapi soalnya kosong (kemungkinan gagal saat dibuat), hapus dan buat ulang
        if ($existingNilai) {
            $soalCount = DB::table('soal_anggota')->where('id_nilai_soal', $existingNilai->id)->count();
            if ($soalCount === 0) {
                $existingNilai->delete();
                $existingNilai = null;
            }
        }

        if (!$existingNilai) {
            // Load soal
            $soals = SoalPertemuan::where([
                ['id_bank_soal', $bankSoal->id],
                ['tampilkan', '1']
            ])
            ->orderBy('jenis', 'ASC')
            ->orderBy('nomor_soal', 'ASC')
            ->get();

            if ($soals->isEmpty()) {
                $this->dispatchAlert('error', 'Soal Belum Tersedia', 'Soal untuk part ini belum dipublikasikan. Silakan hubungi pemateri.');
                return;
            }

            $totalSoal = $this->totalSoal;

            DB::transaction(function () use ($soals, $bankSoal, $part, $anggota, $totalSoal) {
                $nilaiSoal = NilaiSoalAnggota::create([
                    'tanggal'       => date('Y-m-d'),
                    'id_bank_soal'  => $bankSoal->id,
                    'id_part'       => $this->partId,
                    'id_pertemuan'  => $part->id_pertemuan,
                    'id_anggota'    => $anggota->id,
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
                    $totalBobot = (float)$bankSoal->bobot_pg + (float)$bankSoal->bobot_kompleks 
                        + (float)$bankSoal->bobot_jodohkan + (float)$bankSoal->bobot_isian 
                        + (float)$bankSoal->bobot_esai;
                    $bobotNotSet = $totalBobot <= 0;

                    if ($soal->jenis == '5') {
                        if ($bobotNotSet) {
                            $pointEssai = $totalSoal > 0 ? 100 / $totalSoal : 0;
                        } else {
                            $pointEssai = (float)$bankSoal->jml_esai > 0 
                                ? (float)$bankSoal->bobot_esai / (float)$bankSoal->jml_esai 
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
                            $pointSoal = $totalSoal > 0 ? 100 / $totalSoal : 0;
                        } else {
                            $pointSoal = (float)$bankSoal->{$jmlField} > 0 
                                ? (float)$bankSoal->{$bobotField} / (float)$bankSoal->{$jmlField} 
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

        return redirect('/anggota/mengerjakan/' . Crypt::encryptString($this->partId));
    }

    public function render()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $this->bankSoal = BankSoalPertemuan::find($this->bankSoalId);
        $this->part = PartPertemuan::with('pertemuan.program')->find($this->partId);

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
