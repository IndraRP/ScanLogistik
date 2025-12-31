<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use Livewire\Attributes\On;
use Livewire\Component;

class AllBarang extends Component
{
    public $barangs;
    public $barangEdit;
    public $nama_barang, $deskripsi, $kode_barcode, $stok, $status;

    public function mount()
    {
        $this->barangs = Barang::with('verifier')->get();
        // dd($this->barangs);
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function view($id)
    {
        return redirect()->route('view_barang', ['id' => $id]);
    }

    public function render()
    {
        return view('livewire.admin.all-barang', [
            'barangs' => $this->barangs
        ])
            ->extends('layouts.app')
            ->section('content');
    }

    // Fungsi untuk memuat data barang yang akan diedit
    public function edit($id)
    {
        return redirect()->route('edit_barang', ['id' => $id]);
    }

    #[On('delete-confirmed')]
    public function deleteConfirmed($id)
    {
        $this->deleteBarang($id);
    }

    public function deleteBarang($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            $this->dispatch('alert-error', 'Barang tidak ditemukan!');
            return;
        }

        $barang->delete();

        session()->flash('success', 'Barang berhasil terhapus!');
        return redirect()->to('/AllBarang');
    }

    // Fungsi untuk menyimpan perubahan barang
    public function updateBarang()
    {
        $barang = Barang::find($this->barangEdit->id);
        $barang->nama_barang = $this->nama_barang;
        $barang->deskripsi = $this->deskripsi;
        $barang->kode_barcode = $this->kode_barcode;
        $barang->stok = $this->stok;
        $barang->status = $this->status;
        $barang->save();

        session()->flash('message', 'Barang berhasil diperbarui!');
        $this->barangs = Barang::with('verifier')->get(); // Update data
        $this->dispatch('closeEditModal');
        return redirect()->to(request()->header('Referer'));
    }

    public function downloadBarcode($filename)
    {
        $filename = str_replace('storage/', '', $filename);

        $filePath = storage_path("app/public/{$filename}");

        // dd($filePath);
        // Periksa apakah file ada
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            session()->flash('message', 'File barcode tidak ditemukan.');
            return back();
        }
    }
}
