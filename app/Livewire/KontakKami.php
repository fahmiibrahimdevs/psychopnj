<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Kontak Kami - KSM Psychorobotic')]
class KontakKami extends Component
{
    public function render()
    {
        return view('livewire.kontak-kami')->extends('components.layouts.welcome');
    }
}
