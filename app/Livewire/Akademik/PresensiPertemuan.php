<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Pertemuan;
use App\Models\Anggota;
use App\Models\PresensiPertemuan as ModelsPresensiPertemuan;
use Illuminate\Support\Facades\DB;

class PresensiPertemuan extends Component
{
    #[Title('Presensi Pertemuan')]

    public $selectedPertemuan = null;
    public $searchTerm = '';
    public $pertemuans = [];
    public $anggotaData = [];
    public $presensiData = []; // Data from database
    public $tempPresensiData = []; // Temporary changes (not saved yet)
    public $activeTab = 'pengurus'; // Default tab
    public $statistik = [
        'total' => 0,
        'hadir' => 0,
        'izin' => 0,
        'sakit' => 0,
        'alfa' => 0,
        'tanpa_keterangan' => 0
    ];

    public function mount()
    {
        $pertemuansData = DB::table('pertemuan')
                            ->select(
                                'pertemuan.id',
                                'pertemuan.judul_pertemuan',
                                'pertemuan.pertemuan_ke',
                                'program_pembelajaran.nama_program'
                            )
                            ->leftJoin('program_pembelajaran', 'pertemuan.id_program', '=', 'program_pembelajaran.id')
                            ->leftJoin('tahun_kepengurusan', 'program_pembelajaran.id_tahun', '=', 'tahun_kepengurusan.id')
                            ->where('tahun_kepengurusan.status', '=', 'aktif')
                            ->orderBy('program_pembelajaran.nama_program', 'ASC')
                            ->orderBy('pertemuan.pertemuan_ke', 'ASC')
                            ->get()
                            ->map(fn($item) => (array) $item);
        
        $this->pertemuans = $pertemuansData->groupBy('nama_program')->toArray();
    }

    public function updatedSelectedPertemuan($value)
    {
        // Force reset presensi data first
        $this->presensiData = [];
        $this->tempPresensiData = [];
        
        if (!$value) {
            $this->anggotaData = [];
            return;
        }

        $this->loadAnggota();
    }

    public function updatedSearchTerm()
    {
        if ($this->selectedPertemuan) {
            $this->loadAnggota();
        }
    }

    private function loadAnggota()
    {
        $search = '%' . $this->searchTerm . '%';

        // Force clear all presensi data before loading new data
        $this->presensiData = [];
        $this->anggotaData = [];

        // Get jenis_presensi from selected pertemuan
        $pertemuan = DB::table('pertemuan')
                        ->select('id', 'jenis_presensi')
                        ->where('id', '=', $this->selectedPertemuan)
                        ->first();
        
        if (!$pertemuan) {
            return;
        }

        $jenisPresensi = $pertemuan->jenis_presensi ? explode(',', $pertemuan->jenis_presensi) : ['pengurus', 'anggota'];

        // Get all anggota and group by status_anggota
        $query = DB::table('anggota')
                    ->select(
                        'anggota.id',
                        'anggota.nama_lengkap',
                        'anggota.jurusan_prodi_kelas',
                        'anggota.status_anggota',
                        'anggota.foto',
                        'anggota.id_department',
                        'departments.nama_department'
                    )
                    ->leftJoin('tahun_kepengurusan', 'anggota.id_tahun', '=', 'tahun_kepengurusan.id')
                    ->leftJoin('departments', 'anggota.id_department', '=', 'departments.id')
                    ->where('tahun_kepengurusan.status', '=', 'aktif')
                    ->where('anggota.status_aktif', '=', 'aktif')
                    ->where('anggota.nama_lengkap', 'LIKE', $search);

        // Filter berdasarkan jenis_presensi
        if (!in_array('pengurus', $jenisPresensi) || !in_array('anggota', $jenisPresensi)) {
            // Jika tidak keduanya, filter berdasarkan status_anggota
            $query->whereIn('anggota.status_anggota', $jenisPresensi);
        }

        $anggota = $query->orderBy('anggota.status_anggota', 'DESC') // pengurus first
                            ->orderBy('departments.nama_department', 'ASC')
                            ->orderBy('anggota.nama_lengkap', 'ASC')
                            ->get()
                            ->map(fn($item) => (array) $item);

        // Group by status_anggota, then for pengurus, group by department
        $grouped = $anggota->groupBy('status_anggota');
        
        foreach ($grouped as $status => $members) {
            if ($status === 'pengurus') {
                $grouped[$status] = collect($members)->groupBy('nama_department')->toArray();
            }
        }

        $this->anggotaData = $grouped->toArray();

        // Only load presensi for the selected pertemuan
        if ($this->selectedPertemuan) {
            $existingPresensi = DB::table('presensi_pertemuan')
                                    ->select('id_anggota', 'status', 'waktu', 'metode')
                                    ->where('id_pertemuan', '=', $this->selectedPertemuan)
                                    ->get();
            
            foreach ($existingPresensi as $presensi) {
                $this->presensiData[$presensi->id_anggota] = [
                    'status' => $presensi->status,
                    'waktu' => $presensi->waktu,
                    'metode' => $presensi->metode
                ];
            }
        }
        
        // Calculate statistics
        $this->calculateStatistik();
    }
    
