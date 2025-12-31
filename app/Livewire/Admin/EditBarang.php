<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditBarang extends Component
{
    use WithFileUploads;

    public $barang_id;
    public $stock_code;
    public $part_number;
    public $mnemonic;
    public $nama_barang;
    public $deskripsi;
    public $image;
    public $old_image;
    public $note;
    public $location;
    public $warehouse;
    public $uom;
    public $qty;
    public $soh_odoo;
    public $outstanding_belum_wr;
    public $difference;
    public $remarks;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $barang = Barang::findOrFail($id);

        // dd($barang);

        $this->barang_id = $barang->id;
        $this->stock_code = $barang->stock_code;
        $this->part_number = $barang->part_number;
        $this->mnemonic = $barang->mnemonic;
        $this->nama_barang = $barang->nama_barang;
        $this->deskripsi = $barang->deskripsi;
        $this->old_image = $barang->image;
        $this->note = $barang->note;
        $this->location = $barang->location;
        $this->warehouse = $barang->warehouse;
        $this->uom = $barang->uom;
        $this->qty = $barang->qty;
        $this->soh_odoo = $barang->soh_odoo;
        $this->outstanding_belum_wr = $barang->outstanding_belum_wr;
        $this->difference = $barang->difference;
        $this->remarks = $barang->remarks;
    }

    public function update()
    {
        $this->validate([
            'stock_code' => 'required',
            'nama_barang' => 'required',
            'qty' => 'required|numeric',
            'image' => 'nullable|image|max:10240',
        ]);

        $barang = Barang::findOrFail($this->barang_id);

        /** Upload image jika diganti */
        if ($this->image) {
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                // dd($this->old_image, $this->image);
                Storage::disk('public')->delete($this->old_image);
            }
            $imagePath = $this->image->store('barang', 'public');
        } else {
            $imagePath = $this->old_image;
        }

        $barang->update([
            'stock_code' => $this->stock_code,
            'part_number' => $this->part_number,
            'mnemonic' => $this->mnemonic,
            'nama_barang' => $this->nama_barang,
            'deskripsi' => $this->deskripsi,
            'image' => $imagePath,
            'note' => $this->note,
            'location' => $this->location,
            'warehouse' => $this->warehouse,
            'uom' => $this->uom,
            'qty' => $this->qty,
            'soh_odoo' => $this->soh_odoo,
            'outstanding_belum_wr' => $this->outstanding_belum_wr,
            'difference' => $this->difference,
            'remarks' => $this->remarks,
        ]);

        session()->flash('success', 'Barang berhasil diperbarui');
        return redirect()->to('/AllBarang');
    }


    public function render()
    {
        return view('livewire.admin.edit-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
