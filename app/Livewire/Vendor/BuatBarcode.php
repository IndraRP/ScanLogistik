<?php

namespace App\Livewire\Vendor;

use App\Models\Barang;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BuatBarcode extends Component
{
    public $barangList = [];
    public $selectedBarang = [];
    public $stokBarang = [];
    public $generatedQrPath = null;

    public function mount()
    {
        $this->barangList = Barang::all();
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function submit()
    {
        $data = [];

        foreach ($this->selectedBarang as $barangId) {
            $barang = Barang::find($barangId);
            if ($barang) {
                $data[] = [
                    'id' => $barang->id,
                    'nama' => $barang->nama_barang,
                    'stok' => $this->stokBarang[$barangId] ?? 0,
                ];
            }
        }

        $jsonData = json_encode($data);
        $path = $this->generateQRCode($jsonData);

        // Simpan path ke variabel properti
        $this->generatedQrPath = $path;

        // Flash data ke session
        session()->flash('qr_path', $path);
        session()->flash('message', 'QR Code berhasil dibuat!');

        // Dispatch event ke browser dengan string URL, bukan array
        $this->dispatch('barcodeGenerated', ['url' => asset($path)]);
    }

    public function generateQRCode($data)
    {
        $filename = 'group_qr_' . time() . '.png';
        $path = 'barcodes/' . $filename;

        // dd($path);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->size(300)
            ->margin(10)
            ->build();

        Storage::disk('public')->put($path, $result->getString());

        return 'storage/' . $path;
    }

    public function downloadQrCode()
    {
        if ($this->generatedQrPath) {
            return response()->download(public_path($this->generatedQrPath));
        }
    }

    public function render()
    {
        return view('livewire.vendor.buat-barcode')
            ->extends('layouts.app')
            ->section('content');
    }
}
