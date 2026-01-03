<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use App\Models\ArticlePost;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class Welcome extends Component
{
    #[Title('Psychorobotic PNJ 2026')]

    public $total_projects;

    public function mount()
    {
        // $this->total_projects = DB::table('projects')->count() ?? "0";
    }

    public function render()
    {
        return view('livewire.welcome')->extends('components.layouts.welcome');
    }
}
