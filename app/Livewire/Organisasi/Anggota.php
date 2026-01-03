<?php

namespace App\Livewire\Organisasi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use Illuminate\Support\Facades\DB;
use App\Models\Anggota as ModelsAnggota;
use App\Models\OpenRecruitment as ModelsOpenRecruitment;

class Anggota extends Component
{
    use WithPagination;
    #[Title('Anggota')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_user'             => '',
        'id_tahun'            => 'required',
        'id_divisi'           => 'required',
        'nama_lengkap'        => 'required',
        'nama_jabatan'        => 'required',
        'kelas'               => 'required',
        'jurusan'             => 'required',
        'nim'                 => '',
        'no_hp'               => '',
        'status_anggota'      => 'required',
        'status_aktif'        => 'required',
        'foto'                => '',
        'email'               => 'required|email',
        'password'            => '',
        'password_confirmation' => '',
        'motivasi'            => '',
        'pengalaman'          => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $viewData;
    public $activeTab = 'pengurus';

    public $id_user, $id_tahun, $id_divisi, $nama_lengkap, $nama_jabatan, $kelas, $jurusan, $nim, $no_hp, $status_anggota, $status_aktif, $foto;
    public $email, $password, $password_confirmation;
    public $motivasi, $pengalaman;
    public $tahuns, $divisis;

    public function mount()
    {
        $this->id_user             = '';
        // Set id_tahun default ke tahun kepengurusan yang aktif
        $tahunAktif = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
        $this->id_tahun            = $tahunAktif ? $tahunAktif->id : '';
        $this->id_divisi           = '';
        $this->nama_lengkap        = '';
        $this->nama_jabatan        = '';
        $this->kelas               = '';
        $this->jurusan             = '';
        $this->nim                 = '';
        $this->no_hp               = '';
        $this->status_anggota      = '';
        $this->status_aktif        = '';
        $this->foto                = '';
        $this->email               = '';
        $this->password            = '';
        $this->password_confirmation = '';
        $this->motivasi            = '';
        $this->pengalaman          = '';
        $this->getTahunKepengurusan();
        $this->getDivisi($this->id_tahun);
    }

    private function getTahunKepengurusan()
    {
        $this->tahuns = DB::table('tahun_kepengurusan')->select('id', 'nama_tahun')->get();
        // dd($this->tahuns);
    }

    private function getDivisi($idTahun = null)
    {
        $query = DB::table('divisi')->select('id', 'nama_divisi');
        
        if ($idTahun) {
            $query->where('id_tahun', $idTahun);
        }
        
        $this->divisis = $query->get();
    }

    public function updatedIdTahun($value)
    {
        // Reload divisi ketika tahun berubah
        $this->getDivisi($value);
        // Reset divisi yang dipilih
        $this->id_divisi = '';
    }

