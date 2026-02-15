<?php

namespace App\Livewire\Organisasi;

use App\Models\Department as ModelsDepartment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use Illuminate\Support\Facades\DB;
use App\Models\OpenRecruitment as ModelsOpenRecruitment;
use App\Traits\WithPermissionCache;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OpenRecruitmentImport;

class OpenRecruitment extends Component
{
    use WithPagination, WithPermissionCache, WithFileUploads;
    #[Title('Open Recruitment')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_tahun'            => '',
        'jenis_oprec'         => 'required',
        'nama_lengkap'        => 'required',
        'jurusan_prodi_kelas' => 'required',
        'id_department'       => '',
        'nama_jabatan'        => '',
        'status_seleksi'      => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $activeTab = 'pengurus';
    public $viewData = [];
    public $perPagePengurus = 25;
    public $perPageAnggota = 25;

    public $id_tahun, $jenis_oprec, $nama_lengkap, $jurusan_prodi_kelas, $id_department, $nama_jabatan, $status_seleksi;
    public $departments;
    
    // Import Excel
    public $fileImport;

    public function mount()
    {
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        $yearAktif = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
        $this->id_tahun            = $yearAktif ? $yearAktif->id : '';
        
        // Load departments berdasarkan tahun aktif
        $this->departments = DB::table('departments')
            ->select('id', 'nama_department')
            ->where('id_tahun', $this->id_tahun)
            ->where('status', 'aktif')
            ->orderBy('urutan', 'ASC')
            ->get();
            
        $this->jenis_oprec         = '';
        $this->nama_lengkap        = '';
        $this->jurusan_prodi_kelas = '';
        $this->id_department       = '';
        $this->nama_jabatan        = '';
        $this->status_seleksi      = 'pending';
    }

