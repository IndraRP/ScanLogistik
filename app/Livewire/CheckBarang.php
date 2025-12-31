<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StokHistory;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CheckBarang extends Component
{
    public $kode_barcode;
    public $productDescription = null;
    public $scanning = true;
    public $barang = null;
    public $barangList = [];
    public $selectedBarangIds = [];

    // Chart properties
    public $year;
    public $month;
    public $week;
    public $currentBarangId = null;

    protected $listeners = ['loadCharts'];

    public function mount()
    {
        $this->scanning = true;
        $this->productDescription = null;

        // Initialize chart filters
        $this->year = now()->year;
        $this->month = now()->month;
        $this->week = now()->weekOfMonth;

        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function barcodeDetected($barcode)
    {
        Log::info('QR Code terdeteksi: ' . $barcode);

        $barang = Barang::where('stock_code', $barcode)->first();

        if (!$barang) {
            $this->productDescription = 'Barang tidak ditemukan';
            return;
        }

        $this->barangList = [$barang];
        $this->currentBarangId = $barang->id;
        $this->selectedBarangIds[$barang->id] = true;

        $this->scanning = false;
        $this->dispatch('scanningStateChanged', ['scanning' => false]);

        // Load charts after scanning
        $this->loadCharts();
    }

    public function resumeScanning()
    {
        $this->scanning = true;
        $this->barangList = [];
        $this->currentBarangId = null;
        $this->productDescription = null;
        $this->dispatch('scanningStateChanged', ['scanning' => true]);
    }

    public function updatedYear()
    {
        $this->loadCharts();
    }

    public function updatedMonth()
    {
        $this->loadCharts();
    }

    public function updatedWeek()
    {
        $this->loadCharts();
    }

    public function loadCharts()
    {
        if (!$this->currentBarangId) {
            return;
        }

        $this->dispatch('updateAllCharts', [
            'summaryIn' => $this->getTotalMasuk(),
            'summaryOut' => $this->getTotalKeluar(),
            'yearInData' => $this->getYearData('masuk'),
            'monthInData' => $this->getMonthData('masuk'),
            'weekInData' => $this->getWeekData('masuk'),
            'yearOutData' => $this->getYearData('keluar'),
            'monthOutData' => $this->getMonthData('keluar'),
            'weekOutData' => $this->getWeekData('keluar'),
        ]);
    }

    private function getTotalMasuk()
    {
        return StokHistory::where('barang_id', $this->currentBarangId)
            ->where('status', 'masuk')
            ->sum('jumlah');
    }

    private function getTotalKeluar()
    {
        return StokHistory::where('barang_id', $this->currentBarangId)
            ->where('status', 'keluar')
            ->sum('jumlah');
    }

    private function getYearData($status)
    {
        $data = StokHistory::where('barang_id', $this->currentBarangId)
            ->where('status', $status)
            ->whereYear('created_at', $this->year)
            ->selectRaw('MONTH(created_at) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $values = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $months[$i - 1];
            $found = $data->firstWhere('bulan', $i);
            $values[] = $found ? $found->total : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getMonthData($status)
    {
        $startOfMonth = now()->setYear($this->year)->setMonth($this->month)->startOfMonth();
        $endOfMonth = now()->setYear($this->year)->setMonth($this->month)->endOfMonth();

        $data = StokHistory::where('barang_id', $this->currentBarangId)
            ->where('status', $status)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('WEEK(created_at, 1) - WEEK(DATE_SUB(created_at, INTERVAL DAYOFMONTH(created_at) - 1 DAY), 1) + 1 as minggu, SUM(jumlah) as total')
            ->groupBy('minggu')
            ->orderBy('minggu')
            ->get();

        $labels = ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'];
        $values = [0, 0, 0, 0, 0];

        foreach ($data as $item) {
            if ($item->minggu >= 1 && $item->minggu <= 5) {
                $values[$item->minggu - 1] = $item->total;
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getWeekData($status)
    {
        $startOfWeek = now()->setYear($this->year)->setMonth($this->month)->startOfMonth()->addWeeks($this->week - 1);
        $endOfWeek = (clone $startOfWeek)->addDays(6);

        $data = StokHistory::where('barang_id', $this->currentBarangId)
            ->where('status', $status)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DAYOFWEEK(created_at) as hari, SUM(jumlah) as total')
            ->groupBy('hari')
            ->get();

        $labels = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $values = [0, 0, 0, 0, 0, 0, 0];

        foreach ($data as $item) {
            $index = $item->hari - 1;
            if ($index >= 0 && $index < 7) {
                $values[$index] = $item->total;
            }
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function render()
    {
        return view('livewire.check-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
