<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use Livewire\Component;

class ViewBarang extends Component
{
    public $barang;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->barang = Barang::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.view-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
