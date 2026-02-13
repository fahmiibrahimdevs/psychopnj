<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\JenisAnggaran as ModelsJenisAnggaran;
use App\Traits\WithPermissionCache;

class JenisAnggaran extends Component
{
    use WithPermissionCache;
    #[Title('Jenis Anggaran')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama_kategori' => 'required|in:pemasukan,pengeluaran',
        'nama_jenis'    => 'required|string|max:255',
    ];

    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $nama_kategori, $nama_jenis;

    public function mount()
    {
        $this->cacheUserPermissions();
        $this->resetInputFields();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $data = \Illuminate\Support\Facades\DB::table('jenis_anggaran')
                ->select('id', 'nama_kategori', 'nama_jenis')
                ->where('nama_kategori', 'LIKE', $search)
                ->orWhere('nama_jenis', 'LIKE', $search)
                ->orderBy('nama_kategori', 'ASC')
                ->orderBy('nama_jenis', 'ASC')
                ->get()
                ->groupBy('nama_kategori');

        return view('livewire.keuangan.jenis-anggaran', compact('data'));
    }

    public function store()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            ModelsJenisAnggaran::create([
                'nama_kategori' => $this->nama_kategori,
                'nama_jenis'    => $this->nama_jenis,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Jenis anggaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function updated()
    {
        $this->dispatch('initSelect2');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsJenisAnggaran::find($id);
        $this->dataId        = $id;
        $this->nama_kategori = $data->nama_kategori;
        $this->nama_jenis    = $data->nama_jenis;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                ModelsJenisAnggaran::findOrFail($this->dataId)->update([
                    'nama_kategori' => $this->nama_kategori,
                    'nama_jenis'    => $this->nama_jenis,
                ]);

                \Illuminate\Support\Facades\DB::commit();
                $this->dispatchAlert('success', 'Success!', 'Jenis anggaran berhasil diperbarui.');
                $this->dataId = null;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
            }
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
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            ModelsJenisAnggaran::findOrFail($this->dataId)->delete();
            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Jenis anggaran berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    private function searchResetPage()
    {
        if ($this->searchTerm !== $this->previousSearchTerm) {
            $this->previousSearchTerm = $this->searchTerm;
        }
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
        $this->nama_kategori = '';
        $this->nama_jenis    = '';
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->resetInputFields();
    }
}
