<?php

namespace App\Livewire\Organisasi;

use App\Models\TahunKepengurusan as ModelsTahunKepengurusan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class TahunKepengurusan extends Component
{
    use WithPagination;
    #[Title('Tahun Kepengurusan')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama_tahun'          => 'required',
        'mulai'               => 'required',
        'akhir'               => 'required',
        'status'              => 'required',
        'deskripsi'           => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $nama_tahun, $mulai, $akhir, $status, $deskripsi;

    public function mount()
    {
        $this->nama_tahun          = '';
        $this->mulai               = date('Y')+1;
        $this->akhir               = date('Y')+2;
        $this->status              = 'nonaktif';
        $this->deskripsi           = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $data = ModelsTahunKepengurusan::select(
                'tahun_kepengurusan.id', 
                'tahun_kepengurusan.nama_tahun', 
                'tahun_kepengurusan.status',
                'ketua.nama_lengkap as nama_ketua',
                'wakil.nama_lengkap as nama_waketua'
            )
            ->leftJoin('anggota as ketua', function($join) {
                $join->on('tahun_kepengurusan.id', '=', 'ketua.id_tahun')
                     ->where('ketua.nama_jabatan', '=', 'Ketua')
                     ->where('ketua.status_aktif', '=', 'aktif');
            })
            ->leftJoin('anggota as wakil', function($join) {
                $join->on('tahun_kepengurusan.id', '=', 'wakil.id_tahun')
                     ->where('wakil.nama_jabatan', '=', 'Wakil Ketua')
                     ->where('wakil.status_aktif', '=', 'aktif');
            })
            ->where(function ($query) use ($search) {
                $query->where('tahun_kepengurusan.nama_tahun', 'LIKE', $search);
                $query->orWhere('ketua.nama_lengkap', 'LIKE', $search);
                $query->orWhere('wakil.nama_lengkap', 'LIKE', $search);
            })
            ->orderBy('tahun_kepengurusan.id', 'DESC')
            ->paginate($this->lengthData);

        return view('livewire.organisasi.tahun-kepengurusan', compact('data'));
    }

    public function store()
    {
        $this->validate();

        // Jika status aktif, nonaktifkan semua tahun kepengurusan lain
        if ($this->status == 'aktif') {
            ModelsTahunKepengurusan::where('status', 'aktif')->update(['status' => 'nonaktif']);
        }

        ModelsTahunKepengurusan::create([
            'nama_tahun'          => $this->nama_tahun,
            'mulai'               => $this->mulai,
            'akhir'               => $this->akhir,
            'status'              => $this->status,
            'deskripsi'           => $this->deskripsi,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsTahunKepengurusan::where('id', $id)->first();
        $this->dataId           = $id;
        $this->nama_tahun       = $data->nama_tahun;
        $this->mulai            = $data->mulai;
        $this->akhir            = $data->akhir;
        $this->status           = $data->status;
        $this->deskripsi        = $data->deskripsi;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            // Jika status aktif, nonaktifkan semua tahun kepengurusan lain
            if ($this->status == 'aktif') {
                ModelsTahunKepengurusan::where('id', '!=', $this->dataId)
                    ->where('status', 'aktif')
                    ->update(['status' => 'nonaktif']);
            }

            ModelsTahunKepengurusan::findOrFail($this->dataId)->update([
                'nama_tahun'          => $this->nama_tahun,
                'mulai'               => $this->mulai,
                'akhir'               => $this->akhir,
                'status'              => $this->status,
                'deskripsi'           => $this->deskripsi,
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
        ModelsTahunKepengurusan::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
    }

    public function activate($id)
    {
        // Nonaktifkan semua tahun kepengurusan
        ModelsTahunKepengurusan::where('status', 'aktif')->update(['status' => 'nonaktif']);
        
        // Aktifkan tahun kepengurusan yang dipilih
        ModelsTahunKepengurusan::findOrFail($id)->update(['status' => 'aktif']);
        
        $this->dispatchAlert('success', 'Success!', 'Tahun kepengurusan berhasil diaktifkan.');
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
        $this->nama_tahun          = '';
        $this->mulai               = date('Y')+1;
        $this->akhir               = date('Y')+2;
        $this->status              = 'nonaktif';
        $this->deskripsi           = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
