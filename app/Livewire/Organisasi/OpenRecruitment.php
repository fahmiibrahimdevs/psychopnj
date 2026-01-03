<?php

namespace App\Livewire\Organisasi;

use App\Models\Divisi;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use Illuminate\Support\Facades\DB;
use App\Models\OpenRecruitment as ModelsOpenRecruitment;

class OpenRecruitment extends Component
{
    use WithPagination;
    #[Title('Open Recruitment')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_tahun'            => '',
        'jenis_oprec'         => 'required',
        'nama_lengkap'        => 'required',
        'kelas'               => 'required',
        'jurusan'             => 'required',
        'id_divisi'           => '',
        'nama_jabatan'        => '',
        'motivasi'            => '',
        'pengalaman'          => '',
        'status_seleksi'      => '',
    ];

    // Mapping jurusan ke kode email
    private $jurusanEmailMap = [
        'Teknik Sipil' => 'ts',
        'Teknik Mesin' => 'tm',
        'Teknik Elektro' => 'te',
        'Akuntansi' => 'ak',
        'Administrasi Niaga' => 'an',
        'Teknik Grafika Penerbitan' => 'tgp',
        'Teknik Informatika dan Komputer' => 'tik',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $activeTab = 'pengurus';
    public $viewData = [];

    public $id_tahun, $jenis_oprec, $nama_lengkap, $kelas, $jurusan, $id_divisi, $nama_jabatan, $motivasi, $pengalaman, $status_seleksi;
    public $divisis;

    public function mount()
    {
        $this->divisis             = DB::table('divisi')->select('id', 'nama_divisi')->get();
        $this->id_tahun            = TahunKepengurusan::where('status', 'aktif')->first()->id ?? '';
        $this->jenis_oprec         = '';
        $this->nama_lengkap        = '';
        $this->kelas               = '';
        $this->jurusan             = '';
        $this->id_divisi           = '';
        $this->nama_jabatan        = '';
        $this->motivasi            = '';
        $this->pengalaman          = '';
        $this->status_seleksi      = 'pending';
    }

    public function updatedJenisOprec($value)
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

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $dataPengurus = ModelsOpenRecruitment::select(
                    'open_recruitment.id', 
                    'open_recruitment.nama_lengkap', 
                    'open_recruitment.kelas',
                    'open_recruitment.jurusan',
                    'divisi.nama_divisi',
                    'open_recruitment.nama_jabatan',
                    'open_recruitment.status_seleksi',
                )
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->join('divisi', 'open_recruitment.id_divisi', '=', 'divisi.id')
                ->where(function ($query) use ($search) {
                    $query->orWhere('nama_lengkap', 'LIKE', $search);
                    $query->orWhere('kelas', 'LIKE', $search);
                    $query->orWhere('nama_divisi', 'LIKE', $search);
                    $query->orWhere('nama_jabatan', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'pengurus')
                ->orderBy('id', 'ASC')
                ->paginate($this->lengthData);

        $dataAnggota = ModelsOpenRecruitment::select(
                    'open_recruitment.id', 
                    'open_recruitment.nama_lengkap', 
                    'open_recruitment.kelas',
                    'open_recruitment.jurusan',
                    'open_recruitment.status_seleksi',
                )
                ->join('tahun_kepengurusan', 'open_recruitment.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where(function ($query) use ($search) {
                    $query->orWhere('nama_lengkap', 'LIKE', $search);
                    $query->orWhere('kelas', 'LIKE', $search);
                })
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('open_recruitment.jenis_oprec', 'anggota')
                ->orderBy('id', 'ASC')
                ->paginate($this->lengthData);

        return view('livewire.organisasi.open-recruitment', compact('dataPengurus', 'dataAnggota'));
    }

    public function view($id)
    {
        $this->viewData = ModelsOpenRecruitment::select(
                    'open_recruitment.*',
                    'divisi.nama_divisi',
                    'tahun_kepengurusan.nama_tahun'
                )
                ->leftJoin('divisi', 'open_recruitment.id_divisi', '=', 'divisi.id')
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
            'kelas' => 'required',
            'jurusan' => 'required',
        ];

        // Tambah validation untuk pengurus
        if ($this->jenis_oprec === 'pengurus') {
            $rules['id_divisi'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Auto-set untuk anggota
            $this->id_divisi = '';
            $this->nama_jabatan = 'anggota';
        }

        $this->validate($rules);

        ModelsOpenRecruitment::create([
            'id_tahun'            => $this->id_tahun,
            'jenis_oprec'         => $this->jenis_oprec,
            'nama_lengkap'        => $this->nama_lengkap,
            'kelas'               => $this->kelas,
            'jurusan'             => $this->jurusan,
            'id_divisi'           => $this->id_divisi,
            'nama_jabatan'        => $this->nama_jabatan,
            'motivasi'            => $this->motivasi,
            'pengalaman'          => $this->pengalaman,
            'status_seleksi'      => $this->status_seleksi,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsOpenRecruitment::where('id', $id)->first();
        $this->dataId           = $id;
        $this->id_tahun         = $data->id_tahun;
        $this->jenis_oprec      = $data->jenis_oprec;
        $this->nama_lengkap     = $data->nama_lengkap;
        $this->kelas            = $data->kelas;
        $this->jurusan          = $data->jurusan;
        $this->id_divisi        = $data->id_divisi;
        $this->nama_jabatan     = $data->nama_jabatan;
        $this->motivasi         = $data->motivasi;
        $this->pengalaman       = $data->pengalaman;
        $this->status_seleksi   = $data->status_seleksi;
    }

    public function update()
    {
        // Conditional validation
        $rules = [
            'jenis_oprec' => 'required',
            'nama_lengkap' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
        ];

        // Tambah validation untuk pengurus
        if ($this->jenis_oprec === 'pengurus') {
            $rules['id_divisi'] = 'required';
            $rules['nama_jabatan'] = 'required';
        } else {
            // Auto-set untuk anggota
            $this->id_divisi = '';
            $this->nama_jabatan = 'anggota';
        }

        $this->validate($rules);

        if( $this->dataId )
        {
            ModelsOpenRecruitment::findOrFail($this->dataId)->update([
                'id_tahun'            => $this->id_tahun,
                'jenis_oprec'         => $this->jenis_oprec,
                'nama_lengkap'        => $this->nama_lengkap,
                'kelas'               => $this->kelas,
                'jurusan'             => $this->jurusan,
                'id_divisi'           => $this->id_divisi,
                'nama_jabatan'        => $this->nama_jabatan,
                'motivasi'            => $this->motivasi,
                'pengalaman'          => $this->pengalaman,
                'status_seleksi'      => $this->status_seleksi,
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
        $openRecruitment = ModelsOpenRecruitment::findOrFail($this->dataId);
        
        // Delete cascade: user, role, anggota jika status_seleksi = lulus
        if ($openRecruitment->status_seleksi === 'lulus') {
            if ($openRecruitment->id_user) {
                $user = \App\Models\User::find($openRecruitment->id_user);
                if ($user) {
                    // Hapus role dari tabel role_user
                    DB::table('role_user')->where('user_id', $openRecruitment->id_user)->delete();
                    $user->delete();
                }
            }
            
            if ($openRecruitment->id_anggota) {
                \App\Models\Anggota::find($openRecruitment->id_anggota)?->delete();
            }
        }
        
        // Delete open recruitment
        $openRecruitment->delete();
        
        $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
    }

    public function updateStatus($id, $status)
    {
        $openRecruitment = ModelsOpenRecruitment::findOrFail($id);
        $oldStatus = $openRecruitment->status_seleksi;
        
        // Jika status diubah menjadi lulus
        if ($status === 'lulus' && $oldStatus !== 'lulus') {
            // Generate email
            $email = $this->generateEmail($openRecruitment->nama_lengkap, $openRecruitment->jurusan);
            
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
            
            // Create User
            $user = \App\Models\User::create([
                'name' => $openRecruitment->nama_lengkap,
                'email' => $email,
                'password' => bcrypt('psychopnj2026'),
                'active' => $openRecruitment->jenis_oprec == 'pengurus' ? '1' : '0',
            ]);
            
            // Assign role berdasarkan jenis_oprec
            $user->addRole($openRecruitment->jenis_oprec);
            
            // Create Anggota
            $anggota = \App\Models\Anggota::create([
                'id_user' => $user->id,
                'id_tahun' => $openRecruitment->id_tahun,
                'id_divisi' => $openRecruitment->id_divisi,
                'nama_lengkap' => $openRecruitment->nama_lengkap,
                'nama_jabatan' => $openRecruitment->nama_jabatan,
                'kelas' => $openRecruitment->kelas,
                'jurusan' => $openRecruitment->jurusan,
                'nim' => '',
                'no_hp' => '62',
                'status_anggota' => $openRecruitment->jenis_oprec,
                'status_aktif' => 'aktif',
                'foto' => '',
                'motivasi' => $openRecruitment->motivasi,
                'pengalaman' => $openRecruitment->pengalaman,
                'id_open_recruitment' => $openRecruitment->id,
            ]);
            
            // Update open_recruitment dengan id_anggota dan id_user
            $openRecruitment->update([
                'status_seleksi' => $status,
                'id_anggota' => $anggota->id,
                'id_user' => $user->id,
            ]);
            
            $this->dispatch('swal:modal', [
                'type'      => 'success',  
                'message'   => 'Success!', 
                'text'      => "Status seleksi berhasil diubah menjadi Lulus. Anggota otomatis terdaftar dengan email: {$email}"
            ]);
        }
        // Jika status diubah dari lulus ke gagal atau pending
        elseif ($status !== 'lulus' && $oldStatus === 'lulus') {
            // Delete cascade: user, role, anggota
            if ($openRecruitment->id_user) {
                $user = \App\Models\User::find($openRecruitment->id_user);
                if ($user) {
                    // Hapus role dari tabel role_user
                    DB::table('role_user')->where('user_id', $openRecruitment->id_user)->delete();
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
            
            $statusText = $status === 'gagal' ? 'Gagal' : 'Pending';
            $this->dispatch('swal:modal', [
                'type'      => 'success',  
                'message'   => 'Success!', 
                'text'      => "Status seleksi berhasil diubah menjadi {$statusText}. Data anggota dan user telah dihapus."
            ]);
        }
        // Jika hanya update status biasa (pending ke gagal, dll)
        else {
            $openRecruitment->update([
                'status_seleksi' => $status
            ]);
            
            $statusText = $status === 'lulus' ? 'Lulus' : ($status === 'gagal' ? 'Gagal' : 'Pending');
            $this->dispatch('swal:modal', [
                'type'      => 'success',  
                'message'   => 'Success!', 
                'text'      => "Status seleksi berhasil diubah menjadi {$statusText}."
            ]);
        }
    }
    
    private function generateEmail($namaLengkap, $jurusan)
    {
        // Pecah nama berdasarkan spasi dan convert ke lowercase
        $namaParts = explode(' ', strtolower(trim($namaLengkap)));
        
        // Gabungkan dengan titik
        $emailPrefix = implode('.', $namaParts);
        
        // Get kode jurusan
        $kodeJurusan = $this->jurusanEmailMap[$jurusan] ?? 'te';
        
        // Format final: nama.lengkap.jurusan@stu.pnj.ac.id
        return $emailPrefix . '.' . $kodeJurusan . '@stu.pnj.ac.id';
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
