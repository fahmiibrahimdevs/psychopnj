<?php

namespace App\Livewire\Perlengkapan;

use App\Models\Barang as ModelsBarang;
use App\Models\KategoriBarang;
use App\Traits\ImageCompressor;
use App\Services\GoogleSheetsService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangExport;
use App\Imports\BarangImport;
use App\Traits\WithPermissionCache;
use Illuminate\Support\Facades\DB;

class Barang extends Component
{
    use WithPagination, WithFileUploads, ImageCompressor, WithPermissionCache;
    #[Title('Data Barang')]

    protected $listeners = [
        'delete'
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    // Filter
    public $filterKategori = '';
    public $filterJenis = '';
    public $filterKondisi = '';

    public $dataId;
    public $kategori_barang_id, $kode, $nama, $jumlah, $satuan, $jenis, $kondisi, $lokasi, $foto, $keterangan;
    public $fotoPreview;
    public $imageTargetSizeKB;
    
    // Import
    public $importFile;

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'kategori_barang_id' => 'nullable|exists:kategori_barang,id',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'jenis' => 'required|in:habis_pakai,inventaris',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:5120',
            'keterangan' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->cacheUserPermissions();
        $this->resetInputFields();
        $this->imageTargetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsBarang::with(['kategori', 'user'])
            ->where(function ($query) use ($search) {
                $query->where('nama', 'LIKE', $search)
                    ->orWhere('kode', 'LIKE', $search)
                    ->orWhere('lokasi', 'LIKE', $search);
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori_barang_id', $this->filterKategori);
            })
            ->when($this->filterJenis, function ($query) {
                $query->where('jenis', $this->filterJenis);
            })
            ->when($this->filterKondisi, function ($query) {
                $query->where('kondisi', $this->filterKondisi);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($this->lengthData);

        $kategoris = KategoriBarang::orderBy('nama_kategori')->get();

        return view('livewire.perlengkapan.barang', compact('data', 'kategoris'));
    }

    public function store()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $fotoPath = null;
            if ($this->foto) {
                $fotoPath = $this->uploadFoto();
            }

            $barang = ModelsBarang::create([
                'kategori_barang_id' => $this->kategori_barang_id ?: null,
                'kode' => ModelsBarang::generateKode(),
                'nama' => $this->nama,
                'nama_barang' => $this->nama,
                'jumlah' => $this->jumlah,
                'satuan' => $this->satuan,
                'jenis' => $this->jenis,
                'kondisi' => $this->kondisi,
                'lokasi' => $this->lokasi,
                'foto' => $fotoPath,
                'keterangan' => $this->keterangan,
                'id_user' => Auth::id(),
            ]);

            // Sync to Google Sheets - DISABLED
            // try {
            //     $barang->load('kategori');
            //     $googleSheets = new GoogleSheetsService();
            //     $googleSheets->syncBarang($barang);
            // } catch (\Exception $e) {
            //     Log::error('Google Sheets Sync Error on Create: ' . $e->getMessage());
            // }

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Berhasil!', 'Barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsBarang::findOrFail($id);
        $this->dataId = $id;
        $this->kategori_barang_id = $data->kategori_barang_id;
        $this->kode = $data->kode;
        $this->nama = $data->nama;
        $this->jumlah = $data->jumlah;
        $this->satuan = $data->satuan;
        $this->jenis = $data->jenis;
        $this->kondisi = $data->kondisi;
        $this->lokasi = $data->lokasi;
        $this->fotoPreview = $data->foto;
        $this->keterangan = $data->keterangan;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            \Illuminate\Support\Facades\DB::beginTransaction();
            try {
                $barang = ModelsBarang::findOrFail($this->dataId);
                
                $fotoPath = $barang->foto;
                if ($this->foto) {
                    // Hapus foto lama
                    if ($barang->foto) {
                        Storage::disk('public')->delete($barang->foto);
                    }
                    $fotoPath = $this->uploadFoto();
                }

                $barang->update([
                    'kategori_barang_id' => $this->kategori_barang_id ?: null,
                    'nama' => $this->nama,
                    'nama_barang' => $this->nama,
                    'jumlah' => $this->jumlah,
                    'satuan' => $this->satuan,
                    'jenis' => $this->jenis,
                    'kondisi' => $this->kondisi,
                    'lokasi' => $this->lokasi,
                    'foto' => $fotoPath,
                    'keterangan' => $this->keterangan,
                ]);

                // Sync to Google Sheets - DISABLED
                // try {
                //     $barang->load('kategori');
                //     $googleSheets = new GoogleSheetsService();
                //     $googleSheets->syncBarang($barang);
                // } catch (\Exception $e) {
                //     Log::error('Google Sheets Sync Error on Update: ' . $e->getMessage());
                // }

                \Illuminate\Support\Facades\DB::commit();
                $this->dispatchAlert('success', 'Berhasil!', 'Barang berhasil diperbarui.');
                $this->dataId = null;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
                $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
            }
        }
    }

