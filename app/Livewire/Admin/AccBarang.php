<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\StokPending;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AccBarang extends Component
{
    public $stokPendingList;

    public function mount()
    {
        $this->stokPendingList = StokPending::with(['barang', 'requestedBy'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();


        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function approve($id)
    {
        $stok = StokPending::findOrFail($id);

        // Update status stok_pending
        $stok->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Tambahkan ke stok barang asli
        $barang = Barang::findOrFail($stok->barang_id);
        $barang->stok += $stok->jumlah;
        $barang->save();

        session()->flash('message', 'Stok berhasil disetujui dan ditambahkan ke barang.');

        // Refresh daftar stok pending
        $this->mount();
    }

    public function reject($id)
    {
        $stok = StokPending::findOrFail($id);
        $stok->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        session()->flash('message', 'Stok berhasil ditolak.');
        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.acc-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
