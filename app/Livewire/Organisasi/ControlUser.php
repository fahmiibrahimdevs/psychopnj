<?php

namespace App\Livewire\Organisasi;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class ControlUser extends Component
{
    use WithPagination;
    #[Title('Control User')]

    public $lengthData = 25;
    public $searchTerm;
    public $previousSearchTerm = '';

    public function searchResetPage()
    {
        if ($this->searchTerm !== $this->previousSearchTerm) {
            $this->resetPage();
        }

        $this->previousSearchTerm = $this->searchTerm;
    }

    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);
        
        // Toggle active status
        $newStatus = $user->active === '1' ? '0' : '1';
        $user->update(['active' => $newStatus]);
        
        $statusText = $newStatus === '1' ? 'active' : 'inactive';
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Success!',
            'message' => "User status updated to {$statusText}."
        ]);
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $users = User::select('users.id', 'users.name', 'users.email', 'users.active', 'users.created_at')
            ->where(function ($query) use ($search) {
                $query->where('users.name', 'LIKE', $search)
                    ->orWhere('users.email', 'LIKE', $search);
            })
            ->orderBy('users.id', 'DESC')
            ->paginate($this->lengthData);

        return view('livewire.organisasi.control-user', [
            'users' => $users
        ]);
    }
}
