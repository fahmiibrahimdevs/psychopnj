<?php

namespace App\Livewire\Perlengkapan;

use App\Models\KategoriBarang as ModelsKategoriBarang;
use App\Services\GoogleSheetsService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KategoriBarangExport;
use App\Imports\KategoriBarangImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KategoriBarang extends Component
{
    use WithPagination, WithFileUploads;
    #[Title('Kategori Barang')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama' => 'required|string|max:255',
    ];

    private $SYNC_TO_SHEETS = false; // Disable Google Sheets sync

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $nama;
    
    // Import
    public $importFile;

    public function mount()
    {
        $this->nama = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsKategoriBarang::select('kategori_barang.*')
            ->with('user')
            ->selectRaw('(SELECT COUNT(*) FROM barangs WHERE barangs.kategori_barang_id = kategori_barang.id) as jumlah_barang')
            ->where('nama_kategori', 'LIKE', $search)
            ->orderBy('nama_kategori', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.perlengkapan.kategori-barang', compact('data'));
    }

    public function store()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $kategori = ModelsKategoriBarang::create([
                'nama_kategori' => $this->nama,
                'status' => 'aktif',
                'id_user' => Auth::id(),
            ]);

            // Sync to Google Sheets - DISABLED
            if ($this->SYNC_TO_SHEETS) {
                try {
                    $googleSheets = new GoogleSheetsService();
                    $googleSheets->syncKategoriBarang($kategori);
                } catch (\Exception $e) {
                    // Google Sheets sync error shouldn't rollback DB transaction
                    $this->dispatch('swal:modal', [
                        'type' => 'warning',
                        'message' => 'Berhasil disimpan!',
                        'text' => 'Data tersimpan tapi gagal sync ke Google Sheets: ' . $e->getMessage()
                    ]);
                }
            }

            DB::commit();
            $this->dispatchAlert('success', 'Berhasil!', 'Kategori barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsKategoriBarang::findOrFail($id);
        $this->dataId = $id;
        $this->nama = $data->nama_kategori;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            try {
                DB::beginTransaction();

                $kategori = ModelsKategoriBarang::findOrFail($this->dataId);
                $kategori->update([
                    'nama_kategori' => $this->nama,
                ]);

                // Sync to Google Sheets - DISABLED
                if ($this->SYNC_TO_SHEETS) {
                    try {
                        $googleSheets = new GoogleSheetsService();
                        $googleSheets->syncKategoriBarang($kategori);
                    } catch (\Exception $e) {
                        // Google Sheets sync error shouldn't rollback DB transaction
                        $this->dispatch('swal:modal', [
                            'type' => 'warning',
                            'message' => 'Berhasil diperbarui!',
                            'text' => 'Data tersimpan tapi gagal sync ke Google Sheets: ' . $e->getMessage()
                        ]);
                    }
                }

                DB::commit();
                $this->dispatchAlert('success', 'Berhasil!', 'Kategori barang berhasil diperbarui.');
                $this->dataId = null;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Gagal!',
                    'text' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }
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
        try {
            DB::beginTransaction();

            $kategori = ModelsKategoriBarang::findOrFail($this->dataId);
            $kategoriId = $kategori->id;
            
            $kategori->delete();

            // Delete from Google Sheets - DISABLED
            if ($this->SYNC_TO_SHEETS) {
                try {
                    $googleSheets = new GoogleSheetsService();
                    $googleSheets->deleteKategoriBarang($kategoriId);
                } catch (\Exception $e) {
                    // Google Sheets delete error shouldn't rollback DB transaction
                    $this->dispatch('swal:modal', [
                        'type' => 'warning',
                        'message' => 'Berhasil dihapus!',
                    ]);
                }
            }

            DB::commit();
            $this->dispatchAlert('success', 'Berhasil!', 'Kategori barang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
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
            'type' => $type,
            'message' => $message,
            'text' => $text
        ]);

        $this->resetInputFields();
    }

    public function isEditingMode($mode)
    {
        $this->isEditing = $mode;
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->nama = '';
        $this->isEditing = false;
        $this->dataId = null;
    }

    public function downloadPdf()
    {
        $data = ModelsKategoriBarang::withCount('barangs')->orderBy('nama', 'ASC')->get();
        $pdf = Pdf::loadView('exports.kategori-barang-table', ['data' => $data])
            ->setPaper('a4', 'portrait');
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Kategori_Barang_' . date('Y-m-d') . '.pdf');
    }

    public function downloadExcel()
    {
        $data = ModelsKategoriBarang::orderBy('nama', 'ASC')->get();
        return Excel::download(new KategoriBarangExport($data), 'Kategori_Barang_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadTemplate()
    {
        $headers = [
            ['ID', 'Nama Kategori']
        ];

        $examples = [
            ['', 'KONEKTOR'],
            ['', 'INPUT'],
            ['', 'OUTPUT'],
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
        }, 'Template_Import_Kategori_Barang.xlsx');
    }

    public function importExcel()
    {
        $this->validate([
            'importFile' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new KategoriBarangImport, $this->importFile->getRealPath());
            
            $this->importFile = null;
            
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Berhasil!',
                'text' => 'Data kategori berhasil diimport.'
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
                'text' => implode("\n", $errorMessages)
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Terjadi kesalahan saat import: ' . $e->getMessage()
            ]);
        }
    }

    private function syncAllToGoogleSheets()
    {
        try {
            $googleSheets = new GoogleSheetsService();
            $kategoris = ModelsKategoriBarang::all();
            
            foreach ($kategoris as $kategori) {
                $googleSheets->syncKategoriBarang($kategori);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal:modal', [
                'type' => 'warning',
                'message' => 'Peringatan!',
                'text' => 'Data berhasil diimport tapi gagal sync ke Google Sheets: ' . $e->getMessage()
            ]);
        }
    }
}
