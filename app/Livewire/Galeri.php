<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Galeri - KSM Psychorobotic')]
class Galeri extends Component
{
    public function render()
    {
        return view('livewire.galeri')->extends('components.layouts.welcome');
    }
}
