<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StokHistory;
use Livewire\Component;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class CreateBarang extends Component
{
    use WithFileUploads;

    public $stock_code;
    public $part_number;
    public $mnemonic;
    public $nama_barang;
    public $warehouse;
    public $uom;
    public $qty;
    public $location;
    public $deskripsi;
    public $image;
    public $status = 'pending';

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }
    // Add error handling for file upload
    public function updatedImage()
    {
        try {
            $this->validate([
                'image' => 'image|max:10240',
            ]);
        } catch (\Exception $e) {
            session()->flash('upload_error', 'Upload gagal: ' . $e->getMessage());
        }
    }

    public function submit()
    {
        $this->validate([
            'stock_code'   => 'required',
            'nama_barang'  => 'required',
            'qty'          => 'required|numeric',
            'image'        => 'nullable|image|max:10240',
        ]);

        /** Upload image barang **/
        $imagePath = null;
        if ($this->image) {
            $filename = 'barang_' . time() . '.' . $this->image->getClientOriginalExtension();
            $imagePath = $this->image->storeAs('barang', $filename, 'public');
        }

        /** Generate barcode **/
        $barcodePath = $this->generateQRCode($this->stock_code);

        /** Update atau Create barang **/
        $barang = Barang::updateOrCreate(
            ['stock_code' => $this->stock_code],
            [
                'part_number'   => $this->part_number,
                'mnemonic'      => $this->mnemonic,
                'nama_barang'   => $this->nama_barang,
                'warehouse'     => $this->warehouse,
                'uom'           => $this->uom,
                'qty'           => $this->qty,
                'soh_odoo'      => $this->qty,
                'location'      => $this->location,
                'difference'    => 0,
                'kode_barcode'  => 'BC' . $this->stock_code,
                'image'         => $imagePath,
                'image_barcode' => $barcodePath,
                'status'        => 'approved',
                'deskripsi'     => $this->deskripsi
            ]
        );

        /** ✅ TAMBAH STOK HISTORY **/
        StokHistory::create([
            'barang_id'    => $barang->id,
            'jumlah'       => $this->qty,
            'status'       => 'masuk',
            'requested_by' => Auth::id(),
        ]);

        /** Alert **/
        LivewireAlert::title('Barang Tersimpan!')
            ->success()
            ->show();

        $this->reset();
    }


    public function generateQRCode($stockCode)
    {
        $filename = 'barcode_' . $stockCode . '.png';
        $path = 'barcodes/' . $filename;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($stockCode)
            ->size(300)
            ->margin(10)
            ->build();

        Storage::disk('public')->put($path, $result->getString());

        return $path;
    }

    public function render()
    {
        return view('livewire.create-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
