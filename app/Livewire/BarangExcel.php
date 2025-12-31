<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Barang;
use App\Models\StokHistory;
use Maatwebsite\Excel\Facades\Excel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class BarangExcel extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $rows = [];        // data dari excel
    public $images = [];      // image per row (index-based)

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    /** STEP 1: Baca Excel */
    public function previewExcel()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $this->dispatch('swal-loading');

            // 🔥 AMBIL PATH FILE LIVEWIRE-TMP (TANPA STORE)
            $fullPath = $this->excelFile->getRealPath();

            // Baca excel langsung
            $this->rows = SimpleExcelReader::create($fullPath)
                ->getRows()
                ->toArray();

            // dd($this->rows);

            $this->images = [];

            // 🔥 RESET FILE LIVEWIRE → otomatis hapus livewire-tmp
            $this->reset('excelFile');

            $this->dispatch('swal-success', [
                'title' => 'Berhasil',
                'text'  => 'Preview Excel berhasil dimuat'
            ]);
        } catch (\Throwable $e) {

            $this->reset('excelFile');

            $this->dispatch('swal-error', [
                'title' => 'Gagal',
                'text'  => $e->getMessage()
            ]);
        }
    }


    /** Generate QR */
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

    /** STEP 2: Save ke DB */
    public function saveAll()
    {
        DB::transaction(function () {

            foreach ($this->rows as $index => $row) {

                // upload image
                $imagePath = null;
                if (isset($this->images[$index])) {
                    $imagePath = $this->images[$index]
                        ->store('barang', 'public');
                }

                // generate barcode
                $barcodePath = $this->generateQRCode($row['stock_code']);

                // simpan / update barang
                $barang = Barang::updateOrCreate(
                    ['stock_code' => $row['stock_code']],
                    [
                        'part_number'   => $row['part_number'] ?? null,
                        'mnemonic'      => $row['mnemonic'] ?? null,
                        'nama_barang'   => $row['nama_barang'],
                        'deskripsi'     => $row['deskripsi'] ?? null,
                        'note'          => $row['note'] ?? null,
                        'location'      => $row['location'] ?? null,
                        'warehouse'     => $row['warehouse'] ?? null,
                        'uom'           => $row['uom'] ?? null,
                        'qty'           => $row['qty'],
                        'soh_odoo'      => $row['soh_odoo'] ?? $row['qty'],
                        'difference'    => $row['difference'] ?? 0,
                        'remarks'       => $row['remarks'] ?? null,
                        'image'         => $imagePath,
                        'image_barcode' => $barcodePath,
                        'status'        => 'approved',
                        'kode_barcode'  => $row['stock_code'],
                    ]
                );

                // 👉 simpan ke stok_history
                StokHistory::create([
                    'barang_id'   => $barang->id,
                    'jumlah'      => (int) $row['qty'],
                    'status'      => 'masuk',
                    'requested_by' => auth()->id(),
                ]);
            }
        });

        // reset semua
        $this->reset(['rows', 'images', 'excelFile']);

        $this->dispatch('swal-success', [
            'title' => 'Berhasil',
            'text'  => 'Semua barang berhasil disimpan'
        ]);
    }

    public function render()
    {
        return view('livewire.barang-excel')
            ->extends('layouts.app')
            ->section('content');
    }
}
