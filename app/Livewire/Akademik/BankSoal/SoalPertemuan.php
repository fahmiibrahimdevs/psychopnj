<?php

namespace App\Livewire\Akademik\BankSoal;

use Livewire\Component;
use App\Models\Pertemuan;
use App\Models\BankSoalPertemuan;
use App\Models\SoalPertemuan as SoalPertemuanModel;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SoalPertemuan extends Component
{
    #[Title('Kelola Bank Soal')]

    protected $listeners = ['delete'];

    public $pertemuanId, $pertemuan, $bankSoal;
    public $selectedJenis = '1';
    public $dataId, $isEditing = false;

    // Question fields
    public $datas = [];
    public $soal = '';
    public $opsi_a = '', $opsi_b = '', $opsi_c = '', $opsi_d = '', $opsi_e = '';
    public $jawaban = '';

    // Complex question types
    public $opsi_kompleks = [];
    public $opsi_benar_kompleks = [];
    public $jawaban_jodohkan = [];

    public function mount($pertemuanId)
    {
        // Authorization check - only pengurus role
        if (!auth()->user()->hasRole('pengurus')) {
            abort(403, 'Unauthorized access. Hanya pengurus yang dapat mengakses halaman ini.');
        }

        $this->pertemuanId = $pertemuanId;
        $this->loadPertemuanData();
    }

    private function loadPertemuanData()
    {
        $this->pertemuan = Pertemuan::with('program.tahunKepengurusan', 'bankSoal')
            ->findOrFail($this->pertemuanId);

        $this->bankSoal = $this->pertemuan->bankSoal;

        if (!$this->bankSoal) {
            abort(404, 'Bank soal belum dibuat untuk pertemuan ini.');
        }
    }

    public function render()
    {
        $this->loadPertemuanData();

        // Load soal data - Optimized with DB::table
        $this->datas = DB::table('soal_pertemuan')
            ->select('id', 'soal', 'nomor_soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban', 'tampilkan')
            ->where('id_bank_soal', $this->bankSoal->id)
            ->where('jenis', $this->selectedJenis)
            ->orderBy('nomor_soal')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        $total_dibuat = count($this->datas);

        // Calculate counts based on selected type
        $columns = [
            "1" => ["jumlah" => "jml_pg", "tampil" => "tampil_pg", "bobot" => "bobot_pg"],
            "2" => ["jumlah" => "jml_kompleks", "tampil" => "tampil_kompleks", "bobot" => "bobot_kompleks"],
            "3" => ["jumlah" => "jml_jodohkan", "tampil" => "tampil_jodohkan", "bobot" => "bobot_jodohkan"],
            "4" => ["jumlah" => "jml_isian", "tampil" => "tampil_isian", "bobot" => "bobot_isian"],
            "5" => ["jumlah" => "jml_esai", "tampil" => "tampil_esai", "bobot" => "bobot_esai"],
        ];

        $seharusnya = 0;
        $ditampilkan = 0;
        $bobotnya = 0;

        if (array_key_exists($this->selectedJenis, $columns)) {
            $columnJumlah = $columns[$this->selectedJenis]['jumlah'];
            $columnTampil = $columns[$this->selectedJenis]['tampil'];
            $columnBobot = $columns[$this->selectedJenis]['bobot'];

            $seharusnya = $this->bankSoal->$columnJumlah ?? 0;
            $ditampilkan = $this->bankSoal->$columnTampil ?? 0;
            $bobotnya = $this->bankSoal->$columnBobot ?? 0;
        }

        // Calculate total stats with caching (5 minutes)
        $cacheKey = "bank_soal_stats_{$this->bankSoal->id}";
        
        $stats = Cache::remember($cacheKey, 300, function () {
            return [
                'seluruh_total_seharusnya' => $this->bankSoal->jml_pg + $this->bankSoal->jml_kompleks +
                    $this->bankSoal->jml_jodohkan + $this->bankSoal->jml_isian + $this->bankSoal->jml_esai,
                'seluruh_total_dibuat' => DB::table('soal_pertemuan')
                    ->where('id_bank_soal', $this->bankSoal->id)
                    ->count(),
                'seluruh_total_ditampilkan' => $this->bankSoal->tampil_pg + $this->bankSoal->tampil_kompleks +
                    $this->bankSoal->tampil_jodohkan + $this->bankSoal->tampil_isian + $this->bankSoal->tampil_esai
            ];
        });
        
        $seluruh_total_seharusnya = $stats['seluruh_total_seharusnya'];
        $seluruh_total_dibuat = $stats['seluruh_total_dibuat'];
        $seluruh_total_ditampilkan = $stats['seluruh_total_ditampilkan'];

        if ($this->selectedJenis == "3") {
            $this->dispatch('linkerListJDH');
        }

        return view('livewire.akademik.bank-soal.soal-pertemuan', compact(
            'total_dibuat',
            'seharusnya',
            'ditampilkan',
            'bobotnya',
            'seluruh_total_seharusnya',
            'seluruh_total_dibuat',
            'seluruh_total_ditampilkan'
        ));
    }

    public function jenis($jenis)
    {
        $this->selectedJenis = $jenis;
        $this->resetInputFields();
    }

    // PG Kompleks methods
    public function addOpsiKompleks()
    {
        $nextKey = chr(count($this->opsi_kompleks) + 65);
        $this->opsi_kompleks[$nextKey] = '';

        $this->dispatch('initSummernotePGK');
    }

    public function removeOpsiKompleks($key)
    {
        if (isset($this->opsi_kompleks[$key])) {
            unset($this->opsi_kompleks[$key]);

            $this->opsi_benar_kompleks = array_filter($this->opsi_benar_kompleks, function ($correctKey) use ($key) {
                return $correctKey !== $key;
            });

            $newKeys = range('A', chr(65 + count($this->opsi_kompleks) - 1));
            $this->opsi_kompleks = array_combine($newKeys, array_values($this->opsi_kompleks));

            $this->opsi_benar_kompleks = array_map(function ($oldKey) use ($newKeys) {
                return array_search($oldKey, $newKeys) !== false ? $newKeys[array_search($oldKey, $newKeys)] : '';
            }, $this->opsi_benar_kompleks);
        }
    }

    public function toggleCorrectOpsiKompleks($key)
    {
        if (in_array($key, $this->opsi_benar_kompleks)) {
            $this->opsi_benar_kompleks = array_diff($this->opsi_benar_kompleks, [$key]);
        } else {
            $this->opsi_benar_kompleks[] = $key;
        }
    }

    public function isEditingMode($mode)
    {
        $this->isEditing = $mode;
        $this->dispatchSummernoteInit();
    }

    private function dispatchSummernoteInit()
    {
        if ($this->selectedJenis == "1") {
            $this->dispatch('initSummernotePG');
        } else if ($this->selectedJenis == "2") {
            $this->dispatch('initSummernotePGK');
        } else if ($this->selectedJenis == "3") {
            $this->dispatch('initSummernoteJDH');
        } else if ($this->selectedJenis == "4") {
            $this->dispatch('initSummernoteIS');
        } else if ($this->selectedJenis == "5") {
            $this->dispatch('initSummernoteES');
        }
    }

    private function resetInputFields()
    {
        $this->soal = '';
        $this->opsi_a = '';
        $this->opsi_b = '';
        $this->opsi_c = '';
        $this->opsi_d = '';
        $this->opsi_e = '';
        $this->jawaban = '';

        $this->opsi_kompleks = [];
        $this->opsi_benar_kompleks = [];
        $this->jawaban_jodohkan = [];

        $this->dispatchSummernoteInit();
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    public function store()
    {
        $lastNomorSoal = DB::table('soal_pertemuan')
            ->where('id_bank_soal', $this->bankSoal->id)
            ->where('jenis', $this->selectedJenis)
            ->max('nomor_soal');

        $nomor_soal = $lastNomorSoal !== null ? $lastNomorSoal + 1 : 1;

        // Prepare data based on question type
        if ($this->selectedJenis == "1") {
            $opsi_a = $this->opsi_a;
            $opsi_b = $this->opsi_b;
            $opsi_c = $this->opsi_c;
            $opsi_d = $this->opsi_d;
            $opsi_e = $this->opsi_e;
            $jawaban = $this->jawaban;
        } elseif ($this->selectedJenis == "2") {
            $opsi_a = json_encode($this->opsi_kompleks, JSON_UNESCAPED_SLASHES);
            $opsi_b = '';
            $opsi_c = '';
            $opsi_d = '';
            $opsi_e = '';
            $jawaban = json_encode($this->opsi_benar_kompleks, JSON_UNESCAPED_SLASHES);
        } else if ($this->selectedJenis == "3") {
            $opsi_a = '';
            $opsi_b = '';
            $opsi_c = '';
            $opsi_d = '';
            $opsi_e = '';
            $jawaban = json_encode($this->processLinks($this->jawaban_jodohkan), JSON_UNESCAPED_SLASHES);
        } else if ($this->selectedJenis == "4" || $this->selectedJenis == "5") {
            $opsi_a = '';
            $opsi_b = '';
            $opsi_c = '';
            $opsi_d = '';
            $opsi_e = '';
            $jawaban = $this->jawaban;
        }

        SoalPertemuanModel::create([
            'id_bank_soal' => $this->bankSoal->id,
            'jenis' => $this->selectedJenis,
            'nomor_soal' => $nomor_soal,
            'soal' => $this->soal,
            'opsi_a' => $opsi_a,
            'opsi_b' => $opsi_b,
            'opsi_c' => $opsi_c,
            'opsi_d' => $opsi_d,
            'opsi_e' => $opsi_e,
            'jawaban' => $jawaban,
            'tampilkan' => '0',
        ]);

        // Clear cache after creating new soal
        Cache::forget("bank_soal_stats_{$this->bankSoal->id}");

        $this->dispatchAlert('success', 'Berhasil!', 'Soal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = SoalPertemuanModel::findOrFail($id);

        $this->dataId = $id;
        $this->soal = $data->soal;

        if ($this->selectedJenis == '1') {
            $this->opsi_a = $data->opsi_a;
            $this->opsi_b = $data->opsi_b;
            $this->opsi_c = $data->opsi_c;
            $this->opsi_d = $data->opsi_d;
            $this->opsi_e = $data->opsi_e;
            $this->jawaban = $data->jawaban;
        } elseif ($this->selectedJenis == '2') {
            $this->opsi_kompleks = json_decode($data->opsi_a, true) ?? [];
            $this->opsi_benar_kompleks = json_decode($data->jawaban, true) ?? [];
        } elseif ($this->selectedJenis == '3') {
            $this->jawaban = json_decode($data->jawaban);

        } elseif ($this->selectedJenis == '4' || $this->selectedJenis == '5') {
            $this->jawaban = $data->jawaban;
        }

        $this->dispatchSummernoteInit();
    }

    public function update()
    {
        // Prepare data based on question type
        if ($this->selectedJenis == "1") {
            $opsi_a = $this->opsi_a;
            $opsi_b = $this->opsi_b;
            $opsi_c = $this->opsi_c;
            $opsi_d = $this->opsi_d;
            $opsi_e = $this->opsi_e;
            $jawaban = $this->jawaban;
        } elseif ($this->selectedJenis == "2") {
            $opsi_a = json_encode($this->opsi_kompleks, JSON_UNESCAPED_SLASHES);
            $opsi_b = '';
            $opsi_c = '';
            $opsi_d = '';
            $opsi_e = '';
            $jawaban = json_encode($this->opsi_benar_kompleks, JSON_UNESCAPED_SLASHES);
        } else if ($this->selectedJenis == "3") {
            $opsi_a = '';
            $opsi_b = '';
            $opsi_c = '';
            $opsi_d = '';
            $opsi_e = '';
            $jawaban = json_encode($this->processLinks($this->jawaban_jodohkan), JSON_UNESCAPED_SLASHES);
        } else if ($this->selectedJenis == "4" || $this->selectedJenis == "5") {
            $opsi_a = '';
            $opsi_b = '';
            $opsi_c = '';
            $opsi_d = '';
            $opsi_e = '';
            $jawaban = $this->jawaban;
        }

        if ($this->dataId) {
            SoalPertemuanModel::findOrFail($this->dataId)->update([
                'soal' => $this->soal,
                'opsi_a' => $opsi_a,
                'opsi_b' => $opsi_b,
                'opsi_c' => $opsi_c,
                'opsi_d' => $opsi_d,
                'opsi_e' => $opsi_e,
                'jawaban' => $jawaban,
            ]);

            // Clear cache after updating soal
            Cache::forget("bank_soal_stats_{$this->bankSoal->id}");

            $this->dispatchAlert('success', 'Berhasil!', 'Soal berhasil diperbarui.');
            $this->dataId = null;
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Apakah Anda yakin?',
            'text' => 'Soal yang dihapus tidak dapat dikembalikan!'
        ]);
    }

    public function delete()
    {
        SoalPertemuanModel::findOrFail($this->dataId)->delete();
        
        // Clear cache after deleting soal
        Cache::forget("bank_soal_stats_{$this->bankSoal->id}");
        
        $this->dispatchAlert('success', 'Berhasil!', 'Soal berhasil dihapus.');
    }

    public function status($id, $active)
    {
        // Get current counts
        $columns = [
            '1' => 'tampil_pg',
            '2' => 'tampil_kompleks',
            '3' => 'tampil_jodohkan',
            '4' => 'tampil_isian',
            '5' => 'tampil_esai'
        ];

        $jenisToJumlah = [
            '1' => 'jml_pg',
            '2' => 'jml_kompleks',
            '3' => 'jml_jodohkan',
            '4' => 'jml_isian',
            '5' => 'jml_esai'
        ];

        $columnTampil = $columns[$this->selectedJenis];
        $columnJumlah = $jenisToJumlah[$this->selectedJenis];

        $currentTampil = $this->bankSoal->$columnTampil;
        $seharusnya = $this->bankSoal->$columnJumlah;

        // Prevent enabling if limit reached
        if ($active == "1") {
            if ((int)$currentTampil >= (int)$seharusnya) {
                $this->dispatchAlert('warning', 'Batas Tercapai!', 'Anda sudah menampilkan ' . $currentTampil . ' soal. Maksimal yang diperbolehkan: ' . $seharusnya . ' soal.Silakan matikan salah satu soal terlebih dahulu.');
                return;
            }
        }

        // Update soal tampilkan status
        DB::table('soal_pertemuan')
            ->where('id', $id)
            ->update(['tampilkan' => $active]);

        // Recalculate total tampil for this type - Optimized
        $total_tampil = DB::table('soal_pertemuan')
            ->where('id_bank_soal', $this->bankSoal->id)
            ->where('jenis', $this->selectedJenis)
            ->where('tampilkan', '1')
            ->count();

        // Update bank soal tampil count
        DB::table('bank_soal_pertemuan')
            ->where('id', $this->bankSoal->id)
            ->update([$columnTampil => $total_tampil]);
        
        // Clear cache after toggling status
        Cache::forget("bank_soal_stats_{$this->bankSoal->id}");
    }

    public function updateStatus($status)
    {
        // Update bank soal status
        DB::table('bank_soal_pertemuan')
            ->where('id', $this->bankSoal->id)
            ->update(['status' => $status]); // 0 for inactive, 1 for active

        // Clear cache
        Cache::forget("bank_soal_stats_{$this->bankSoal->id}");

        $message = $status == '1' ? 'Bank Soal berhasil diaktifkan.' : 'Bank Soal berhasil dinonaktifkan.';
        $this->dispatchAlert('success', 'Berhasil!', $message);
        
        // Refresh data
        $this->loadPertemuanData();
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type' => $type,
            'message' => $message,
            'text' => $text
        ]);

        $this->resetInputFields();
    }

    private function detectFormat($links)
    {
        if (is_array($links)) {
            return 'arrayOfArrays';
        } elseif (is_object($links) && !is_array($links)) {
            return 'objectOfArrays';
        }
        return 'unknown';
    }

    private function processLinks(&$jawaban)
    {
        $format = $this->detectFormat($jawaban['links']);
        $newLinks = [];

        if ($format === 'objectOfArrays') {
            foreach ($jawaban['links'] as $key => $value) {
                $index = (int)$key;
                $newLinks[$index] = $value;
            }
        } elseif ($format === 'arrayOfArrays') {
            $newLinks = $jawaban['links'];
        }

        if (!array_key_exists(0, $newLinks)) {
            $newLinks = array_merge([null], $newLinks);
        }

        $jawaban['links'] = array_values($newLinks);

        return $jawaban;
    }
}
