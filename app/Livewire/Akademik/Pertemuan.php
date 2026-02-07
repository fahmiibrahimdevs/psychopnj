<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\ProgramKegiatan;
use App\Models\Pertemuan as ModelsPertemuan;
use App\Models\PertemuanFile;
use App\Models\PertemuanGaleri;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\ImageCompressor;

class Pertemuan extends Component
{
    use WithPagination, WithFileUploads, ImageCompressor;
    #[Title('Pertemuan')]

    protected $listeners = [
        'delete',
        'deleteFile',
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
        'files.*'           => 'nullable|file|mimes:ppt,pptx,pdf,zip,jpg,jpeg,png|max:102400',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $id_program, $nama_pemateri, $pertemuan_ke, $judul_pertemuan, $deskripsi, $tanggal, $minggu_ke, $thumbnail, $status, $jenis_presensi = [];
    public $programs;
    public $oldThumbnail;
    public $activeTab = 'info'; // Track active tab
    public $filterProgram = null; // Filter by program, null = show nothing
    
    // Multiple file upload
    public $files = [];
    public $existingFiles = [];
    
    // Gallery management
    public $galleryPertemuanId;
    public $galleryFiles = [];
    public $galleryItems = [];
    public $galleryPage = 1;
    public $galleryPerPage = 12;
    public $hasMoreGallery = true;
    public $selectedGalleryId;
    
    // Image compression settings
    protected $imageTargetSizeKB = 500;  // Target size for gallery images in KB
    protected $thumbnailTargetSizeKB = 200;  // Target size for thumbnails in KB
    protected $imageMaxWidth = 1920;  // Max width for images in pixels

    public function mount()
    {
        $this->programs = ProgramKegiatan::select('program_pembelajaran.id', 'program_pembelajaran.nama_program')
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

        $data = ModelsPertemuan::select('pertemuan.*', 'program_pembelajaran.nama_program')
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

        // Get active tahun kepengurusan name
        $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
        $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';

        // Get program name
        $program = ProgramKegiatan::findOrFail($this->id_program);
        $programFolder = strtoupper($program->nama_program);

        $thumbnailPath = null;
        if ($this->thumbnail) {
            // Generate filename from judul_pertemuan
            $fileName = 'thumbnail-' . strtolower(str_replace(' ', '-', $this->judul_pertemuan)) . '-' . rand(10, 99);
            $extension = $this->thumbnail->getClientOriginalExtension();
            $thumbnailPath = $this->thumbnail->storeAs("{$tahunFolder}/{$programFolder}/pertemuan-{$this->pertemuan_ke}/thumbnails", $fileName . '.' . $extension, 'public');
            
            // Compress thumbnail
            $fullPath = storage_path('app/public/' . $thumbnailPath);
            $this->compressImageToSize($fullPath, $this->thumbnailTargetSizeKB, 800);
            
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

        // Upload multiple files
        if (!empty($this->files)) {
            $this->uploadMultipleFiles($pertemuan->id);
        }

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
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
        
        // Load existing files
        $this->existingFiles = PertemuanFile::where('id_pertemuan', $id)->get()->toArray();
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            // Get active tahun kepengurusan name
            $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
            $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';

            // Get program name
            $program = ProgramKegiatan::findOrFail($this->id_program);
            $programFolder = strtoupper($program->nama_program);

            $thumbnailPath = $this->oldThumbnail;
            
            if ($this->thumbnail) {
                // Hapus thumbnail lama jika ada
                if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                    Storage::disk('public')->delete($this->oldThumbnail);
                }
                // Generate filename from judul_pertemuan
                $fileName = 'thumbnail-' . strtolower(str_replace(' ', '-', $this->judul_pertemuan)) . '-' . rand(10, 99);
                $extension = $this->thumbnail->getClientOriginalExtension();
                $thumbnailPath = $this->thumbnail->storeAs("{$tahunFolder}/{$programFolder}/pertemuan-{$this->pertemuan_ke}/thumbnails", $fileName . '.' . $extension, 'public');
                
                // Compress thumbnail
                $fullPath = storage_path('app/public/' . $thumbnailPath);
                $this->compressImageToSize($fullPath, $this->thumbnailTargetSizeKB, 800);
                
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

            // Upload additional files
            if (!empty($this->files)) {
                $this->uploadMultipleFiles($this->dataId);
            }

            $this->dispatchAlert('success', 'Success!', 'Data updated successfully.');
            $this->dataId = null;
        }
    }

    private function uploadMultipleFiles($pertemuanId)
    {
        // Ambil data pertemuan untuk mendapatkan pertemuan_ke
        $pertemuan = ModelsPertemuan::findOrFail($pertemuanId);
        $pertemuanKe = $pertemuan->pertemuan_ke;
        
        // Get active tahun kepengurusan name
        $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
        $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';
        
        // Get program name
        $program = ProgramKegiatan::findOrFail($pertemuan->id_program);
        $programFolder = strtoupper($program->nama_program);
        
        foreach ($this->files as $file) {
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            $originalName = $file->getClientOriginalName();
            
            // Tentukan folder berdasarkan ekstensi
            $folder = $this->determineFolder($extension);
            
            // Generate nama file yang aman
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $fileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fileName);
            $finalFileName = $fileName . '.' . $extension;
            
            // Path file yang akan disimpan dengan pertemuan_ke
            $filePath = "{$tahunFolder}/{$programFolder}/pertemuan-{$pertemuanKe}/{$folder}/{$finalFileName}";
            
            // Cek apakah file dengan nama sama sudah ada
            $existingFile = PertemuanFile::where('id_pertemuan', $pertemuanId)
                                         ->where('file_path', $filePath)
                                         ->first();
            
            if ($existingFile) {
                // Hapus file lama dari storage
                if (Storage::disk('public')->exists($existingFile->file_path)) {
                    Storage::disk('public')->delete($existingFile->file_path);
                }
                
                // Upload file baru
                $file->storeAs(
                    "{$tahunFolder}/{$programFolder}/pertemuan-{$pertemuanKe}/{$folder}",
                    $finalFileName,
                    'public'
                );
                
                // Update metadata
                $existingFile->update([
                    'ukuran_file' => $fileSize,
                ]);
            } else {
                // Upload file baru
                $file->storeAs(
                    "{$tahunFolder}/{$programFolder}/pertemuan-{$pertemuanKe}/{$folder}",
                    $finalFileName,
                    'public'
                );
                
                // Simpan metadata ke database
                PertemuanFile::create([
                    'id_pertemuan' => $pertemuanId,
                    'file_path'    => $filePath,
                    'ukuran_file'  => $fileSize,
                ]);
            }
        }
    }