    private function calculateStatistik()
    {
        // Reset statistik
        $this->statistik = [
            'total' => 0,
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alfa' => 0,
            'tanpa_keterangan' => 0
        ];

        // Flatten anggotaData to get all valid member IDs
        $validMemberIds = [];
        
        foreach ($this->anggotaData as $statusAnggota => $group) {
            if ($statusAnggota === 'pengurus') {
                // Pengurus has nested structure: department -> members
                foreach ($group as $departmentMembers) {
                    foreach ($departmentMembers as $member) {
                        $validMemberIds[] = $member['id'];
                    }
                }
            } else {
                // Anggota has flat structure: directly members
                foreach ($group as $member) {
                    $validMemberIds[] = $member['id'];
                }
            }
        }

        $this->statistik['total'] = count($validMemberIds);
        
        // Count status only for valid members
        foreach ($validMemberIds as $id) {
            if (isset($this->presensiData[$id])) {
                $data = $this->presensiData[$id];
                $status = is_array($data) ? ($data['status'] ?? '') : $data;
                
                if ($status == 'hadir') {
                    $this->statistik['hadir']++;
                } elseif ($status == 'izin') {
                    $this->statistik['izin']++;
                } elseif ($status == 'sakit') {
                    $this->statistik['sakit']++;
                } elseif ($status == 'alfa') {
                    $this->statistik['alfa']++;
                }
            }
        }
        
        // Calculate tanpa keterangan directly from valid members who don't have a status counted above
        // Note: Logic above only counts verified statuses. If status is empty string, it falls through.
        // So we can just subtract.
        $this->statistik['tanpa_keterangan'] = $this->statistik['total'] 
            - $this->statistik['hadir'] 
            - $this->statistik['izin'] 
            - $this->statistik['sakit'] 
            - $this->statistik['alfa'];
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.akademik.presensi-pertemuan');
    }

    public function togglePresensi($idAnggota, $status)
    {
        // Store in temporary data (not saved yet)
        $this->tempPresensiData[$idAnggota] = ['status' => $status];
        
        // Skip rendering to prevent chart refresh
        $this->skipRender();
    }

    public function clearPresensi($idAnggota)
    {
        // Mark as cleared in temporary data
        $this->tempPresensiData[$idAnggota] = ['status' => ''];
        
        // Skip rendering to prevent chart refresh
        $this->skipRender();
    }

    public function updatePresensi()
    {
        if (!$this->selectedPertemuan) {
            $this->dispatch('swal:modal', [
                'type'      => 'error',  
                'message'   => 'Error!', 
                'text'      => 'Please select a pertemuan first!'
            ]);
            return;
        }

        DB::beginTransaction();
        
        try {
            $deleteIds = [];
            $updateData = [];
            $createData = [];
            
            // Get all existing records in one query
            $existingRecords = DB::table('presensi_pertemuan')
                ->select('id', 'id_anggota', 'status')
                ->where('id_pertemuan', '=', $this->selectedPertemuan)
                ->whereIn('id_anggota', array_keys($this->tempPresensiData))
                ->get()
                ->keyBy('id_anggota');
            
            // Process temporary changes
            foreach ($this->tempPresensiData as $idAnggota => $data) {
                $status = is_array($data) ? ($data['status'] ?? null) : $data;
                
                // If status is empty, mark for deletion
                if (empty($status)) {
                    if (isset($existingRecords[$idAnggota])) {
                        $deleteIds[] = $idAnggota;
                    }
                    continue;
                }
                
                $existing = $existingRecords[$idAnggota] ?? null;

                if ($existing) {
                    // Only update if status changed
                    if ($existing->status !== $status) {
                        $updateData[] = [
                            'id' => $existing->id,
                            'status' => $status,
                            'waktu' => now(),
                            'metode' => 'manual'
                        ];
                    }
                } else {
                    // Prepare for bulk create
                    $createData[] = [
                        'id_pertemuan' => $this->selectedPertemuan,
                        'id_anggota' => $idAnggota,
                        'status' => $status,
                        'waktu' => now(),
                        'metode' => 'manual',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            // Bulk delete
            if (!empty($deleteIds)) {
                DB::table('presensi_pertemuan')
                    ->where('id_pertemuan', '=', $this->selectedPertemuan)
                    ->whereIn('id_anggota', $deleteIds)
                    ->delete();
            }
            
            // Bulk update
            foreach ($updateData as $update) {
                DB::table('presensi_pertemuan')
                    ->where('id', '=', $update['id'])
                    ->update([
                        'status' => $update['status'],
                        'waktu' => $update['waktu'],
                        'metode' => $update['metode'],
                        'updated_at' => now()
                    ]);
            }
            
            // Bulk insert
            if (!empty($createData)) {
                DB::table('presensi_pertemuan')->insert($createData);
            }

            DB::commit();
            
            // Clear temporary data
            $this->tempPresensiData = [];
            
            // Show success message first (before heavy reload)
            $this->dispatch('swal:modal', [
                'type'      => 'success',  
                'message'   => 'Success!', 
                'text'      => 'Presensi updated successfully!'
            ]);
            
            // Reload data to get updated waktu and statistics
            $this->loadAnggota();

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('swal:modal', [
                'type'      => 'error',  
                'message'   => 'Error!', 
                'text'      => 'Failed to update presensi: ' . $e->getMessage()
            ]);
        }
    }
}

