<?php

namespace App\Livewire\Organisasi;

use App\Models\TahunKepengurusan as ModelsTahunKepengurusan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Traits\WithPermissionCache;

class TahunKepengurusan extends Component
{
    use WithPagination, WithPermissionCache;
    #[Title('Tahun Kepengurusan')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama_tahun'          => 'required',
        'status'              => 'required',
        'deskripsi'           => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $nama_tahun, $status, $deskripsi;

    public function mount()
    {
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        $this->nama_tahun          = '';
        $this->status              = 'nonaktif';
        $this->deskripsi           = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $query = DB::table('tahun_kepengurusan')
            ->select(
                'tahun_kepengurusan.id', 
                'tahun_kepengurusan.nama_tahun', 
                'tahun_kepengurusan.status',
                'ketua.nama_lengkap as nama_ketua',
                'wakil.nama_lengkap as nama_waketua'
            )
            ->leftJoin('anggota as ketua', function($join) {
                $join->on('tahun_kepengurusan.id', '=', 'ketua.id_tahun')
                     ->where('ketua.nama_jabatan', '=', 'Ketua')
                     ->where('ketua.status_aktif', '=', 'aktif');
            })
            ->leftJoin('anggota as wakil', function($join) {
                $join->on('tahun_kepengurusan.id', '=', 'wakil.id_tahun')
                     ->where('wakil.nama_jabatan', '=', 'Wakil Ketua')
                     ->where('wakil.status_aktif', '=', 'aktif');
            })
            ->where(function ($query) use ($search) {
                $query->where('tahun_kepengurusan.nama_tahun', 'LIKE', $search)
                      ->orWhere('ketua.nama_lengkap', 'LIKE', $search)
                      ->orWhere('wakil.nama_lengkap', 'LIKE', $search);
            })
            ->orderBy('tahun_kepengurusan.id', 'DESC');

        $total = $query->count();
        $tahuns = $query->skip(($this->getPage() - 1) * $this->lengthData)
            ->take($this->lengthData)
            ->get();

        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $tahuns,
            $total,
            $this->lengthData,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.organisasi.tahun-kepengurusan', compact('data'));
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            // Jika status aktif, nonaktifkan semua tahun kepengurusan lain
            if ($this->status == 'aktif') {
                DB::table('tahun_kepengurusan')
                    ->where('status', 'aktif')
                    ->update(['status' => 'nonaktif']);
            }

            DB::table('tahun_kepengurusan')->insert([
                'nama_tahun'          => $this->nama_tahun,
                'status'              => $this->status,
                'deskripsi'           => $this->deskripsi,
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
            $data = DB::table('tahun_kepengurusan')
                ->select('id', 'nama_tahun', 'status', 'deskripsi')
                ->where('id', $id)
                ->first();
                
            $this->dataId           = $id;
            $this->nama_tahun       = $data->nama_tahun;
            $this->status           = $data->status;
            $this->deskripsi        = $data->deskripsi;
        } catch (\Exception $e) {
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            DB::beginTransaction();
            try {
                // Jika status aktif, nonaktifkan semua tahun kepengurusan lain
                if ($this->status == 'aktif') {
                    DB::table('tahun_kepengurusan')
                        ->where('id', '!=', $this->dataId)
                        ->where('status', 'aktif')
                        ->update(['status' => 'nonaktif']);
                }

                DB::table('tahun_kepengurusan')
                    ->where('id', $this->dataId)
                    ->update([
                        'nama_tahun'          => $this->nama_tahun,
                        'status'              => $this->status,
                        'deskripsi'           => $this->deskripsi,
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
            $tahun = \App\Models\TahunKepengurusan::findOrFail($this->dataId);
            
            // Hapus foto dari anggota
            $anggotas = \App\Models\Anggota::where('id_tahun', $this->dataId)->get();
            foreach ($anggotas as $anggota) {
                if ($anggota->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto);
                }
            }
            
            // Hapus thumbnail dari projects
            $projects = \App\Models\Project::where('id_tahun', $this->dataId)->get();
            foreach ($projects as $project) {
                if ($project->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($project->thumbnail)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($project->thumbnail);
                }
            }
            
            // Hapus foto dari profil organisasi
            $profils = \App\Models\ProfilOrganisasi::where('id_tahun', $this->dataId)->get();
            foreach ($profils as $profil) {
                if ($profil->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($profil->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($profil->foto);
                }
            }
            
            // Hapus foto dari open recruitment
            $oprecs = \App\Models\OpenRecruitment::where('id_tahun', $this->dataId)->get();
            foreach ($oprecs as $oprec) {
                if ($oprec->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($oprec->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oprec->foto);
                }
            }
            
            // Hapus thumbnail dan files dari program pembelajaran
            $programs = \App\Models\ProgramPembelajaran::where('id_tahun', $this->dataId)->get();
            foreach ($programs as $program) {
                if ($program->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($program->thumbnail)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($program->thumbnail);
                }
                
                // Hapus files dari pertemuan yang ada di program ini
                $pertemuans = \App\Models\Pertemuan::where('id_program', $program->id)->get();
                foreach ($pertemuans as $pertemuan) {
                    // Hapus thumbnail pertemuan
                    if ($pertemuan->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($pertemuan->thumbnail)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($pertemuan->thumbnail);
                    }
                    
                    // Hapus files pertemuan
                    $files = \App\Models\PertemuanFile::where('id_pertemuan', $pertemuan->id)->get();
                    foreach ($files as $file) {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($file->file_path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->file_path);
                        }
                    }
                    
                    // Hapus galeri pertemuan
                    $galeris = \App\Models\PertemuanGaleri::where('id_pertemuan', $pertemuan->id)->get();
                    foreach ($galeris as $galeri) {
                        if ($galeri->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($galeri->file_path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($galeri->file_path);
                        }
                    }
                }
            }
            
            // Hapus files dari transaksi keuangan
            $keuangans = \App\Models\Keuangan::where('id_tahun', $this->dataId)->get();
            foreach ($keuangans as $keuangan) {
                $files = \App\Models\TransaksiFile::where('id_transaksi', $keuangan->id)->get();
                foreach ($files as $file) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($file->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($file->file_path);
                    }
                }
            }
            
            // Hapus tahun kepengurusan (cascade akan hapus semua record di database)
            $tahun->delete();
                
            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        DB::beginTransaction();
        try {
            // Nonaktifkan semua tahun kepengurusan
            DB::table('tahun_kepengurusan')
                ->where('status', 'aktif')
                ->update(['status' => 'nonaktif']);
            
            // Aktifkan tahun kepengurusan yang dipilih
            DB::table('tahun_kepengurusan')
                ->where('id', $id)
                ->update(['status' => 'aktif']);
            
            DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Tahun kepengurusan berhasil diaktifkan.');
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
    }
    
    private function resetInputFields()
    {
        $this->nama_tahun          = '';
        $this->status              = 'nonaktif';
        $this->deskripsi           = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
