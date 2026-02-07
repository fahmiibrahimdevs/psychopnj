<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\TahunKepengurusan;
use App\Models\Anggota;
use App\Models\Project as ModelsProject;
use App\Models\ProjectTeamMember;

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
        $this->tahuns = DB::table('tahun_kepengurusan')
            ->select('id', 'nama_tahun')
            ->orderBy('id', 'ASC')
            ->get();
            
        $activeTahun = DB::table('tahun_kepengurusan')
            ->select('id')
            ->where('status', 'aktif')
            ->first();
            
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;
        
        if ($activeTahun) {
            $this->pengurus = DB::table('anggota')
                ->select('id', 'nama_lengkap', 'nama_jabatan')
                ->where('id_tahun', $activeTahun->id)
                ->where('status_anggota', 'pengurus')
                ->where('status_aktif', 'aktif')
                ->orderBy('nama_lengkap')
                ->get();
                
            $this->anggotas = DB::table('anggota')
                ->select('id', 'nama_lengkap', 'status_anggota')
                ->where('id_tahun', $activeTahun->id)
                ->where('status_anggota', 'anggota')
                ->where('status_aktif', 'aktif')
                ->orderBy('nama_lengkap')
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
        $activeTahun = DB::table('tahun_kepengurusan')
            ->select('id')
            ->where('status', 'aktif')
            ->first();
            
        if (!$activeTahun) {
            $this->anggotas = collect();
            return;
        }

        // Get all anggota IDs that are already assigned to projects (through teams)
        $assignedAnggotaIds = DB::table('project_team_members')
            ->join('project_teams', 'project_team_members.id_project_team', '=', 'project_teams.id')
            ->join('projects', 'project_teams.id_project', '=', 'projects.id')
            ->where('projects.id_tahun', $activeTahun->id)
            ->when($excludeProjectId, function($query) use ($excludeProjectId) {
                $query->where('projects.id', '!=', $excludeProjectId);
            })
            ->pluck('project_team_members.id_anggota')
            ->unique()
            ->toArray();

        $this->anggotas = DB::table('anggota')
            ->select('id', 'nama_lengkap', 'status_anggota')
            ->where('id_tahun', $activeTahun->id)
            ->where('status_anggota', 'anggota')
            ->where('status_aktif', 'aktif')
            ->whereNotIn('id', $assignedAnggotaIds)
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        // Optimized single query with subqueries for counts
        $query = DB::table('projects')
            ->select(
                'projects.id',
                'projects.id_tahun',
                'projects.nama_project',
                'projects.deskripsi',
                'projects.status',
                'projects.tanggal_mulai',
                'projects.tanggal_selesai',
                'tahun_kepengurusan.nama_tahun',
                // Subquery for teams count
                DB::raw('(SELECT COUNT(*) FROM project_teams WHERE project_teams.id_project = projects.id) as teams_count'),
                // Subquery for leaders count
                DB::raw('(SELECT COUNT(*) FROM project_team_members 
                         INNER JOIN project_teams ON project_teams.id = project_team_members.id_project_team
                         WHERE project_teams.id_project = projects.id 
                         AND project_team_members.role = "leader") as leaders_count'),
                // Subquery for members count
                DB::raw('(SELECT COUNT(*) FROM project_team_members 
                         INNER JOIN project_teams ON project_teams.id = project_team_members.id_project_team
                         WHERE project_teams.id_project = projects.id 
                         AND project_team_members.role = "anggota") as members_count')
            )
            ->leftJoin('tahun_kepengurusan', 'projects.id_tahun', '=', 'tahun_kepengurusan.id')
            ->where('tahun_kepengurusan.status', 'aktif')
            ->where(function ($query) use ($search) {
                $query->where('tahun_kepengurusan.nama_tahun', 'LIKE', $search)
                      ->orWhere('projects.nama_project', 'LIKE', $search);
            })
            ->orderBy('projects.id', 'DESC');

        // Get total count efficiently
        $total = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->count();

        // Get paginated results
        $projects = $query
            ->skip(($this->getPage() - 1) * $this->lengthData)
            ->take($this->lengthData)
            ->get();

        $data = new LengthAwarePaginator(
            $projects,
            $total,
            $this->lengthData,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.akademik.project', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsProject::create([
            'id_tahun'        => $this->id_tahun,
            'nama_project'    => $this->nama_project,
            'deskripsi'       => $this->deskripsi,
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
        $data = DB::table('projects')
            ->select(
                'id',
                'id_tahun',
                'nama_project',
                'deskripsi',
                'status',
                'tanggal_mulai',
                'tanggal_selesai',
                'thumbnail',
                'link_gdrive'
            )
            ->where('id', $id)
            ->first();
            
        $this->dataId           = $id;
        $this->id_tahun         = $data->id_tahun;
        $this->nama_project     = $data->nama_project;
        $this->deskripsi        = $data->deskripsi;
        $this->status           = $data->status;
        $this->tanggal_mulai    = $data->tanggal_mulai;
        $this->tanggal_selesai  = $data->tanggal_selesai;
        $this->thumbnail        = $data->thumbnail;
        $this->link_gdrive      = $data->link_gdrive;
        
        $this->dispatch('initSelect2');
    }

    public function view($id)
    {
        // Get project data
        $project = DB::table('projects')
            ->select(
                'projects.id',
                'projects.nama_project',
                'projects.deskripsi',
                'projects.status',
                'projects.tanggal_mulai',
                'projects.tanggal_selesai',
                'projects.link_gdrive'
            )
            ->where('projects.id', $id)
            ->first();

        // Get teams (just basic info)
        $teams = DB::table('project_teams')
            ->select(
                'project_teams.id',
                'project_teams.nama_kelompok',
                'project_teams.deskripsi'
            )
            ->where('project_teams.id_project', $id)
            ->orderBy('project_teams.nama_kelompok')
            ->get();

        // Get ALL members in single query
        $teamIds = $teams->pluck('id')->toArray();
        $allMembers = [];
        
        if (!empty($teamIds)) {
            $allMembers = DB::table('project_team_members')
                ->select(
                    'project_team_members.id_project_team',
                    'project_team_members.id_anggota',
                    'project_team_members.role',
                    'anggota.nama_lengkap'
                )
                ->join('anggota', 'project_team_members.id_anggota', '=', 'anggota.id')
                ->whereIn('project_team_members.id_project_team', $teamIds)
                ->orderByRaw("FIELD(role, 'leader', 'anggota')")
                ->get()
                ->groupBy('id_project_team');
        }

        // Attach members to teams
        foreach ($teams as $team) {
            $team->members = $allMembers[$team->id] ?? collect();
        }
        
        $this->viewData = [
            'project' => $project,
            'teams' => $teams,
        ];
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            DB::table('projects')
                ->where('id', $this->dataId)
                ->update([
                    'id_tahun'        => $this->id_tahun,
                    'nama_project'    => $this->nama_project,
                    'deskripsi'       => $this->deskripsi,
                    'status'          => $this->status,
                    'tanggal_mulai'   => $this->tanggal_mulai,
                    'tanggal_selesai' => $this->tanggal_selesai,
                    'thumbnail'       => $this->thumbnail,
                    'link_gdrive'     => $this->link_gdrive,
                    'updated_at'      => now(),
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