    private function uploadFoto()
    {
        $tahunNama = DB::table('tahun_kepengurusan')->where('status', 'aktif')->value('nama_tahun') ?? date('Y');
        $namaBarang = strtoupper(str_replace(' ', ' ', $this->nama));
        $randomChar = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
        $filename = $namaBarang . '_' . $randomChar . '.' . $this->foto->getClientOriginalExtension();
        $path = $this->foto->storeAs($tahunNama . '/Dept. Perlengkapan', $filename, 'public');
        
        // Compress image only if larger than target
        $fullPath = Storage::disk('public')->path($path);
        $targetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
        $currentSizeKB = filesize($fullPath) / 1024;
        
        if ($currentSizeKB > $targetSizeKB) {
            $this->compressImageToSize($fullPath, $targetSizeKB, 800);
        }
        
        return $path;
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Apakah anda yakin?',
            'text' => 'Data yang dihapus tidak dapat dikembalikan!'
        ]);
    }

    public function delete()
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $barang = ModelsBarang::findOrFail($this->dataId);
            $kode = $barang->kode;
            
            // Hapus foto jika ada
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }
            
            $barang->delete();

            // Delete from Google Sheets - DISABLED
            // try {
            //     $googleSheets = new GoogleSheetsService();
            //     $googleSheets->deleteBarang($kode);
            // } catch (\Exception $e) {
            //     Log::error('Google Sheets Delete Error: ' . $e->getMessage());
            // }

            \Illuminate\Support\Facades\DB::commit();
            $this->dispatchAlert('success', 'Berhasil!', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function removeFoto()
    {
        if ($this->dataId) {
            $barang = ModelsBarang::findOrFail($this->dataId);
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
                $barang->update(['foto' => null]);
            }
        }
        $this->foto = null;
        $this->fotoPreview = null;
    }

    public function updatingLengthData()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function updatingFilterJenis()
    {
        $this->resetPage();
    }

    public function updatingFilterKondisi()
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
            'type' => $type,
            'message' => $message,
            'text' => $text
        ]);

        $this->resetInputFields();
    }

    public function isEditingMode($mode)
    {
        $this->isEditing = $mode;
        if (!$mode) {
            $this->resetInputFields();
        }
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->dataId = null;
        $this->kategori_barang_id = '';
        $this->kode = '';
        $this->nama = '';
        $this->jumlah = 1;
        $this->satuan = 'pcs';
        $this->jenis = 'inventaris';
        $this->kondisi = 'baik';
        $this->lokasi = '';
        $this->foto = null;
        $this->fotoPreview = null;
        $this->keterangan = '';
        $this->isEditing = false;
    }

    public function downloadPdf()
    {
        $data = $this->prepareExportData();
        $pdf = Pdf::loadView('exports.barang-table', ['data' => $data])
            ->setPaper('a4', 'portrait');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Data_Barang_' . date('Y-m-d') . '.pdf');
    }

    public function downloadExcel()
    {
        $data = $this->prepareExportData();
        return Excel::download(new BarangExport($data), 'Data_Barang_' . date('Y-m-d') . '.xlsx');
    }

    private function prepareExportData()
    {
        $search = '%' . $this->searchTerm . '%';

        $query = ModelsBarang::with('kategori')
            ->where(function ($query) use ($search) {
                $query->where('nama', 'LIKE', $search)
                    ->orWhere('kode', 'LIKE', $search)
                    ->orWhere('lokasi', 'LIKE', $search);
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori_barang_id', $this->filterKategori);
            })
            ->when($this->filterJenis, function ($query) {
                $query->where('jenis', $this->filterJenis);
            })
            ->when($this->filterKondisi, function ($query) {
                $query->where('kondisi', $this->filterKondisi);
            })
            ->orderBy('jenis', 'ASC')
            ->orderBy('kategori_barang_id', 'ASC')
            ->orderBy('nama', 'ASC')
            ->get();

        return $query->map(function ($item) {
            return [
                'kode' => $item->kode,
                'nama' => $item->nama,
                'kategori' => $item->kategori ? $item->kategori->nama : '-',
                'jenis' => $item->jenis == 'inventaris' ? 'Inventaris' : 'Habis Pakai',
                'jumlah' => $item->jumlah,
                'satuan' => $item->satuan,
                'tersedia' => $item->stok_tersedia,
                'kondisi' => $item->kondisi == 'baik' ? 'Baik' : ($item->kondisi == 'rusak_ringan' ? 'Rusak Ringan' : 'Rusak Berat'),
                'lokasi' => $item->lokasi ?: '-',
                'keterangan' => $item->keterangan ?: '-',
            ];
        });
    }

    public function importExcel()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new BarangImport, $this->importFile->getRealPath());
            
            $this->importFile = null;
            
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Berhasil!',
                'text' => 'Data barang berhasil diimport.'
            ]);

            $this->dispatch('closeModal', 'importModal');

            // Sync all imported data to Google Sheets
            $this->syncAllToGoogleSheets();

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            
            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Import Gagal!',
                'text' => "Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593.\n" . implode("\n", $errorMessages)
            ]);
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. Terjadi kesalahan saat import: ' . $e->getMessage()
            ]);
        }
    }

    private function syncAllToGoogleSheets()
    {
        try {
            $googleSheets = new GoogleSheetsService();
            $barangs = ModelsBarang::with('kategori')->get();
            
            foreach ($barangs as $barang) {
                $googleSheets->syncBarang($barang);
            }
        } catch (\Exception $e) {
            Log::error('Bulk Google Sheets Sync Error: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            ['ID', 'Kode', 'Nama Barang', 'Kategori', 'Jenis', 'Jumlah', 'Satuan', 'Kondisi', 'Lokasi', 'Keterangan']
        ];

        $examples = [
            ['', '', 'Socket XT60 Yellow Male', 'KONEKTOR', 'Bahan Habis Pakai', '20', 'pcs', 'Baik', 'Rak A', ''],
            ['', '', 'Module TCRT5000', 'INPUT', 'Bahan Habis Pakai', '10', 'pcs', 'Baik', 'Rak A', ''],
            ['', '', 'Servo HiTec HS-5625MG', 'OUTPUT', 'Inventaris', '5', 'pcs', 'Baik', 'Rak A', 'Untuk robot'],
        ];

        $data = array_merge($headers, $examples);

        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $data;
            
            public function __construct($data)
            {
                $this->data = $data;
            }
            
            public function array(): array
            {
                return $this->data;
            }
        }, 'Template_Import_Barang.xlsx');
    }
}
