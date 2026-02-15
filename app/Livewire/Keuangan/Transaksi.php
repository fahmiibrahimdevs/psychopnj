<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Keuangan as ModelsKeuangan;
use App\Models\Anggaran as ModelsAnggaran;
use App\Models\Department;
use App\Models\Project;
use App\Models\JenisAnggaran;
use App\Models\TransaksiFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KeuanganExport;
use App\Traits\ImageCompressor;
use App\Traits\WithPermissionCache;

class Transaksi extends Component
{
    use WithPagination, WithFileUploads, ImageCompressor, WithPermissionCache;
    #[Title('Transaksi Keuangan')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'tanggal'          => 'required|date',
        'jenis'            => 'required',
        'kategori'         => 'required',
        'deskripsi'        => 'required',
        'nominal'          => 'required|numeric|min:0',
        'id_department'    => 'required_if:kategori,Departemen,dept,Dept',
        'id_project'       => 'required_if:kategori,Project,project',
        'filesNota.*'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'filesReimburse.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        'filesFoto.*'      => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv|max:51200',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $filterJenis = '';
    public $filterKategori = '';

    public $dataId;

    public $tanggal, $jenis, $kategori, $id_department, $id_project, $deskripsi, $nominal, $bukti;
    public $activeTahunId;
    public $departments, $projects;
    
    // File uploads
    public $filesNota = [];
    public $filesReimburse = [];
    public $filesFoto = [];
    public $existingFiles = [];
    
    // Image compression settings (loaded from .env)
    protected $imageTargetSizeKB;

    public function mount()
    {
        $this->cacheUserPermissions();
        // Load compression setting from .env
        $this->imageTargetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
        
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;

        // Filter departments by active tahun
        // Filter departments by active tahun
        $this->departments = DB::table('departments')
            ->where('id_tahun', $this->activeTahunId)
            ->select('id', 'nama_department')
            ->orderBy('nama_department')
            ->get();
        $this->projects = DB::table('projects')
            ->where('id_tahun', $this->activeTahunId)
            ->select('id', 'nama_project')
            ->orderBy('nama_project')
            ->get();
        
        $this->resetInputFields();
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $query = DB::table('keuangan')
            ->leftJoin('departments', 'keuangan.id_department', '=', 'departments.id')
            ->leftJoin('projects', 'keuangan.id_project', '=', 'projects.id')
            ->leftJoin('users', 'keuangan.id_user', '=', 'users.id')
            ->select(
                'keuangan.*',
                'departments.nama_department',
                'projects.nama_project',
                'users.name as user_name'
            )
            ->where('keuangan.id_tahun', $this->activeTahunId)
            ->where('keuangan.deskripsi', 'LIKE', $search);
            
        if ($this->filterJenis) {
            $query->where('keuangan.jenis', $this->filterJenis);
        }
        if ($this->filterKategori) {
            $query->where('keuangan.kategori', $this->filterKategori);
        }

        $data = $query->orderBy('keuangan.tanggal', 'DESC')
            ->orderBy('keuangan.id', 'DESC')
            ->paginate($this->lengthData);

        // Calculate running totals (Optimized)
        // Fetch only necessary columns for calculation
        $allTransactions = DB::table('keuangan')
            ->where('id_tahun', $this->activeTahunId)
            ->select('id', 'jenis', 'nominal')
            ->orderBy('tanggal', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $runningTotal = 0;
        $runningTotals = [];
        foreach ($allTransactions as $tx) {
            if ($tx->jenis === 'pemasukan') {
                $runningTotal += $tx->nominal;
            } else {
                $runningTotal -= $tx->nominal;
            }
            $runningTotals[$tx->id] = $runningTotal;
        }

        // Summary Statistics (Optimized)
        // Use single query for stats if possible, or separate lightweight queries
        $stats = DB::table('keuangan')
            ->where('id_tahun', $this->activeTahunId)
            ->selectRaw("
                SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE 0 END) as total_pemasukan,
                SUM(CASE WHEN jenis = 'pengeluaran' THEN nominal ELSE 0 END) as total_pengeluaran
            ")
            ->first();

        $totalPemasukan = $stats->total_pemasukan ?? 0;
        $totalPengeluaran = $stats->total_pengeluaran ?? 0;
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Load kategori (Optimized)
        $jenisAnggaranPemasukan = DB::table('jenis_anggaran')
            ->where('nama_kategori', 'pemasukan')
            ->orderBy('nama_jenis')
            ->get();
        $jenisAnggaranPengeluaran = DB::table('jenis_anggaran')
            ->where('nama_kategori', 'pengeluaran')
            ->orderBy('nama_jenis')
            ->get();

        return view('livewire.keuangan.transaksi', compact(
            'data', 
            'runningTotals', 
            'totalPemasukan', 
            'totalPengeluaran', 
            'saldoAkhir',
            'jenisAnggaranPemasukan',
            'jenisAnggaranPengeluaran'
        ));
    }

    public function updated()
    {
        $this->dispatch('initSelect2');
    }

    public function updatedKategori()
    {
        $this->id_department = "";
        $this->id_project = "";
    }

    public function updatedJenis()
    {
        // Reset kategori, department, dan project ketika jenis berubah
        $this->kategori = '';
        $this->id_department = null;
        $this->id_project = null;
    }

    public function store()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $transaksi = ModelsKeuangan::create([
                'id_tahun'      => $this->activeTahunId,
                'tanggal'       => $this->tanggal,
                'jenis'         => $this->jenis,
                'kategori'      => $this->kategori,
                'id_department' => in_array(strtolower($this->kategori), ['departemen', 'dept']) ? $this->id_department : null,
                'id_project'    => in_array(strtolower($this->kategori), ['project']) ? $this->id_project : null,
                'deskripsi'     => $this->deskripsi,
                'nominal'       => $this->nominal,
                'bukti'         => $this->bukti,
                'id_user'       => Auth::id(),
            ]);

            // Upload files if any
            if (!empty($this->filesNota) || !empty($this->filesReimburse) || !empty($this->filesFoto)) {
                $this->uploadTransaksiFiles($transaksi->id);
            }

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsKeuangan::find($id);
        $this->dataId        = $id;
        $this->tanggal       = $data->tanggal->format('Y-m-d');
        $this->jenis         = $data->jenis;
        $this->kategori      = $data->kategori;
        $this->id_department = $data->id_department;
        $this->id_project    = $data->id_project;
        $this->deskripsi     = $data->deskripsi;
        $this->nominal       = (int) $data->nominal;
        $this->bukti         = $data->bukti;
        
        // Load existing files
        $this->existingFiles = TransaksiFile::where('id_transaksi', $id)
            ->orderBy('tipe')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                ModelsKeuangan::findOrFail($this->dataId)->update([
                    'tanggal'       => $this->tanggal,
                    'jenis'         => $this->jenis,
                    'kategori'      => $this->kategori,
                    'id_department' => in_array(strtolower($this->kategori), ['departemen', 'dept']) ? $this->id_department : null,
                    'id_project'    => in_array(strtolower($this->kategori), ['project']) ? $this->id_project : null,
                    'deskripsi'     => $this->deskripsi,
                    'nominal'       => $this->nominal,
                    'bukti'         => $this->bukti,
                ]);

                // Upload new files if any
                if (!empty($this->filesNota) || !empty($this->filesReimburse) || !empty($this->filesFoto)) {
                    $this->uploadTransaksiFiles($this->dataId);
                }

                \Illuminate\Support\Facades\DB::commit();
                $this->dispatchAlert('success', 'Success!', 'Transaksi berhasil diperbarui.');
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
        $this->dispatch('swal:confirm', [
            'type'      => 'warning',  
            'message'   => 'Yakin hapus?', 
            'text'      => 'Data yang dihapus tidak dapat dikembalikan!'
        ]);
    }

