<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Anggota;
use App\Models\Department;
use App\Models\Pertemuan;
use App\Models\ProfilOrganisasi;
use App\Models\TahunKepengurusan;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Welcome extends Component
{
    #[Title('PR PNJ - Psychorobotic')]

    public $profilOrganisasi;
    public $totalAnggota;
    public $totalProjects;
    public $totalWorkshops;
    public $totalPertemuan;
    public $departments;
    public $latestProjects;
    public $recentActivities;

    public function mount()
    {
        // Get Profil Organisasi (Visi & Misi)
        $this->profilOrganisasi = ProfilOrganisasi::first();
        
        // Get Statistics
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->totalAnggota = Anggota::where('id_tahun', $activeTahun?->id)->count();
        $this->totalProjects = Project::where('id_tahun', $activeTahun?->id)->count();
        $this->totalWorkshops = Pertemuan::whereHas('program', function($query) use ($activeTahun) {
                $query->where('id_tahun', $activeTahun?->id);
            })
            ->count();
        // Total pertemuan untuk statistik (karena tidak ada kolom jenis di projects)
        $this->totalPertemuan = Pertemuan::whereHas('program', function($query) use ($activeTahun) {
                $query->where('id_tahun', $activeTahun?->id);
            })
            ->where('status', 'visible')
            ->count();
        
        // Get Departments - sort by urutan
        $this->departments = Department::where('id_tahun', $activeTahun?->id)
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();
        
        // Get Latest Projects (4 projects)
        $this->latestProjects = Project::where('id_tahun', $activeTahun?->id)
            ->where('status', '!=', 'draft')
            ->latest()
            ->take(4)
            ->get();
        
        // Get Recent Activities (4 pertemuan terakhir)
        $this->recentActivities = Pertemuan::whereHas('program', function($query) use ($activeTahun) {
                $query->where('id_tahun', $activeTahun?->id);
            })
            ->latest('tanggal')
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.welcome')->extends('components.layouts.welcome');
    }
}
