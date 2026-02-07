<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\IuranKas as ModelsIuranKas;
use App\Models\IuranKasPeriode;
use App\Models\Keuangan;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\IuranKasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class IuranKas extends Component
{
    #[Title('Iuran Kas')]

    public $activeTahunId;
    public $periodeList = [];
    
    public $newPeriode = '';
    public $nominalDefault = 5000;
    
    // Modal properties
    public $showGenerateModal = false;
    public $showRenameModal = false;
    
    public $searchTerm = '';
    
    // Rename state
    public $oldPeriodeName = '';
    public $renamePeriodeValue = '';
    
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
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;
    }

    public function render()
    {
        $this->loadPeriodeList();
        $data = $this->prepareData();
        return view('livewire.keuangan.iuran-kas', $data);
    }

    private function prepareData()
    {
        $matrix = [
            'pengurus' => [],
            'anggota' => []
        ];

        $summary = [
            'total_keseluruhan' => 0
        ];

        if ($this->activeTahunId) {
            $members = Anggota::where('id_tahun', $this->activeTahunId)
                ->where('status_aktif', 'aktif')
                ->when($this->searchTerm, function ($query) {
                    $query->where('nama_lengkap', 'like', '%' . $this->searchTerm . '%');
                })
                ->orderBy('nama_lengkap', 'ASC')
                ->get();

            $memberIds = $members->pluck('id');
            
            // Fetch payments
            $iuranRecords = ModelsIuranKas::where('id_tahun', $this->activeTahunId)
                ->whereIn('id_anggota', $memberIds)
                ->get()
                ->groupBy('id_anggota');

            foreach ($members as $member) {
                $memberIuran = $iuranRecords->get($member->id, collect());
                
                $payments = [];
                $totalBayar = 0;

                foreach ($this->periodeList as $periode) {
                    // Match by name
                    $record = $memberIuran->firstWhere('periode', $periode);
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

                $data = [
                    'id' => $member->id,
                    'nama' => $member->nama_lengkap,
                    'payments' => $payments,
                    'total_bayar' => $totalBayar
                ];

                $summary['total_keseluruhan'] += $totalBayar;

                if ($member->status_anggota === 'pengurus') {
                    $matrix['pengurus'][] = $data;
                } else {
                    $matrix['anggota'][] = $data;
                }
            }
        }
        
        return compact('matrix', 'summary');
    }

    public function downloadExcel()
    {
        $this->loadPeriodeList();
        $data = $this->prepareData();
        return Excel::download(new IuranKasExport($data['matrix'], $this->periodeList, $data['summary']), 'Laporan_Iuran_Kas.xlsx');
    }

    public function downloadPdf()
    {
        $this->loadPeriodeList();
        $data = $this->prepareData();
        
        $pdf = Pdf::loadView('exports.iuran-kas-table', [
            'matrix' => $data['matrix'],
            'periodeList' => $this->periodeList,
            'summary' => $data['summary']
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan_Iuran_Kas.pdf');
    }

    public function loadPeriodeList()
    {
        // Now fetch from IuranKasPeriode table
        $this->periodeList = IuranKasPeriode::where('id_tahun', $this->activeTahunId)
            ->orderBy('id', 'ASC') // Or by name if preferred
            ->pluck('nama_periode')
            ->toArray();
    }

    public function toggleStatus($anggotaId, $periode)
    {
        $iuran = ModelsIuranKas::where('id_tahun', $this->activeTahunId)
            ->where('id_anggota', $anggotaId)
            ->where('periode', $periode)
            ->first();

        if ($iuran) {
             $iuran->delete(); // Remove payment record
        } else {
            // Create payment record
            ModelsIuranKas::create([
                'id_tahun' => $this->activeTahunId,
                'id_anggota' => $anggotaId,
                'periode' => $periode,
                'nominal' => $this->nominalDefault,
                'status' => 'lunas',
                'tanggal_bayar' => now()->toDateString(),
                'id_user' => Auth::id(),
            ]);
        }
    }

    public function openGenerateModal()
    {
        $this->newPeriode = '';
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

        // Create the Period
        IuranKasPeriode::create([
            'id_tahun' => $this->activeTahunId,
            'nama_periode' => $this->newPeriode
        ]);

        $this->newPeriode = '';

        $this->dispatch('close-modal', ['id' => 'generateModal']);
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'message' => 'Success!',
            'text' => 'Periode baru berhasil dibuat.'
        ]);
    }

    public function renamePeriode()
    {
        $this->validate([
            'renamePeriodeValue' => 'required|string|max:100',
        ]);
        
        if ($this->renamePeriodeValue === $this->oldPeriodeName) {
            $this->dispatch('close-modal', ['id' => 'renameModal']);
            return;
        }

        $exists = IuranKasPeriode::where('id_tahun', $this->activeTahunId)
            ->where('nama_periode', $this->renamePeriodeValue)
            ->exists();

        if ($exists) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Gagal!',
                'text' => 'Nama periode sudah digunakan.'
            ]);
            return;
        }

        // Update Definition Table
        IuranKasPeriode::where('id_tahun', $this->activeTahunId)
            ->where('nama_periode', $this->oldPeriodeName)
            ->update(['nama_periode' => $this->renamePeriodeValue]);
            
        // Update Payments Table
        ModelsIuranKas::where('id_tahun', $this->activeTahunId)
            ->where('periode', $this->oldPeriodeName)
            ->update(['periode' => $this->renamePeriodeValue]);

        $this->dispatch('close-modal', ['id' => 'renameModal']);
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'message' => 'Success!',
            'text' => 'Periode berhasil diubah.'
        ]);
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
    
    public function destroyPeriode()
    {
        if(!$this->periodeToDelete) return;

        // Delete from Definition Table
        IuranKasPeriode::where('id_tahun', $this->activeTahunId)
            ->where('nama_periode', $this->periodeToDelete)
            ->delete();

        // Delete from Payments Table
        ModelsIuranKas::where('id_tahun', $this->activeTahunId)
            ->where('periode', $this->periodeToDelete)
            ->delete();
            
        $this->periodeToDelete = '';
        
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'message' => 'Terhapus!',
            'text' => 'Periode berhasil dihapus.'
        ]);
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
        
        $iuran = ModelsIuranKas::find($this->editIuranId);
        if ($iuran) {
            $iuran->update([
                'tanggal_bayar' => $this->editTanggalValue
            ]);
        }
        
        $this->dispatch('close-modal', ['id' => 'editDateModal']);
        $this->dispatch('swal:modal', [
            'type' => 'success',
            'message' => 'Updated!',
            'text' => 'Tanggal pembayaran berhasil diubah.'
        ]);
    }
    
    public function getDailyHistoryProperty()
    {
        return ModelsIuranKas::where('id_tahun', $this->activeTahunId)
            ->selectRaw('DATE(tanggal_bayar) as date, SUM(nominal) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
    
    public function openHistoryDetail($date)
    {
        $this->detailDate = $date;
        $this->detailMembers = ModelsIuranKas::with('anggota')
            ->where('id_tahun', $this->activeTahunId)
            ->whereDate('tanggal_bayar', $date)
            ->get();
            
        $this->dispatch('open-modal', ['id' => 'historyDetailModal']);
    }
    
    public function closeHistoryDetail()
    {
        $this->dispatch('close-modal', ['id' => 'historyDetailModal']);
    }
}
