<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HistoryDoorLock;
use Livewire\Attributes\Title;

class DoorLockHistory extends Component
{
    use WithPagination;

    #[Title('Riwayat Door Lock')]

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = HistoryDoorLock::with('anggota')
            ->where(function($q) {
                $q->where('rfid_card', 'like', '%' . $this->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('anggota', function($query) {
                      $query->where('nama_lengkap', 'like', '%' . $this->search . '%');
                  });
            })
            ->orderBy('waktu_akses', 'desc')
            ->paginate($this->perPage);

        return view('livewire.door-lock-history', [
            'logs' => $logs
        ]);
    }
}
