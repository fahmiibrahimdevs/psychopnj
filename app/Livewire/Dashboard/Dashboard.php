<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    #[Title('Dashboard')]
    
    public function render()
    {
        $user = User::find(Auth::user()->id);
        
        if($user->hasRole('pengurus')) {
            return view('livewire.dashboard.dashboard-pengurus');
        } else if ($user->hasRole('anggota')) {
            return view('livewire.dashboard.dashboard-anggota');
        }

        return view('livewire.dashboard.dashboard');
    }
}
