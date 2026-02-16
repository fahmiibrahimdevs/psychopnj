<?php

namespace App\Livewire\Sekretaris;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Traits\WithPermissionCache;
use App\Models\Sekretaris\SuratFile;
use App\Models\Sekretaris\SuratMasuk;
use App\Models\Sekretaris\SuratKeluar;
use Illuminate\Support\Facades\Storage;
use App\Models\Sekretaris\KategoriDokumen;
use App\Models\Sekretaris\DokumenOrganisasi;

class Surat extends Component
{
    use WithFileUploads, WithPagination, WithPermissionCache;

    #[Title('Administrasi Surat & Dokumen')]

    public $activeTab = 'surat-masuk';
    public $searchTerm = '';
    public $lengthData = 10;
    public $isEditing = false;
    public $dataIdToDelete = null; 

    // Category List for Dynamic Tabs
    public $kategoriList = [];

    // Surat Masuk Properties
    public $sm_id;
    public $sm_nomor_surat, $sm_perihal, $sm_pengirim, $sm_ditujukan_kepada, $sm_tanggal_masuk;
    public $sm_files = [];
    
    // Surat Keluar Properties
    public $sk_id;
    public $sk_nomor_surat, $sk_perihal, $sk_penerima, $sk_tanggal_keluar, $sk_status;
    public $sk_files = [];

    // Dokumen Organisasi Properties (Dynamic)
    public $do_id;
    public $do_nama, $do_nomor, $do_deskripsi, $do_tanggal;
    public $do_files = [];

    // Existing Files for Edit Mode
    public $existing_files = [];

    public function mount()
    {
        $this->cacheUserPermissions();
        // Load Categories
        $this->kategoriList = KategoriDokumen::all();

        // Set default dates
        $this->sm_tanggal_masuk = date('Y-m-d');
        $this->sk_tanggal_keluar = date('Y-m-d');
        $this->do_tanggal = date('Y-m-d');
        $this->sk_status = 'Draft';
    }

