<?php

namespace App\Livewire;

use App\Models\Barang;
use Livewire\Component;
use App\Models\StokHistory;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    public $year;
    public $month;
    public $week;
    public $totalBarangMasuk;
    public $totalBarangKeluar;
    public $totalBarang;

    protected $listeners = ['loadCharts'];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->totalBarangMasuk = StokHistory::where('status', 'masuk')->count();
        $this->totalBarangKeluar = StokHistory::where('status', 'keluar')->count();

        $this->totalBarang = Barang::count();
        // dd($this->totalBarang);

        $this->year = now()->year;
        $this->month = now()->month;
        $this->week = now()->weekOfMonth;
    }

    public function loadCharts()
    {
        $this->dispatch('updateAllCharts', [
            'summaryIn' => $this->getTotalMasuk(),
            'summaryOut' => $this->getTotalKeluar(),
            'yearInData' => $this->getYearData('masuk'),
            'monthInData' => $this->getMonthData('masuk'),
            'weekInData' => $this->getWeekData('masuk'),
            'yearOutData' => $this->getYearData('keluar'),
            'monthOutData' => $this->getMonthData('keluar'),
            'weekOutData' => $this->getWeekData('keluar'),
            'topBarangMasuk' => $this->getTopBarang('masuk'),
            'topBarangKeluar' => $this->getTopBarang('keluar'),
        ]);
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

    private function getTotalMasuk()
    {
        return StokHistory::where('status', 'masuk')->sum('jumlah');
    }

    private function getTotalKeluar()
    {
        return StokHistory::where('status', 'keluar')->sum('jumlah');
    }

    private function getYearData($status)
    {
        $data = StokHistory::where('status', $status)
            ->whereYear('created_at', $this->year)
            ->selectRaw('MONTH(created_at) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = [];
        $values = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'];

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

        $data = StokHistory::where('status', $status)
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

        $data = StokHistory::where('status', $status)
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

    private function getTopBarang($status)
    {
        $data = StokHistory::where('status', $status)
            ->select('barang_id', DB::raw('SUM(jumlah) as total'))
            ->groupBy('barang_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('barang')
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $item->barang->nama_barang ?? 'Unknown';
            $values[] = $item->total;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function render()
    {
        return view('livewire.home')
            ->extends('layouts.app')
            ->section('content');
    }
}
