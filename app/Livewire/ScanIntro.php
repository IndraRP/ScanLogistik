<?php

namespace App\Livewire;

use Livewire\Component;

class ScanIntro extends Component
{
    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.scan-intro')
            ->extends('layouts.app')
            ->section('content');
    }
}
