<?php

namespace App\Livewire\Anggota\KerjakanSoal;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use App\Models\SoalAnggota;
use App\Models\NilaiSoalAnggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class Mengerjakan extends Component
{
    #[Title('Mengerjakan Soal')]

    protected $listeners = ['finishUjian'];

    public $opsi, $ragu;
    public $size = 'base', $currentQuestionIndex = 0;
    public $pertemuanId, $anggota;
    public $soals;
    public $isian_singkat, $essai, $pg_kompleks = [], $jawaban_jodohkan = [];

    public function mount($pertemuanId = '')
    {
        try {
            $this->pertemuanId = Crypt::decryptString($pertemuanId);
            $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

            if (!$this->anggota) {
                return redirect('/anggota/daftar-pertemuan');
            }

            $this->currentQuestionIndex = session('anggota_question_index', 0);
        } catch (\Throwable $th) {
            return redirect('/anggota/daftar-pertemuan');
        }

        $this->loadSoals();
    }

    public function render()
    {
        return view('livewire.anggota.kerjakan-soal.mengerjakan', [
            'soals' => $this->soals,
        ]);
    }

    public function loadSoals()
    {
        $paginator = SoalAnggota::select([
                'soal_anggota.id_soal',
                'soal_anggota.id_nilai_soal',
                'nilai_soal_anggota.id_anggota',
                'nilai_soal_anggota.status as status_ujian',
                'soal_anggota.jenis_soal',
                'soal_pertemuan.soal',
                'soal_anggota.opsi_alias_a',
                'soal_anggota.opsi_alias_b',
                'soal_anggota.opsi_alias_c',
                'soal_anggota.opsi_alias_d',
                'soal_anggota.opsi_alias_e',
                'soal_anggota.jawaban_anggota',
                'soal_anggota.ragu',
                'soal_pertemuan.opsi_a',
                'soal_pertemuan.opsi_b',
                'soal_pertemuan.opsi_c',
                'soal_pertemuan.opsi_d',
                'soal_pertemuan.opsi_e',
            ])
            ->leftJoin('soal_pertemuan', 'soal_anggota.id_soal', 'soal_pertemuan.id')
            ->leftJoin('nilai_soal_anggota', 'nilai_soal_anggota.id', 'soal_anggota.id_nilai_soal')
            ->where([
                ['nilai_soal_anggota.id_pertemuan', $this->pertemuanId],
                ['nilai_soal_anggota.id_anggota', $this->anggota->id],
            ])
            ->orderBy('soal_anggota.no_soal_alias', 'ASC')
            ->paginate(50);

        $this->soals = $paginator->toArray();

        if (empty($this->soals['data'])) {
            return redirect('/anggota/daftar-pertemuan');
        }

        // Redirect if already completed
        if ($this->soals['data'][$this->currentQuestionIndex]['status_ujian'] == '1') {
            return redirect('/anggota/daftar-pertemuan');
        }

        foreach ($this->soals['data'] as &$item) {
            $optionsMap = [
                'A' => $item['opsi_a'],
                'B' => $item['opsi_b'],
                'C' => $item['opsi_c'],
                'D' => $item['opsi_d'],
                'E' => $item['opsi_e'],
            ];

            $item['opsi_alias_a'] = $optionsMap[$item['opsi_alias_a']] ?? null;
            $item['opsi_alias_b'] = $optionsMap[$item['opsi_alias_b']] ?? null;
            $item['opsi_alias_c'] = $optionsMap[$item['opsi_alias_c']] ?? null;
            $item['opsi_alias_d'] = $optionsMap[$item['opsi_alias_d']] ?? null;
            $item['opsi_alias_e'] = $optionsMap[$item['opsi_alias_e']] ?? null;

            $item['jawaban_anggota'] = SoalAnggota::select('jawaban_anggota')
                ->leftJoin('nilai_soal_anggota', 'nilai_soal_anggota.id', 'soal_anggota.id_nilai_soal')
                ->where([
                    ['id_soal', $item['id_soal']],
                    ['nilai_soal_anggota.id_anggota', $this->anggota->id],
                ])
                ->value('jawaban_anggota');
        }

        $this->loadCurrentQuestionData();
    }

    private function loadCurrentQuestionData()
    {
        if (empty($this->soals['data'])) return;
        
        $currentQuestion = $this->soals['data'][$this->currentQuestionIndex];

        switch ($currentQuestion['jenis_soal']) {
            case '1':
                $this->opsi = $currentQuestion['jawaban_anggota'];
                break;
            case '2':
                $jawabanAnggota = $currentQuestion['jawaban_anggota'];
                $this->pg_kompleks = is_string($jawabanAnggota) ? json_decode($jawabanAnggota, true) : ($jawabanAnggota ?? []);
                break;
            case '3':
                $this->jawaban_jodohkan = json_decode($currentQuestion['jawaban_anggota']);
                $this->dispatch('initSummernoteJDH');
                break;
            case '4':
                $this->isian_singkat = $currentQuestion['jawaban_anggota'];
                break;
            case '5':
                $this->essai = $currentQuestion['jawaban_anggota'];
                $this->dispatch('initSummernote');
                break;
        }
    }

    public function selectOption($option)
    {
        SoalAnggota::where([
            ['id_nilai_soal', $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal']],
            ['id_soal', $this->soals['data'][$this->currentQuestionIndex]['id_soal']],
        ])->update(['jawaban_anggota' => $option]);

        $this->soals['data'][$this->currentQuestionIndex]['jawaban_anggota'] = $option;
        $this->opsi = $option;
    }

    public function updatedPgKompleks()
    {
        SoalAnggota::where([
            ['id_nilai_soal', $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal']],
            ['id_soal', $this->soals['data'][$this->currentQuestionIndex]['id_soal']],
        ])->update(['jawaban_anggota' => $this->pg_kompleks]);

        $this->soals['data'][$this->currentQuestionIndex]['jawaban_anggota'] = $this->pg_kompleks;
    }

    public function updatedJawabanJodohkan()
    {
        SoalAnggota::where([
            ['id_nilai_soal', $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal']],
            ['id_soal', $this->soals['data'][$this->currentQuestionIndex]['id_soal']],
        ])->update(['jawaban_anggota' => json_encode($this->jawaban_jodohkan, JSON_UNESCAPED_SLASHES)]);

        $this->soals['data'][$this->currentQuestionIndex]['jawaban_anggota'] = json_encode($this->jawaban_jodohkan, JSON_UNESCAPED_SLASHES);
    }

    public function updatedIsianSingkat($value)
    {
        SoalAnggota::where([
            ['id_nilai_soal', $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal']],
            ['id_soal', $this->soals['data'][$this->currentQuestionIndex]['id_soal']],
        ])->update(['jawaban_anggota' => $value]);

        $this->soals['data'][$this->currentQuestionIndex]['jawaban_anggota'] = $value;
        $this->isian_singkat = $value;
    }

    public function updatedEssai($value)
    {
        SoalAnggota::where([
            ['id_nilai_soal', $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal']],
            ['id_soal', $this->soals['data'][$this->currentQuestionIndex]['id_soal']],
        ])->update(['jawaban_anggota' => $value]);

        $this->soals['data'][$this->currentQuestionIndex]['jawaban_anggota'] = $value;
        $this->essai = $value;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->soals['data']) - 1) {
            $this->currentQuestionIndex++;
            session(['anggota_question_index' => $this->currentQuestionIndex]);
            $this->loadCurrentQuestionData();
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            session(['anggota_question_index' => $this->currentQuestionIndex]);
            $this->loadCurrentQuestionData();
        }
    }

    public function goToQuestion($index)
    {
        $this->currentQuestionIndex = $index;
        session(['anggota_question_index' => $this->currentQuestionIndex]);
        $this->loadCurrentQuestionData();
    }

    public function selectRagu($value)
    {
        DB::table('soal_anggota')
            ->where([
                ['id_nilai_soal', $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal']],
                ['id_soal', $this->soals['data'][$this->currentQuestionIndex]['id_soal']],
            ])
            ->update(['ragu' => (string)$value]);

        $this->soals['data'][$this->currentQuestionIndex]['ragu'] = $value;
    }

    public function confirmFinish()
    {
        $this->dispatch('confirm:ujian', [
            'type'    => 'warning',
            'message' => 'Apakah Anda yakin?',
            'text'    => 'Pastikan semua jawaban sudah yakin dan tidak ada yang ragu-ragu.',
        ]);
    }

    public function finishUjian()
    {
        try {
            DB::transaction(function () {
                $idNilaiSoal = $this->soals['data'][$this->currentQuestionIndex]['id_nilai_soal'];

                $raguQuestions = SoalAnggota::where([
                    ['id_nilai_soal', $idNilaiSoal],
                    ['ragu', '1'],
                ])->exists();

                if ($raguQuestions) {
                    $this->dispatchAlert('error', 'Gagal!', 'Pastikan semua soal sudah terjawab dengan yakin dan tidak ada yang ditandai sebagai ragu.');
                    return;
                }

                // Recalculate point_soal if all are zero (bobot was not set)
                $hasZeroPoints = !DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('point_soal', '>', 0)
                    ->whereIn('jenis_soal', ['1', '2', '3', '4'])
                    ->exists()
                    && !DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('point_essai', '>', 0)
                    ->where('jenis_soal', '5')
                    ->exists();

                if ($hasZeroPoints) {
                    $totalSoal = DB::table('soal_anggota')->where('id_nilai_soal', $idNilaiSoal)->count();
                    if ($totalSoal > 0) {
                        $pointPerSoal = 100 / $totalSoal;
                        DB::table('soal_anggota')
                            ->where('id_nilai_soal', $idNilaiSoal)
                            ->whereIn('jenis_soal', ['1', '2', '3', '4'])
                            ->update(['point_soal' => $pointPerSoal]);
                        DB::table('soal_anggota')
                            ->where('id_nilai_soal', $idNilaiSoal)
                            ->where('jenis_soal', '5')
                            ->update(['point_essai' => $pointPerSoal]);
                    }
                }

                // Calculate scores per type
                // PG (1): exact match jawaban_alias = jawaban_anggota
                // PG Kompleks (2): exact match (JSON string comparison)
                // Jodohkan (3): compare links arrays only
                // Isian (4): case-insensitive trimmed comparison
                // Esai (5): manual scoring via koreksi (not auto-scored)

                // Auto-score PG Kompleks (jenis 2) - normalize JSON before comparing
                $pgKompleksSoals = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '2')
                    ->get();

                foreach ($pgKompleksSoals as $pks) {
                    $aliasArr = json_decode($pks->jawaban_alias, true) ?? [];
                    $anggotaArr = json_decode($pks->jawaban_anggota, true) ?? [];
                    sort($aliasArr);
                    sort($anggotaArr);
                    if ($aliasArr == $anggotaArr && !empty($anggotaArr)) {
                        DB::table('soal_anggota')->where('id', $pks->id)
                            ->update(['nilai_otomatis' => $pks->point_soal]);
                    } else {
                        DB::table('soal_anggota')->where('id', $pks->id)
                            ->update(['nilai_otomatis' => 0]);
                    }
                }

                // Auto-score Jodohkan (jenis 3) - compare jawaban matrix (the actual pairings)
                $jodohkanSoals = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '3')
                    ->get();

                foreach ($jodohkanSoals as $js) {
                    $aliasData = json_decode($js->jawaban_alias, true) ?? [];
                    $anggotaData = json_decode($js->jawaban_anggota, true) ?? [];
                    // Compare the jawaban matrix (rows 1+ contain the actual "1"/"0" pairings)
                    $aliasMatrix = array_slice($aliasData['jawaban'] ?? [], 1);
                    $anggotaMatrix = array_slice($anggotaData['jawaban'] ?? [], 1);
                    if ($aliasMatrix == $anggotaMatrix && !empty($anggotaMatrix)) {
                        DB::table('soal_anggota')->where('id', $js->id)
                            ->update(['nilai_otomatis' => $js->point_soal]);
                    } else {
                        DB::table('soal_anggota')->where('id', $js->id)
                            ->update(['nilai_otomatis' => 0]);
                    }
                }

                // Calculate totals per type
                // PG (1): direct SQL comparison
                $nilaiPg = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '1')
                    ->whereColumn('jawaban_alias', 'jawaban_anggota')
                    ->sum('point_soal');

                // PG Kompleks (2): from nilai_otomatis
                $nilaiPk = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '2')
                    ->sum('nilai_otomatis');

                // Jodohkan (3): from nilai_otomatis
                $nilaiJo = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '3')
                    ->sum('nilai_otomatis');

                // Isian (4): case-insensitive trimmed comparison
                $nilaiIs = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '4')
                    ->whereRaw("LOWER(TRIM(jawaban_alias)) = LOWER(TRIM(jawaban_anggota)) AND TRIM(jawaban_anggota) != ''")
                    ->sum('point_soal');

                // Esai (5): auto-score by comparing stripped HTML, case-insensitive
                $esaiSoals = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '5')
                    ->get();

                foreach ($esaiSoals as $es) {
                    $aliasClean = strtolower(trim(strip_tags($es->jawaban_alias ?? '')));
                    $anggotaClean = strtolower(trim(strip_tags($es->jawaban_anggota ?? '')));
                    if ($aliasClean !== '' && $aliasClean === $anggotaClean) {
                        DB::table('soal_anggota')->where('id', $es->id)
                            ->update(['nilai_otomatis' => $es->point_essai]);
                    } else {
                        DB::table('soal_anggota')->where('id', $es->id)
                            ->update(['nilai_otomatis' => 0]);
                    }
                }

                $nilaiEs = DB::table('soal_anggota')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', '5')
                    ->sum('nilai_otomatis');

                $cekPg = DB::table('soal_anggota')
                    ->selectRaw('SUM(CASE WHEN jawaban_alias = jawaban_anggota THEN 1 ELSE 0 END) AS pg_benar, SUM(CASE WHEN jawaban_alias <> jawaban_anggota THEN 1 ELSE 0 END) AS pg_salah')
                    ->where('id_nilai_soal', $idNilaiSoal)
                    ->where('jenis_soal', 1)
                    ->first();

                $nilaiSoal = NilaiSoalAnggota::find($idNilaiSoal);
                $waktuMulai = Carbon::parse($nilaiSoal->mulai);
                $waktuSelesai = Carbon::now();
                $lamaUjian = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S');

                NilaiSoalAnggota::where('id', $idNilaiSoal)
                    ->update([
                        'pg_benar'  => $cekPg->pg_benar ?? 0,
                        'pg_salah'  => $cekPg->pg_salah ?? 0,
                        'nilai_pg'  => $nilaiPg,
                        'nilai_pk'  => $nilaiPk,
                        'nilai_jo'  => $nilaiJo,
                        'nilai_is'  => $nilaiIs,
                        'nilai_es'  => $nilaiEs,
                        'status'    => '1',
                        'dikoreksi' => '0',
                        'selesai'   => $waktuSelesai,
                        'lama_ujian' => $lamaUjian,
                    ]);

                session()->forget('anggota_question_index');

                $this->dispatch('swal:finish', [
                    'type'    => 'success',
                    'message' => 'Selesai!',
                    'text'    => 'Anda telah menyelesaikan semua soal. Terima kasih!',
                ]);
            });
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Gagal!', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function changeSize($size)
    {
        $this->size = $size;
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
