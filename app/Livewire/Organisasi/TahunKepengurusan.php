<?php

namespace App\Livewire\Organisasi;

use App\Models\TahunKepengurusan as ModelsTahunKepengurusan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class TahunKepengurusan extends Component
{
    use WithPagination;
    #[Title('Tahun Kepengurusan')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama_tahun'          => 'required',
        'status'              => 'required',
        'deskripsi'           => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $nama_tahun, $status, $deskripsi;

    public function mount()
    {
        $this->nama_tahun          = '';
        $this->status              = 'nonaktif';
        $this->deskripsi           = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $query = DB::table('tahun_kepengurusan')
            ->select(
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
                $query->where('tahun_kepengurusan.nama_tahun', 'LIKE', $search)
                      ->orWhere('ketua.nama_lengkap', 'LIKE', $search)
                      ->orWhere('wakil.nama_lengkap', 'LIKE', $search);
            })
            ->orderBy('tahun_kepengurusan.id', 'DESC');

        $total = $query->count();
        $tahuns = $query->skip(($this->getPage() - 1) * $this->lengthData)
            ->take($this->lengthData)
            ->get();

        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $tahuns,
            $total,
            $this->lengthData,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.organisasi.tahun-kepengurusan', compact('data'));
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Jika status aktif, nonaktifkan semua tahun kepengurusan lain
            if ($this->status == 'aktif') {
                DB::table('tahun_kepengurusan')
                    ->where('status', 'aktif')
                    ->update(['status' => 'nonaktif']);
            }

            DB::table('tahun_kepengurusan')->insert([
                'nama_tahun'          => $this->nama_tahun,
                'status'              => $this->status,
                'deskripsi'           => $this->deskripsi,
            ]);

            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593');
        }
    }

    public function edit($id)
    {
        $this->isEditing = true;
        try {
            $data = DB::table('tahun_kepengurusan')
                ->select('id', 'nama_tahun', 'status', 'deskripsi')
                ->where('id', $id)
                ->first();
                
            $this->dataId           = $id;
            $this->nama_tahun       = $data->nama_tahun;
            $this->status           = $data->status;
            $this->deskripsi        = $data->deskripsi;
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593');
        }
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            DB::beginTransaction();
            try {
                // Jika status aktif, nonaktifkan semua tahun kepengurusan lain
                if ($this->status == 'aktif') {
                    DB::table('tahun_kepengurusan')
                        ->where('id', '!=', $this->dataId)
                        ->where('status', 'aktif')
                        ->update(['status' => 'nonaktif']);
                }

                DB::table('tahun_kepengurusan')
                    ->where('id', $this->dataId)
                    ->update([
                        'nama_tahun'          => $this->nama_tahun,
                        'status'              => $this->status,
                        'deskripsi'           => $this->deskripsi,
                    ]);

                DB::commit();
                $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
                $this->dataId = null;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593');
            }
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
        DB::beginTransaction();
        try {
            DB::table('tahun_kepengurusan')
                ->where('id', $this->dataId)
                ->delete();
                
            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593');
        }
    }

    public function activate($id)
    {
        DB::beginTransaction();
        try {
            // Nonaktifkan semua tahun kepengurusan
            DB::table('tahun_kepengurusan')
                ->where('status', 'aktif')
                ->update(['status' => 'nonaktif']);
            
            // Aktifkan tahun kepengurusan yang dipilih
            DB::table('tahun_kepengurusan')
                ->where('id', $id)
                ->update(['status' => 'aktif']);
            
            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Tahun kepengurusan berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593');
        }
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
        $this->status              = 'nonaktif';
        $this->deskripsi           = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
