<?php

namespace App\Livewire\Keuangan;

use App\Traits\WithPermissionCache;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\IuranKas as ModelsIuranKas;
use App\Models\IuranKasPeriode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Exports\IuranKasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class IuranKas extends Component
{
    use WithPermissionCache, WithPagination;
    #[Title('Iuran Kas')]

    public $activeTahunId;
    public $periodeList = [];
    public $periodeNominals = [];
    public $periodeLoadedForTahunId = null;
    public $newlyChecked = [];
    public $newlyUnchecked = [];
    
    public $newPeriode = '';
    public $newNominal = 5000;
    
    // Modal properties
    public $showGenerateModal = false;
    public $showRenameModal = false;
    
    public $searchTerm = '';
    public $lengthData = 50;
    public $activeTab = 'pengurus';
    public $selectedJurusan = '';
    
    // Rename state
    public $oldPeriodeName = '';
    public $renamePeriodeValue = '';
    public $renameNominalValue = 0;
    
    // Delete Payment State
    public $paymentToDeleteId = null;
    public $paymentToDeleteMemberId = null;
    public $paymentToDeletePeriode = null;
    
    // History Detail State
    public $detailDate = '';
    public $detailMembers = [];
    
    // Delete state
    public $periodeToDelete = '';
    
    // Edit Date State
    public $editIuranId = null;
    public $editTanggalValue = '';
    
    protected $listeners = [
        'delete' => 'destroyPeriode',
        'destroyPayment' => 'destroyPayment' // New listener
    ];

    public function mount()
    {
        $this->cacheUserPermissions();
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;
        $this->loadPeriodeList();
    }

    public function render()
    {
        $this->loadPeriodeList();
        $data = $this->prepareData(paginateAnggota: true);
        $data['dailyHistory'] = $this->getDailyHistory();
        return view('livewire.keuangan.iuran-kas', $data);
    }

    private function prepareData(bool $paginateAnggota = true): array
    {
        $matrix = [
            'pengurus' => [],
            'anggota' => []
        ];

        $pengurusGrouped = collect();
        $anggotaGrouped = collect();
        $jurusanFilterOptions = collect();
        $countPengurus = 0;
        $countAnggota = 0;

        $summary = [
            'total_keseluruhan' => 0
        ];

        $anggotaPaginator = null;
        $searchTerm = trim((string) $this->searchTerm);

        if ($this->activeTahunId) {
            $baseMembersQuery = DB::table('anggota as a')
                ->leftJoin('departments as d', function ($join) {
                    $join->on('d.id', '=', 'a.id_department')
                        ->on('d.id_tahun', '=', 'a.id_tahun');
                })
                ->select(
                    'a.id',
                    'a.nama_lengkap',
                    'a.status_anggota',
                    'a.jurusan_prodi_kelas',
                    'd.nama_department'
                )
                ->where('a.id_tahun', $this->activeTahunId)
                ->where('a.status_aktif', 'aktif')
                ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                    $query->where('a.nama_lengkap', 'like', '%' . $searchTerm . '%');
                });

            $pengurusMembers = (clone $baseMembersQuery)
                ->where('a.status_anggota', 'pengurus')
                ->orderByRaw('COALESCE(d.nama_department, "") ASC')
                ->orderBy('a.nama_lengkap', 'ASC')
                ->get();

            $countPengurus = $pengurusMembers->count();

            $jurusanFilterOptions = DB::table('anggota as a')
                ->selectRaw('a.jurusan_prodi_kelas, COUNT(*) as total')
                ->where('a.id_tahun', $this->activeTahunId)
                ->where('a.status_aktif', 'aktif')
                ->where('a.status_anggota', 'anggota')
                ->groupBy('a.jurusan_prodi_kelas')
                ->orderBy('a.jurusan_prodi_kelas', 'ASC')
                ->get()
                ->map(function ($row) {
                    $value = trim((string) ($row->jurusan_prodi_kelas ?? ''));

                    return [
                        'value' => $value === '' ? '__empty__' : $value,
                        'label' => $value === '' ? 'Tidak Ada Data' : $value,
                        'total' => (int) $row->total,
                    ];
                })
                ->values();

            $anggotaQuery = (clone $baseMembersQuery)
                ->where('a.status_anggota', 'anggota')
                ->when($this->selectedJurusan !== '', function ($query) {
                    if ($this->selectedJurusan === '__empty__') {
                        $query->where(function ($subQuery) {
                            $subQuery->whereNull('a.jurusan_prodi_kelas')
                                ->orWhere('a.jurusan_prodi_kelas', '');
                        });

                        return;
                    }

                    $query->where('a.jurusan_prodi_kelas', $this->selectedJurusan);
                })
                ->orderBy('a.jurusan_prodi_kelas', 'ASC')
                ->orderBy('a.nama_lengkap', 'ASC');

            if ($paginateAnggota) {
                $anggotaPaginator = $anggotaQuery->paginate($this->lengthData);
                $anggotaMembers = collect($anggotaPaginator->items());
                $countAnggota = $anggotaPaginator->total();
            } else {
                $anggotaMembers = $anggotaQuery->get();
                $countAnggota = $anggotaMembers->count();
            }

            $members = $pengurusMembers->concat($anggotaMembers)->values();
            $memberIds = $members->pluck('id')->all();
            
            $iuranRecordsByMember = collect();

            if (!empty($memberIds)) {
                // Build nested map for O(1) lookup by member and periode.
                $iuranRecordsByMember = DB::table('iuran_kas')
                    ->select('id', 'id_anggota', 'periode', 'status', 'tanggal_bayar', 'nominal')
                    ->where('id_tahun', $this->activeTahunId)
                    ->whereIn('id_anggota', $memberIds)
                    ->get()
                    ->groupBy('id_anggota')
                    ->map(function ($memberRows) {
                        return $memberRows->keyBy('periode');
                    });
            }

            $summary['total_keseluruhan'] = (int) DB::table('iuran_kas as ik')
                ->join('anggota as a', 'a.id', '=', 'ik.id_anggota')
                ->where('ik.id_tahun', $this->activeTahunId)
                ->where('ik.status', 'lunas')
                ->where('a.id_tahun', $this->activeTahunId)
                ->where('a.status_aktif', 'aktif')
                ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                    $query->where('a.nama_lengkap', 'like', '%' . $searchTerm . '%');
                })
                ->sum('ik.nominal');

            $buildMemberData = function ($member) use ($iuranRecordsByMember) {
                $memberIuran = $iuranRecordsByMember->get($member->id, collect());

                $payments = [];
                $totalBayar = 0;

                foreach ($this->periodeList as $periode) {
                    $record = $memberIuran->get($periode);
                    if ($record) {
                        $payments[$periode] = [
                            'id' => $record->id,
                            'status' => $record->status,
                            'tanggal_bayar' => $record->tanggal_bayar,
                            'nominal' => $record->nominal
                        ];
                        if ($record->status === 'lunas') {
                            $totalBayar += $record->nominal;
                        }
                    } else {
                        $payments[$periode] = null;
                    }
                }

                return [
                    'id' => $member->id,
                    'nama' => $member->nama_lengkap,
                    'nama_department' => $member->nama_department,
                    'jurusan_prodi_kelas' => $member->jurusan_prodi_kelas,
                    'payments' => $payments,
                    'total_bayar' => $totalBayar
                ];
            };

            $matrix['pengurus'] = $pengurusMembers
                ->map($buildMemberData)
                ->values()
                ->all();

            $matrix['anggota'] = $anggotaMembers
                ->map($buildMemberData)
                ->values()
                ->all();

            $pengurusGrouped = collect($matrix['pengurus'])->groupBy(function ($row) {
                return $row['nama_department'] ?: 'Tidak Ada Department';
            });

            $anggotaGrouped = collect($matrix['anggota'])->groupBy(function ($row) {
                return $row['jurusan_prodi_kelas'] ?: 'Tidak Ada Data';
            });
        }
        
        return compact('matrix', 'summary', 'pengurusGrouped', 'anggotaGrouped', 'anggotaPaginator', 'countPengurus', 'countAnggota', 'jurusanFilterOptions');
    }

    public function updatingSearchTerm(): void
    {
        $this->resetPage();
    }

    public function updatedLengthData(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedJurusan(): void
    {
        $this->resetPage();
    }

    public function switchTab(string $tab): void
    {
        if (!in_array($tab, ['pengurus', 'anggota'], true)) {
            return;
        }

        $this->activeTab = $tab;
    }

    public function downloadExcel()
    {
        $this->loadPeriodeList();
        $data = $this->prepareData(paginateAnggota: false);
        return Excel::download(new IuranKasExport($data['matrix'], $this->periodeList, $data['summary']), 'Laporan_Iuran_Kas.xlsx');
    }

    public function downloadPdf()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $this->loadPeriodeList();
        $data = $this->prepareData(paginateAnggota: false);
        
        $pdf = Pdf::loadView('exports.iuran-kas-table', [
            'matrix' => $data['matrix'],
            'periodeList' => $this->periodeList,
            'summary' => $data['summary']
        ])->setPaper('a4', 'landscape');

        $pdfContent = $pdf->output();

        return response()->streamDownload(
            fn () => print($pdfContent),
            'Laporan_Iuran_Kas.pdf'
        );
    }

    public function loadPeriodeList()
    {
        if (!$this->activeTahunId) {
            $this->periodeList = [];
            $this->periodeNominals = [];
            $this->periodeLoadedForTahunId = null;
            return;
        }

        if ($this->periodeLoadedForTahunId === $this->activeTahunId && !empty($this->periodeList)) {
            return;
        }

        $periods = DB::table('iuran_kas_periode')
            ->where('id_tahun', $this->activeTahunId)
            ->orderBy('id', 'ASC')
            ->get();
            
        $this->periodeList = $periods->pluck('nama_periode')->toArray();
        $this->periodeNominals = $periods->pluck('nominal', 'nama_periode')->toArray();
        $this->periodeLoadedForTahunId = $this->activeTahunId;
    }

    private function reloadPeriodeList(): void
    {
        $this->periodeLoadedForTahunId = null;
        $this->loadPeriodeList();
    }

    public function toggleStatus($anggotaId, $periode, $namaAnggota = '')
    {
        DB::beginTransaction();
        try {
            $iuran = ModelsIuranKas::where('id_tahun', $this->activeTahunId)
                ->where('id_anggota', $anggotaId)
                ->where('periode', $periode)
                ->first();

            if ($iuran) {
                DB::rollBack();
                $this->paymentToDeleteId = $iuran->id;
                $this->dispatch('swal:confirmPayment', [
                    'type' => 'warning',
                    'message' => 'Batalkan Pembayaran?',
                    'text' => 'Apakah Anda yakin ingin membatalkan pembayaran iuran untuk ' . $namaAnggota . ' di pertemuan ke-' . $periode . '?'
                ]);
                return;
            } else {
                $nominal = isset($this->periodeNominals[$periode]) ? $this->periodeNominals[$periode] : 5000;
                
                // Create payment record
                ModelsIuranKas::create([
                    'id_tahun' => $this->activeTahunId,
                    'id_anggota' => $anggotaId,
                    'periode' => $periode,
                    'nominal' => $nominal,
                    'status' => 'lunas',
                    'tanggal_bayar' => now()->toDateString(),
                    'id_user' => Auth::id(),
                ]);
                $key = $anggotaId . '-' . $periode;
                $this->newlyUnchecked = array_diff($this->newlyUnchecked, [$key]); // Remove from red list if exists
                if (!in_array($key, $this->newlyChecked)) {
                    $this->newlyChecked[] = $key;
                }
            }
            DB::commit();
            $this->clearDailyHistoryCache();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
            ]);
        }
    }

    public function openGenerateModal()
    {
        $this->newPeriode = '';
        $this->newNominal = 5000;
        $this->dispatch('open-modal', ['id' => 'generateModal']);
    }

    public function closeGenerateModal()
    {
        $this->dispatch('close-modal', ['id' => 'generateModal']);
    }
    
    public function openRenameModal($periode)
    {
        $this->oldPeriodeName = $periode;
        $this->renamePeriodeValue = $periode;
        $this->renameNominalValue = isset($this->periodeNominals[$periode]) ? $this->periodeNominals[$periode] : 5000;
        $this->dispatch('open-modal', ['id' => 'renameModal']);
    }
    
    public function closeRenameModal()
    {
        $this->dispatch('close-modal', ['id' => 'renameModal']);
    }

    public function generatePeriode()
    {
        $this->validate([
            'newPeriode' => 'required|string|max:100',
            'newNominal' => 'required|numeric|min:0',
        ]);

        $exists = IuranKasPeriode::where('id_tahun', $this->activeTahunId)
            ->where('nama_periode', $this->newPeriode)
            ->exists();

        if ($exists) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Gagal!',
                'text' => 'Periode ini sudah ada.'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            // Create the Period
            IuranKasPeriode::create([
                'id_tahun' => $this->activeTahunId,
                'nama_periode' => $this->newPeriode,
                'nominal' => $this->newNominal
            ]);

            DB::commit();

            $this->newPeriode = '';
            $this->newNominal = 5000;

            $this->dispatch('close-modal', ['id' => 'generateModal']);
            $this->reloadPeriodeList();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => 'Periode baru berhasil dibuat.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
            ]);
        }
    }

    public function renamePeriode()
    {
        $this->validate([
            'renamePeriodeValue' => 'required|string|max:100',
            'renameNominalValue' => 'required|numeric|min:0',
        ]);
        
        $exists = IuranKasPeriode::where('id_tahun', $this->activeTahunId)
            ->where('nama_periode', $this->renamePeriodeValue)
            ->where('nama_periode', '!=', $this->oldPeriodeName)
            ->exists();

        if ($exists) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Gagal!',
                'text' => 'Nama periode sudah digunakan.'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            // Update Definition Table
            IuranKasPeriode::where('id_tahun', $this->activeTahunId)
                ->where('nama_periode', $this->oldPeriodeName)
                ->update([
                    'nama_periode' => $this->renamePeriodeValue,
                    'nominal' => $this->renameNominalValue
                ]);
                
            // Update Payments Table (Pukul Rata Mutlak)
            ModelsIuranKas::where('id_tahun', $this->activeTahunId)
                ->where('periode', $this->oldPeriodeName)
                ->update([
                    'periode' => $this->renamePeriodeValue,
                    'nominal' => $this->renameNominalValue
                ]);

            DB::commit();
            $this->clearDailyHistoryCache();

            $this->dispatch('close-modal', ['id' => 'renameModal']);
            $this->reloadPeriodeList();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => 'Periode & nominal berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
            ]);
        }
    }
    
    public function confirmDeletePeriode($periodeName)
    {
        $this->periodeToDelete = $periodeName;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Apakah Anda yakin?',
            'text' => 'Menghapus periode ' . $periodeName . ' akan menghapus semua data pembayaran terkait!'
        ]);
    }
    
    public function destroyPayment()
    {
        if (!$this->paymentToDeleteId) return;

        DB::beginTransaction();
        try {
            $payment = ModelsIuranKas::find($this->paymentToDeleteId);
            if ($payment) {
                // Hapus dari state newlyChecked jika ada
                $key = $payment->id_anggota . '-' . $payment->periode;
                $this->newlyChecked = array_diff($this->newlyChecked, [$key]);
                
                // Tambahkan ke list newlyUnchecked (merah)
                if (!in_array($key, $this->newlyUnchecked)) {
                    $this->newlyUnchecked[] = $key;
                }
                
                $payment->delete();
            }
            DB::commit();
            $this->clearDailyHistoryCache();

            $this->paymentToDeleteId = null;

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Dibatalkan!',
                'text' => 'Pembayaran iuran kas berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Terjadi kesalahan. ' . $e->getMessage()
            ]);
        }
    }

    public function destroyPeriode()
    {
        if(!$this->periodeToDelete) return;

        DB::beginTransaction();
        try {
            // Delete from Definition Table
            IuranKasPeriode::where('id_tahun', $this->activeTahunId)
                ->where('nama_periode', $this->periodeToDelete)
                ->delete();

            // Delete from Payments Table
            ModelsIuranKas::where('id_tahun', $this->activeTahunId)
                ->where('periode', $this->periodeToDelete)
                ->delete();
                
            DB::commit();
            $this->clearDailyHistoryCache();
            
            $this->periodeToDelete = '';
            $this->reloadPeriodeList();
            
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Terhapus!',
                'text' => 'Periode berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
            ]);
        }
    }
    
    public function openEditDateModal($iuranId)
    {
        $iuran = ModelsIuranKas::find($iuranId);
        if (!$iuran) return;
        
        $this->editIuranId = $iuran->id;
        // Ensure format is Y-m-d string for HTML date input
        $this->editTanggalValue = $iuran->tanggal_bayar 
            ? $iuran->tanggal_bayar->format('Y-m-d') 
            : now()->format('Y-m-d');
        
        $this->dispatch('open-modal', ['id' => 'editDateModal']);
    }
    
    public function closeEditDateModal()
    {
        $this->dispatch('close-modal', ['id' => 'editDateModal']);
    }
    
    public function updateDate()
    {
        $this->validate([
            'editTanggalValue' => 'required|date',
        ]);
        
        DB::beginTransaction();
        try {
            $iuran = ModelsIuranKas::find($this->editIuranId);
            if ($iuran) {
                $iuran->update([
                    'tanggal_bayar' => $this->editTanggalValue
                ]);
            }
            DB::commit();
            $this->clearDailyHistoryCache();
            
            $this->dispatch('close-modal', ['id' => 'editDateModal']);
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Updated!',
                'text' => 'Tanggal pembayaran berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
            ]);
        }
    }
    
    private function getDailyHistory()
    {
        if (!$this->activeTahunId) {
            return collect();
        }

        $cacheKey = 'iuran-kas:daily-history:tahun:' . $this->activeTahunId;

        return Cache::remember($cacheKey, now()->addMinutes(3), function () {
            return DB::table('iuran_kas')
                ->where('id_tahun', $this->activeTahunId)
                ->selectRaw('tanggal_bayar as date, SUM(nominal) as total, COUNT(*) as count')
                ->groupBy('tanggal_bayar')
                ->orderBy('tanggal_bayar', 'desc')
                ->get();
        });
    }

    private function clearDailyHistoryCache(): void
    {
        if (!$this->activeTahunId) {
            return;
        }

        Cache::forget('iuran-kas:daily-history:tahun:' . $this->activeTahunId);
    }
    
    public function openHistoryDetail($date)
    {
        $this->detailDate = $date;
        $this->detailMembers = ModelsIuranKas::with('anggota')
            ->select('id', 'id_anggota', 'periode', 'nominal', 'tanggal_bayar')
            ->where('id_tahun', $this->activeTahunId)
            ->where('tanggal_bayar', $date)
            ->get();
            
        $this->dispatch('open-modal', ['id' => 'historyDetailModal']);
    }
    
    public function closeHistoryDetail()
    {
        $this->dispatch('close-modal', ['id' => 'historyDetailModal']);
    }
}
