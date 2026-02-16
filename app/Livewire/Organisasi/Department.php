<?php

namespace App\Livewire\Organisasi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\TahunKepengurusan;
use App\Models\Department as ModelsDepartment;
use App\Traits\WithPermissionCache;

class Department extends Component
{
    use WithPagination, WithPermissionCache;
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
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        $this->tahuns = DB::table('tahun_kepengurusan')
            ->select('id', 'nama_tahun')
            ->orderBy('id', 'ASC')
            ->get();
            
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

        $query = DB::table('departments')
            ->select(
                'departments.id',
                'departments.id_tahun',
                'departments.nama_department',
                'departments.kategori',
                'departments.deskripsi',
                'departments.ikon',
                'departments.urutan',
                'departments.status',
                'departments.max_members',
                'tahun_kepengurusan.nama_tahun'
            )
            ->leftJoin('tahun_kepengurusan', 'departments.id_tahun', '=', 'tahun_kepengurusan.id')
            ->where('tahun_kepengurusan.status', 'aktif')
            ->where(function ($query) use ($search) {
                $query->where('tahun_kepengurusan.nama_tahun', 'LIKE', $search)
                      ->orWhere('departments.nama_department', 'LIKE', $search);
            })
            ->orderBy('departments.id', 'ASC');

        $total = $query->count();
        $departments = $query->skip(($this->getPage() - 1) * $this->lengthData)
            ->take($this->lengthData)
            ->get();

        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $departments,
            $total,
            $this->lengthData,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.organisasi.department', compact('data'));
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            DB::table('departments')->insert([
                'id_tahun'            => $this->id_tahun,
                'nama_department'     => $this->nama_department,
                'kategori'            => $this->kategori,
                'deskripsi'           => $this->deskripsi,
                'ikon'                => $this->ikon,
                'urutan'              => $this->urutan,
                'status'              => $this->status,
                'max_members'         => $this->max_members ?: null,
            ]);

            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->isEditing = true;
        try {
            $data = DB::table('departments')
                ->select(
                    'id',
                    'id_tahun',
                    'nama_department',
                    'kategori',
                    'deskripsi',
                    'ikon',
                    'urutan',
                    'status',
                    'max_members'
                )
                ->where('id', $id)
                ->first();
                
            $this->dataId           = $id;
            $this->id_tahun         = $data->id_tahun;
            $this->nama_department  = $data->nama_department;
            $this->kategori         = $data->kategori;
            $this->deskripsi        = $data->deskripsi;
            $this->ikon             = $data->ikon;
            $this->urutan           = $data->urutan;
            $this->status           = $data->status;
            $this->max_members      = $data->max_members;
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            DB::beginTransaction();
            try {
                DB::table('departments')
                    ->where('id', $this->dataId)
                    ->update([
                        'id_tahun'            => $this->id_tahun,
                        'nama_department'     => $this->nama_department,
                        'kategori'            => $this->kategori,
                        'deskripsi'           => $this->deskripsi,
                        'ikon'                => $this->ikon,
                        'urutan'              => $this->urutan,
                        'status'              => $this->status,
                        'max_members'         => $this->max_members ?: null,
                    ]);

                DB::commit();
                $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
                $this->dataId = null;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
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
            $department = \App\Models\Department::findOrFail($this->dataId);
            
            // Hapus foto dari anggota yang ada di department ini
            $anggotas = \App\Models\Anggota::where('id_department', $this->dataId)->get();
            foreach ($anggotas as $anggota) {
                if ($anggota->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto);
                }
            }
            
            // Hapus department (cascade akan hapus anggota)
            $department->delete();
                
            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
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
