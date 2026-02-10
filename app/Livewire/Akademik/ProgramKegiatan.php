<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\ProgramKegiatan as ModelsProgramKegiatan;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\ImageCompressor;

class ProgramKegiatan extends Component
{
    use WithPagination, WithFileUploads, ImageCompressor;
    #[Title('Program Kegiatan')]

    protected $listeners = [
        'delete'
    ];

    public $viewPertemuanModal = false;
    public $selectedProgramId;
    public $pertemuanList = [];

    protected $rules = [
        'id_tahun'            => 'required',
        'nama_program'        => 'required',
        'jenis_program'       => 'required',
        'deskripsi'           => 'required',
        'jumlah_pertemuan'    => 'required',
        'penyelenggara'       => 'required',
        'thumbnail'           => 'nullable|image|max:102400',
        'untuk_anggota'       => 'boolean',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $id_tahun, $nama_program, $jenis_program, $deskripsi, $jumlah_pertemuan, $penyelenggara, $thumbnail, $untuk_anggota;
    public $tahuns;
    public $oldThumbnail;
    
    // Image compression settings (loaded from .env)
    protected $thumbnailTargetSizeKB;

    public function mount()
    {
        // Load compression setting from .env
        $this->thumbnailTargetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
        
        // Get active tahun kepengurusan
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        
        $this->tahuns = TahunKepengurusan::select('id', 'nama_tahun')->orderBy('id', 'ASC')->get();
        $this->id_tahun            = $activeTahun ? $activeTahun->id : '';
        $this->nama_program        = '';
        $this->jenis_program       = '';
        $this->deskripsi           = '';
        $this->jumlah_pertemuan    = '';
        $this->penyelenggara       = '';

        $this->thumbnail           = null;
        $this->untuk_anggota       = false;
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $data = DB::table('program_pembelajaran')
                ->select(
                    'program_pembelajaran.id',
                    'program_pembelajaran.id_tahun',
                    'program_pembelajaran.nama_program',
                    'program_pembelajaran.jenis_program',
                    'program_pembelajaran.deskripsi',
                    'program_pembelajaran.jumlah_pertemuan',
                    'program_pembelajaran.penyelenggara',
                    'program_pembelajaran.thumbnail',
                    'program_pembelajaran.untuk_anggota',
                    'program_pembelajaran.status',
                    'program_pembelajaran.created_at',
                    'tahun_kepengurusan.nama_tahun'
                )
                ->leftJoin('tahun_kepengurusan', 'program_pembelajaran.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where('nama_program', 'LIKE', $search)
                ->where('tahun_kepengurusan.status', 'aktif')
                ->orderBy('program_pembelajaran.id', 'DESC')
                ->paginate($this->lengthData);

        return view('livewire.akademik.program-kegiatan', compact('data'));
    }

    public function store()
    {
        $this->validate();

        // Get active tahun kepengurusan name
        $activeTahun = DB::table('tahun_kepengurusan')
            ->select('id', 'nama_tahun')
            ->where('status', 'aktif')
            ->first();
        $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';

        $thumbnailPath = null;
        if ($this->thumbnail) {
            // Generate nama file dari nama_program (uppercase with space)
            $programFolder = strtoupper($this->nama_program);
            $fileName = strtoupper(str_replace(' ', '_', $this->nama_program)) . '_' . rand(10, 99);
            $extension = $this->thumbnail->getClientOriginalExtension();
            $thumbnailPath = $this->thumbnail->storeAs("{$tahunFolder}/{$programFolder}", $fileName . '.' . $extension, 'public');
            
            // Compress thumbnail only if larger than target
            $fullPath = storage_path('app/public/' . $thumbnailPath);
            $currentSizeKB = filesize($fullPath) / 1024;
            
            if ($currentSizeKB > $this->thumbnailTargetSizeKB) {
                $this->compressImageToSize($fullPath, $this->thumbnailTargetSizeKB, 800);
            }
            
            // Update path if PNG was converted to JPG
            if ($extension === 'png' && !file_exists($fullPath)) {
                $thumbnailPath = preg_replace('/\.png$/i', '.jpg', $thumbnailPath);
            }
        }

        DB::table('program_pembelajaran')->insert([
            'id_tahun'            => $this->id_tahun,
            'nama_program'        => $this->nama_program,
            'jenis_program'       => $this->jenis_program,
            'deskripsi'           => $this->deskripsi,
            'jumlah_pertemuan'    => $this->jumlah_pertemuan,
            'penyelenggara'       => $this->penyelenggara,
            'thumbnail'           => $thumbnailPath,

            'untuk_anggota'       => $this->untuk_anggota ? 1 : 0,
            'status'              => 'aktif',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = DB::table('program_pembelajaran')
            ->select('id', 'id_tahun', 'nama_program', 'jenis_program', 'deskripsi', 'jumlah_pertemuan', 'penyelenggara', 'thumbnail', 'untuk_anggota')
            ->where('id', $id)
            ->first();
        $this->dataId           = $id;
        $this->id_tahun         = $data->id_tahun;
        $this->nama_program     = $data->nama_program;
        $this->jenis_program    = $data->jenis_program;
        $this->deskripsi        = $data->deskripsi;
        $this->jumlah_pertemuan = $data->jumlah_pertemuan;
        $this->penyelenggara    = $data->penyelenggara;
        $this->oldThumbnail     = $data->thumbnail;

        $this->thumbnail        = null;
        $this->untuk_anggota    = (bool) $data->untuk_anggota;
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            // Get active tahun kepengurusan name
            $activeTahun = DB::table('tahun_kepengurusan')
                ->select('id', 'nama_tahun')
                ->where('status', 'aktif')
                ->first();
            $tahunFolder = $activeTahun ? $activeTahun->nama_tahun : 'default';

            $thumbnailPath = $this->oldThumbnail;
            
            if ($this->thumbnail) {
                // Hapus thumbnail lama jika ada
                if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                    Storage::disk('public')->delete($this->oldThumbnail);
                }
                // Generate nama file dari nama_program (uppercase with space)
                $programFolder = strtoupper($this->nama_program);
                $fileName = strtoupper(str_replace(' ', '_', $this->nama_program)) . '_' . rand(10, 99);
                $extension = $this->thumbnail->getClientOriginalExtension();
                $thumbnailPath = $this->thumbnail->storeAs("{$tahunFolder}/{$programFolder}", $fileName . '.' . $extension, 'public');
                
                // Compress thumbnail only if larger than target
                $fullPath = storage_path('app/public/' . $thumbnailPath);
                $currentSizeKB = filesize($fullPath) / 1024;
                
                if ($currentSizeKB > $this->thumbnailTargetSizeKB) {
                    $this->compressImageToSize($fullPath, $this->thumbnailTargetSizeKB, 800);
                }
                
                // Update path if PNG was converted to JPG
                if ($extension === 'png' && !file_exists($fullPath)) {
                    $thumbnailPath = preg_replace('/\.png$/i', '.jpg', $thumbnailPath);
                }
            }

            DB::table('program_pembelajaran')
                ->where('id', $this->dataId)
                ->update([
                    'id_tahun'            => $this->id_tahun,
                    'nama_program'        => $this->nama_program,
                    'jenis_program'       => $this->jenis_program,
                    'deskripsi'           => $this->deskripsi,
                    'jumlah_pertemuan'    => $this->jumlah_pertemuan,
                    'penyelenggara'       => $this->penyelenggara,

                    'thumbnail'           => $thumbnailPath,
                    'untuk_anggota'       => $this->untuk_anggota ? 1 : 0,
                    'updated_at'          => now(),

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
        $data = DB::table('program_pembelajaran')
            ->select('thumbnail')
            ->where('id', $this->dataId)
            ->first();
        
        // Hapus thumbnail jika ada
        if ($data && $data->thumbnail && Storage::disk('public')->exists($data->thumbnail)) {
            Storage::disk('public')->delete($data->thumbnail);
        }
        
        DB::table('program_pembelajaran')->where('id', $this->dataId)->delete();
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
    }
    
    public function viewPertemuan($programId)
    {
        $this->selectedProgramId = $programId;
        $this->pertemuanList = DB::table('pertemuan')
            ->select('id', 'id_program', 'pertemuan_ke', 'judul_pertemuan', 'nama_pemateri', 'deskripsi', 'tanggal', 'minggu_ke', 'thumbnail', 'status')
            ->where('id_program', $programId)
            ->orderBy('pertemuan_ke', 'ASC')
            ->get();
        $this->viewPertemuanModal = true;
    }
    
    public function closeViewModal()
    {
        $this->viewPertemuanModal = false;
        $this->selectedProgramId = null;
        $this->pertemuanList = [];
    }
    
    private function resetInputFields()
    {
        $this->id_tahun            = '';
        $this->nama_program        = '';
        $this->jenis_program       = '';
        $this->deskripsi           = '';
        $this->jumlah_pertemuan    = '';
        $this->penyelenggara       = '';
        $this->thumbnail           = null;

        $this->oldThumbnail        = null;
        $this->untuk_anggota       = false;
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
