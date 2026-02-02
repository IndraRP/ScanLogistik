<?php

namespace App\Livewire;

use App\Models\StokHistory;
use Livewire\Component;

class HistoryMasuk extends Component
{
    public $barangId;
    public $histories = [];

    public function mount($barangId = null)
    {
        $this->barangId = $barangId;

        $query = StokHistory::query()
            ->with(['barang', 'requestedBy'])
            ->where('status', 'masuk')
            ->orderBy('created_at', 'desc');

        if ($this->barangId) {
            $query->where('barang_id', $this->barangId);
        }

        $this->histories = $query->get();
    }

    public function render()
    {
        return view('livewire.history-masuk')
            ->extends('layouts.app')
            ->section('content');
    }
}
