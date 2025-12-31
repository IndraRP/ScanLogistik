<?php

namespace App\Livewire;

use Livewire\Component;

class BarangMasuk extends Component
{
    public function render()
    {
        return view('livewire.barang-masuk')
            ->extends('layouts.app')
            ->section('content');
    }
}
