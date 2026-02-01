<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Anggota;
use App\Models\Project as ModelsProject;

class Project extends Component
{
    use WithPagination;
    #[Title('Projects')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_tahun'        => 'required',
        'nama_project'    => 'required',
        'deskripsi'       => 'required',
        'id_leader'       => 'required',
        'id_anggota'      => '',
        'status'          => 'required',
        'tanggal_mulai'   => 'nullable|date',
        'tanggal_selesai' => 'nullable|date',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $id_tahun, $nama_project, $deskripsi, $id_leader = [], $id_anggota = [], $status, $tanggal_mulai, $tanggal_selesai, $thumbnail, $link_gdrive;
    public $tahuns, $pengurus, $anggotas, $activeTahunId;
    public $viewData = null;

    public function mount()
    {
        $this->tahuns = TahunKepengurusan::select('id', 'nama_tahun')->orderBy('id', 'ASC')->get();
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;
        
        if ($activeTahun) {
            $this->pengurus = Anggota::where('id_tahun', $activeTahun->id)
                ->where('status_anggota', 'pengurus')
                ->where('status_aktif', 'aktif')
                ->select('id', 'nama_lengkap', 'nama_jabatan')
                ->get();
            $this->anggotas = Anggota::where('id_tahun', $activeTahun->id)
                ->where('status_anggota', 'anggota')
                ->where('status_aktif', 'aktif')
                ->select('id', 'nama_lengkap', 'status_anggota')
                ->get();
        } else {
            $this->pengurus = collect();
            $this->anggotas = collect();
        }
        
        $this->resetInputFields();
        $this->loadAvailableAnggotas();
    }

    private function loadAvailableAnggotas($excludeProjectId = null)
    {
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        if (!$activeTahun) {
            $this->anggotas = collect();
            return;
        }

        // Get all anggota IDs that are already assigned to projects
        $assignedAnggotaIds = [];
        $projects = ModelsProject::where('id_tahun', $activeTahun->id)
            ->when($excludeProjectId, function($query) use ($excludeProjectId) {
                $query->where('id', '!=', $excludeProjectId);
            })
            ->get();
        
        foreach ($projects as $project) {
            if ($project->id_anggota) {
                $ids = explode(',', $project->id_anggota);
                $assignedAnggotaIds = array_merge($assignedAnggotaIds, $ids);
            }
        }
        $assignedAnggotaIds = array_unique($assignedAnggotaIds);

        $this->anggotas = Anggota::where('id_tahun', $activeTahun->id)
            ->where('status_anggota', 'anggota')
            ->where('status_aktif', 'aktif')
            ->whereNotIn('id', $assignedAnggotaIds)
            ->select('id', 'nama_lengkap', 'status_anggota')
            ->get();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $data = ModelsProject::select('projects.*', 'tahun_kepengurusan.nama_tahun', 'anggota.nama_lengkap as leader_name')
                ->leftJoin('tahun_kepengurusan', 'projects.id_tahun', '=', 'tahun_kepengurusan.id')
                ->leftJoin('anggota', 'projects.id_leader', '=', 'anggota.id')
                ->where(function ($query) use ($search) {
                    $query->where('nama_tahun', 'LIKE', $search);
                    $query->orWhere('nama_project', 'LIKE', $search);
                    $query->orWhere('anggota.nama_lengkap', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->orderBy('projects.id', 'ASC')
                ->paginate($this->lengthData);

        return view('livewire.akademik.project', compact('data'));
    }

    public function store()
    {
        $this->validate();

        $leaderIds = is_array($this->id_leader) ? implode(',', $this->id_leader) : $this->id_leader;
        $anggotaIds = is_array($this->id_anggota) ? implode(',', $this->id_anggota) : $this->id_anggota;

        ModelsProject::create([
            'id_tahun'        => $this->id_tahun,
            'nama_project'    => $this->nama_project,
            'deskripsi'       => $this->deskripsi,
            'id_leader'       => $leaderIds,
            'id_anggota'      => $anggotaIds,
            'status'          => $this->status,
            'tanggal_mulai'   => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'thumbnail'       => $this->thumbnail,
            'link_gdrive'     => $this->link_gdrive,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Project created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsProject::where('id', $id)->first();
        $this->dataId           = $id;
        $this->id_tahun         = $data->id_tahun;
        $this->nama_project     = $data->nama_project;
        $this->deskripsi        = $data->deskripsi;
        $this->id_leader        = $data->id_leader ? explode(',', $data->id_leader) : [];
        $this->id_anggota       = $data->id_anggota ? explode(',', $data->id_anggota) : [];
        $this->status           = $data->status;
        $this->tanggal_mulai    = $data->tanggal_mulai;
        $this->tanggal_selesai  = $data->tanggal_selesai;
        $this->thumbnail        = $data->thumbnail;
        $this->link_gdrive      = $data->link_gdrive;
        
        // Reload available anggotas excluding current project's anggota
        $this->loadAvailableAnggotas($id);
        
        // Re-add the current project's anggota to the list for editing
        if ($data->id_anggota) {
            $currentAnggotaIds = explode(',', $data->id_anggota);
            $currentAnggotas = Anggota::whereIn('id', $currentAnggotaIds)
                ->select('id', 'nama_lengkap', 'status_anggota')
                ->get();
            $this->anggotas = $this->anggotas->merge($currentAnggotas)->unique('id');
        }
        $this->dispatch('initSelect2');
    }

    public function view($id)
    {
        $data = ModelsProject::where('id', $id)->first();
        
        // Get leader names
        $leaderIds = $data->id_leader ? explode(',', $data->id_leader) : [];
        $leaders = Anggota::whereIn('id', $leaderIds)->get();
        
        // Get anggota names
        $anggotaIds = $data->id_anggota ? explode(',', $data->id_anggota) : [];
        $anggotas = Anggota::whereIn('id', $anggotaIds)->get();
        
        $this->viewData = [
            'project' => $data,
            'leaders' => $leaders,
            'anggotas' => $anggotas,
        ];
    }

    public function update()
    {
        $this->validate();

        $leaderIds = is_array($this->id_leader) ? implode(',', $this->id_leader) : $this->id_leader;
        $anggotaIds = is_array($this->id_anggota) ? implode(',', $this->id_anggota) : $this->id_anggota;

        if ($this->dataId) {
            ModelsProject::findOrFail($this->dataId)->update([
                'id_tahun'        => $this->id_tahun,
                'nama_project'    => $this->nama_project,
                'deskripsi'       => $this->deskripsi,
                'id_leader'       => $leaderIds,
                'id_anggota'      => $anggotaIds,
                'status'          => $this->status,
                'tanggal_mulai'   => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
                'thumbnail'       => $this->thumbnail,
                'link_gdrive'     => $this->link_gdrive,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Project updated successfully.');
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
        ModelsProject::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Success!', 'Project deleted successfully.');
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
        $this->dispatch('initSelect2');
    }

    public function updated()
    {
        $this->dispatch('initSelect2');
    }
    
    private function resetInputFields()
    {
        $this->id_tahun         = $this->activeTahunId;
        $this->nama_project     = '';
        $this->deskripsi        = '';
        $this->id_leader        = [];
        $this->id_anggota       = [];
        $this->status           = '';
        $this->tanggal_mulai    = null;
        $this->tanggal_selesai  = null;
        $this->thumbnail        = '';
        $this->link_gdrive      = '';
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->resetInputFields();
    }
}