    private function determineFolder($extension)
    {
        $extension = strtolower($extension);
        
        if (in_array($extension, ['ppt', 'pptx'])) {
            return 'ppt';
        } elseif ($extension === 'pdf') {
            return 'pdf';
        } elseif ($extension === 'zip') {
            return 'zip';
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return 'image';
        }
        
        return 'other';
    }

    public $fileIdToDelete = null;

    public function deleteFileConfirm($fileId)
    {
        $this->fileIdToDelete = $fileId;
        $file = PertemuanFile::findOrFail($fileId);
        $fileName = basename($file->file_path);
        
        $this->dispatch('swal:confirmFile', [
            'type'      => 'warning',  
            'message'   => 'Are you sure?', 
            'text'      => 'You are about to delete file: <strong>' . $fileName . '</strong>'
        ]);
    }

    public function deleteFile()
    {
        $file = PertemuanFile::findOrFail($this->fileIdToDelete);
        
        // Hapus file dari storage
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        
        // Hapus record dari database
        $file->delete();
        
        // Refresh existing files
        $this->existingFiles = PertemuanFile::where('id_pertemuan', $this->dataId)->get()->toArray();
        
        $this->fileIdToDelete = null;
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
        $data = ModelsPertemuan::findOrFail($this->dataId);
        
        // Get active tahun kepengurusan name
        $activeTahun = \App\Models\TahunKepengurusan::where('status', 'aktif')->first();
        $tahunFolder = $activeTahun ? $activeTahun->mulai : 'default';
        
        // Hapus thumbnail jika ada
        if ($data->thumbnail && Storage::disk('public')->exists($data->thumbnail)) {
            Storage::disk('public')->delete($data->thumbnail);
        }
        
        // Hapus semua file dari storage berdasarkan record di database
        $files = PertemuanFile::where('id_pertemuan', $this->dataId)->get();
        foreach ($files as $file) {
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        }
        
        // Hapus semua record file dari database
        PertemuanFile::where('id_pertemuan', $this->dataId)->delete();
        
        $data->delete();
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
        $this->files               = [];
        $this->existingFiles       = [];
        $this->activeTab           = 'info';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
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
            
            $tahun = $pertemuan->program->tahunKepengurusan->tahun_mulai ?? date('Y');
            $namaProgram = $pertemuan->program->nama_program ?? 'unknown';
            $pertemuanKe = $pertemuan->pertemuan_ke ?? 1;
            
            // Convert to UPPERCASE
            $namaProgram = strtoupper($namaProgram);
            
            // Build path: storage/{tahun}/{NAMA PROGRAM}/pertemuan-{pertemuan ke}/gallery
            $basePath = "{$tahun}/{$namaProgram}/pertemuan-{$pertemuanKe}/gallery";
            
            // Get current count for naming
            $existingCount = PertemuanGaleri::where('id_pertemuan', $this->galleryPertemuanId)->count();
            $counter = $existingCount + 1;
            
            foreach ($this->galleryFiles as $file) {
                $extension = $file->getClientOriginalExtension();
                $tipe = in_array($extension, ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                
                // Generate filename: IMG_001.jpg, IMG_002.mp4, etc
                $filename = sprintf('IMG_%03d.%s', $counter, $extension);
                $path = $file->storeAs($basePath, $filename, 'public');
                
                // Compress image (skip if video)
                if ($tipe === 'image') {
                    $fullPath = storage_path('app/public/' . $path);
                    $this->compressImageToSize($fullPath, $this->imageTargetSizeKB, $this->imageMaxWidth);
                    
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
                'text' => 'Failed to upload files: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteGalleryConfirm($id)
    {
        $this->selectedGalleryId = $id;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => 'You won\'t be able to revert this!'
        ]);
    }

    public function deleteGalleryItem()
    {
        try {
            $item = PertemuanGaleri::findOrFail($this->selectedGalleryId);
            
            // Delete file from storage
            if (Storage::disk('public')->exists($item->file_path)) {
                Storage::disk('public')->delete($item->file_path);
            }
            
            $item->delete();

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
                'text' => 'Failed to delete: ' . $e->getMessage()
            ]);
        }
    }
}
