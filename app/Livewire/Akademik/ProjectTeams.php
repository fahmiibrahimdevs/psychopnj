<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\Project as ModelsProject;
use App\Models\ProjectTeam;
use App\Models\ProjectTeamMember;
use App\Models\Anggota;
use App\Traits\WithPermissionCache;

class ProjectTeams extends Component
{
    use WithPermissionCache;
    #[Title('Kelompok Project')]

    protected $listeners = [
        'deleteTeam'
    ];

    public $projectId;
    public $project;
    public $teams;
    
    // Form properties
    public $teamId;
    public $nama_kelompok;
    public $deskripsi_kelompok;
    public $id_leader;
    public $id_anggota = [];
    
    public $anggotas;
    public $isEditingTeam = false;

    protected $rules = [
        'nama_kelompok' => 'required|string|max:255',
        'deskripsi_kelompok' => 'nullable|string',
        'id_leader' => 'required|exists:anggota,id',
        'id_anggota' => 'array',
        'id_anggota.*' => 'exists:anggota,id',
    ];

    public function mount($projectId)
    {
        $this->cacheUserPermissions();
        $this->projectId = $projectId;
        $this->loadProject();
        $this->loadTeams();
        $this->loadAvailableAnggotas();
    }

    public function loadProject()
    {
        $this->project = DB::table('projects')
            ->select(
                'projects.id',
                'projects.id_tahun',
                'projects.nama_project',
                'projects.deskripsi',
                'projects.status',
                'tahun_kepengurusan.nama_tahun'
            )
            ->join('tahun_kepengurusan', 'projects.id_tahun', '=', 'tahun_kepengurusan.id')
            ->where('projects.id', $this->projectId)
            ->first();
            
        if (!$this->project) {
            abort(404);
        }
    }

    public function loadTeams()
    {
        $teams = DB::table('project_teams')
            ->select('id', 'id_project', 'nama_kelompok', 'deskripsi')
            ->where('id_project', $this->projectId)
            ->orderBy('nama_kelompok')
            ->get();

        // Get members for each team
        foreach ($teams as $team) {
            $members = DB::table('project_team_members')
                ->select(
                    'project_team_members.id',
                    'project_team_members.id_anggota',
                    'project_team_members.role',
                    'anggota.nama_lengkap',
                    'anggota.nim'
                )
                ->join('anggota', 'project_team_members.id_anggota', '=', 'anggota.id')
                ->where('project_team_members.id_project_team', $team->id)
                ->orderByRaw("FIELD(role, 'leader', 'anggota')")
                ->get();
            
            $team->members = $members;
        }

        $this->teams = $teams;
    }

