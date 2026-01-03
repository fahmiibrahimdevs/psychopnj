<?php

namespace App\Livewire\Organisasi;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\ProfilOrganisasi as ModelsProfilOrganisasi;

class ProfilOrganisasi extends Component
{
    use WithPagination;
    #[Title('Profil Organisasi')]

    protected $listeners = [
        'delete'
    ];

    protected $rules = [
        'id_tahun'            => '',
        'headline'            => '',
        'deskripsi'           => '',
        'visi'                => '',
        'misi'                => '',
        'foto'                => '',
        'tagline'             => '',
    ];

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';
    public $isEditing = false;

    public $dataId;

    public $id_tahun, $headline, $deskripsi, $visi, $misi, $foto, $tagline;

    public function mount()
    {
        $this->id_tahun            = DB::table('tahun_kepengurusan')->where('status', 'aktif')->value('id');
        $this->headline            = '';
        $this->deskripsi           = '';
        $this->visi                = '';
        $this->misi                = '';
        $this->foto                = '';
        $this->tagline             = '';
    }
    
    private function initSummernote()
    {
        $this->dispatch('initSummernote');
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $data = ModelsProfilOrganisasi::select('profil_organisasi.*', 'tahun_kepengurusan.nama_tahun')
                ->join('tahun_kepengurusan', 'profil_organisasi.id_tahun', '=', 'tahun_kepengurusan.id')
                ->where(function ($query) use ($search) {
                    $query->where('nama_tahun', 'LIKE', $search);
                })
                ->orderBy('id', 'ASC')
                ->where('tahun_kepengurusan.status', 'aktif')
                ->paginate($this->lengthData);

        return view('livewire.organisasi.profil-organisasi', compact('data'));
    }

    public function store()
    {
        $this->validate();

        ModelsProfilOrganisasi::create([
            'id_tahun'            => $this->id_tahun,
            'headline'            => $this->headline,
            'deskripsi'           => $this->deskripsi,
            'visi'                => $this->visi,
            'misi'                => $this->misi,
            'foto'                => $this->foto,
            'tagline'             => $this->tagline,
        ]);

        $this->dispatchAlert('success', 'Success!', 'Data created successfully.');
    }

    public function edit($id)
    {
        $this->isEditing        = true;
        $data = ModelsProfilOrganisasi::where('id', $id)->first();
        $this->dataId           = $id;
        $this->id_tahun         = $data->id_tahun;
        $this->headline         = $data->headline;
        $this->deskripsi        = $data->deskripsi;
        $this->visi             = $data->visi;
        $this->misi             = $data->misi;
        $this->foto             = $data->foto;
        $this->tagline          = $data->tagline;

        $this->initSummernote();
    }

    public function update()
    {
        $this->validate();

        if( $this->dataId )
        {
            ModelsProfilOrganisasi::findOrFail($this->dataId)->update([
                'id_tahun'            => $this->id_tahun,
                'headline'            => $this->headline,
                'deskripsi'           => $this->deskripsi,
                'visi'                => $this->visi,
                'misi'                => $this->misi,
                'foto'                => $this->foto,
                'tagline'             => $this->tagline,
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
        ModelsProfilOrganisasi::findOrFail($this->dataId)->delete();
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
        $this->initSummernote();
    }
    
    private function resetInputFields()
    {
        $this->id_tahun            = DB::table('tahun_kepengurusan')->where('status', 'aktif')->value('id');
        $this->headline            = '';
        $this->deskripsi           = '';
        $this->visi                = '';
        $this->misi                = '';
        $this->foto                = '';
        $this->tagline             = '';
    }

    public function cancel()
    {
        $this->isEditing       = false;
        $this->resetInputFields();
    }
}
