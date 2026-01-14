<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\StokHistory;
use Livewire\Attributes\On;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;

class AllBarang extends Component
{
    public $barangs;
    public $barangEdit;
    public $nama_barang, $deskripsi, $kode_barcode, $stok, $status;


    public function mount()
    {
        $this->barangs = Barang::with('verifier')
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($this->barangs);
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function view($id)
    {
        return redirect()->route('view_barang', ['id' => $id]);
    }

    public function render()
    {
        return view('livewire.admin.all-barang', [
            'barangs' => $this->barangs
        ])
            ->extends('layouts.app')
            ->section('content');
    }

    // Fungsi untuk memuat data barang yang akan diedit
    public function edit($id)
    {
        return redirect()->route('edit_barang', ['id' => $id]);
    }

    #[On('delete-confirmed')]
    public function deleteConfirmed($id)
    {
        $this->deleteBarang($id);
    }

    public function deleteBarang($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            $this->dispatch('alert-error', 'Barang tidak ditemukan!');
            return;
        }

        $barang->delete();

        session()->flash('success', 'Barang berhasil terhapus!');
        return redirect()->to('/AllBarang');
    }

    // Fungsi untuk menyimpan perubahan barang
    public function updateBarang()
    {
        $barang = Barang::find($this->barangEdit->id);
        $barang->nama_barang = $this->nama_barang;
        $barang->deskripsi = $this->deskripsi;
        $barang->kode_barcode = $this->kode_barcode;
        $barang->stok = $this->stok;
        $barang->status = $this->status;
        $barang->save();

        session()->flash('message', 'Barang berhasil diperbarui!');
        $this->barangs = Barang::with('verifier')->get(); // Update data
        $this->dispatch('closeEditModal');
        return redirect()->to(request()->header('Referer'));
    }

    public function downloadBarcode($filename)
    {
        $filename = str_replace('storage/', '', $filename);

        $filePath = storage_path("app/public/{$filename}");

        // dd($filePath);
        // Periksa apakah file ada
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            session()->flash('message', 'File barcode tidak ditemukan.');
            return back();
        }
    }


    public function export(Request $request)
    {
        $ids = json_decode($request->ids, true);

        // VALIDASI
        if (!$ids || count($ids) === 0) {
            abort(400, 'Barang tidak dipilih');
        }

        $spreadsheet = new Spreadsheet();
        $sheetIndex = 0;

        foreach ($ids as $id) {

            $barang = Barang::findOrFail($id);
            $histories = StokHistory::where('barang_id', $id)->get();

            /**
             * =========================
             * SHEET BARANG
             * =========================
             */
            if ($sheetIndex === 0) {
                $sheetBarang = $spreadsheet->getActiveSheet();
            } else {
                $sheetBarang = $spreadsheet->createSheet();
            }

            $sheetBarang->setTitle('Barang - ' . substr($barang->stock_code, 0, 20));

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
                    'Updated At'
                ]
            ], null, 'A1');

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
             * SHEET HISTORY
             * =========================
             */
            $sheetHistory = $spreadsheet->createSheet();
            $sheetHistory->setTitle('History - ' . substr($barang->stock_code, 0, 20));

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

            $sheetIndex += 2;
        }

        /**
         * =========================
         * DOWNLOAD
         * =========================
         */
        $fileName = 'Barang_Multi_' . now()->format('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/' . $fileName);

        (new Xlsx($spreadsheet))->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
