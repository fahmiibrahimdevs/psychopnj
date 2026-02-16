<?php

namespace App\Livewire\Organisasi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use Illuminate\Support\Facades\DB;
use App\Models\Anggota as ModelsAnggota;
use App\Models\Department as ModelsDepartment;
use App\Models\OpenRecruitment as ModelsOpenRecruitment;
use App\Traits\WithPermissionCache;

class Anggota extends Component
{
    use WithPagination, WithPermissionCache;
    #[Title('Anggota')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_user'             => '',
        'id_tahun'            => 'required',
        'id_department'       => 'required',
        'nama_lengkap'        => 'required',
        'nama_jabatan'        => 'required',
        'jurusan_prodi_kelas' => 'required',
        'nim'                 => '',
        'ttl'                 => '',
        'alamat'              => '',
        'email'               => 'required|email',
        'no_hp'               => '',
        'status_anggota'      => 'required',
        'status_aktif'        => 'required',
        'foto'                => '',
        'password'            => '',
        'password_confirmation' => '',
        'role'                => 'required',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $viewData;
    public $activeTab = 'pengurus';
    public $perPagePengurus = 25;
    public $perPageAnggota = 25;

    public $id_user, $id_tahun, $id_department, $nama_lengkap, $nama_jabatan, $jurusan_prodi_kelas, $nim, $ttl, $alamat, $email, $no_hp, $status_anggota, $status_aktif, $foto;
    public $password, $password_confirmation;
    public $tahuns, $departments;
    public $role;
    public $roles = [];

    public function mount()
    {
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        $this->id_user             = '';
        // Set id_tahun default ke tahun kepengurusan yang aktif
        $yearAktif = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
        $this->id_tahun            = $yearAktif ? $yearAktif->id : '';
        $this->id_department       = '';
        $this->nama_lengkap        = '';
        $this->nama_jabatan        = '';
        $this->jurusan_prodi_kelas = '';
        $this->nim                 = '';
        $this->ttl                 = '';
        $this->alamat              = '';
        $this->email               = '';
        $this->no_hp               = '08';
        $this->status_anggota      = '';
        $this->status_aktif        = '';
        $this->foto                = '';
        $this->password            = '';
        $this->password_confirmation = '';
        $this->role                = '';
        $this->getTahunKepengurusan();
        $this->getDepartment($this->id_tahun);
        $this->getRoles();
    }

    private function getRoles()
    {
        $this->roles = \Spatie\Permission\Models\Role::where('name', '!=', 'super_admin')->pluck('name');
    }

    private function getTahunKepengurusan()
    {
        $this->tahuns = DB::table('tahun_kepengurusan')->select('id', 'nama_tahun')->get();
        // dd($this->tahuns);
    }

    private function getDepartment($idTahun = null)
    {
        $query = DB::table('departments')->select('id', 'nama_department');
        
        if ($idTahun) {
            $query->where('id_tahun', $idTahun);
        }
        
        $this->departments = $query->get();
    }

    public function updatedIdTahun($value)
    {
        // Reload department ketika tahun berubah
        $this->getDepartment($value);
        // Reset department yang dipilih
        $this->id_department = '';
    }

