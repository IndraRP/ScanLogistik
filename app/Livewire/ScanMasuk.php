<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StokHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ScanMasuk extends Component
{
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

    public function addStock()
    {
        if (empty($this->barangList)) {
            $this->addError('selection', 'Tidak ada barang hasil scan.');
            return;
        }

        $messages = [];

        foreach ($this->barangList as $item) {

            $stockCode = $item->stock_code;

            $barang = Barang::where('stock_code', $stockCode)->first();

            if (!$barang) {
                $this->addError("barang.{$stockCode}", 'Barang tidak ditemukan.');
                continue;
            }

            if (!is_numeric($item->qty) || $item->qty <= 0) {
                $this->addError("stok.{$stockCode}", 'Nilai stok tidak valid.');
                continue;
            }

            try {
                $qtyLama = (int) $barang->qty;
                $qtyQR   = (int) $item->qty;
                $qtyBaru = $qtyLama + $qtyQR;

                // ✅ Update stok barang
                $barang->update([
                    'qty' => $qtyBaru
                ]);

                // ✅ Insert ke stok history
                StokHistory::create([
                    'barang_id'   => $barang->id,
                    'jumlah'      => $qtyQR,
                    'status'      => 'masuk',
                    'requested_by' => Auth::id(), // id user login
                ]);

                $messages[] = "Stok {$barang->nama_barang} bertambah {$qtyQR} (Total: {$qtyBaru}).";
            } catch (\Exception $e) {
                Log::error('Add stock error: ' . $e->getMessage());
                $this->addError("system.{$stockCode}", 'Gagal menambah stok.');
            }
        }

        if (!empty($messages)) {
            session()->flash('message', implode('<br>', $messages));
            return redirect('/ScanMasuk');
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
        return view('livewire.scan-masuk')
            ->extends('layouts.app')
            ->section('content');
    }
}
