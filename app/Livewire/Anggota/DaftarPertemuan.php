<?php

namespace App\Livewire\Anggota;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use App\Models\Pertemuan;
use App\Models\ProgramKegiatan;
use App\Models\NilaiSoalAnggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DaftarPertemuan extends Component
{
    use WithPagination;
    #[Title('Daftar Pertemuan')]

    public $filterProgram = ''; // Keeping this to avoid breaking if referenced, but logic will shift
    public $searchTerm = '';
    public $anggota;
    public $showGalleryModal = false;
    public $showFilesModal = false;
    public $selectedPertemuan;
    public $galleries = [];
    public $files = [];
    public $galleryLimit = 12;

    // New properties for program selection
    public $selectedProgramId = null;
    public $selectedProgram = null;
    
    public $lengthData = 25;

    public function mount()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }
    }

    public function selectProgram($programId)
    {
        $this->selectedProgramId = $programId;
        $this->selectedProgram = ProgramKegiatan::find($programId);
        $this->searchTerm = '';
        $this->resetPage(); // Reset pagination when switching contexts
    }

    public function backToPrograms()
    {
        $this->selectedProgramId = null;
        $this->selectedProgram = null;
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function openGallery($pertemuanId)
    {
        $this->selectedPertemuan = Pertemuan::findOrFail($pertemuanId);
        $this->galleryLimit = 12;
        $this->loadGalleries($pertemuanId);
        $this->showGalleryModal = true;
        $this->dispatch('open-modal', modal: 'galleryModal');
    }

    public function loadGalleries($pertemuanId)
    {
        $this->galleries = DB::table('pertemuan_galeri')
            ->where('id_pertemuan', $pertemuanId)
            ->orderBy('created_at', 'DESC')
            ->limit($this->galleryLimit)
            ->get();
    }

    public function loadMoreGallery()
    {
        $this->galleryLimit += 12;
        $this->loadGalleries($this->selectedPertemuan->id);
    }

    public function openFiles($pertemuanId)
    {
        $this->selectedPertemuan = Pertemuan::with('parts.files')->findOrFail($pertemuanId);
        $this->showFilesModal = true;
        $this->dispatch('open-modal', modal: 'filesModal');
    }

    public function downloadFile($filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }
        $this->dispatch('swal:modal', [
            'type' => 'error',
            'message' => 'File tidak ditemukan!',
            'text' => ''
        ]);
    }

    public function render()
    {
        if ($this->selectedProgramId) {
            // Render Pertemuan List for selected program
            $pertemuans = DB::table('pertemuan')
                ->select(
                    'pertemuan.id',
                    'pertemuan.judul_pertemuan',
                    'pertemuan.tanggal',
                    'pertemuan.deskripsi',
                    'pertemuan.thumbnail',
                    'pertemuan.pertemuan_ke',
                    'pertemuan.nama_pemateri',
                    'pertemuan.minggu_ke',
                    'program_pembelajaran.nama_program',
                    DB::raw("(SELECT COUNT(*) FROM pertemuan_galeri WHERE pertemuan_galeri.id_pertemuan = pertemuan.id) as gallery_count"),
                    DB::raw("(SELECT COUNT(*) FROM part_file INNER JOIN part_pertemuan ON part_file.id_part = part_pertemuan.id WHERE part_pertemuan.id_pertemuan = pertemuan.id) as files_count")
                )
                ->leftJoin('program_pembelajaran', 'program_pembelajaran.id', '=', 'pertemuan.id_program')
                ->where('program_pembelajaran.id_tahun', $this->anggota->id_tahun)
                ->where('pertemuan.id_program', $this->selectedProgramId)
                ->where('pertemuan.status', 'visible')
                ->when($this->searchTerm, function ($query) {
                    $query->where('pertemuan.judul_pertemuan', 'like', '%' . $this->searchTerm . '%');
                })
                ->orderBy('pertemuan.tanggal', 'DESC')
                ->paginate($this->lengthData);

            // Load parts with bank soal and nilai for each pertemuan
            $pertemuanIds = $pertemuans->pluck('id')->toArray();
            
            $parts = DB::table('part_pertemuan')
                ->select(
                    'part_pertemuan.id',
                    'part_pertemuan.id_pertemuan',
                    'part_pertemuan.urutan',
                    'part_pertemuan.nama_part',
                    'part_pertemuan.deskripsi',
                    'bank_soal_pertemuan.id as bank_soal_id',
                    'bank_soal_pertemuan.status as bank_soal_status',
                    DB::raw("(COALESCE(bank_soal_pertemuan.jml_pg, 0) + COALESCE(bank_soal_pertemuan.jml_kompleks, 0) + COALESCE(bank_soal_pertemuan.jml_jodohkan, 0) + COALESCE(bank_soal_pertemuan.jml_isian, 0) + COALESCE(bank_soal_pertemuan.jml_esai, 0)) as total_soal"),
                    'nilai_soal_anggota.status as status_ujian',
                    'nilai_soal_anggota.id as nilai_id',
                    DB::raw("(SELECT COUNT(*) FROM part_file WHERE part_file.id_part = part_pertemuan.id) as part_files_count")
                )
                ->leftJoin('bank_soal_pertemuan', 'bank_soal_pertemuan.id_part', '=', 'part_pertemuan.id')
                ->leftJoin('nilai_soal_anggota', function ($join) {
                    $join->on('nilai_soal_anggota.id_part', '=', 'part_pertemuan.id')
                        ->where('nilai_soal_anggota.id_anggota', '=', $this->anggota->id);
                })
                ->whereIn('part_pertemuan.id_pertemuan', $pertemuanIds)
                ->orderBy('part_pertemuan.urutan', 'ASC')
                ->get()
                ->groupBy('id_pertemuan');

            return view('livewire.anggota.daftar-pertemuan', [
                'pertemuans' => $pertemuans,
                'parts' => $parts,
                'isProgramView' => false
            ]);
        } else {
            // Render Program List
            $programs = DB::table('program_pembelajaran')
                ->select(
                    'id',
                    'nama_program',
                    'deskripsi',
                    'jenis_program',
                    'jumlah_pertemuan',
                    'penyelenggara',
                    'thumbnail',
                    'untuk_anggota'
                )
                ->where('id_tahun', $this->anggota->id_tahun)
                ->where('untuk_anggota', true)
                ->when($this->searchTerm, function ($query) {
                    $query->where('nama_program', 'like', '%' . $this->searchTerm . '%');
                })
                ->orderBy('nama_program')
                ->paginate($this->lengthData);

            return view('livewire.anggota.daftar-pertemuan', [
                'programs' => $programs,
                'isProgramView' => true
            ]);
        }
    }
}
