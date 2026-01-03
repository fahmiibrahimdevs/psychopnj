<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

class CekSertifikat extends Component
{
    #[Title('Cek Sertifikat - KSM Psychorobotic')]

    public function render()
    {
        return view('livewire.cek-sertifikat')->extends('components.layouts.welcome');
    }
}