    public function updatedJenisOprec($value)
    {
        // Auto-set untuk anggota
        if ($value === 'anggota') {
            $this->id_department = '';
            $this->nama_jabatan = 'anggota';
        } else {
            // Reset untuk pengurus
            $this->id_department = '';
            $this->nama_jabatan = '';
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function loadMorePengurus()
    {
        $this->perPagePengurus += 25;
    }

    public function loadMoreAnggota()
    {
        $this->perPageAnggota += 25;
    }

    public function render()
    {
        $search = '%'.$this->searchTerm.'%';

        // Get data pengurus with load more
        $dataPengurus = DB::table('open_recruitment')
                ->select(
                    'open_recruitment.id',
                    'open_recruitment.id_tahun',
                    'open_recruitment.jenis_oprec',
                    'open_recruitment.nama_lengkap',
                    'open_recruitment.jurusan_prodi_kelas',
                    'open_recruitment.id_department',
                    'open_recruitment.nama_jabatan',
                    'open_recruitment.status_seleksi',
                    'departments.nama_department'
                )
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->join('departments', 'open_recruitment.id_department', '=', 'departments.id')
                ->where(function ($query) use ($search) {
                    $query->where('open_recruitment.nama_lengkap', 'LIKE', $search)
                          ->orWhere('open_recruitment.jurusan_prodi_kelas', 'LIKE', $search)
                          ->orWhere('departments.nama_department', 'LIKE', $search)
                          ->orWhere('open_recruitment.nama_jabatan', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'pengurus')
                ->orderBy('open_recruitment.id_department', 'ASC')
                ->orderBy('open_recruitment.id', 'ASC')
                ->limit($this->perPagePengurus)
                ->get()
                ->groupBy('nama_department');

        $countPengurus = DB::table('open_recruitment')
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'pengurus')
                ->count();

        // Get data anggota with load more (grouped by jurusan_prodi_kelas)
        $dataAnggota = DB::table('open_recruitment')
                ->select(
                    'open_recruitment.id',
                    'open_recruitment.id_tahun',
                    'open_recruitment.jenis_oprec',
                    'open_recruitment.nama_lengkap',
                    'open_recruitment.jurusan_prodi_kelas',
                    'open_recruitment.status_seleksi'
                )
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where(function ($query) use ($search) {
                    $query->where('open_recruitment.nama_lengkap', 'LIKE', $search)
                          ->orWhere('open_recruitment.jurusan_prodi_kelas', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'anggota')
                ->orderBy('open_recruitment.jurusan_prodi_kelas', 'ASC')
                ->orderBy('open_recruitment.id', 'ASC')
                ->limit($this->perPageAnggota)
                ->get()
                ->groupBy('jurusan_prodi_kelas');

        $countAnggota = DB::table('open_recruitment')
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'anggota')
                ->count();

        // Statistik berdasarkan Jurusan/Prodi/Kelas untuk Pengurus
        $statistikPengurus = DB::table('open_recruitment')
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'pengurus')
                ->select('jurusan_prodi_kelas', DB::raw('count(*) as total'))
                ->groupBy('jurusan_prodi_kelas')
                ->orderByDesc('total')
                ->get()
                ->map(function ($item) use ($countPengurus) {
                    return [
                        'jurusan_prodi_kelas' => $item->jurusan_prodi_kelas,
                        'total' => $item->total,
                        'persentase' => $countPengurus > 0 ? round(($item->total / $countPengurus) * 100, 1) : 0
                    ];
                });

        // Statistik berdasarkan Jurusan/Prodi/Kelas untuk Anggota
        $statistikAnggota = DB::table('open_recruitment')
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'anggota')
                ->select('jurusan_prodi_kelas', DB::raw('count(*) as total'))
                ->groupBy('jurusan_prodi_kelas')
                ->orderByDesc('total')
                ->get()
                ->map(function ($item) use ($countAnggota) {
                    return [
                        'jurusan_prodi_kelas' => $item->jurusan_prodi_kelas,
                        'total' => $item->total,
                        'persentase' => $countAnggota > 0 ? round(($item->total / $countAnggota) * 100, 1) : 0
                    ];
                });

        return view('livewire.organisasi.open-recruitment', compact('dataPengurus', 'dataAnggota', 'countPengurus', 'countAnggota', 'statistikPengurus', 'statistikAnggota'));
    }

    public function view($id)
    {
        $this->viewData = DB::table('open_recruitment')
                ->select(
                    'open_recruitment.id',
                    'open_recruitment.id_tahun',
                    'open_recruitment.jenis_oprec',
                    'open_recruitment.nama_lengkap',
                    'open_recruitment.email',
                    'open_recruitment.no_hp',
                    'open_recruitment.jurusan_prodi_kelas',
                    'open_recruitment.alasan',
                    'open_recruitment.tautan_twibbon',
                    'open_recruitment.id_department',
                    'open_recruitment.nama_jabatan',
                    'open_recruitment.status_seleksi',
                    'departments.nama_department',
                    'tahun_kepengurusan.nama_tahun'
                )
                ->leftJoin('departments', 'departments.id', '=', 'open_recruitment.id_department')
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('open_recruitment.id', $id)
                ->first();
    }

    public function store()
    {
        // Conditional validation
        $rules = [
            'jenis_oprec' => 'required',
            'nama_lengkap' => 'required',
            'jurusan_prodi_kelas' => 'required',
        ];

        // Tambah validation untuk pengurus
        if ($this->jenis_oprec === 'pengurus') {
            $rules['id_department'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Auto-set untuk anggota
            $this->id_department = '';
            $this->nama_jabatan = 'anggota';
        }

        $this->validate($rules);

        DB::beginTransaction();
        try {
            DB::table('open_recruitment')->insert([
                'id_tahun'            => $this->id_tahun,
                'jenis_oprec'         => $this->jenis_oprec,
                'nama_lengkap'        => $this->nama_lengkap,
                'jurusan_prodi_kelas' => $this->jurusan_prodi_kelas,
                'id_department'       => $this->id_department ?: 0,
                'nama_jabatan'        => $this->nama_jabatan,
                'status_seleksi'      => $this->status_seleksi,
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
            $data = DB::table('open_recruitment')
                ->select('id', 'id_tahun', 'jenis_oprec', 'nama_lengkap', 'jurusan_prodi_kelas', 'id_department', 'nama_jabatan', 'status_seleksi')
                ->where('id', $id)
                ->first();
                
            $this->dataId           = $id;
            $this->id_tahun         = $data->id_tahun;
            $this->jenis_oprec      = $data->jenis_oprec;
            $this->nama_lengkap     = $data->nama_lengkap;
            $this->jurusan_prodi_kelas = $data->jurusan_prodi_kelas;
            $this->id_department    = $data->id_department;
            $this->nama_jabatan     = $data->nama_jabatan;
            $this->status_seleksi   = $data->status_seleksi;
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function update()
    {
        // Conditional validation
        $rules = [
            'jenis_oprec' => 'required',
            'nama_lengkap' => 'required',
            'jurusan_prodi_kelas' => 'required',
        ];

        // Tambah validation untuk pengurus
        if ($this->jenis_oprec === 'pengurus') {
            $rules['id_department'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Auto-set untuk anggota
            $this->id_department = '';
            $this->nama_jabatan = 'anggota';
        }

        $this->validate($rules);

        if( $this->dataId )
        {
            DB::beginTransaction();
            try {
                DB::table('open_recruitment')
                    ->where('id', $this->dataId)
                    ->update([
                        'id_tahun'            => $this->id_tahun,
                        'jenis_oprec'         => $this->jenis_oprec,
                        'nama_lengkap'        => $this->nama_lengkap,
                        'jurusan_prodi_kelas' => $this->jurusan_prodi_kelas,
                        'id_department'       => $this->id_department ?: 0,
                        'nama_jabatan'        => $this->nama_jabatan,
                        'status_seleksi'      => $this->status_seleksi,
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
            $openRecruitment = ModelsOpenRecruitment::findOrFail($this->dataId);
            
            // Delete cascade: user, role, anggota jika status_seleksi = lulus
            if ($openRecruitment->status_seleksi === 'lulus') {
                if ($openRecruitment->id_user) {
                    $user = \App\Models\User::find($openRecruitment->id_user);
                    if ($user) {
                        // Hapus semua role dari user
                        $user->syncRoles([]); 
                        $user->delete();
                    }
                }
                
                if ($openRecruitment->id_anggota) {
                    \App\Models\Anggota::find($openRecruitment->id_anggota)?->delete();
                }
            }
            
            // Delete open recruitment
            $openRecruitment->delete();
            
            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $openRecruitment = ModelsOpenRecruitment::findOrFail($id);
            $oldStatus = $openRecruitment->status_seleksi;
            
            // Generate email from jurusan_prodi_kelas
            $email = $this->generateEmailFromJurusanProdiKelas($openRecruitment->nama_lengkap, $openRecruitment->jurusan_prodi_kelas);

            // Jika status diubah menjadi lulus
            if ($status === 'lulus' && $oldStatus !== 'lulus') {
                // Check duplicate email atau nama
                $existingUser = \App\Models\User::where('email', $email)->first();
                $existingAnggota = \App\Models\Anggota::where('nama_lengkap', $openRecruitment->nama_lengkap)
                    ->where('id_tahun', $openRecruitment->id_tahun)
                    ->first();
                
                if ($existingUser || $existingAnggota) {
                    $this->dispatch('swal:modal', [
                        'type'      => 'error',  
                        'message'   => 'Error!', 
                        'text'      => 'Pendaftar sudah terdaftar sebagai anggota atau email sudah digunakan.'
                    ]);
                    return;
                }
                
                DB::beginTransaction();
                // Create User
                $user = \App\Models\User::create([
                    'name' => $openRecruitment->nama_lengkap,
                    'email' => $email,
                    'password' => bcrypt('psychopnj2026'),
                    'active' => $openRecruitment->jenis_oprec == 'pengurus' ? '1' : '0',
                ]);
                
                // Assign role berdasarkan jenis_oprec
                // Karena role 'pengurus' tidak ada, dan open recruitment belum menentukan jabatan spesifik yang match ke role,
                // maka default-nya kita set 'anggota'. Admin bisa mengubah role nanti di menu Anggota.
                $user->assignRole('anggota');
                
                // Create Anggota
                $anggota = \App\Models\Anggota::create([
                    'id_user' => $user->id,
                    'id_tahun' => $openRecruitment->id_tahun,
                    'id_department' => $openRecruitment->id_department,
                    'nama_lengkap' => $openRecruitment->nama_lengkap,
                    'nama_jabatan' => $openRecruitment->nama_jabatan,
                    'jurusan_prodi_kelas' => $openRecruitment->jurusan_prodi_kelas,
                    'nim' => '',
                    'ttl' => '',
                    'alamat' => '',
                    'email' => $email,
                    'no_hp' => '08',
                    'status_anggota' => $openRecruitment->jenis_oprec,
                    'status_aktif' => 'aktif',
                    'foto' => '',
                    'id_open_recruitment' => $openRecruitment->id,
                ]);
                
                // Update open_recruitment dengan id_anggota dan id_user
                $openRecruitment->update([
                    'status_seleksi' => $status,
                    'id_anggota' => $anggota->id,
                    'id_user' => $user->id,
                ]);
                
                DB::commit();
                $this->dispatch('swal:modal', [
                    'type'      => 'success',  
                    'message'   => 'Success!', 
                    'text'      => "Status seleksi berhasil diubah menjadi Lulus. Anggota otomatis terdaftar dengan email: {$email}"
                ]);
            }
            // Jika status diubah dari lulus ke gagal atau pending
            elseif ($status !== 'lulus' && $oldStatus === 'lulus') {
                DB::beginTransaction();
                // Delete cascade: user, role, anggota
                if ($openRecruitment->id_user) {
                    $user = \App\Models\User::find($openRecruitment->id_user);
                    if ($user) {
                        // Hapus semua role dari user
                        $user->syncRoles([]); 
                        $user->delete();
                    }
                }
                
                if ($openRecruitment->id_anggota) {
                    \App\Models\Anggota::find($openRecruitment->id_anggota)?->delete();
                }
                
                $openRecruitment->update([
                    'status_seleksi' => $status,
                    'id_anggota' => null,
                    'id_user' => null,
                ]);
                
                DB::commit();
                $statusText = $status === 'gagal' ? 'Gagal' : 'Pending';
                $this->dispatch('swal:modal', [
                    'type'      => 'success',  
                    'message'   => 'Success!', 
                    'text'      => "Status seleksi berhasil diubah menjadi {$statusText}. Data anggota dan user telah dihapus."
                ]);
            }
            // Jika hanya update status biasa (pending ke gagal, dll)
            else {
                DB::beginTransaction();
                $openRecruitment->update([
                    'status_seleksi' => $status
                ]);
                
                DB::commit();
                $statusText = $status === 'lulus' ? 'Lulus' : ($status === 'gagal' ? 'Gagal' : 'Pending');
                $this->dispatch('swal:modal', [
                    'type'      => 'success',  
                    'message'   => 'Success!', 
                    'text'      => "Status seleksi berhasil diubah menjadi {$statusText}."
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }
    
    private function generateEmailFromJurusanProdiKelas($namaLengkap, $jurusanProdiKelas)
    {
        // Pecah nama berdasarkan spasi dan convert ke lowercase
        $namaParts = explode(' ', strtolower(trim($namaLengkap)));
        
        // Gabungkan dengan titik
        $emailPrefix = implode('.', $namaParts);
        
        // Extract kode jurusan dari jurusan_prodi_kelas (contoh: TE/EC/4D -> te)
        $parts = explode('/', $jurusanProdiKelas);
        $kodeJurusan = isset($parts[0]) ? strtolower(trim($parts[0])) : 'te';
        
        // Format final: nama.lengkap.jurusan@stu.pnj.ac.id
        return $emailPrefix . '.' . $kodeJurusan . '@stu.pnj.ac.id';
    }

    public function importExcel()
    {
        $this->validate([
            'fileImport' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            DB::beginTransaction();
            
            $import = new OpenRecruitmentImport($this->id_tahun);
            Excel::import($import, $this->fileImport->getRealPath());
            
            DB::commit();
            
            $this->fileImport = null;
            $this->dispatchAlert('success', 'Success!', 'Data anggota berhasil diimport.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Import gagal: ' . $e->getMessage());
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
        $this->mount();
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
