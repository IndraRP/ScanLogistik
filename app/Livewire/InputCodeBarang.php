<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StokHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class InputCodeBarang extends Component
{
    use WithFileUploads;
    public $stock_code;
    public $barang;
    public $step = 1; // 1=input, 2=preview, 3=aksi
    public $action; // masuk | keluar
    public $qtyKeluar;
    public $qtyMasuk;
    public $imageKondisi;
    public $kerusakanBarang;

    public function searchBarang()
    {
        $this->validate([
            'stock_code' => 'required'
        ]);

        $this->barang = Barang::where('stock_code', $this->stock_code)->first();

        if (!$this->barang) {
            session()->flash('message', 'Barang tidak ditemukan');
            return;
        }

        $this->step = 2;
    }

    public function pilihAksi($aksi)
    {
        $this->action = $aksi;
        $this->step = 3;
    }

    public function submitKeluar()
    {
        $this->validate([
            'qtyKeluar'   => 'required|numeric|min:1',
            'imageKondisi' => 'max:10240'
        ]);

        if ($this->qtyKeluar > $this->barang->qty) {
            session()->flash('message', 'Stok tidak mencukupi');
            return;
        }

        $imagePath = null;
        if ($this->imageKondisi) {
            $imagePath = $this->imageKondisi
                ->store('kondisi_barang/masuk', 'public');
        }

        $this->barang->decrement('qty', $this->qtyKeluar);
        $this->barang->decrement('soh_odoo', $this->qtyKeluar);

        StokHistory::create([
            'barang_id'    => $this->barang->id,
            'jumlah'       => $this->qtyKeluar,
            'kerusakan'    => $this->kerusakanBarang,
            'status'       => 'keluar',
            'image'        => $imagePath, // ✅ simpan image
            'requested_by' => Auth::id(),
        ]);

        session()->flash('message', 'Barang keluar berhasil');
        $this->resetFlow();
    }

    public function submitMasuk()
    {
        $this->validate([
            'qtyMasuk'    => 'required|numeric|min:1',
            'imageKondisi' => 'max:10240'
        ]);


        $imagePath = null;
        if ($this->imageKondisi) {
            $imagePath = $this->imageKondisi
                ->store('kondisi_barang/masuk', 'public');
        }


        $this->barang->increment('qty', $this->qtyMasuk);
        $this->barang->increment('soh_odoo', $this->qtyMasuk);

        StokHistory::create([
            'barang_id'    => $this->barang->id,
            'jumlah'       => $this->qtyMasuk,
            'kerusakan'    => $this->kerusakanBarang,
            'status'       => 'masuk',
            'image'        => $imagePath, // ✅ simpan image
            'requested_by' => Auth::id(),
        ]);

        session()->flash('message', 'Barang masuk berhasil');
        $this->resetFlow();
    }

    public function resetFlow()
    {
        $this->reset([
            'stock_code',
            'barang',
            'step',
            'action',
            'qtyMasuk',
            'qtyKeluar',
            'imageKondisi',
        ]);

        $this->step = 1;
    }

    public function render()
    {
        return view('livewire.input-code-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
