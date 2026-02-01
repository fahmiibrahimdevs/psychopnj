<?php

namespace App\Livewire\Perlengkapan;

use App\Models\KategoriBarang as ModelsKategoriBarang;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class KategoriBarang extends Component
{
    use WithPagination;
    #[Title('Kategori Barang')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'nama' => 'required|string|max:255',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;
    public $nama;

    public function mount()
    {
        $this->nama = '';
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%' . $this->searchTerm . '%';

        $data = ModelsKategoriBarang::select('kategori_barang.*')
            ->selectRaw('(SELECT COUNT(*) FROM barangs WHERE barangs.kategori_barang_id = kategori_barang.id) as jumlah_barang')
            ->where('nama', 'LIKE', $search)
            ->orderBy('nama', 'ASC')
            ->paginate($this->lengthData);

        return view('livewire.perlengkapan.kategori-barang', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsKategoriBarang::create([
            'nama' => $this->nama,
        ]);

        $this->dispatchAlert('success', 'Berhasil!', 'Kategori barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->isEditing = true;
        $data = ModelsKategoriBarang::findOrFail($id);
        $this->dataId = $id;
        $this->nama = $data->nama;
    }

    public function update()
    {
        $this->validate();

        if ($this->dataId) {
            ModelsKategoriBarang::findOrFail($this->dataId)->update([
                'nama' => $this->nama,
            ]);

            $this->dispatchAlert('success', 'Berhasil!', 'Kategori barang berhasil diperbarui.');
            $this->dataId = null;
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
        ModelsKategoriBarang::findOrFail($this->dataId)->delete();
        $this->dispatchAlert('success', 'Berhasil!', 'Kategori barang berhasil dihapus.');
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
}
