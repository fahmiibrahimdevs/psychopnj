<?php

namespace App\Livewire\Perlengkapan;

use App\Models\PengadaanBarang as ModelsPengadaanBarang;
use App\Models\Keuangan;
use App\Models\Department;
use App\Models\Project;
use App\Models\Anggota;
use App\Models\TahunKepengurusan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Traits\WithPermissionCache;

class PengadaanBarang extends Component
{
    use WithPagination, WithPermissionCache;
    #[Title('Pengadaan Barang')]

    protected $listeners = [
        'delete',
        'approve',
        'reject',
        'rollback'
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;
    public $filterStatus = '';
    public $activeTab = ''; // dept-{id}, proj-{id}, lainnya

    public $dataId;
    public $nama_barang, $nama_toko, $jumlah, $harga, $total, $link_pembelian, $status, $catatan;
    public $prioritas = 'Sedang', $biaya_lainnya = 0, $keterangan = '-';
    public $kategori_anggaran = 'lainnya'; // dept, project, lainnya
    public $department_id, $project_id;

    protected function rules()
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'biaya_lainnya' => 'required|integer|min:0',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'keterangan' => 'nullable|string|max:255',
            'nama_toko' => 'nullable|string|max:255',
            'link_pembelian' => 'nullable|url|max:500',
            'kategori_anggaran' => 'required|in:dept,project,lainnya',
            'department_id' => 'nullable|required_if:kategori_anggaran,dept|exists:departments,id',
            'project_id' => 'nullable|required_if:kategori_anggaran,project|exists:projects,id',
        ];
    }

    protected $messages = [
        'department_id.required_if' => 'Pilih department jika kategori adalah Department.',
        'project_id.required_if' => 'Pilih project jika kategori adalah Project.',
    ];

    public function mount()
    {
        $this->cacheUserPermissions();
        $this->resetInputFields();
    }

    public function updatedJumlah()
    {
        $this->calculateTotal();
    }

    public function updatedHarga()
    {
        $this->calculateTotal();
    }

    public function updatedBiayaLainnya()
    {
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $this->total = ((int)$this->jumlah * (int)$this->harga) + (int)$this->biaya_lainnya;
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $tahunAktif = TahunKepengurusan::where('status', 'aktif')->first();

        $data = ModelsPengadaanBarang::select('pengadaan_barang.*')
            ->leftJoin('departments', 'pengadaan_barang.department_id', '=', 'departments.id')
            ->leftJoin('projects', 'pengadaan_barang.project_id', '=', 'projects.id')
            ->with(['pengusul', 'department', 'project', 'keuangan', 'user'])
            ->when($tahunAktif, function ($q) use ($tahunAktif) {
                $q->where('pengadaan_barang.tahun_kepengurusan_id', $tahunAktif->id);
            })
            ->where(function ($query) use ($search) {
                $query->where('pengadaan_barang.nama_barang', 'LIKE', $search);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('pengadaan_barang.status', $this->filterStatus);
            })
            ->when($this->activeTab, function ($query) {
                if (str_starts_with($this->activeTab, 'dept-')) {
                    $deptId = str_replace('dept-', '', $this->activeTab);
                    $query->where('pengadaan_barang.department_id', $deptId);
                } elseif (str_starts_with($this->activeTab, 'proj-')) {
                    $projId = str_replace('proj-', '', $this->activeTab);
                    $query->where('pengadaan_barang.project_id', $projId);
                } elseif ($this->activeTab === 'lainnya') {
                    $query->whereNull('pengadaan_barang.department_id')
                          ->whereNull('pengadaan_barang.project_id');
                }
            })
            ->orderByRaw("FIELD(pengadaan_barang.prioritas, 'Tinggi', 'Sedang', 'Rendah') ASC")
            ->orderBy('pengadaan_barang.created_at', 'DESC')
            ->paginate($this->lengthData);

        $departments = Department::when($tahunAktif, function ($q) use ($tahunAktif) {
            $q->where('id_tahun', $tahunAktif->id);
        })->orderBy('nama_department')->get();

        $projects = Project::when($tahunAktif, function ($q) use ($tahunAktif) {
            $q->where('id_tahun', $tahunAktif->id);
        })->orderBy('nama_project')->get();

        // Set default active tab if empty
        if (empty($this->activeTab)) {
            if ($departments->isNotEmpty()) {
                $this->activeTab = 'dept-' . $departments->first()->id;
            } elseif ($projects->isNotEmpty()) {
                $this->activeTab = 'proj-' . $projects->first()->id;
            } else {
                $this->activeTab = 'lainnya';
            }
        }

        return view('livewire.perlengkapan.pengadaan-barang', compact('data', 'departments', 'projects'));
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

        $pengusul = Anggota::where('id_tahun', $tahunAktif->id)
            ->where('id_user', auth()->id())
            ->first();

        if (!$pengusul) {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Anda tidak terdaftar sebagai anggota di tahun kepengurusan aktif.'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            ModelsPengadaanBarang::create([
                'tahun_kepengurusan_id' => $tahunAktif->id,
                'pengusul_id' => $pengusul->id,
                'department_id' => $this->kategori_anggaran === 'dept' ? $this->department_id : null,
                'project_id' => $this->kategori_anggaran === 'project' ? $this->project_id : null,
                'nama_barang' => $this->nama_barang,
                'jumlah' => $this->jumlah,
                'harga' => $this->harga,
                'biaya_lainnya' => $this->biaya_lainnya,
                'total' => (($this->jumlah * $this->harga) + $this->biaya_lainnya),
                'prioritas' => $this->prioritas,
                'keterangan' => $this->keterangan ?? '-',
                'nama_toko' => $this->nama_toko,
                'link_pembelian' => $this->link_pembelian,
                'status' => 'diusulkan',
                'id_user' => auth()->id(),
            ]);

            DB::commit();
            $this->dispatchAlert('success', 'Berhasil!', 'Usulan pengadaan barang berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsPengadaanBarang::findOrFail($id);
        
        if ($data->status !== 'diusulkan') {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Hanya usulan dengan status "Diusulkan" yang dapat diedit.'
            ]);
            return;
        }

        $this->dataId = $id;
        $this->nama_barang = $data->nama_barang;
        $this->jumlah = $data->jumlah;
        $this->harga = $data->harga;
        $this->biaya_lainnya = $data->biaya_lainnya;
        $this->total = $data->total;
        $this->prioritas = $data->prioritas;
        $this->keterangan = $data->keterangan;
        $this->nama_toko = $data->nama_toko;
        $this->link_pembelian = $data->link_pembelian;
        
        if ($data->department_id) {
            $this->kategori_anggaran = 'dept';
            $this->department_id = $data->department_id;
        } elseif ($data->project_id) {
            $this->kategori_anggaran = 'project';
            $this->project_id = $data->project_id;
        } else {
            $this->kategori_anggaran = 'lainnya';
        }
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            $pengadaan = ModelsPengadaanBarang::findOrFail($this->dataId);
            
            if ($pengadaan->status !== 'diusulkan') {
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Hanya usulan dengan status "Diusulkan" yang dapat diedit.'
                ]);
                return;
            }

            DB::beginTransaction();
            try {
                $pengadaan->update([
                    'department_id' => $this->kategori_anggaran === 'dept' ? $this->department_id : null,
                    'project_id' => $this->kategori_anggaran === 'project' ? $this->project_id : null,
                    'nama_barang' => $this->nama_barang,
                    'jumlah' => $this->jumlah,
                    'harga' => $this->harga,
                    'biaya_lainnya' => $this->biaya_lainnya,
                    'total' => (($this->jumlah * $this->harga) + $this->biaya_lainnya),
                    'prioritas' => $this->prioritas,
                    'keterangan' => $this->keterangan ?? '-',
                    'nama_toko' => $this->nama_toko,
                    'link_pembelian' => $this->link_pembelian,
                ]);

                DB::commit();
                $this->dispatchAlert('success', 'Berhasil!', 'Usulan pengadaan berhasil diperbarui.');
                $this->dataId = null;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
            }
        }
    }

    public function approveConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirmApprove', [
            'type' => 'question',
            'message' => 'Setujui Pengadaan?',
            'text' => 'Pengadaan akan disetujui dan otomatis masuk ke transaksi keuangan sebagai pengeluaran.'
        ]);
    }

    public function approve()
    {
        if ($this->dataId) {
            $pengadaan = ModelsPengadaanBarang::findOrFail($this->dataId);
            
            if ($pengadaan->status !== 'diusulkan') {
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Status pengadaan tidak valid.'
                ]);
                return;
            }

            try {
                DB::transaction(function () use ($pengadaan) {
                    // Tentukan kategori keuangan
                    $kategori = 'Pengeluaran Lainnya';
                    if ($pengadaan->department_id) {
                        $kategori = 'Departemen';
                    } elseif ($pengadaan->project_id) {
                        $kategori = 'Project';
                    }

                    // Buat transaksi keuangan
                    $keuangan = Keuangan::create([
                        'id_tahun' => $pengadaan->tahun_kepengurusan_id,
                        'tanggal' => now(),
                        'jenis' => 'pengeluaran',
                        'kategori' => $kategori,
                        'id_department' => $pengadaan->department_id,
                        'id_project' => $pengadaan->project_id,
                        'deskripsi' => "Pengadaan: {$pengadaan->nama_barang} ({$pengadaan->jumlah} pcs)",
                        'nominal' => $pengadaan->total,
                        'id_user' => auth()->id(),
                    ]);

                    // Update pengadaan
                    $pengadaan->update([
                        'status' => 'disetujui',
                        'keuangan_id' => $keuangan->id,
                    ]);
                });

                $this->dispatch('swal:modal', [
                    'type' => 'success',
                    'message' => 'Berhasil!',
                    'text' => 'Pengadaan disetujui dan transaksi keuangan telah dibuat.'
                ]);
            } catch (\Exception $e) {
                // DB::transaction automatically rolls back on exception
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
                ]);
            }
            $this->dataId = null;
        }
    }

    public function rollbackConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirmRollback', [
            'type' => 'warning',
            'message' => 'Batalkan Persetujuan?',
            'text' => 'Status akan kembali menjadi "Diusulkan" dan transaksi keuangan akan dihapus permanen.'
        ]);
    }

    public function rollback()
    {
        if ($this->dataId) {
            $pengadaan = ModelsPengadaanBarang::findOrFail($this->dataId);
            
            if (!in_array($pengadaan->status, ['disetujui', 'ditolak'])) {
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Hanya pengadaan yang disetujui atau ditolak yang dapat dibatalkan.'
                ]);
                return;
            }

            try {
                DB::transaction(function () use ($pengadaan) {
                    // Hapus transaksi keuangan jika ada
                    if ($pengadaan->keuangan_id) {
                        Keuangan::find($pengadaan->keuangan_id)?->delete();
                    }

                    // Kembalikan status
                    $pengadaan->update([
                        'status' => 'diusulkan',
                        'keuangan_id' => null,
                        'catatan' => null,
                    ]);
                });

                $this->dispatch('swal:modal', [
                    'type' => 'success',
                    'message' => 'Berhasil!',
                    'text' => 'Persetujuan dibatalkan. Status kembali menjadi Diusulkan.'
                ]);
            } catch (\Exception $e) {
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
                ]);
            }
            $this->dataId = null;
        }
    }

    public function rejectConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirmReject', [
            'type' => 'warning',
            'message' => 'Tolak Pengadaan?',
            'text' => 'Usulan pengadaan akan ditolak.'
        ]);
    }

    public function reject()
    {
        if ($this->dataId) {
            DB::beginTransaction();
            try {
                $pengadaan = ModelsPengadaanBarang::findOrFail($this->dataId);
                $pengadaan->update([
                    'status' => 'ditolak',
                    'catatan' => $this->catatan,
                ]);

                DB::commit();
                $this->dispatch('swal:modal', [
                    'type' => 'success',
                    'message' => 'Berhasil!',
                    'text' => 'Pengadaan telah ditolak.'
                ]);
                $this->dataId = null;
                $this->catatan = '';
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('swal:modal', [
                    'type' => 'error',
                    'message' => 'Error!',
                    'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
                ]);
            }
        }
    }

    public function markAsSelesai($id)
    {
        $pengadaan = ModelsPengadaanBarang::findOrFail($id);
        
        if ($pengadaan->status !== 'disetujui') {
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Hanya pengadaan yang disetujui yang bisa ditandai selesai.'
            ]);
            return;
        }

        DB::beginTransaction();
        try {
            $pengadaan->update(['status' => 'selesai']);
            DB::commit();

            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Berhasil!',
                'text' => 'Pengadaan telah ditandai selesai.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage()
            ]);
        }
    }

    public function deleteConfirm($id)
    {
        $this->dataId = $id;
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'message' => 'Apakah anda yakin?',
            'text' => 'Data pengadaan yang dihapus tidak dapat dikembalikan!'
        ]);
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            $pengadaan = ModelsPengadaanBarang::findOrFail($this->dataId);
            
            // Jika sudah ada keuangan, hapus juga
            if ($pengadaan->keuangan_id) {
                Keuangan::find($pengadaan->keuangan_id)?->delete();
            }
            
            $pengadaan->delete();
            DB::commit();
            $this->dispatchAlert('success', 'Berhasil!', 'Data pengadaan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchAlert('error', 'Error!', 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593. ' . $e->getMessage());
        }
    }

    public function updatingLengthData()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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
        $this->nama_barang = '';
        $this->jumlah = 1;
        $this->harga = 0;
        $this->biaya_lainnya = 0;
        $this->total = 0;
        $this->prioritas = 'Sedang';
        $this->keterangan = '-';
        $this->nama_toko = '';
        $this->link_pembelian = '';
        $this->status = 'diusulkan';
        $this->catatan = '';
        $this->kategori_anggaran = 'lainnya';
        $this->department_id = '';
        $this->project_id = '';
        $this->isEditing = false;
    }
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
}
