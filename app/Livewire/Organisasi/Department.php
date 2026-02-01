<?php

namespace App\Livewire\Organisasi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Department as ModelsDepartment;

class Department extends Component
{
    use WithPagination;
    #[Title('Department')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_tahun'            => 'required',
        'nama_department'     => 'required',
        'kategori'            => 'required',
        'deskripsi'           => 'required',
        'ikon'                => 'required',
        'urutan'              => 'required',
        'status'              => 'required',
        'max_members'         => 'nullable|numeric',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $id_tahun, $nama_department, $kategori, $deskripsi, $ikon, $urutan, $status, $max_members;
    public $tahuns;

    public function mount()
    {
        $this->tahuns = TahunKepengurusan::select('id', 'nama_tahun')->orderBy('id', 'ASC')->get();
        $this->id_tahun            = '';
        $this->nama_department     = '';
        $this->kategori            = '';
        $this->deskripsi           = '';
        $this->ikon                = '';
        $this->urutan              = '';
        $this->status              = '';
        $this->max_members         = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $data = ModelsDepartment::select('departments.*', 'tahun_kepengurusan.nama_tahun')
                ->leftJoin('tahun_kepengurusan', 'departments.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where(function ($query) use ($search) {
                    $query->where('nama_tahun', 'LIKE', $search);
                    $query->orWhere('nama_department', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->orderBy('id', 'ASC')
                ->paginate($this->lengthData);

        return view('livewire.organisasi.department', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsDepartment::create([
            'id_tahun'            => $this->id_tahun,
            'nama_department'     => $this->nama_department,
            'kategori'            => $this->kategori,
            'deskripsi'           => $this->deskripsi,
            'ikon'                => $this->ikon,
            'urutan'              => $this->urutan,
            'status'              => $this->status,
            'max_members'         => $this->max_members,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsDepartment::where('id', $id)->first();
        $this->dataId           = $id;
        $this->id_tahun         = $data->id_tahun;
        $this->nama_department  = $data->nama_department;
        $this->kategori         = $data->kategori;
        $this->deskripsi        = $data->deskripsi;
        $this->ikon             = $data->ikon;
        $this->urutan           = $data->urutan;
        $this->status           = $data->status;
        $this->max_members      = $data->max_members;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            ModelsDepartment::findOrFail($this->dataId)->update([
                'id_tahun'            => $this->id_tahun,
                'nama_department'     => $this->nama_department,
                'kategori'            => $this->kategori,
                'deskripsi'           => $this->deskripsi,
                'ikon'                => $this->ikon,
                'urutan'              => $this->urutan,
                'status'              => $this->status,
                'max_members'         => $this->max_members,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
            $this->dataId = null;
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type'      => 'warning',  
            'message'   => 'Are you sure?', 
            'text'      => 'If you delete the data, it cannot be restored!'
        ]);
    }

    public function delete()
    {
        ModelsDepartment::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
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
        $this->id_tahun            = 'opsi1';
        $this->nama_department     = '';
        $this->kategori            = '';
        $this->deskripsi           = '';
        $this->ikon                = '';
        $this->urutan              = '';
        $this->status              = 'opsi1';
        $this->max_members         = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
