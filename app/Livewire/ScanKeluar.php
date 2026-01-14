<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StokHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ScanKeluar extends Component
{
    use WithFileUploads;
    public $kode_barcode;
    public $productDescription = null;
    public $scanning = true;
    public $barang = null;
    public $barangList = [];  // Array untuk menyimpan semua barang dari QR
    public $selectedBarangIds = []; // Array untuk menyimpan ID barang yang dipilih
    // Tidak perlu lagi stokInputs karena akan menggunakan nilai stok langsung dari QR

    // Data sementara untuk tampilan
    public $productNames = [];
    public $productStocks = [];
    public $qtyKeluar = [];

    public $damage_image;
    public $kerusakan;

    public function mount()
    {
        $this->scanning = true;
        $this->productDescription = null;
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function barcodeDetected($barcode)
    {
        Log::info('QR Code terdeteksi: ' . $barcode);

        // barcode = stock_code
        $barang = Barang::where('stock_code', $barcode)->first();

        if (!$barang) {
            $this->productDescription = 'Barang tidak ditemukan';
            return;
        }

        // simpan hasil
        $this->barangList = [$barang];
        // dd($this->barangList);

        $this->selectedBarangIds[$barang->id] = true;
        $this->productNames[$barang->id]  = $barang->nama_barang;
        $this->productStocks[$barang->id] = $barang->stok;

        // stop scan
        $this->scanning = false;
        $this->dispatch('scanningStateChanged', ['scanning' => false]);
    }

    public function minusStock()
    {
        if (empty($this->barangList)) {
            $this->addError('selection', 'Tidak ada barang hasil scan.');
            return;
        }

        $messages = [];

        foreach ($this->barangList as $item) {

            $barang = Barang::find($item->id);

            if (!$barang) {
                $this->addError("barang.{$item->id}", 'Barang tidak ditemukan.');
                continue;
            }

            $qtyKeluar = $this->qtyKeluar[$item->id] ?? null;

            // dd($qtyKeluar);

            if (!is_numeric($qtyKeluar) || $qtyKeluar < 0) {
                $this->addError("qtyKeluar.{$item->id}", 'Jumlah keluar tidak valid.');
                continue;
            }

            if ($qtyKeluar > $barang->qty) {
                $this->addError("qtyKeluar.{$item->id}", 'Stok tidak mencukupi.');
                continue;
            }

            try {
                DB::transaction(function () use ($barang, $qtyKeluar) {

                    // ✅ Kurangi stok
                    $barang->update([
                        'qty' => $barang->qty - $qtyKeluar,
                        'soh_odoo' => $barang->soh_odoo - $qtyKeluar
                    ]);


                    $imagePath = null;

                    if ($this->damage_image) {
                        $imagePath = $this->damage_image
                            ->store('kondisi_barang/masuk', 'public');
                    }

                    // ✅ Simpan ke stok history
                    StokHistory::create([
                        'barang_id'    => $barang->id,
                        'jumlah'       => $qtyKeluar,
                        'status'       => 'keluar',
                        'image'        => $imagePath,
                        'kerusakan'    => $this->kerusakan,
                        'requested_by' => Auth::id(),
                    ]);
                });

                $messages[] = "Stok {$barang->nama_barang} berkurang {$qtyKeluar} (Sisa: {$barang->qty}).";
            } catch (\Exception $e) {
                Log::error('Minus stock error: ' . $e->getMessage());
                $this->addError("system.{$item->id}", 'Gagal mengurangi stok.');
            }
        }

        if (!empty($messages)) {
            session()->flash('message', implode('<br>', $messages));
            return redirect('/ScanKeluar');
        }
    }


    public function resumeScanning()
    {
        $this->scanning = true;
        $this->barangList = [];
        $this->selectedBarangIds = [];
        $this->productNames = [];
        $this->productStocks = [];
        $this->productDescription = null;

        $this->dispatch('scanningStateChanged', ['scanning' => true]);
    }

    public function render()
    {
        return view('livewire.scan-keluar')
            ->extends('layouts.app')
            ->section('content');
    }
}
