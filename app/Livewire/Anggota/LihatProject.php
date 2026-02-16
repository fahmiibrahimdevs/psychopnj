<?php

namespace App\Livewire\Anggota;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LihatProject extends Component
{
    use WithPagination;
    #[Title('Project / Kegiatan')]

    public $anggota;
    public $searchTerm = '';
    public $filterStatus = '';
    public $viewData = null;

    public function mount()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function viewProject($id)
    {
        $this->viewData = DB::table('projects')
            ->select(
                'projects.*',
                'tahun_kepengurusan.nama_tahun'
            )
            ->leftJoin('tahun_kepengurusan', 'tahun_kepengurusan.id', 'projects.id_tahun')
            ->where('projects.id', $id)
            ->first();

        if ($this->viewData) {
            // Get teams
            $this->viewData->teams = DB::table('project_teams')
                ->select('project_teams.*')
                ->where('project_teams.id_project', $id)
                ->get()
                ->map(function ($team) {
                    $team->members = DB::table('project_team_members')
                        ->select('anggota.nama_lengkap', 'anggota.jurusan_prodi_kelas', 'project_team_members.role')
                        ->join('anggota', 'anggota.id', 'project_team_members.id_anggota')
                        ->where('project_team_members.id_team', $team->id)
                        ->get();
                    return $team;
                });
        }
    }

    public function closeView()
    {
        $this->viewData = null;
    }

    public function render()
    {
        $projects = DB::table('projects')
            ->select(
                'projects.*',
                'tahun_kepengurusan.nama_tahun'
            )
            ->leftJoin('tahun_kepengurusan', 'tahun_kepengurusan.id', 'projects.id_tahun')
            ->where('projects.id_tahun', $this->anggota->id_tahun);

        if ($this->searchTerm) {
            $projects->where('projects.nama_project', 'LIKE', "%{$this->searchTerm}%");
        }

        if ($this->filterStatus) {
            $projects->where('projects.status', $this->filterStatus);
        }

        $projects = $projects->orderBy('projects.created_at', 'DESC')->paginate(12);

        return view('livewire.anggota.lihat-project', [
            'projects' => $projects,
        ]);
    }
}