    protected function rules()
    {
        if ($this->activeTab === 'surat-masuk') {
            return [
                'sm_nomor_surat' => 'required',
                'sm_perihal' => 'required',
                'sm_pengirim' => 'required',
                'sm_ditujukan_kepada' => 'required',
                'sm_tanggal_masuk' => 'required|date',
                'sm_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            ];
        } elseif ($this->activeTab === 'surat-keluar') {
            return [
                'sk_nomor_surat' => 'required',
                'sk_perihal' => 'required',
                'sk_penerima' => 'required',
                'sk_tanggal_keluar' => 'required|date',
                'sk_status' => 'required',
                'sk_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
            ];
        } else {
            // Rules for Dokumen Organisasi
            return [
                'do_nama' => 'required',
                'do_nomor' => 'nullable', // Optional
                'do_deskripsi' => 'nullable',
                'do_tanggal' => 'required|date',
                'do_files.*' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png|max:10240',
            ];
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->searchTerm = '';
        $this->cancel();
    }

    public function render()
    {
        \Carbon\Carbon::setLocale('id');
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $idTahun = $activeTahun ? $activeTahun->id : null;
        $activeTab = $this->activeTab;

        if ($activeTab === 'surat-masuk') {
            $data = SuratMasuk::with('files')
                ->where('id_tahun_kepengurusan', $idTahun)
                ->where(function($q) {
                    $q->where('nomor_surat', 'like', '%'.$this->searchTerm.'%')
                      ->orWhere('perihal', 'like', '%'.$this->searchTerm.'%')
                      ->orWhere('pengirim', 'like', '%'.$this->searchTerm.'%');
                })
                ->orderBy('tanggal_masuk', 'desc')
                ->paginate($this->lengthData);
                
        } elseif ($activeTab === 'surat-keluar') {
            $data = SuratKeluar::with('files')
                ->where('id_tahun_kepengurusan', $idTahun)
                ->where(function($q) {
                    $q->where('nomor_surat', 'like', '%'.$this->searchTerm.'%')
                      ->orWhere('perihal', 'like', '%'.$this->searchTerm.'%')
                      ->orWhere('penerima', 'like', '%'.$this->searchTerm.'%');
                })
                ->orderBy('tanggal_keluar', 'desc')
                ->paginate($this->lengthData);

        } else {
            // Dynamic Documents based on Category Slug
            $kategori = $this->kategoriList->where('slug', $activeTab)->first();
            
            if ($kategori) {
                $data = DokumenOrganisasi::with('files')
                    ->where('id_tahun_kepengurusan', $idTahun)
                    ->where('id_kategori_dokumen', $kategori->id)
                    ->where(function($q) {
                        $q->where('nama_dokumen', 'like', '%'.$this->searchTerm.'%')
                          ->orWhere('nomor_dokumen', 'like', '%'.$this->searchTerm.'%')
                          ->orWhere('deskripsi', 'like', '%'.$this->searchTerm.'%');
                    })
                    ->orderBy('tanggal', 'desc')
                    ->paginate($this->lengthData);
            } else {
                // Fallback empty pagination if tab mismatch
                $data = \App\Models\Sekretaris\SuratMasuk::whereRaw('1=0')->paginate($this->lengthData);
            }
        }

        return view('livewire.sekretaris.surat', [
            'data' => $data,
            'activeTahun' => $activeTahun,
            'currentKategori' => isset($kategori) ? $kategori : null
        ]);
    }

    public function resetInputFields()
    {
        $this->sm_id = null; $this->sm_nomor_surat = ''; $this->sm_perihal = ''; 
        $this->sm_pengirim = ''; $this->sm_ditujukan_kepada = ''; 
        $this->sm_tanggal_masuk = date('Y-m-d'); $this->sm_files = [];
        
        $this->sk_id = null; $this->sk_nomor_surat = ''; $this->sk_perihal = ''; 
        $this->sk_penerima = ''; $this->sk_tanggal_keluar = date('Y-m-d'); 
        $this->sk_status = 'Draft'; $this->sk_files = [];

        $this->do_id = null; $this->do_nama = ''; $this->do_nomor = '';
        $this->do_deskripsi = ''; $this->do_tanggal = date('Y-m-d');
        $this->do_files = [];
        
        $this->existing_files = [];
        $this->isEditing = false;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    private function generateFileName($prefix, $mainText)
    {
        $safePrefix = str_replace(['/', '\\'], '-', $prefix);
        $safePrefix = preg_replace('/[^A-Za-z0-9\-\_ ]/', '', $safePrefix);
        $safeMain = preg_replace('/[^A-Za-z0-9\-\_ ]/', '', $mainText);
        
        $safePrefix = trim(substr($safePrefix, 0, 50));
        $safeMain = trim(substr($safeMain, 0, 80));

        return "{$safePrefix} - {$safeMain}";
    }

    public function store()
    {
        $this->validate();

        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        if (!$activeTahun) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Tidak ada Tahun Kepengurusan Aktif!']);
            return;
        }
        $tahunFolder = $activeTahun->nama_tahun;

        // Common file saving logic
        $saveFiles = function($model, $files, $folderPath, $prefix, $nameKey, $tanggal) use ($tahunFolder) {
            // Get month and year from tanggal
            setlocale(LC_TIME, 'id_ID.UTF-8', 'Indonesian_Indonesia.1252', 'id_ID', 'IND');
            $bulanTahun = ucfirst(\Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('MMMM YYYY'));
            
            foreach ($files as $file) {
                $baseName = $this->generateFileName($prefix, $nameKey);
                $randomSuffix = strtoupper(Str::random(2));
                $finalName = "{$baseName}_{$randomSuffix}." . $file->getClientOriginalExtension();
                
                $fullPath = "{$folderPath}/{$bulanTahun}";
                $filePath = $file->storeAs($fullPath, $finalName, 'public');

                $model->files()->create([
                    'file_path' => $filePath,
                    'file_name' => $finalName,
                    'file_size' => $file->getSize(),
                ]);
            }
        };

        if ($this->activeTab === 'surat-masuk') {
            $surat = SuratMasuk::create([
                'id_tahun_kepengurusan' => $activeTahun->id,
                'nomor_surat' => $this->sm_nomor_surat,
                'perihal' => $this->sm_perihal,
                'pengirim' => $this->sm_pengirim,
                'ditujukan_kepada' => $this->sm_ditujukan_kepada,
                'tanggal_masuk' => $this->sm_tanggal_masuk,
            ]);
            $saveFiles($surat, $this->sm_files, "{$tahunFolder}/Dept. Kesekretariatan/Surat Masuk", $this->sm_nomor_surat, $this->sm_perihal, $this->sm_tanggal_masuk);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Surat Masuk berhasil ditambahkan!']);

        } elseif ($this->activeTab === 'surat-keluar') {
            $surat = SuratKeluar::create([
                'id_tahun_kepengurusan' => $activeTahun->id,
                'nomor_surat' => $this->sk_nomor_surat,
                'perihal' => $this->sk_perihal,
                'penerima' => $this->sk_penerima,
                'tanggal_keluar' => $this->sk_tanggal_keluar,
                'status' => $this->sk_status,
            ]);
            $saveFiles($surat, $this->sk_files, "{$tahunFolder}/Dept. Kesekretariatan/Surat Keluar", $this->sk_nomor_surat, $this->sk_perihal, $this->sk_tanggal_keluar);
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Surat Keluar berhasil ditambahkan!']);
            
        } else {
            // Dynamic Dokumen Organisasi
            $kategori = $this->kategoriList->where('slug', $this->activeTab)->first();
            if ($kategori) {
                $dokumen = DokumenOrganisasi::create([
                    'id_tahun_kepengurusan' => $activeTahun->id,
                    'id_kategori_dokumen' => $kategori->id,
                    'nama_dokumen' => $this->do_nama,
                    'nomor_dokumen' => $this->do_nomor,
                    'deskripsi' => $this->do_deskripsi,
                    'tanggal' => $this->do_tanggal,
                ]);
                // Folder: Dept. Kesekretariatan/{Nama Kategori}
                // Prefix: Nomor Dokumen (kalau ada) atau 'Dokumen'
                $prefix = $this->do_nomor ? $this->do_nomor : 'DOC';
                $saveFiles($dokumen, $this->do_files, "{$tahunFolder}/Dept. Kesekretariatan/{$kategori->nama_kategori}", $prefix, $this->do_nama, $this->do_tanggal);
                
                $this->dispatch('alert', ['type' => 'success', 'message' => "{$kategori->nama_kategori} berhasil ditambahkan!"]);
            }
        }

        $this->resetInputFields();
        $this->dispatch('closeModal');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        
        if ($this->activeTab === 'surat-masuk') {
            $data = SuratMasuk::with('files')->findOrFail($id);
            $this->sm_id = $id;
            $this->sm_nomor_surat = $data->nomor_surat;
            $this->sm_perihal = $data->perihal;
            $this->sm_pengirim = $data->pengirim;
            $this->sm_ditujukan_kepada = $data->ditujukan_kepada;
            $this->sm_tanggal_masuk = $data->tanggal_masuk;
            $this->existing_files = $data->files;

        } elseif ($this->activeTab === 'surat-keluar') {
            $data = SuratKeluar::with('files')->findOrFail($id);
            $this->sk_id = $id;
            $this->sk_nomor_surat = $data->nomor_surat;
            $this->sk_perihal = $data->perihal;
            $this->sk_penerima = $data->penerima;
            $this->sk_tanggal_keluar = $data->tanggal_keluar;
            $this->sk_status = $data->status;
            $this->existing_files = $data->files;

        } else {
            // Dokumen Organisasi
            $data = DokumenOrganisasi::with('files')->findOrFail($id);
            $this->do_id = $id;
            $this->do_nama = $data->nama_dokumen;
            $this->do_nomor = $data->nomor_dokumen;
            $this->do_deskripsi = $data->deskripsi;
            $this->do_tanggal = $data->tanggal;
            $this->existing_files = $data->files;
        }
    }

    public function update()
    {
        $this->validate();

        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'Top_Secret_Project';

        $saveFiles = function($model, $files, $folderPath, $prefix, $nameKey, $tanggal) use ($tahunFolder) {
            // Get month and year from tanggal
            setlocale(LC_TIME, 'id_ID.UTF-8', 'Indonesian_Indonesia.1252', 'id_ID', 'IND');
            $bulanTahun = ucfirst(\Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('MMMM YYYY'));
            
            foreach ($files as $file) {
                $baseName = $this->generateFileName($prefix, $nameKey);
                $randomSuffix = strtoupper(Str::random(2));
                $finalName = "{$baseName}_{$randomSuffix}." . $file->getClientOriginalExtension();
                
                $fullPath = "{$folderPath}/{$bulanTahun}";
                $filePath = $file->storeAs($fullPath, $finalName, 'public');

                $model->files()->create([
                    'file_path' => $filePath,
                    'file_name' => $finalName,
                    'file_size' => $file->getSize(),
                ]);
            }
        };

        if ($this->activeTab === 'surat-masuk') {
            $surat = SuratMasuk::findOrFail($this->sm_id);
            $surat->update([
                'nomor_surat' => $this->sm_nomor_surat,
                'perihal' => $this->sm_perihal,
                'pengirim' => $this->sm_pengirim,
                'ditujukan_kepada' => $this->sm_ditujukan_kepada,
                'tanggal_masuk' => $this->sm_tanggal_masuk,
            ]);
            if (!empty($this->sm_files)) {
                $saveFiles($surat, $this->sm_files, "{$tahunFolder}/Dept. Kesekretariatan/Surat Masuk", $this->sm_nomor_surat, $this->sm_perihal, $this->sm_tanggal_masuk);
            }
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Surat Masuk berhasil diperbarui!']);

        } elseif ($this->activeTab === 'surat-keluar') {
            $surat = SuratKeluar::findOrFail($this->sk_id);
            $surat->update([
                'nomor_surat' => $this->sk_nomor_surat,
                'perihal' => $this->sk_perihal,
                'penerima' => $this->sk_penerima,
                'tanggal_keluar' => $this->sk_tanggal_keluar,
                'status' => $this->sk_status,
            ]);
            if (!empty($this->sk_files)) {
                $saveFiles($surat, $this->sk_files, "{$tahunFolder}/Dept. Kesekretariatan/Surat Keluar", $this->sk_nomor_surat, $this->sk_perihal, $this->sk_tanggal_keluar);
            }
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Surat Keluar berhasil diperbarui!']);

        } else {
            // Update Dokumen Organisasi
            $kategori = $this->kategoriList->where('slug', $this->activeTab)->first();
            if ($kategori) {
                $dokumen = DokumenOrganisasi::findOrFail($this->do_id);
                $dokumen->update([
                    'nama_dokumen' => $this->do_nama,
                    'nomor_dokumen' => $this->do_nomor,
                    'deskripsi' => $this->do_deskripsi,
                    'tanggal' => $this->do_tanggal,
                ]);
                
                if (!empty($this->do_files)) {
                    $prefix = $this->do_nomor ? $this->do_nomor : 'DOC';
                    $saveFiles($dokumen, $this->do_files, "{$tahunFolder}/Dept. Kesekretariatan/{$kategori->nama_kategori}", $prefix, $this->do_nama, $this->do_tanggal);
                }
                $this->dispatch('alert', ['type' => 'success', 'message' => "{$kategori->nama_kategori} berhasil diperbarui!"]);
            }
        }

        $this->resetInputFields();
        $this->dispatch('closeModal');
    }

    public function deleteFileConfirm($fileId)
    {
        $this->dispatch('swal:confirm-file-delete', [
            'type'      => 'warning',
            'message'   => 'Hapus File ini?',
            'text'      => 'File tidak dapat dikembalikan!',
            'id'        => $fileId
        ]);
    }

    protected $listeners = ['deleteFile' => 'deleteFile', 'delete' => 'delete'];

    public function deleteFile($fileId)
    {
        $file = SuratFile::findOrFail($fileId);
        
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        // Refresh properties to reflect deletion in modal
        $suratId = $file->suratable_id;
        $suratType = $file->suratable_type;

        $file->delete();
        
        if ($this->isEditing) {
            $surat = $suratType::with('files')->find($suratId);
            if ($surat) {
               $this->existing_files = $surat->files;
            }
        }

        $this->dispatch('alert', ['type' => 'success', 'message' => 'File berhasil dihapus!']);
    }

    public function deleteConfirm($id)
    {
        $this->dispatch('showDeleteConfirmation', $id);
    }

    public function delete($id)
    {
        // Handle array passing from JS
        if (is_array($id)) {
            $id = $id[0];
        }

        if ($this->activeTab === 'surat-masuk') {
            $data = SuratMasuk::with('files')->findOrFail($id);
        } elseif ($this->activeTab === 'surat-keluar') {
            $data = SuratKeluar::with('files')->findOrFail($id);
        } else {
             $data = DokumenOrganisasi::with('files')->findOrFail($id);
        }

        foreach ($data->files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $data->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Data dan file berhasil dihapus!']);
    }
}
