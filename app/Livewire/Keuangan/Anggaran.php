<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Anggaran as ModelsAnggaran;
use App\Models\Department;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class Anggaran extends Component
{
    use WithPagination;
    #[Title('Anggaran')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'kategori'      => 'required',
        'jenis'         => 'required',
        'nama'          => 'required',
        'nominal'       => 'required|numeric|min:0',
        'id_department' => 'required_if:jenis,dept',
        'id_project'    => 'required_if:jenis,project',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $filterKategori = '';

    public $dataId;

    public $kategori, $jenis, $id_department, $id_project, $nama, $nominal;
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

        $query = ModelsAnggaran::with(['department', 'project', 'user'])
            ->where('id_tahun', $this->activeTahunId)
            ->where('nama', 'LIKE', $search);
            
        if ($this->filterKategori) {
            $query->where('kategori', $this->filterKategori);
        }

        $data = $query->orderBy('kategori', 'ASC')
            ->orderBy('jenis', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        // Calculate totals
        $totalPemasukan = ModelsAnggaran::where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'pemasukan')->sum('nominal');
        $totalPengeluaran = ModelsAnggaran::where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'pengeluaran')->sum('nominal');

        return view('livewire.keuangan.anggaran', compact('data', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function store()
    {
        $this->validate();

        ModelsAnggaran::create([
            'id_tahun'      => $this->activeTahunId,
            'kategori'      => $this->kategori,
            'jenis'         => $this->jenis,
            'id_department' => $this->jenis === 'dept' ? $this->id_department : null,
            'id_project'    => $this->jenis === 'project' ? $this->id_project : null,
            'nama'          => $this->nama,
            'nominal'       => $this->nominal,
            'id_user'       => Auth::id(),
        ]);

        $this->dispatchAlert('success', 'Success!', 'Anggaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsAnggaran::find($id);
        $this->dataId        = $id;
        $this->kategori      = $data->kategori;
        $this->jenis         = $data->jenis;
        $this->id_department = $data->id_department;
        $this->id_project    = $data->id_project;
        $this->nama          = $data->nama;
        $this->nominal       = $data->nominal;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsAnggaran::findOrFail($this->dataId)->update([
                'kategori'      => $this->kategori,
                'jenis'         => $this->jenis,
                'id_department' => $this->jenis === 'dept' ? $this->id_department : null,
                'id_project'    => $this->jenis === 'project' ? $this->id_project : null,
                'nama'          => $this->nama,
                'nominal'       => $this->nominal,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Anggaran berhasil diperbarui.');
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
        ModelsAnggaran::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Anggaran berhasil dihapus.');
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
        $this->kategori      = '';
        $this->jenis         = '';
        $this->id_department = '';
        $this->id_project    = '';
        $this->nama          = '';
        $this->nominal       = '';
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->resetInputFields();
    }

    public function getJenisLabel($jenis)
    {
        $labels = [
            'saldo_awal' => 'Saldo Awal',
            'iuran_kas'  => 'Iuran Kas',
            'sponsor'    => 'Sponsor',
            'dept'       => 'Departemen',
            'project'    => 'Project',
            'lainnya'    => 'Lainnya',
        ];
        return $labels[$jenis] ?? $jenis;
    }
}