    public function updatedStatusAnggota($value)
    {
        // Auto-set untuk anggota
        if ($value === 'anggota') {
            $this->id_department = '';
            $this->nama_jabatan = 'anggota';
            $this->role = 'anggota';
        } else {
            // Reset untuk pengurus
            $this->id_department = '';
            $this->nama_jabatan = '';
            $this->role = '';
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function initSelect2()
    {
        $this->dispatch('initSelect2');
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
        $dataPengurus = DB::table('anggota')
                ->select(
                    'anggota.id',
                    'anggota.id_department',
                    'anggota.nama_lengkap',
                    'anggota.nama_jabatan',
                    'anggota.jurusan_prodi_kelas',
                    'anggota.nim',
                    'anggota.email',
                    'anggota.no_hp',
                    'anggota.status_aktif',
                    'anggota.foto',
                    'departments.nama_department',
                    'tahun_kepengurusan.nama_tahun'
                )
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->leftJoin('departments', 'departments.id', '=', 'anggota.id_department')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'pengurus')
                ->where(function ($query) use ($search) {
                    $query->where('anggota.nama_lengkap', 'LIKE', $search)
                          ->orWhere('anggota.nama_jabatan', 'LIKE', $search)
                          ->orWhere('anggota.jurusan_prodi_kelas', 'LIKE', $search)
                          ->orWhere('departments.nama_department', 'LIKE', $search);
                })
                ->orderBy('anggota.id_department', 'ASC')
                ->orderBy('anggota.id', 'ASC')
                ->limit($this->perPagePengurus)
                ->get()
                ->groupBy('nama_department');

        $countPengurus = DB::table('anggota')
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'pengurus')
                ->count();

        // Get data anggota with load more (grouped by jurusan_prodi_kelas)
        $dataAnggota = DB::table('anggota')
                ->select(
                    'anggota.id',
                    'anggota.id_department',
                    'anggota.nama_lengkap',
                    'anggota.nama_jabatan',
                    'anggota.jurusan_prodi_kelas',
                    'anggota.nim',
                    'anggota.email',
                    'anggota.no_hp',
                    'anggota.status_aktif',
                    'anggota.foto',
                    'departments.nama_department',
                    'tahun_kepengurusan.nama_tahun'
                )
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->leftJoin('departments', 'departments.id', '=', 'anggota.id_department')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'anggota')
                ->where(function ($query) use ($search) {
                    $query->where('anggota.nama_lengkap', 'LIKE', $search)
                          ->orWhere('anggota.nama_jabatan', 'LIKE', $search)
                          ->orWhere('anggota.jurusan_prodi_kelas', 'LIKE', $search);
                })
                ->orderBy('anggota.jurusan_prodi_kelas', 'ASC')
                ->orderBy('anggota.id', 'ASC')
                ->limit($this->perPageAnggota)
                ->get()
                ->groupBy('jurusan_prodi_kelas');

