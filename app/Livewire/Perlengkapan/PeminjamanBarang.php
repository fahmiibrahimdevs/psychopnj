<?php

namespace App\Livewire\Perlengkapan;

use App\Models\PeminjamanBarang as ModelsPeminjamanBarang;
use App\Models\PeminjamanBarangDetail;
use App\Models\Barang;
use App\Models\Anggota;
use App\Models\TahunKepengurusan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class PeminjamanBarang extends Component
{
    use WithPagination;
    #[Title('Peminjaman Barang')]

    protected $listeners = [
        'delete'
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $showDetail = false;
    public $activeTab = 'dipinjam';

    public $dataId;
    public $nama_peminjam, $kontak_peminjam, $tanggal_pinjam, $tanggal_kembali, $keperluan, $status, $catatan;
    
    // Untuk multi-select barang
    public $selectedBarangs = [];
    public $barangList = [];
    
    // Detail view
    public $detailData = null;

    protected function rules()
    {
        return [
            'nama_peminjam' => 'required|string|max:255',
            'kontak_peminjam' => 'nullable|string|max:50',
            'tanggal_pinjam' => 'required|date',
            'keperluan' => 'required|string|max:255',
            'selectedBarangs' => 'required|array|min:1',
            'selectedBarangs.*.barang_id' => 'required|exists:barangs,id',
            'selectedBarangs.*.jumlah' => 'required|integer|min:1',
        ];
    }

    protected $messages = [
        'selectedBarangs.required' => 'Pilih minimal satu barang untuk dipinjam.',
        'selectedBarangs.min' => 'Pilih minimal satu barang untuk dipinjam.',
    ];

    public function mount()
    {
        $this->resetInputFields();
        $this->loadBarangList();
    }

    public function loadBarangList()
    {
        $this->barangList = Barang::where('kondisi', '!=', 'rusak_berat')
            ->orderBy('nama')
            ->get()
            ->map(function ($barang) {
                return [
                    'id' => $barang->id,
                    'kode' => $barang->kode,
                    'nama' => $barang->nama,
                    'stok_tersedia' => $barang->stok_tersedia,
                    'satuan' => $barang->satuan,
                ];
            });
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $tahunAktif = TahunKepengurusan::where('status', 'aktif')->first();

        $query = ModelsPeminjamanBarang::with(['pencatat', 'details.barang', 'user'])
            ->when($tahunAktif, function ($q) use ($tahunAktif) {
                $q->where('tahun_kepengurusan_id', $tahunAktif->id);
            })
            ->where(function ($query) use ($search) {
                $query->where('nama_peminjam', 'LIKE', $search)
                    ->orWhere('keperluan', 'LIKE', $search)
                    ->orWhere('kontak_peminjam', 'LIKE', $search);
            });

        if ($this->activeTab === 'dipinjam') {
            $query->where('status', 'dipinjam');
        } else {
            $query->where('status', 'dikembalikan');
        }

        $data = $query->orderBy('created_at', 'DESC')
            ->paginate($this->lengthData);

        return view('livewire.perlengkapan.peminjaman-barang', compact('data'));
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function addBarangRow()
    {
        $this->selectedBarangs[] = ['barang_id' => '', 'jumlah' => 1];
    }

    public function removeBarangRow($index)
    {
        unset($this->selectedBarangs[$index]);
        $this->selectedBarangs = array_values($this->selectedBarangs);
    }

    public function store()
    {
        $this->validate();

        $tahunAktif = TahunKepengurusan::where('status', 'aktif')->first();
        if (!$tahunAktif) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tidak ada tahun kepengurusan aktif.'
            ]);
            return;
        }

        $pencatat = Anggota::where('id_tahun', $tahunAktif->id)
            ->where('id_user', auth()->id())
            ->first();

        if (!$pencatat) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Anda tidak terdaftar sebagai anggota di tahun kepengurusan aktif.'
            ]);
            return;
        }

        // Validasi stok tersedia
        foreach ($this->selectedBarangs as $item) {
            $barang = Barang::find($item['barang_id']);
            if ($barang && $barang->stok_tersedia < $item['jumlah']) {
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Stok Tidak Cukup!',
                    'text' => "Stok {$barang->nama} tersedia hanya {$barang->stok_tersedia} {$barang->satuan}."
                ]);
                return;
            }
        }

        DB::transaction(function () use ($tahunAktif, $pencatat) {
            $peminjaman = ModelsPeminjamanBarang::create([
                'tahun_kepengurusan_id' => $tahunAktif->id,
                'pencatat_id' => $pencatat->id,
                'nama_peminjam' => $this->nama_peminjam,
                'kontak_peminjam' => $this->kontak_peminjam,
                'tanggal_pinjam' => $this->tanggal_pinjam,
                'keperluan' => $this->keperluan,
                'status' => 'dipinjam',
                'id_user' => auth()->id(),
            ]);

            foreach ($this->selectedBarangs as $item) {
                PeminjamanBarangDetail::create([
                    'peminjaman_barang_id' => $peminjaman->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }
        });

        $this->loadBarangList();
        $this->dispatchAlert('success', 'Berhasil!', 'Peminjaman barang berhasil dicatat.');
    }

    public function showDetailModal($id)
    {
        $this->detailData = ModelsPeminjamanBarang::with(['pencatat', 'details.barang'])->findOrFail($id);
        $this->showDetail = true;
    }

    public function closeDetailModal()
    {
        $this->showDetail = false;
        $this->detailData = null;
    }

    public function kembalikan($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirmKembali', [
            'type' => 'question',
            'message' => 'Konfirmasi Pengembalian',
            'text' => 'Apakah barang sudah dikembalikan semua?'
        ]);
    }

    public function prosesKembalikan()
    {
        if ($this->dataId) {
            ModelsPeminjamanBarang::findOrFail($this->dataId)->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now(),
                'catatan' => $this->catatan,
            ]);

            $this->loadBarangList();
            $this->dispatchAlert('success', 'Berhasil!', 'Barang telah dikembalikan.');
            $this->dataId = null;
            $this->catatan = '';
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Apakah anda yakin?',
            'text' => 'Data peminjaman yang dihapus tidak dapat dikembalikan!'
        ]);
    }

    public function delete()
    {
        ModelsPeminjamanBarang::findOrFail($this->dataId)->delete();
        $this->loadBarangList();
        $this->dispatchAlert('success', 'Berhasil!', 'Data peminjaman berhasil dihapus.');
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
        $this->nama_peminjam = '';
        $this->kontak_peminjam = '';
        $this->tanggal_pinjam = date('Y-m-d');
        $this->tanggal_kembali = null;
        $this->keperluan = '';
        $this->status = 'dipinjam';
        $this->catatan = '';
        $this->selectedBarangs = [['barang_id' => '', 'jumlah' => 1]];
        $this->isEditing = false;
    }
}
