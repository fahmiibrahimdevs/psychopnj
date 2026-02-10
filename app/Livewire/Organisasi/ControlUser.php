<?php

namespace App\Livewire\Organisasi;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $user = DB::table('users')
                ->select('active')
                ->where('id', $userId)
                ->first();
            
            if (!$user) {
                throw new \Exception("User not found");
            }
            
            // Toggle active status
            $newStatus = $user->active == '1' ? '0' : '1';
            
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'active' => $newStatus,
                    'updated_at' => now()
                ]);
            
            DB::commit();

            $statusText = $newStatus === '1' ? 'active' : 'inactive';
            $this->dispatch('swal:modal', [
                'type' => 'success',
                'message' => 'Success!',
                'text' => "User status updated to {$statusText}."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:modal', [
                'type' => 'error',
                'message' => 'Error!',
                'text' => 'Tolong hubungi Fahmi Ibrahim. Wa: 0856-9125-3593'
            ]);
        }
    }

    public function render()
    {
        $this->searchResetPage();
        $search = '%'.$this->searchTerm.'%';

        $query = DB::table('users')
            ->select(
                'users.id', 
                'users.name', 
                'users.email', 
                'users.active', 
                'users.created_at',
                DB::raw('GROUP_CONCAT(roles.display_name SEPARATOR ", ") as roles_names')
            )
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->where(function ($query) use ($search) {
                $query->where('users.name', 'LIKE', $search)
                      ->orWhere('users.email', 'LIKE', $search);
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.active', 'users.created_at')
            ->orderBy('users.id', 'DESC');

        $total = DB::table('users')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', $search)
                      ->orWhere('email', 'LIKE', $search);
            })
            ->count();
            
        $usersData = $query->skip(($this->getPage() - 1) * $this->lengthData)
            ->take($this->lengthData)
            ->get();

        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $usersData,
            $total,
            $this->lengthData,
            $this->getPage(),
            ['path' => request()->url()]
        );

        return view('livewire.organisasi.control-user', [
            'users' => $users
        ]);
    }
}