        $countAnggota = DB::table('anggota')
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'anggota')
                ->count();

        // Statistik berdasarkan Jurusan/Prodi/Kelas untuk Pengurus
        $statistikPengurus = DB::table('anggota')
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'pengurus')
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
        $statistikAnggota = DB::table('anggota')
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'anggota')
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

        return view('livewire.organisasi.anggota', compact('dataPengurus', 'dataAnggota', 'countPengurus', 'countAnggota', 'statistikPengurus', 'statistikAnggota'));
    }

    public function store()
    {
        // Auto-set untuk anggota sebelum validasi
        if ($this->status_anggota === 'anggota') {
            $this->id_department = '';
            $this->nama_jabatan = 'anggota';
        }

        // Conditional validation berdasarkan status_anggota
        $rules = $this->rules;
        if ($this->status_anggota === 'pengurus') {
            $rules['id_department'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Untuk anggota, id_department dan nama_jabatan tidak required
            unset($rules['id_department']);
            unset($rules['nama_jabatan']);
        }

        $this->validate($rules);

        // Validasi password confirmation
        if ($this->password && $this->password !== $this->password_confirmation) {
            $this->dispatchAlert('warning', 'Warning!', 'Password confirmation does not match.');
            return;
        }

        DB::beginTransaction();
        try {
            // Create or update user
            $userId = $this->id_user;
            if ($this->email) {
                if ($userId) {
                    // Update existing user
                    $user = \App\Models\User::find($userId);
                    if ($user) {
                        $userData = [
                            'email' => $this->email,
                            'active' => $this->status_aktif == 'aktif' ? '1' : '0',
                        ];
                        if ($this->password) {
                            $userData['password'] = bcrypt($this->password);
                        }
                        $user->update($userData);
                    }
                } else {
                    // Create new user
                    $user = \App\Models\User::create([
                        'name' => $this->nama_lengkap,
                        'email' => $this->email,
                        'password' => bcrypt($this->password ?: 'password123'),
                        'active' => $this->status_aktif == 'aktif' ? '1' : '0',
                    ]);
                    
                    // Assign role berdasarkan status_anggota
                    $user->assignRole($this->role ?: $this->status_anggota);
                    
                    $userId = $user->id;
                }
            }

            ModelsAnggota::create([
                'id_user'             => $userId,
                'id_tahun'            => $this->id_tahun,
                'id_department'       => $this->id_department ?: 0,
                'nama_lengkap'        => $this->nama_lengkap,
                'nama_jabatan'        => $this->nama_jabatan,
                'jurusan_prodi_kelas' => $this->jurusan_prodi_kelas,
                'nim'                 => $this->nim,
                'ttl'                 => $this->ttl,
                'alamat'              => $this->alamat,
                'email'               => $this->email,
                'no_hp'               => $this->no_hp,
                'status_anggota'      => $this->status_anggota,
                'status_aktif'        => $this->status_aktif,
                'foto'                => $this->foto,
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
            $data = DB::table('anggota')
                ->select(
                    'id',
                    'id_user',
                    'id_tahun',
                    'id_department',
                    'nama_lengkap',
                    'nama_jabatan',
                    'jurusan_prodi_kelas',
                    'nim',
                    'ttl',
                    'alamat',
                    'email',
                    'no_hp',
                    'status_anggota',
                    'status_aktif',
                    'foto'
                )
                ->where('id', $id)
                ->first();
                
            $this->getTahunKepengurusan();
            $this->dataId           = $id;
            $this->id_user          = $data->id_user;
            $this->id_tahun         = $data->id_tahun;
            $this->getDepartment($data->id_tahun);
            $this->id_department    = $data->id_department;
            $this->nama_lengkap     = $data->nama_lengkap;
            $this->nama_jabatan     = $data->nama_jabatan;
            $this->jurusan_prodi_kelas = $data->jurusan_prodi_kelas;
            $this->nim              = $data->nim;
            $this->ttl              = $data->ttl;
            $this->alamat           = $data->alamat;
            $this->email            = $data->email;
            $this->no_hp            = $data->no_hp;
            $this->status_anggota   = $data->status_anggota;
            $this->status_aktif     = $data->status_aktif;
            $this->foto             = $data->foto;
            
            // Load email dari user jika berbeda
            if ($data->id_user) {
                $user = \App\Models\User::find($data->id_user);
                if ($user && $user->email !== $data->email) {
                    $this->email = $user->email;
                }
                
                if ($user) {
                    $this->role = $user->getRoleNames()->first();
                }
            }
            $this->password = '';
            $this->password_confirmation = '';
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        try {
            $this->viewData = DB::table('anggota')
                ->select(
                    'anggota.id',
                    'anggota.id_user',
                    'anggota.id_tahun',
                    'anggota.id_department',
                    'anggota.nama_lengkap',
                    'anggota.nama_jabatan',
                    'anggota.jurusan_prodi_kelas',
                    'anggota.nim',
                    'anggota.ttl',
                    'anggota.alamat',
                    'anggota.email',
                    'anggota.no_hp',
                    'anggota.status_anggota',
                    'anggota.status_aktif',
                    'anggota.foto',
                    'departments.nama_department',
                    'tahun_kepengurusan.nama_tahun',
                    'users.email as user_email'
                )
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->leftJoin('departments', 'departments.id', '=', 'anggota.id_department')
                ->leftJoin('users', 'users.id', '=', 'anggota.id_user')
                ->where('anggota.id', $id)
                ->first();
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function update()
    {
        // Auto-set untuk anggota sebelum validasi
        if ($this->status_anggota === 'anggota') {
            $this->id_department = '';
            $this->nama_jabatan = 'anggota';
        }

        // Conditional validation berdasarkan status_anggota
        $rules = $this->rules;
        if ($this->status_anggota === 'pengurus') {
            $rules['id_department'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Untuk anggota, id_department dan nama_jabatan tidak required
            unset($rules['id_department']);
            unset($rules['nama_jabatan']);
        }

        $this->validate($rules);

        // Validasi password confirmation
        if ($this->password && $this->password !== $this->password_confirmation) {
            $this->addError('password_confirmation', 'Password confirmation does not match.');
            return;
        }

        if( $this->dataId )
        {
            DB::beginTransaction();
            try {
                // Update or create user
                $userId = $this->id_user;
                if ($this->email) {
                    if ($userId) {
                        // Update existing user
                        $user = \App\Models\User::find($userId);
                        if ($user) {
                            $userData = [
                                'email' => $this->email,
                                'active' => $this->status_aktif == 'aktif' ? '1' : '0',
                            ];
                            if ($this->password) {
                                $userData['password'] = bcrypt($this->password);
                            }
                            $user->update($userData);
                            
                            // Sync role berdasarkan status_anggota
                            $user->syncRoles([$this->role ?: $this->status_anggota]);
                        }
                    } else {
                        // Create new user
                        $user = \App\Models\User::create([
                            'name' => $this->nama_lengkap,
                            'email' => $this->email,
                            'password' => bcrypt($this->password ?: 'password123'),
                            'active' => $this->status_aktif == 'aktif' ? '1' : '0',
                        ]);
                        
                        // Assign role
                        $user->assignRole($this->role ?: $this->status_anggota);
                        
                        $userId = $user->id;
                    }
                }

                ModelsAnggota::findOrFail($this->dataId)->update([
                    'id_user'             => $userId,
                    'id_tahun'            => $this->id_tahun,
                    'id_department'       => $this->id_department ?: 0,
                    'nama_lengkap'        => $this->nama_lengkap,
                    'nama_jabatan'        => $this->nama_jabatan,
                    'jurusan_prodi_kelas' => $this->jurusan_prodi_kelas,
                    'nim'                 => $this->nim,
                    'ttl'                 => $this->ttl,
                    'alamat'              => $this->alamat,
                    'email'               => $this->email,
                    'no_hp'               => $this->no_hp,
                    'status_anggota'      => $this->status_anggota,
                    'status_aktif'        => $this->status_aktif,
                    'foto'                => $this->foto,
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
            $anggota = ModelsAnggota::findOrFail($this->dataId);
            
            // Hapus foto jika ada
            if ($anggota->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto);
            }
            
            // Hapus user jika ada
            if ($anggota->id_user) {
                $user = \App\Models\User::find($anggota->id_user);
                if ($user) {
                    // Hapus semua role dari user
                    $user->syncRoles([]); 
                    $user->delete();
                }
            }
            
            // Update open_recruitment untuk unlink (jangan hapus)
            if ($anggota->id_open_recruitment) {
                ModelsOpenRecruitment::where('id', $anggota->id_open_recruitment)
                    ->update([
                        'id_anggota' => null,
                        'id_user' => null
                    ]);
            }
            
            // Hapus anggota
            $anggota->delete();
            
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
        
        // Jika mode add (false), set id_tahun ke tahun aktif
        if (!$mode) {
            $tahunAktif = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
            $this->id_tahun = $tahunAktif ? $tahunAktif->id : '';
            $this->getDepartment($this->id_tahun);
        }
    }
    
    private function resetInputFields()
    {
        $this->id_user             = '';
        // Reset id_tahun ke tahun kepengurusan yang aktif
        $tahunAktif = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
        $this->id_tahun            = $tahunAktif ? $tahunAktif->id : '';
        $this->getDepartment($this->id_tahun);
        $this->id_department       = '';
        $this->nama_lengkap        = '';
        $this->nama_jabatan        = '';
        $this-> jurusan_prodi_kelas = '';
        $this->nim                 = '';
        $this->ttl                 = '';
        $this->alamat              = '';
        $this->email               = '';
        $this->no_hp               = '08';
        $this->status_anggota      = '';
        $this->status_aktif        = '';
        $this->foto                = '';
        $this->password            = '';
        $this->password_confirmation = '';
        $this->role                = '';

    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}