    public function delete()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            ModelsKeuangan::findOrFail($this->dataId)->delete();
            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Success!', 'Transaksi berhasil dihapus.');
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
        $this->dispatch('initSelect2');
    }
    
    private function resetInputFields()
    {
        $this->tanggal       = date('Y-m-d');
        $this->jenis         = '';
        $this->kategori      = '';
        $this->id_department = '';
        $this->id_project    = '';
        $this->deskripsi     = '';
        $this->nominal       = '';
        $this->bukti         = '';
        $this->filesNota     = [];
        $this->filesReimburse = [];
        $this->filesFoto     = [];
        $this->existingFiles = [];
    }

    public function cancel()
    {
        $this->isEditing = false;
        $this->resetInputFields();
    }

    public function getKategoriLabel($kategori)
    {
        $labels = [
            'saldo_awal' => 'Saldo Awal',
            'iuran_kas'  => 'Iuran Kas',
            'sponsor'    => 'Sponsor',
            'dept'       => 'Departemen',
            'project'    => 'Project',
            'lainnya'    => 'Lainnya',
        ];
        return $labels[$kategori] ?? $kategori;
    }

    public function downloadExcel()
    {
        $data = $this->prepareExportData();
        return Excel::download(new KeuanganExport($data), 'Buku_Kas_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadPdf()
    {
        $data = $this->prepareExportData();
        
        $pdf = Pdf::loadView('exports.keuangan-table', [
            'data' => $data
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Buku_Kas_' . date('Y-m-d') . '.pdf');
    }

    private function prepareExportData()
    {
        $transactions = ModelsKeuangan::with(['department', 'project'])
            ->where('id_tahun', $this->activeTahunId)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $runningTotal = 0;
        $data = [];

        foreach ($transactions as $tx) {
            if ($tx->jenis === 'pemasukan') {
                $runningTotal += $tx->nominal;
            } else {
                $runningTotal -= $tx->nominal;
            }

            $data[] = [
                'tanggal' => $tx->tanggal,
                'deskripsi' => $tx->deskripsi,
                'kategori' => $this->getKategoriLabel($tx->kategori),
                'department' => $tx->department ? $tx->department->nama_department : '-',
                'project' => $tx->project ? $tx->project->nama_project : '-',
                'pemasukan' => $tx->jenis === 'pemasukan' ? $tx->nominal : 0,
                'pengeluaran' => $tx->jenis === 'pengeluaran' ? $tx->nominal : 0,
                'saldo' => $runningTotal
            ];
        }

        return $data;
    }

    /**
     * Upload transaction files with security and compression
     */
    private function uploadTransaksiFiles($transaksiId)
    {
        $transaksi = ModelsKeuangan::with(['department', 'project'])->findOrFail($transaksiId);
        
        // Build base path
        $basePath = $this->buildFilePath($transaksi);
        
        // Upload nota files
        if (!empty($this->filesNota)) {
            $this->processFileUpload($this->filesNota, 'nota', $transaksiId, $basePath);
        }
        
        // Upload reimburse files
        if (!empty($this->filesReimburse)) {
            $this->processFileUpload($this->filesReimburse, 'reimburse', $transaksiId, $basePath);
        }
        
        // Upload foto files
        if (!empty($this->filesFoto)) {
            $this->processFileUpload($this->filesFoto, 'foto', $transaksiId, $basePath);
        }
    }

    /**
     * Build file path based on transaction category
     */
    private function buildFilePath($transaksi): string
    {
        // Get tahun kepengurusan
        $tahun = $transaksi->tahunKepengurusan->nama_tahun ?? date('Y');
        
        // Jenis transaksi (Pemasukan/Pengeluaran)
        $jenis = ucfirst($transaksi->jenis);
        
        // Bulan (format: 01, 02, 03, etc.)
        $bulan = $transaksi->tanggal->format('m');
        
        // Determine kategori folder name
        if ($transaksi->kategori === 'Departemen' && $transaksi->department) {
            $kategoriFolder = 'Dept. ' . $transaksi->department->nama_department;
        } elseif ($transaksi->kategori === 'Project' && $transaksi->project) {
            $kategoriFolder = 'Project ' . $transaksi->project->nama_project;
        } else {
            $kategoriFolder = $transaksi->kategori;
        }
        
        return "{$tahun}/Bendahara/{$jenis}/{$bulan}/{$kategoriFolder}";
    }

    /**
     * Process file upload with validation, compression, and security
     */
    private function processFileUpload($files, $tipe, $transaksiId, $basePath)
    {
        $transaksi = ModelsKeuangan::findOrFail($transaksiId);
        
        foreach ($files as $file) {
            // Security: Validate mime type
            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();
            
            // Generate safe filename
            $tanggal = $transaksi->tanggal->format('Y-m-d');
            $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 2);
            $safeFilename = "{$tipe}_{$tanggal}-{$random}.{$extension}";
            
            // Store file
            $filePath = $file->storeAs($basePath, $safeFilename, 'public');
            $fullPath = storage_path('app/public/' . $filePath);
            $fileSize = $file->getSize();
            
            // Compress images (not videos or PDFs)
            if (in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png'])) {
                $currentSizeKB = filesize($fullPath) / 1024;
                
                if ($currentSizeKB > $this->imageTargetSizeKB) {
                    $this->compressImageToSize($fullPath, $this->imageTargetSizeKB);
                }
                
                // Update file size after compression
                if (file_exists($fullPath)) {
                    $fileSize = filesize($fullPath);
                }
                
                // Update path if PNG was converted to JPG
                if ($extension === 'png' && !file_exists($fullPath)) {
                    $filePath = preg_replace('/\.png$/i', '.jpg', $filePath);
                    $safeFilename = preg_replace('/\.png$/i', '.jpg', $safeFilename);
                }
            }
            
            // Save to database
            TransaksiFile::create([
                'id_transaksi' => $transaksiId,
                'tipe' => $tipe,
                'file_path' => $filePath,
                'original_name' => $originalName,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
            ]);
        }
    }

    /**
     * Delete file from storage and database
     */
    public function deleteFile($fileId)
    {
        try {
            $file = TransaksiFile::findOrFail($fileId);
            
            // Delete from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // Delete from database
            $file->delete();
            
            // Refresh existing files
            if ($this->dataId) {
                $this->existingFiles = TransaksiFile::where('id_transaksi', $this->dataId)
                    ->orderBy('tipe')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->toArray();
            }
            
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => 'File berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. Gagal menghapus file: ' . $e->getMessage()
            ]);
        }
    }
}
