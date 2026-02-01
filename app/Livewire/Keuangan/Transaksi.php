<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Keuangan as ModelsKeuangan;
use App\Models\Department;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KeuanganExport;

class Transaksi extends Component
{
    use WithPagination;
    #[Title('Transaksi Keuangan')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'tanggal'       => 'required|date',
        'jenis'         => 'required',
        'kategori'      => 'required',
        'deskripsi'     => 'required',
        'nominal'       => 'required|numeric|min:0',
        'id_department' => 'required_if:kategori,dept',
        'id_project'    => 'required_if:kategori,project',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $filterJenis = '';
    public $filterKategori = '';

    public $dataId;

    public $tanggal, $jenis, $kategori, $id_department, $id_project, $deskripsi, $nominal, $bukti;
    public $activeTahunId;
    public $departments, $projects;

    public function mount()
    {
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;

        // Filter departments by active tahun
        $this->departments = Department::where('id_tahun', $this->activeTahunId)
            ->select('id', 'nama_department')
            ->orderBy('nama_department')
            ->get();
        $this->projects = Project::where('id_tahun', $this->activeTahunId)
            ->select('id', 'nama_project')
            ->orderBy('nama_project')
            ->get();
        
        $this->resetInputFields();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $query = ModelsKeuangan::with(['department', 'project'])
            ->where('id_tahun', $this->activeTahunId)
            ->where('deskripsi', 'LIKE', $search);
            
        if ($this->filterJenis) {
            $query->where('jenis', $this->filterJenis);
        }
        if ($this->filterKategori) {
            $query->where('kategori', $this->filterKategori);
        }

        $data = $query->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate($this->lengthData);

        // Calculate running totals
        $allTransactions = ModelsKeuangan::where('id_tahun', $this->activeTahunId)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $runningTotal = 0;
        $runningTotals = [];
        foreach ($allTransactions as $tx) {
            if ($tx->jenis === 'pemasukan') {
                $runningTotal += $tx->nominal;
            } else {
                $runningTotal -= $tx->nominal;
            }
            $runningTotals[$tx->id] = $runningTotal;
        }

        // Summary
        $totalPemasukan = ModelsKeuangan::where('id_tahun', $this->activeTahunId)
            ->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = ModelsKeuangan::where('id_tahun', $this->activeTahunId)
            ->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('livewire.keuangan.transaksi', compact('data', 'runningTotals', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    public function store()
    {
        $this->validate();

        ModelsKeuangan::create([
            'id_tahun'      => $this->activeTahunId,
            'tanggal'       => $this->tanggal,
            'jenis'         => $this->jenis,
            'kategori'      => $this->kategori,
            'id_department' => $this->kategori === 'dept' ? $this->id_department : null,
            'id_project'    => $this->kategori === 'project' ? $this->id_project : null,
            'deskripsi'     => $this->deskripsi,
            'nominal'       => $this->nominal,
            'bukti'         => $this->bukti,
            'id_user'       => Auth::id(),
        ]);

        $this->dispatchAlert('success', 'Success!', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsKeuangan::find($id);
        $this->dataId        = $id;
        $this->tanggal       = $data->tanggal->format('Y-m-d');
        $this->jenis         = $data->jenis;
        $this->kategori      = $data->kategori;
        $this->id_department = $data->id_department;
        $this->id_project    = $data->id_project;
        $this->deskripsi     = $data->deskripsi;
        $this->nominal       = $data->nominal;
        $this->bukti         = $data->bukti;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsKeuangan::findOrFail($this->dataId)->update([
                'tanggal'       => $this->tanggal,
                'jenis'         => $this->jenis,
                'kategori'      => $this->kategori,
                'id_department' => $this->kategori === 'dept' ? $this->id_department : null,
                'id_project'    => $this->kategori === 'project' ? $this->id_project : null,
                'deskripsi'     => $this->deskripsi,
                'nominal'       => $this->nominal,
                'bukti'         => $this->bukti,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Transaksi berhasil diperbarui.');
            $this->dataId = null;
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type'      => 'warning',  
            'message'   => 'Yakin hapus?', 
            'text'      => 'Data yang dihapus tidak dapat dikembalikan!'
        ]);
    }

    public function delete()
    {
        ModelsKeuangan::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Transaksi berhasil dihapus.');
    }

    public function updatingLengthData()
    {
        $this->resetPage();
    }

    private function searchResetPage()
    {
        if ($this->searchTerm !== $this->previousSearchTerm) {
            $this->resetPage();
        }
        $this->previousSearchTerm = $this->searchTerm;
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type'      => $type,  
            'message'   => $message, 
            'text'      => $text
        ]);

        $this->resetInputFields();
    }

    public function isEditingMode($mode)
    {
        $this->isEditing = $mode;
    }
    
    private function resetInputFields()
    {
        $this->tanggal       = date('Y-m-d');
        $this->jenis         = '';
        $this->kategori      = '';
        $this->id_department = '';
        $this->id_project    = '';
        $this->deskripsi     = '';
        $this->nominal       = '';
        $this->bukti         = '';
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->resetInputFields();
    }

    public function getKategoriLabel($kategori)
    {
        $labels = [
            'saldo_awal' => 'Saldo Awal',
            'iuran_kas'  => 'Iuran Kas',
            'sponsor'    => 'Sponsor',
            'dept'       => 'Departemen',
            'project'    => 'Project',
            'lainnya'    => 'Lainnya',
        ];
        return $labels[$kategori] ?? $kategori;
    }

    public function downloadExcel()
    {
        $data = $this->prepareExportData();
        return Excel::download(new KeuanganExport($data), 'Buku_Kas_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadPdf()
    {
        $data = $this->prepareExportData();
        
        $pdf = Pdf::loadView('exports.keuangan-table', [
            'data' => $data
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Buku_Kas_' . date('Y-m-d') . '.pdf');
    }

    private function prepareExportData()
    {
        $transactions = ModelsKeuangan::with(['department', 'project'])
            ->where('id_tahun', $this->activeTahunId)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $runningTotal = 0;
        $data = [];

        foreach ($transactions as $tx) {
            if ($tx->jenis === 'pemasukan') {
                $runningTotal += $tx->nominal;
            } else {
                $runningTotal -= $tx->nominal;
            }

            $data[] = [
                'tanggal' => $tx->tanggal,
                'deskripsi' => $tx->deskripsi,
                'kategori' => $this->getKategoriLabel($tx->kategori),
                'department' => $tx->department ? $tx->department->nama_department : '-',
                'project' => $tx->project ? $tx->project->nama_project : '-',
                'pemasukan' => $tx->jenis === 'pemasukan' ? $tx->nominal : 0,
                'pengeluaran' => $tx->jenis === 'pengeluaran' ? $tx->nominal : 0,
                'saldo' => $runningTotal
            ];
        }

        return $data;
    }
}
