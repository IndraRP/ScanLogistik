<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\StokHistory;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ViewBarang extends Component
{
    public $barang;
    public $stokHistories;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->barang = Barang::findOrFail($id);

        $this->stokHistories = StokHistory::with('requestedBy')
            ->where('barang_id', $id)
            ->latest()
            ->get();

        // dd($this->stokHistories);
    }

    public function export($id)
    {
        $barang = Barang::findOrFail($id);
        $histories = StokHistory::where('barang_id', $id)->get();

        $spreadsheet = new Spreadsheet();

        /**
         * =========================
         * SHEET 1 - DATA BARANG
         * =========================
         */
        $sheetBarang = $spreadsheet->getActiveSheet();
        $sheetBarang->setTitle('Barang');

        // HEADER
        $sheetBarang->fromArray([
            [
                'ID',
                'Stock Code',
                'Part Number',
                'Mnemonic',
                'Nama Barang',
                'Deskripsi',
                'Kode Barcode',
                'Status',
                'Location',
                'Warehouse',
                'UOM',
                'Qty',
                'SOH Odoo',
                'Outstanding Belum WR',
                'Difference',
                'Remarks',
                'Note',
                'Verified By',
                'Created By',
                'Updated By',
                'Created At',
                'Updated At',
            ]
        ], null, 'A1');

        // DATA
        $sheetBarang->fromArray([
            [
                $barang->id,
                $barang->stock_code,
                $barang->part_number,
                $barang->mnemonic,
                $barang->nama_barang,
                $barang->deskripsi,
                $barang->kode_barcode,
                $barang->status,
                $barang->location,
                $barang->warehouse,
                $barang->uom,
                $barang->qty,
                $barang->soh_odoo,
                $barang->outstanding_belum_wr,
                $barang->difference,
                $barang->remarks,
                $barang->note,
                $barang->verified_by,
                $barang->created_by,
                $barang->updated_by,
                $barang->created_at,
                $barang->updated_at,
            ]
        ], null, 'A2');

        /**
         * =========================
         * SHEET 2 - STOK HISTORY
         * =========================
         */
        $sheetHistory = $spreadsheet->createSheet();
        $sheetHistory->setTitle('Stok History');

        $sheetHistory->fromArray([
            ['Tanggal', 'Jumlah', 'Status', 'Kerusakan', 'User']
        ], null, 'A1');

        $row = 2;
        foreach ($histories as $history) {
            $sheetHistory->fromArray([
                $history->created_at,
                $history->jumlah,
                $history->status,
                $history->kerusakan ?? '-',
                $history->requested_by,
            ], null, "A{$row}");
            $row++;
        }

        /**
         * =========================
         * DOWNLOAD
         * =========================
         */
        $fileName = 'Barang_' . $barang->stock_code . '.xlsx';
        $tempPath = storage_path('app/' . $fileName);

        (new Xlsx($spreadsheet))->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.admin.view-barang')
            ->extends('layouts.app')
            ->section('content');
    }
}