    public function updatedStatusAnggota($value)
    {
        // Auto-set untuk anggota
        if ($value === 'anggota') {
            $this->id_divisi = '';
            $this->nama_jabatan = 'anggota';
        } else {
            // Reset untuk pengurus
            $this->id_divisi = '';
            $this->nama_jabatan = '';
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

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $dataPengurus = ModelsAnggota::select('anggota.id', 'divisi.nama_divisi', 'anggota.nama_jabatan', 'anggota.nama_lengkap', 'anggota.kelas', 'anggota.jurusan', 'anggota.status_anggota', 'anggota.status_aktif', 'anggota.foto', 'tahun_kepengurusan.nama_tahun')
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->leftJoin('divisi', 'divisi.id', '=', 'anggota.id_divisi')
                ->where(function ($query) use ($search) {
                    $query->where('nama_lengkap', 'LIKE', $search);
                    $query->orWhere('nama_jabatan', 'LIKE', $search);
                    $query->orWhere('kelas', 'LIKE', $search);
                    $query->orWhere('jurusan', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'pengurus')
                ->orderBy('id', 'ASC')
                ->paginate($this->lengthData);

        $dataAnggota = ModelsAnggota::select('anggota.id', 'divisi.nama_divisi', 'anggota.nama_jabatan', 'anggota.nama_lengkap', 'anggota.kelas', 'anggota.jurusan', 'anggota.status_anggota', 'anggota.status_aktif', 'anggota.foto', 'tahun_kepengurusan.nama_tahun')
                ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
                ->leftJoin('divisi', 'divisi.id', '=', 'anggota.id_divisi')
                ->where(function ($query) use ($search) {
                    $query->where('nama_lengkap', 'LIKE', $search);
                    $query->orWhere('nama_jabatan', 'LIKE', $search);
                    $query->orWhere('kelas', 'LIKE', $search);
                    $query->orWhere('jurusan', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('anggota.status_anggota', 'anggota')
                ->orderBy('id', 'ASC')
                ->paginate($this->lengthData);

        return view('livewire.organisasi.anggota', compact('dataPengurus', 'dataAnggota'));
    }

    public function store()
    {
        // Auto-set untuk anggota sebelum validasi
        if ($this->status_anggota === 'anggota') {
            $this->id_divisi = '';
            $this->nama_jabatan = 'anggota';
        }

        // Conditional validation berdasarkan status_anggota
        $rules = $this->rules;
        if ($this->status_anggota === 'pengurus') {
            $rules['id_divisi'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Untuk anggota, id_divisi dan nama_jabatan tidak required
            unset($rules['id_divisi']);
            unset($rules['nama_jabatan']);
        }

        $this->validate($rules);

        // Validasi password confirmation
        if ($this->password && $this->password !== $this->password_confirmation) {
            $this->dispatchAlert('warning', 'Warning!', 'Password confirmation does not match.');
            return;
        }

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
                
                // Assign role berdasarkan status_anggota (pengurus atau anggota)
                $user->addRole($this->status_anggota);
                
                $userId = $user->id;
            }
        }

        ModelsAnggota::create([
            'id_user'             => $userId,
            'id_tahun'            => $this->id_tahun,
            'id_divisi'           => $this->id_divisi,
            'nama_lengkap'        => $this->nama_lengkap,
            'nama_jabatan'        => $this->nama_jabatan,
            'kelas'               => $this->kelas,
            'jurusan'             => $this->jurusan,
            'nim'                 => $this->nim,
            'no_hp'               => $this->no_hp,
            'status_anggota'      => $this->status_anggota,
            'status_aktif'        => $this->status_aktif,
            'foto'                => $this->foto,
            'motivasi'            => $this->motivasi,
            'pengalaman'          => $this->pengalaman,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsAnggota::where('id', $id)->first();
        $this->getTahunKepengurusan();
        $this->dataId           = $id;
        $this->id_user          = $data->id_user;
        $this->id_tahun         = $data->id_tahun;
        $this->getDivisi($data->id_tahun);
        $this->id_divisi        = $data->id_divisi;
        $this->nama_lengkap     = $data->nama_lengkap;
        $this->nama_jabatan     = $data->nama_jabatan;
        $this->kelas            = $data->kelas;
        $this->jurusan          = $data->jurusan;
        $this->nim              = $data->nim;
        $this->no_hp            = $data->no_hp;
        $this->status_anggota   = $data->status_anggota;
        $this->status_aktif     = $data->status_aktif;
        $this->foto             = $data->foto;
        $this->motivasi         = $data->motivasi;
        $this->pengalaman       = $data->pengalaman;
        
        // Load email dari user
        if ($data->id_user) {
            $user = \App\Models\User::find($data->id_user);
            if ($user) {
                $this->email = $user->email;
            }
        }
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function view($id)
    {
        $this->viewData = ModelsAnggota::select(
                'anggota.*',
                'divisi.nama_divisi',
                'tahun_kepengurusan.nama_tahun',
                'tahun_kepengurusan.mulai',
                'tahun_kepengurusan.akhir',
                'users.email'
            )
            ->join('tahun_kepengurusan', 'tahun_kepengurusan.id', '=', 'anggota.id_tahun')
            ->leftJoin('divisi', 'divisi.id', '=', 'anggota.id_divisi')
            ->leftJoin('users', 'users.id', '=', 'anggota.id_user')
            ->where('anggota.id', $id)
            ->first();
    }

    public function update()
    {
        // Auto-set untuk anggota sebelum validasi
        if ($this->status_anggota === 'anggota') {
            $this->id_divisi = '';
            $this->nama_jabatan = 'anggota';
        }

        // Conditional validation berdasarkan status_anggota
        $rules = $this->rules;
        if ($this->status_anggota === 'pengurus') {
            $rules['id_divisi'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Untuk anggota, id_divisi dan nama_jabatan tidak required
            unset($rules['id_divisi']);
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
                        $user->syncRoles([$this->status_anggota]);
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
                    $user->addRole($this->status_anggota);
                    
                    $userId = $user->id;
                }
            }

            ModelsAnggota::findOrFail($this->dataId)->update([
                'id_user'             => $userId,
                'id_tahun'            => $this->id_tahun,
                'id_divisi'           => $this->id_divisi,
                'nama_lengkap'        => $this->nama_lengkap,
                'nama_jabatan'        => $this->nama_jabatan,
                'kelas'               => $this->kelas,
                'jurusan'             => $this->jurusan,
                'nim'                 => $this->nim,
                'no_hp'               => $this->no_hp,
                'status_anggota'      => $this->status_anggota,
                'status_aktif'        => $this->status_aktif,
                'foto'                => $this->foto,
                'motivasi'            => $this->motivasi,
                'pengalaman'          => $this->pengalaman,
            ]);

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
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
        $anggota = ModelsAnggota::findOrFail($this->dataId);
        
        // Hapus user jika ada
        if ($anggota->id_user) {
            $user = \App\Models\User::find($anggota->id_user);
            if ($user) {
                // Hapus role dari tabel role_user
                DB::table('role_user')->where('user_id', $anggota->id_user)->delete();
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
        
        $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
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
            $this->getDivisi($this->id_tahun);
        }
    }
    
    private function resetInputFields()
    {
        $this->id_user             = '';
        // Reset id_tahun ke tahun kepengurusan yang aktif
        $tahunAktif = DB::table('tahun_kepengurusan')->where('status', 'aktif')->first();
        $this->id_tahun            = $tahunAktif ? $tahunAktif->id : '';
        $this->getDivisi($this->id_tahun);
        $this->id_divisi           = '';
        $this->nama_lengkap        = '';
        $this->nama_jabatan        = '';
        $this->kelas               = '';
        $this->jurusan             = '';
        $this->nim                 = '';
        $this->no_hp               = '';
        $this->status_anggota      = '';
        $this->status_aktif        = '';
        $this->foto                = '';
        $this->email               = '';
        $this->password            = '';
        $this->password_confirmation = '';
        $this->motivasi            = '';
        $this->pengalaman          = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}