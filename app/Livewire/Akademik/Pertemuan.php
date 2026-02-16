<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\ProgramKegiatan;
use App\Models\Pertemuan as ModelsPertemuan;
use App\Models\PartPertemuan;
use App\Models\PartFile;
use App\Models\PertemuanGaleri;
use App\Models\BankSoalPertemuan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\ImageCompressor;
use App\Traits\WithPermissionCache;

class Pertemuan extends Component
{
    use WithPagination, WithFileUploads, ImageCompressor, WithPermissionCache;
    #[Title('Pertemuan')]

    protected $listeners = [
        'delete',
        'deletePart',
        'deletePartFile',
        'deleteGalleryItem'
    ];

    protected $rules = [
        'id_program'        => 'required',
        'nama_pemateri'     => 'required',
        'pertemuan_ke'      => 'required',
        'judul_pertemuan'   => 'required',
        'deskripsi'         => 'required',
        'tanggal'           => 'required',
        'minggu_ke'         => 'required',
        'thumbnail'         => 'nullable|image|max:2048',
        'status'            => 'required',
        'jenis_presensi'    => 'required|array',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $id_program, $nama_pemateri, $pertemuan_ke, $judul_pertemuan, $deskripsi, $tanggal, $minggu_ke, $thumbnail, $status, $jenis_presensi = [];
    public $programs;
    public $oldThumbnail;
    
    // Part management
    public $parts = [];
    public $partId, $nama_part, $deskripsi_part;
    public $isEditingPart = false;
    public $partFiles = [];
    public $partFilesToUpload = [];
    public $fileIdToDelete; // Add this for file deletion
    
    // Bank Soal configuration for part
    public $jml_pg = 0, $jml_kompleks = 0, $jml_jodohkan = 0, $jml_isian = 0, $jml_esai = 0;
    public $bobot_pg = 0, $bobot_kompleks = 0, $bobot_jodohkan = 0, $bobot_isian = 0, $bobot_esai = 0;
    public $opsi = '4';
    
    public $activeTab = 'info'; // Track active tab
    public $filterProgram = null; // Filter by program, null = show nothing
    
    // Gallery management
    public $galleryPertemuanId;
    public $galleryFiles = [];
    public $galleryItems = [];
    public $galleryPage = 1;
    public $galleryPerPage = 12;
    public $hasMoreGallery = true;
    public $selectedGalleryId;
    
    // Image compression settings (loaded from .env)
    protected $imageTargetSizeKB;

    public function mount()
    {
        // Cache user permissions to avoid N+1 queries
        $this->cacheUserPermissions();
        
        // Load compression setting from .env
        $this->imageTargetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
        
        $this->programs = DB::table('program_pembelajaran')
                            ->select('program_pembelajaran.id', 'program_pembelajaran.nama_program')
                            ->leftJoin('tahun_kepengurusan', 'program_pembelajaran.id_tahun', '=', 'tahun_kepengurusan.id')
                            ->where('tahun_kepengurusan.status', 'aktif')
                            ->orderBy('program_pembelajaran.id', 'DESC')
                            ->get();
        $this->id_program          = '';
        $this->nama_pemateri       = '';
        $this->pertemuan_ke        = '';
        $this->judul_pertemuan     = '';
        $this->deskripsi           = '';
        $this->tanggal             = '';
        $this->minggu_ke           = '';
        $this->thumbnail           = null;
        $this->status              = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        // If no program selected, return empty paginated data
        if ($this->filterProgram === null || $this->filterProgram === '') {
            $data = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->lengthData);
            return view('livewire.akademik.pertemuan', compact('data'));
        }

        $data = DB::table('pertemuan')
                ->select('pertemuan.*', 'program_pembelajaran.nama_program')
                ->leftJoin('program_pembelajaran', 'pertemuan.id_program', '=', 'program_pembelajaran.id')
                ->leftJoin('tahun_kepengurusan', 'program_pembelajaran.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->where('pertemuan.id_program', $this->filterProgram)
                ->where(function ($q) use ($search) {
                    $q->where('nama_program', 'LIKE', $search);
                    $q->orWhere('judul_pertemuan', 'LIKE', $search);
                    $q->orWhere('nama_pemateri', 'LIKE', $search);
                })
                ->orderBy('pertemuan.id', 'DESC')
                ->paginate($this->lengthData);

        return view('livewire.akademik.pertemuan', compact('data'));
    }

    public function store()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Get active tahun kepengurusan name
            $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
            $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';

            // Get program name
            $program = ProgramKegiatan::findOrFail($this->id_program);
            $programFolder = $program->nama_program;

            $thumbnailPath = null;
            if ($this->thumbnail) {
                // Generate filename from judul_pertemuan
                $randomChar = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
                $fileName = 'Thumbnail - ' . $this->judul_pertemuan . '_' . $randomChar;
                $extension = $this->thumbnail->getClientOriginalExtension();
                $thumbnailPath = $this->thumbnail->storeAs("{$tahunFolder}/Dept. PRE/{$programFolder}/Pertemuan {$this->pertemuan_ke}", $fileName . '.' . $extension, 'public');
                
                // Compress thumbnail only if larger than target
                $fullPath = storage_path('app/public/' . $thumbnailPath);
                $currentSizeKB = filesize($fullPath) / 1024;
                
                if ($currentSizeKB > $this->imageTargetSizeKB) {
                    $this->compressImageToSize($fullPath, $this->imageTargetSizeKB, 800);
                }
                
                // Update path if PNG was converted to JPG
                if ($extension === 'png' && !file_exists($fullPath)) {
                    $thumbnailPath = preg_replace('/\.png$/i', '.jpg', $thumbnailPath);
                }
            }

            $pertemuan = ModelsPertemuan::create([
                'id_program'        => $this->id_program,
                'nama_pemateri'     => $this->nama_pemateri,
                'pertemuan_ke'      => $this->pertemuan_ke,
                'judul_pertemuan'   => $this->judul_pertemuan,
                'deskripsi'         => $this->deskripsi,
                'tanggal'           => $this->tanggal,
                'minggu_ke'         => $this->minggu_ke,
                'thumbnail'         => $thumbnailPath,
                'status'            => $this->status,
                'jenis_presensi'    => implode(',', $this->jenis_presensi),
            ]);

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsPertemuan::where('id', $id)->first();
        $this->dataId           = $id;
        $this->id_program       = $data->id_program;
        $this->nama_pemateri    = $data->nama_pemateri;
        $this->pertemuan_ke     = $data->pertemuan_ke;
        $this->judul_pertemuan  = $data->judul_pertemuan;
        $this->deskripsi        = $data->deskripsi;
        $this->tanggal          = $data->tanggal;
        $this->minggu_ke        = $data->minggu_ke;
        $this->oldThumbnail     = $data->thumbnail;
        $this->status           = $data->status;
        $this->jenis_presensi   = $data->jenis_presensi ? explode(',', $data->jenis_presensi) : ['pengurus', 'anggota'];
        $this->thumbnail        = null;
        
        // Load parts dengan files
        $this->parts = PartPertemuan::where('id_pertemuan', $id)
            ->with('files', 'bankSoal')
            ->orderBy('urutan')
            ->get()
            ->toArray();
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                // Get active tahun kepengurusan name
                $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
                $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';

                // Get program name
                $program = ProgramKegiatan::findOrFail($this->id_program);
                $programFolder = $program->nama_program;

                $thumbnailPath = $this->oldThumbnail;
                
                if ($this->thumbnail) {
                    // Hapus thumbnail lama jika ada
                    if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                        Storage::disk('public')->delete($this->oldThumbnail);
                    }
                    // Generate filename from judul_pertemuan
                    $randomChar = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
                    $fileName = 'Thumbnail - ' . $this->judul_pertemuan . '_' . $randomChar;
                    $extension = $this->thumbnail->getClientOriginalExtension();
                    $thumbnailPath = $this->thumbnail->storeAs("{$tahunFolder}/Dept. PRE/{$programFolder}/Pertemuan {$this->pertemuan_ke}", $fileName . '.' . $extension, 'public');
                    
                    // Compress thumbnail only if larger than target
                    $fullPath = storage_path('app/public/' . $thumbnailPath);
                    $currentSizeKB = filesize($fullPath) / 1024;
                    
                    if ($currentSizeKB > $this->imageTargetSizeKB) {
                        $this->compressImageToSize($fullPath, $this->imageTargetSizeKB, 800);
                    }
                    
                    // Update path if PNG was converted to JPG
                    if ($extension === 'png' && !file_exists($fullPath)) {
                        $thumbnailPath = preg_replace('/\.png$/i', '.jpg', $thumbnailPath);
                    }
                }

