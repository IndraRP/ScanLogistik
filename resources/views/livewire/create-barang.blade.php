<div class="">
    <div class="d-flex align-items-center justify-content-center utama p-4" style="background-color: #5f6df5;">
        <i class="fa-solid fa-angle-left fs-3 mb-0 text-white"></i>
        <p class="fw-semibold fs-4 mb-0 ms-4 text-center text-white">Input Barang</p>
    </div>

    <div class="d-block d-md-flex">
        <a href="/BarangMasuk">
            <div class="rounded-pill icon-wrapper d-flex justify-content-center align-items-center">
                <i class="bi bi-arrow-left-short icon"></i>
            </div>
        </a>

        @if (!$barcodePreview)
            <div class="py-md-4 container py-0">
                <form wire:submit.prevent="submit" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="stock_code" class="form-label">Stock Code</label>
                        <input type="text" id="stock_code" class="form-control" wire:model="stock_code" placeholder="Contoh: STK-00123" required>
                    </div>

                    <div class="mb-3">
                        <label for="part_number" class="form-label">Part Number</label>
                        <input type="text" id="part_number" class="form-control" wire:model="part_number" placeholder="Contoh: PN-AX1209" required>
                    </div>

                    <div class="mb-3">
                        <label for="mnemonic" class="form-label">Mnemonic</label>
                        <input type="text" id="mnemonic" class="form-control" wire:model="mnemonic" placeholder="Singkatan barang (misal: BRG-ELC)" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang</label>
                        <input type="text" id="nama_barang" class="form-control" wire:model="nama_barang" placeholder="Contoh: Motor Servo 220V" required>
                    </div>

                    <div class="mb-3">
                        <label for="warehouse" class="form-label">Warehouse</label>
                        <input type="text" id="warehouse" class="form-control" wire:model="warehouse" placeholder="Gudang A / Gudang Utama" required>
                    </div>

                    <div class="mb-3">
                        <label for="uom" class="form-label">UOM</label>
                        <input type="text" id="uom" class="form-control" wire:model="uom" placeholder="PCS / BOX / UNIT" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-block mb-2">
                            <label for="qty" class="form-label mb-0">Qty</label>
                            <small class="text-danger d-block">Optional</small>
                        </div>
                        <input type="number" id="qty" class="form-control" wire:model="qty" placeholder="Jumlah awal (jika ada)">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" id="location" class="form-control" wire:model="location" placeholder="Rak / Blok / Area (misal: RAK-B2)" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Status Barang</label>
                        <select id="deskripsi" class="form-select" wire:model="status" required>
                            <option value="">-- Pilih Status Barang --</option>
                            <option value="Available">
                                Available — tersedia untuk proyek
                            </option>
                            <option value="In use">
                                In use — sudah diperuntukkan proyek tetapi masih di warehouse
                            </option>
                            <option value="Damaged">
                                Damaged — rusak
                            </option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" id="image" class="form-control" wire:model="image" accept="image/*" required>
                    </div>

                    <h4 class="mb-1 mt-4">Detail Barang Masuk (Opsional)</h4>
                    <hr>

                    <div class="row mb-4 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Foto Barang / Kerusakan</label>
                            <input type="file" class="form-control" wire:model="damage_image" accept="image/*">
                            <small class="text-muted">
                                Upload kondisi barang saat diterima
                            </small>

                            @error("damage_image")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Keterangan Kerusakan</label>
                            <textarea class="form-control" wire:model.defer="kerusakan" rows="3" placeholder="Contoh: Kemasan penyok, segel rusak, atau barang pecah"></textarea>

                            @error("kerusakan")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <button class="btn btn-primary w-100">
                        SAVE
                    </button>

                </form>
            </div>
        @endif


    </div>

    @if ($barcodePreview)
        <div class="mt-4 pb-5 text-center">
            <p class="fw-semibold mb-2">QR / Barcode Barang</p>
            <img src="{{ asset("storage/" . $barcodePreview) }}" class="img-fluid rounded shadow" style="max-width: 220px;">
            <div class="mt-2">
                <a href="{{ asset("storage/" . $barcodePreview) }}" download class="btn btn-success btn-sm">
                    Download Barcode
                </a>
            </div>
        </div>
    @endif



    <style>
        .icon-wrapper {
            border: 2px solid #5f6df5;
            width: 50px;
            height: 50px;
            margin: 20px;
            background-color: white;
        }

        .icon-wrapper:hover {
            background-color: rgb(213, 243, 255);
        }

        .icon {
            color: #5f6df5;
            font-size: 35px;
        }

        .form-wrapper {
            max-width: 360px;
        }

        .custom-input {
            border: none;
            border-radius: 14px;
            padding: 14px 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            font-size: 15px;
        }

        .custom-input::placeholder {
            color: #4f6cff;
            font-weight: 500;
        }

        .custom-input:focus {
            box-shadow: 0 0 0 0.15rem rgba(79, 108, 255, .25);
        }
    </style>
</div>
