<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use App\Models\Anggota;

class Profile extends Component
{
    use WithFileUploads;
    
    #[Title('Profile')]
    
    // User data
    public $name;
    public $email;
    public $current_password;
    public $password;
    public $password_confirmation;
    
    // Anggota data
    public $anggota;
    public $nama_lengkap;
    public $nama_jabatan;
    public $jurusan_prodi_kelas;
    public $nim;
    public $ttl;
    public $alamat;
    public $no_hp;
    public $foto;
    public $foto_preview;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
    ];
    
    protected $messages = [
        'name.required' => 'Nama harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'current_password.required' => 'Password saat ini harus diisi',
        'password.required' => 'Password baru harus diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'nama_lengkap.required' => 'Nama lengkap harus diisi',
        'jurusan_prodi_kelas.required' => 'Jurusan/Prodi/Kelas harus diisi',
        'foto.image' => 'File harus berupa gambar',
        'foto.max' => 'Ukuran foto maksimal 2MB',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        
        // Load data anggota jika ada
        $this->anggota = Anggota::where('id_user', $user->id)->first();
        
        if ($this->anggota) {
            $this->nama_lengkap = $this->anggota->nama_lengkap;
            $this->nama_jabatan = $this->anggota->nama_jabatan;
            $this->jurusan_prodi_kelas = $this->anggota->jurusan_prodi_kelas;
            $this->nim = $this->anggota->nim;
            $this->ttl = $this->anggota->ttl;
            $this->alamat = $this->anggota->alamat;
            $this->no_hp = $this->anggota->no_hp;
            $this->foto_preview = storageUrl($this->anggota->foto);
        }
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        try {
            $user = Auth::user();
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Berhasil!',
                'text' => 'Profile berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    public function updateBiodata()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jurusan_prodi_kelas' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        try {
            if (!$this->anggota) {
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Data anggota tidak ditemukan. Hubungi administrator.'
                ]);
                return;
            }

            $updateData = [
                'nama_lengkap' => $this->nama_lengkap,
                'nama_jabatan' => $this->nama_jabatan ?? '-',
                'jurusan_prodi_kelas' => $this->jurusan_prodi_kelas,
                'nim' => $this->nim,
                'ttl' => $this->ttl,
                'alamat' => $this->alamat,
                'no_hp' => $this->no_hp,
            ];

            // Handle foto upload
            if ($this->foto) {
                // Hapus foto lama jika ada
                if ($this->anggota->foto && Storage::disk('public')->exists($this->anggota->foto)) {
                    Storage::disk('public')->delete($this->anggota->foto);
                }

                // Upload foto baru
                $extension = $this->foto->getClientOriginalExtension();
                $filename = 'anggota_' . $this->anggota->id . '_' . time() . '.' . $extension;
                $year = date('Y');
                $path = $this->foto->storeAs($year . '/anggota', $filename, 'public');
                
                $updateData['foto'] = $path;
                $this->foto_preview = storageUrl($path);
            }

            $this->anggota->update($updateData);
            
            // Reset foto input
            $this->foto = null;

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Berhasil!',
                'text' => 'Biodata berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $user = Auth::user();

            // Cek password saat ini
            if (!Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Password saat ini tidak sesuai');
                return;
            }

            // Update password
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            // Reset form password
            $this->current_password = '';
            $this->password = '';
            $this->password_confirmation = '';

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Berhasil!',
                'text' => 'Password berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