                ModelsPertemuan::findOrFail($this->dataId)->update([
                    'id_program'        => $this->id_program,
                    'nama_pemateri'     => $this->nama_pemateri,
                    'pertemuan_ke'      => $this->pertemuan_ke,
                    'judul_pertemuan'   => $this->judul_pertemuan,
                    'deskripsi'         => $this->deskripsi,
                    'tanggal'           => $this->tanggal,
                    'minggu_ke'         => $this->minggu_ke,
                    'thumbnail'         => $thumbnailPath,
                    'status'            => $this->status,
                    'jenis_presensi'    => implode(',', $this->jenis_presensi),
                ]);

                \Illuminate\Support\Facades\DB::commit();
                $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
                $this->dataId = null;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
            }
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirmPertemuan', [
            'type'      => 'warning',  
            'message'   => 'Are you sure?', 
            'text'      => 'If you delete the data, it cannot be restored!'
        ]);
    }

    public function delete()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $data = ModelsPertemuan::findOrFail($this->dataId);
            
            // Get active tahun kepengurusan name
            $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
            $tahunFolder = $activeTahun ? $activeTahun->mulai : 'default';
            
            // Hapus thumbnail jika ada
            if ($data->thumbnail && Storage::disk('public')->exists($data->thumbnail)) {
                Storage::disk('public')->delete($data->thumbnail);
            }
            
            // Hapus semua parts (cascade akan handle files dan bank soal)
            $parts = PartPertemuan::where('id_pertemuan', $this->dataId)->get();
            foreach ($parts as $part) {
                // Hapus files dari storage
                foreach ($part->files as $file) {
                    if (Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                }
            }
            
            // Hapus semua file galeri dari storage (DB record akan terhapus via cascade)
            $galeriFiles = PertemuanGaleri::where('id_pertemuan', $this->dataId)->get();
            foreach ($galeriFiles as $galeri) {
                if ($galeri->file_path && Storage::disk('public')->exists($galeri->file_path)) {
                    Storage::disk('public')->delete($galeri->file_path);
                }
            }
            
            $data->delete();

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Data deleted successfully.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
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
        $this->activeTab = 'info';
    }
    
    private function resetInputFields()
    {
        $this->id_program          = '';
        $this->nama_pemateri       = '';
        $this->pertemuan_ke        = '';
        $this->judul_pertemuan     = '';
        $this->deskripsi           = '';
        $this->tanggal             = '';
        $this->minggu_ke           = '';
        $this->thumbnail           = null;
        $this->status              = '';
        $this->oldThumbnail        = null;
        $this->parts               = [];
        $this->partId              = null;
        $this->nama_part           = '';
        $this->deskripsi_part      = '';
        $this->isEditingPart       = false;
        $this->partFiles           = [];
        $this->partFilesToUpload   = [];
        $this->activeTab           = 'info';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }

    public function cancelEditPart()
    {
        $this->partId = null;
        $this->nama_part = '';
        $this->deskripsi_part = '';
        $this->isEditingPart = false;
        $this->resetBankSoalFields();
    }
    
    private function resetBankSoalFields()
    {
        $this->jml_pg = 0;
        $this->jml_kompleks = 0;
        $this->jml_jodohkan = 0;
        $this->jml_isian = 0;
        $this->jml_esai = 0;
        $this->bobot_pg = 0;
        $this->bobot_kompleks = 0;
        $this->bobot_jodohkan = 0;
        $this->bobot_isian = 0;
        $this->bobot_esai = 0;
        $this->opsi = '4';
    }

    // Gallery Management Methods
    public function openGalleryModal($pertemuanId)
    {
        $this->galleryPertemuanId = $pertemuanId;
        $this->galleryPage = 1;
        $this->galleryItems = [];
        $this->hasMoreGallery = true;
        $this->loadGalleryItems();
        $this->dispatch('open-gallery-modal');
    }

    public function loadGalleryItems()
    {
        $items = PertemuanGaleri::where('id_pertemuan', $this->galleryPertemuanId)
            ->orderBy('id', 'DESC')
            ->skip(($this->galleryPage - 1) * $this->galleryPerPage)
            ->take($this->galleryPerPage)
            ->get()
            ->toArray();

        if (count($items) < $this->galleryPerPage) {
            $this->hasMoreGallery = false;
        }

        $this->galleryItems = array_merge($this->galleryItems, $items);
        $this->galleryPage++;
    }

    public function uploadGalleryFiles()
    {
        $this->validate([
            'galleryFiles.*' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480',
        ]);

        DB::beginTransaction();
        try {
            // Get pertemuan data to build path
            $pertemuan = ModelsPertemuan::with('program.tahunKepengurusan')
                ->findOrFail($this->galleryPertemuanId);
            
            $tahun = $pertemuan->program->tahunKepengurusan->nama_tahun ?? date('Y');
            $namaProgram = $pertemuan->program->nama_program ?? 'unknown';
            $pertemuanKe = $pertemuan->pertemuan_ke ?? 1;
            
            // Build path: storage/{tahun}/Dept. PRE/{nama program}/Pertemuan {pertemuan ke}/GALLERY
            $basePath = "{$tahun}/Dept. PRE/{$namaProgram}/Pertemuan {$pertemuanKe}/GALLERY";
            
            // Get current count for naming
            $existingCount = PertemuanGaleri::where('id_pertemuan', $this->galleryPertemuanId)->count();
            $counter = $existingCount + 1;
            
            foreach ($this->galleryFiles as $file) {
                $extension = $file->getClientOriginalExtension();
                $tipe = in_array($extension, ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                
                // Generate filename: IMG_001.jpg, IMG_002.mp4, etc
                $filename = sprintf('IMG_%03d.%s', $counter, $extension);
                $path = $file->storeAs($basePath, $filename, 'public');
                
                // Compress image (skip if video or already small)
                if ($tipe === 'image') {
                    $fullPath = storage_path('app/public/' . $path);
                    $currentSizeKB = filesize($fullPath) / 1024;
                    
                    if ($currentSizeKB > $this->imageTargetSizeKB) {
                        $this->compressImageToSize($fullPath, $this->imageTargetSizeKB);
                    }
                    
                    // Update path if PNG was converted to JPG
                    if ($extension === 'png' && !file_exists($fullPath)) {
                        $path = preg_replace('/\.png$/i', '.jpg', $path);
                        $filename = preg_replace('/\.png$/i', '.jpg', $filename);
                    }
                }

                PertemuanGaleri::create([
                    'id_pertemuan' => $this->galleryPertemuanId,
                    'tipe' => $tipe,
                    'file_path' => $path,
                    'caption' => null,
                ]);
                
                $counter++;
            }

            DB::commit();
            
            // Reload gallery
            $this->galleryPage = 1;
            $this->galleryItems = [];
            $this->hasMoreGallery = true;
            $this->galleryFiles = [];
            $this->loadGalleryItems();

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => 'Files uploaded and compressed to ~500KB!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. Failed to upload files: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteGalleryConfirm($id)
    {
        $this->selectedGalleryId = $id;
        $this->dispatch('swal:confirmGallery', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => 'You won\'t be able to revert this!'
        ]);
    }

    public function deleteGalleryItem()
    {
        try {
            $item = PertemuanGaleri::find($this->selectedGalleryId);
            
            if ($item) {
                // Delete file from storage
                if (Storage::disk('public')->exists($item->file_path)) {
                    Storage::disk('public')->delete($item->file_path);
                }
                
                $item->delete();
            }

            // Remove from array
            $this->galleryItems = array_filter($this->galleryItems, function($item) {
                return $item['id'] != $this->selectedGalleryId;
            });

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Deleted!',
                'text' => 'Gallery item has been deleted.'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. Failed to delete: ' . $e->getMessage()
            ]);
        }
    }

    public function redirectToSoal($pertemuanId)
    {
        return redirect()->route('pertemuan.soal', ['pertemuanId' => $pertemuanId]);
    }
    
    // ========== PART MANAGEMENT METHODS ==========
    
    public function addPart()
    {
        $this->validate([
            'nama_part' => 'required|string|max:255',
        ]);
        
        if (!$this->dataId) {
            $this->dispatchAlert('error', 'Error!', 'Simpan pertemuan terlebih dahulu sebelum menambah part.');
            return;
        }
        
        DB::beginTransaction();
        try {
            // Get max urutan
            $maxUrutan = PartPertemuan::where('id_pertemuan', $this->dataId)->max('urutan') ?? 0;
            
            $part = PartPertemuan::create([
                'id_pertemuan' => $this->dataId,
                'urutan' => $maxUrutan + 1,
                'nama_part' => $this->nama_part,
                'deskripsi' => $this->deskripsi_part,
            ]);
            
            // Create bank soal if at least one jumlah soal is set
            if ($this->jml_pg > 0 || $this->jml_kompleks > 0 || $this->jml_jodohkan > 0 || $this->jml_isian > 0 || $this->jml_esai > 0) {
                $pertemuan = ModelsPertemuan::with('program')->findOrFail($this->dataId);
                
                BankSoalPertemuan::create([
                    'id_part' => $part->id,
                    'id_tahun' => $pertemuan->program->id_tahun,
                    'jml_pg' => $this->jml_pg,
                    'jml_kompleks' => $this->jml_kompleks,
                    'jml_jodohkan' => $this->jml_jodohkan,
                    'jml_isian' => $this->jml_isian,
                    'jml_esai' => $this->jml_esai,
                    'tampil_pg' => 0,
                    'tampil_kompleks' => 0,
                    'tampil_jodohkan' => 0,
                    'tampil_isian' => 0,
                    'tampil_esai' => 0,
                    'bobot_pg' => $this->bobot_pg,
                    'bobot_kompleks' => $this->bobot_kompleks,
                    'bobot_jodohkan' => $this->bobot_jodohkan,
                    'bobot_isian' => $this->bobot_isian,
                    'bobot_esai' => $this->bobot_esai,
                    'opsi' => $this->opsi,
                    'status' => '0', // Inactive by default
                ]);
            }
            
            // Reload parts
            $this->parts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->with('files', 'bankSoal')
                ->orderBy('urutan')
                ->get()
                ->toArray();
            
            // Reset form
            $this->nama_part = '';
            $this->deskripsi_part = '';
            $this->resetBankSoalFields();
            
            DB::commit();
            $this->dispatch('swal:partAdded');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Gagal menambah part: ' . $e->getMessage()
            ]);
        }
    }
    
    public function editPart($partId)
    {
        $part = PartPertemuan::with('bankSoal')->findOrFail($partId);
        $this->partId = $partId;
        $this->nama_part = $part->nama_part;
        $this->deskripsi_part = $part->deskripsi;
        
        // Load bank soal data if exists
        if ($part->bankSoal) {
            $bs = $part->bankSoal;
            $this->jml_pg = $bs->jml_pg;
            $this->jml_kompleks = $bs->jml_kompleks;
            $this->jml_jodohkan = $bs->jml_jodohkan;
            $this->jml_isian = $bs->jml_isian;
            $this->jml_esai = $bs->jml_esai;
            $this->bobot_pg = $bs->bobot_pg;
            $this->bobot_kompleks = $bs->bobot_kompleks;
            $this->bobot_jodohkan = $bs->bobot_jodohkan;
            $this->bobot_isian = $bs->bobot_isian;
            $this->bobot_esai = $bs->bobot_esai;
            $this->opsi = $bs->opsi;
        } else {
            $this->resetBankSoalFields();
        }
        
        $this->isEditingPart = true;
    }
    
    public function updatePart()
    {
        $this->validate([
            'nama_part' => 'required|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $part = PartPertemuan::with('bankSoal')->findOrFail($this->partId);
            $part->update([
                'nama_part' => $this->nama_part,
                'deskripsi' => $this->deskripsi_part,
            ]);
            
            // Update or create bank soal
            if ($this->jml_pg > 0 || $this->jml_kompleks > 0 || $this->jml_jodohkan > 0 || $this->jml_isian > 0 || $this->jml_esai > 0) {
                $pertemuan = ModelsPertemuan::with('program')->findOrFail($this->dataId);
                
                $bankSoalData = [
                    'id_tahun' => $pertemuan->program->id_tahun,
                    'jml_pg' => $this->jml_pg,
                    'jml_kompleks' => $this->jml_kompleks,
                    'jml_jodohkan' => $this->jml_jodohkan,
                    'jml_isian' => $this->jml_isian,
                    'jml_esai' => $this->jml_esai,
                    'tampil_pg' => 0,
                    'tampil_kompleks' => 0,
                    'tampil_jodohkan' => 0,
                    'tampil_isian' => 0,
                    'tampil_esai' => 0,
                    'bobot_pg' => $this->bobot_pg,
                    'bobot_kompleks' => $this->bobot_kompleks,
                    'bobot_jodohkan' => $this->bobot_jodohkan,
                    'bobot_isian' => $this->bobot_isian,
                    'bobot_esai' => $this->bobot_esai,
                    'opsi' => $this->opsi,
                ];
                
                if ($part->bankSoal) {
                    $part->bankSoal->update($bankSoalData);
                } else {
                    BankSoalPertemuan::create(array_merge($bankSoalData, [
                        'id_part' => $part->id,
                        'status' => '0',
                    ]));
                }
            }
            
            // Reload parts
            $this->parts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->with('files', 'bankSoal')
                ->orderBy('urutan')
                ->get()
                ->toArray();
            
            $this->partId = null;
            $this->nama_part = '';
            $this->deskripsi_part = '';
            $this->isEditingPart = false;
            
            DB::commit();
            $this->dispatch('swal:partUpdated');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Gagal update part: ' . $e->getMessage()
            ]);
        }
    }
    
    public function deletePartConfirm($partId)
    {
        $this->partId = $partId;
        $part = PartPertemuan::findOrFail($partId);
        
        $this->dispatch('swal:confirmPart', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => 'Menghapus part "' . $part->nama_part . '" akan menghapus semua bank soal dan file di dalamnya!'
        ]);
    }
    
    public function deletePart()
    {
        DB::beginTransaction();
        try {
            $part = PartPertemuan::findOrFail($this->partId);
            
            // Hapus semua files dari storage
            foreach ($part->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }
            
            // Delete part (cascade akan handle bank soal dan files di DB)
            $part->delete();
            
            // Reorder remaining parts
            $remainingParts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->orderBy('urutan')
                ->get();
            
            $urutan = 1;
            foreach ($remainingParts as $p) {
                $p->update(['urutan' => $urutan++]);
            }
            
            // Reload parts
            $this->parts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->with('files', 'bankSoal')
                ->orderBy('urutan')
                ->get()
                ->toArray();
            
            $this->partId = null;
            
            DB::commit();
            $this->dispatch('swal:partDeleted');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Gagal menghapus part: ' . $e->getMessage()
            ]);
        }
    }
    
    public function reorderParts($orderedIds)
    {
        DB::beginTransaction();
        try {
            $urutan = 1;
            foreach ($orderedIds as $id) {
                PartPertemuan::where('id', $id)->update(['urutan' => $urutan++]);
            }
            
            // Reload parts
            $this->parts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->with('files', 'bankSoal')
                ->orderBy('urutan')
                ->get()
                ->toArray();
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Gagal reorder parts: ' . $e->getMessage()
            ]);
        }
    }
    
    public function uploadPartFiles($partId)
    {
        $this->validate([
            'partFilesToUpload.*' => 'required|file|mimes:ppt,pptx,pdf,zip,jpg,jpeg,png|max:102400',
        ]);
        
        DB::beginTransaction();
        try {
            $part = PartPertemuan::with('pertemuan.program.tahunKepengurusan')->findOrFail($partId);
            $pertemuan = $part->pertemuan;
            
            // Get path info
            $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
            $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';
            $program = $pertemuan->program;
            $programFolder = $program->nama_program;
            $pertemuanKe = $pertemuan->pertemuan_ke;
            $partUrutan = $part->urutan;
            
            // Path: {tahun}/Dept. PRE/{program}/Pertemuan {ke}/Part {urutan}/
            $basePath = "{$tahunFolder}/Dept. PRE/{$programFolder}/Pertemuan {$pertemuanKe}/Part {$partUrutan}";
            
            foreach ($this->partFilesToUpload as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                
                // Generate safe filename
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $randomChar = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
                $finalFileName = $fileName . '_' . $randomChar . '.' . $extension;
                
                // Upload file
                $filePath = $file->storeAs($basePath, $finalFileName, 'public');
                
                // Save to database
                PartFile::create([
                    'id_part' => $partId,
                    'file_path' => $filePath,
                    'original_name' => $originalName,
                    'ukuran_file' => $fileSize,
                    'mime_type' => $mimeType,
                ]);
            }
            
            // Reload parts
            $this->parts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->with('files', 'bankSoal')
                ->orderBy('urutan')
                ->get()
                ->toArray();
            
            $this->partFilesToUpload = [];
            
            DB::commit();
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => 'Files berhasil diupload.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Gagal upload files: ' . $e->getMessage()
            ]);
        }
    }
    
    public function deletePartFileConfirm($fileId)
    {
        $this->fileIdToDelete = $fileId;
        $file = PartFile::findOrFail($fileId);
        
        $this->dispatch('swal:confirmPartFile', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => 'Menghapus file: ' . $file->original_name
        ]);
    }
    
    public function deletePartFile()
    {
        DB::beginTransaction();
        try {
            $file = PartFile::findOrFail($this->fileIdToDelete);
            
            // Delete from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            $file->delete();
            
            // Reload parts
            $this->parts = PartPertemuan::where('id_pertemuan', $this->dataId)
                ->with('files', 'bankSoal')
                ->orderBy('urutan')
                ->get()
                ->toArray();
            
            $this->fileIdToDelete = null;
            
            DB::commit();
            $this->dispatch('swal:fileDeleted');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Gagal menghapus file: ' . $e->getMessage()
            ]);
        }
    }
}
