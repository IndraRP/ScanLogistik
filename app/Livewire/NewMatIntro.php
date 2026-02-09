<?php

namespace App\Livewire;

use Livewire\Component;

class NewMatIntro extends Component
{
    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.new-mat-intro')
            ->extends('layouts.app')
            ->section('content');
    }
}
