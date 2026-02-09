<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\StokHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class HistoryKeluar extends Component
{
    public $barangId;
    public $histories = [];

    public function mount($barangId = null)
    {
        $this->barangId = $barangId;

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $query = StokHistory::query()
            ->with(['barang', 'requestedBy'])
            ->where('status', 'keluar')
            ->orderBy('created_at', 'desc');

        if ($this->barangId) {
            $query->where('barang_id', $this->barangId);
        }

        $this->histories = $query->get();
    }

    public function exportPdf()
    {
        if (!auth()->check()) {
            abort(403);
        }

        $query = StokHistory::with(['barang', 'requestedBy'])
            ->where('status', 'keluar')
            ->orderBy('created_at', 'desc');

        if ($this->barangId) {
            $query->where('barang_id', $this->barangId);
        }

        $histories = $query->get();

        // Ambil barang (kalau single)
        $barang = $this->barangId
            ? Barang::find($this->barangId)
            : null;

        $pdf = Pdf::loadView('exports.barang_history_pdf', [
            'barang' => $barang,
            'histories' => $histories,
        ])->setPaper('A4', 'landscape');

        $fileName = 'History_Keluar_' . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function exportExcel()
    {
        if (!auth()->check()) {
            abort(403);
        }

        $query = StokHistory::with(['barang', 'requestedBy'])
            ->where('status', 'keluar')
            ->orderBy('created_at', 'desc');

        if ($this->barangId) {
            $query->where('barang_id', $this->barangId);
        }

        $histories = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('History Keluar');

        // Header
        $sheet->fromArray([
            ['Tanggal', 'Stock Code', 'Nama Barang', 'Jumlah', 'Status', 'User']
        ], null, 'A1');

        $row = 2;
        foreach ($histories as $history) {
            $sheet->fromArray([
                $history->created_at,
                optional($history->barang)->stock_code,
                optional($history->barang)->nama_barang,
                $history->jumlah,
                $history->status,
                optional($history->requestedBy)->name,
            ], null, "A{$row}");
            $row++;
        }

        $fileName = 'History_Keluar_' . now()->format('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/' . $fileName);

        (new Xlsx($spreadsheet))->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.history-keluar')
            ->extends('layouts.app')
            ->section('content');
    }
}