    public function loadAvailableAnggotas($excludeTeamId = null)
    {
        // Get anggota yang sudah terdaftar di team lain dalam project yang sama
        $assignedAnggotaIds = DB::table('project_team_members')
            ->join('project_teams', 'project_team_members.id_project_team', '=', 'project_teams.id')
            ->where('project_teams.id_project', $this->projectId)
            ->when($excludeTeamId, function($query) use ($excludeTeamId) {
                $query->where('project_teams.id', '!=', $excludeTeamId);
            })
            ->pluck('project_team_members.id_anggota')
            ->toArray();

        // Get anggota yang belum terdaftar
        $this->anggotas = DB::table('anggota')
            ->select('id', 'nama_lengkap', 'status_anggota', 'nim')
            ->where('id_tahun', $this->project->id_tahun)
            ->where('status_aktif', 'aktif')
            ->whereNotIn('id', $assignedAnggotaIds)
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function render()
    {
        return view('livewire.akademik.project-teams');
    }

    public function openTeamModal()
    {
        $this->resetTeamForm();
        $this->loadAvailableAnggotas();
        $this->isEditingTeam = false;
        $this->dispatch('initSelect2');
    }

    public function editTeam($teamId)
    {
        $this->isEditingTeam = true;
        $this->teamId = $teamId;
        
        $team = DB::table('project_teams')
            ->select('id', 'id_project', 'nama_kelompok', 'deskripsi')
            ->where('id', $teamId)
            ->first();
            
        if (!$team) {
            return;
        }
        
        $this->nama_kelompok = $team->nama_kelompok;
        $this->deskripsi_kelompok = $team->deskripsi;
        
        // Get members
        $members = DB::table('project_team_members')
            ->select('id_anggota', 'role')
            ->where('id_project_team', $teamId)
            ->get();
        
        // Get leader
        $leader = $members->where('role', 'leader')->first();
        $this->id_leader = $leader ? $leader->id_anggota : null;
        
        // Get anggota
        $this->id_anggota = $members->where('role', 'anggota')->pluck('id_anggota')->toArray();
        
        // Load available anggotas excluding current team
        $this->loadAvailableAnggotas($teamId);
        
        // Add current team members to available list for editing
        $currentMemberIds = $members->pluck('id_anggota')->toArray();
        if (!empty($currentMemberIds)) {
            $currentMembers = DB::table('anggota')
                ->select('id', 'nama_lengkap', 'status_anggota')
                ->whereIn('id', $currentMemberIds)
                ->get();
            $this->anggotas = $this->anggotas->merge($currentMembers)->unique('id')->sortBy('nama_lengkap');
        }
        
        $this->dispatch('initSelect2');
    }

    public function saveTeam()
    {
        $this->validate();

        if ($this->isEditingTeam) {
            $this->updateTeam();
        } else {
            $this->createTeam();
        }
    }

    private function createTeam()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $team = ProjectTeam::create([
                'id_project' => $this->projectId,
                'nama_kelompok' => $this->nama_kelompok,
                'deskripsi' => $this->deskripsi_kelompok,
            ]);

            // Add leader
            ProjectTeamMember::create([
                'id_project_team' => $team->id,
                'id_anggota' => $this->id_leader,
                'role' => 'leader',
            ]);

            // Add anggota (exclude leader to prevent duplicate)
            $anggotaIds = array_diff($this->id_anggota, [$this->id_leader]);
            foreach ($anggotaIds as $anggotaId) {
                ProjectTeamMember::create([
                    'id_project_team' => $team->id,
                    'id_anggota' => $anggotaId,
                    'role' => 'anggota',
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            $this->loadTeams();
            $this->dispatchAlert('success', 'Success!', 'Kelompok berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function updated()
    {
        $this->dispatch('initSelect2');
    }

    private function updateTeam()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $team = ProjectTeam::findOrFail($this->teamId);
            
            $team->update([
                'nama_kelompok' => $this->nama_kelompok,
                'deskripsi' => $this->deskripsi_kelompok,
            ]);

            // Delete existing members
            ProjectTeamMember::where('id_project_team', $team->id)->delete();

            // Add new leader
            ProjectTeamMember::create([
                'id_project_team' => $team->id,
                'id_anggota' => $this->id_leader,
                'role' => 'leader',
            ]);

            // Add new anggota (exclude leader to prevent duplicate)
            $anggotaIds = array_diff($this->id_anggota, [$this->id_leader]);
            foreach ($anggotaIds as $anggotaId) {
                ProjectTeamMember::create([
                    'id_project_team' => $team->id,
                    'id_anggota' => $anggotaId,
                    'role' => 'anggota',
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            $this->loadTeams();
            $this->dispatchAlert('success', 'Success!', 'Kelompok berhasil diperbarui.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function deleteTeamConfirm($teamId)
    {
        $this->teamId = $teamId;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Hapus kelompok?',
            'text' => 'Semua anggota dalam kelompok ini akan dihapus!'
        ]);
    }

    public function deleteTeam()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Delete team members first
            DB::table('project_team_members')
                ->where('id_project_team', $this->teamId)
                ->delete();
                
            // Delete team
            DB::table('project_teams')
                ->where('id', $this->teamId)
                ->delete();
                
            \Illuminate\Support\Facades\DB::commit();
            $this->loadTeams();
            $this->dispatchAlert('success', 'Success!', 'Kelompok berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function closeTeamModal()
    {
        $this->resetTeamForm();
    }

    private function resetTeamForm()
    {
        $this->teamId = null;
        $this->nama_kelompok = '';
        $this->deskripsi_kelompok = '';
        $this->id_leader = null;
        $this->id_anggota = [];
    }

    private function dispatchAlert($type, $message, $text)
    {
        $this->dispatch('swal:modal', [
            'type' => $type,
            'message' => $message,
            'text' => $text
        ]);
        
        // Close modal after success
        $this->js("$('#teamModal').modal('hide');");

        $this->resetTeamForm();
    }
}
