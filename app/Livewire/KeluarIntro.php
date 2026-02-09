<?php

namespace App\Livewire;

use Livewire\Component;

class KeluarIntro extends Component
{
    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.keluar-intro')
            ->extends('layouts.app')
            ->section('content');
    }
}
