<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Anggaran as ModelsAnggaran;
use App\Models\Department;
use App\Models\Project;
use App\Models\JenisAnggaran;
use App\Traits\WithPermissionCache;
use Illuminate\Support\Facades\Auth;

class Anggaran extends Component
{
    use WithPagination, WithPermissionCache;
    #[Title('Anggaran')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'kategori'      => 'required',
        'jenis'         => 'required',
        'nama'          => 'required',
        'nominal'       => 'required|numeric|min:0',
        'id_department' => 'required_if:jenis,Departemen',
        'id_project'    => 'required_if:jenis,Project',
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
    public $jenisAnggaranList = [];

    public function mount()
    {
        $this->cacheUserPermissions();
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;

        // Load jenis anggaran from database
        $this->loadJenisAnggaran();

        // Filter departments by active tahun
        $this->departments = \Illuminate\Support\Facades\DB::table('departments')
            ->where('id_tahun', $this->activeTahunId)
            ->select('id', 'nama_department')
            ->orderBy('nama_department')
            ->get();
        $this->projects = \Illuminate\Support\Facades\DB::table('projects')
            ->where('id_tahun', $this->activeTahunId)
            ->select('id', 'nama_project')
            ->orderBy('nama_project')
            ->get();
        
        $this->resetInputFields();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $query = \Illuminate\Support\Facades\DB::table('anggaran')
            ->leftJoin('departments', 'anggaran.id_department', '=', 'departments.id')
            ->leftJoin('projects', 'anggaran.id_project', '=', 'projects.id')
            ->leftJoin('users', 'anggaran.id_user', '=', 'users.id')
            ->select(
                'anggaran.id',
                'anggaran.kategori',
                'anggaran.jenis',
                'anggaran.nama',
                'anggaran.nominal',
                'anggaran.id_department',
                'anggaran.id_project',
                'departments.nama_department as department_name',
                'projects.nama_project as project_name',
                'users.name as user_name'
            )
            ->where('anggaran.id_tahun', $this->activeTahunId)
            ->where('anggaran.nama', 'LIKE', $search);
            
        if ($this->filterKategori) {
            $query->where('anggaran.kategori', $this->filterKategori);
        }

        $data = $query->orderBy('anggaran.kategori', 'ASC')
            ->orderBy('anggaran.jenis', 'ASC')
            ->orderBy('anggaran.id', 'ASC')
            ->get();

        // Calculate totals
        $totalPemasukan = \Illuminate\Support\Facades\DB::table('anggaran')
            ->where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'pemasukan')->sum('nominal');
        $totalPengeluaran = \Illuminate\Support\Facades\DB::table('anggaran')
            ->where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'pengeluaran')->sum('nominal');

        return view('livewire.keuangan.anggaran', compact('data', 'totalPemasukan', 'totalPengeluaran'));
    }

    public function store()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            ModelsAnggaran::create([
                'id_tahun'      => $this->activeTahunId,
                'kategori'      => $this->kategori,
                'jenis'         => $this->jenis,
                'id_department' => $this->jenis === 'Departemen' ? $this->id_department : null,
                'id_project'    => $this->jenis === 'Project' ? $this->id_project : null,
                'nama'          => $this->nama,
                'nominal'       => $this->nominal,
                'id_user'       => Auth::id(),
            ]);

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Anggaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function updated()
    {
        $this->dispatch('initSelect2');
    }

    public function updatedKategori()
    {
        $this->jenis = "";
        $this->id_department = "";
        $this->id_project = "";
    }

    public function updatedJenis()
    {
        $this->id_department = "";
        $this->id_project = "";
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
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                ModelsAnggaran::findOrFail($this->dataId)->update([
                    'kategori'      => $this->kategori,
                    'jenis'         => $this->jenis,
                    'id_department' => $this->jenis === 'Departemen' ? $this->id_department : null,
                    'id_project'    => $this->jenis === 'Project' ? $this->id_project : null,
                    'nama'          => $this->nama,
                    'nominal'       => $this->nominal,
                ]);

                \Illuminate\Support\Facades\DB::commit();
                $this->dispatchAlert('success', 'Success!', 'Anggaran berhasil diperbarui.');
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
            ModelsAnggaran::findOrFail($this->dataId)->delete();
            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Anggaran berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
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
        // Karena sekarang sudah disimpan dalam format Capitalized, langsung return
        return $jenis;
    }

    public function loadJenisAnggaran()
    {
        $this->jenisAnggaranList = \Illuminate\Support\Facades\DB::table('jenis_anggaran')
            ->select('nama_kategori', 'nama_jenis')
            ->orderBy('nama_kategori')
            ->orderBy('nama_jenis')
            ->get()
            ->groupBy('nama_kategori')
            ->toArray();
    }

    public function getJenisAnggaranByKategori($kategori)
    {
        return \Illuminate\Support\Facades\DB::table('jenis_anggaran')
            ->where('nama_kategori', $kategori)
            ->pluck('nama_jenis')
            ->toArray();
    }
}
