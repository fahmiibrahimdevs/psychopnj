<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TentangKami extends Component
{
    public function render()
    {
        $activeTahun = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
        $profilOrganisasi = DB::table('profil_organisasi')->first();
        
        $totalAnggota = DB::table('anggota')
            ->when($activeTahun, function($query) use ($activeTahun) {
                return $query->where('id_tahun', $activeTahun->id);
            })
            ->where('status_aktif', 'aktif')
            ->count();
        
        $departments = DB::table('departments')
            ->when($activeTahun, function($query) use ($activeTahun) {
                return $query->where('id_tahun', $activeTahun->id);
            })
            ->where('status', 'aktif')
            ->orderBy('urutan', 'asc')
            ->get();
        
        // Get anggota for each department
        $departmentsWithMembers = [];
        foreach ($departments as $dept) {
            $members = DB::table('anggota')
                ->where('id_department', $dept->id)
                ->where('status_aktif', 'aktif')
                ->orderByRaw("CASE 
                    WHEN nama_jabatan LIKE '%ketua%' AND nama_jabatan NOT LIKE '%wakil%' THEN 1 
                    WHEN nama_jabatan LIKE '%kepala%' AND nama_jabatan NOT LIKE '%wakil%' THEN 1 
                    WHEN nama_jabatan LIKE '%wakil%' THEN 2 
                    ELSE 3 
                END")
                ->orderBy('nama_lengkap', 'asc')
                ->get();
            
            $departmentsWithMembers[] = [
                'department' => $dept,
                'members' => $members
            ];
        }
        
        return view('livewire.tentang-kami', [
            'profilOrganisasi' => $profilOrganisasi,
            'activeTahun' => $activeTahun,
            'totalAnggota' => $totalAnggota,
            'departmentsWithMembers' => $departmentsWithMembers,
        ])->extends('components.layouts.welcome', [
            'title' => 'PR PNJ - Psychorobotic - Tentang Kami'
        ]);
    }
}
