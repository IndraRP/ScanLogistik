<div class="pb-5">

    <div class="d-flex align-items-center justify-content-center utama p-4" style="background-color: #5f6df5;">
        <i class="fa-solid fa-angle-left fs-3 mb-0 text-white"></i>
        <p class="fw-semibold fs-4 mb-0 ms-4 text-center text-white">Import Barang via Excel</p>
    </div>

    <a href="/BarangMasuk">
        <div class="rounded-pill icon-wrapper d-flex justify-content-center align-items-center">
            <i class="bi bi-arrow-left-short icon"></i>
        </div>
    </a>

    <div class="container mt-4">
        <p class="fw-semibold">Masukkan File Excel</p>
        {{-- Upload Excel --}}
        <input type="file" wire:model="excelFile" class="form-control mb-3">

        <div class="d-flex align-items-center">
            <button wire:click="previewExcel" class="btn btn-primary fs-7 mb-1">
                Preview Excel
            </button>

            <a href="{{ url("/download-template") }}" class="btn btn-success fs-7 mb-1 ms-2">
                Download Excel
            </a>
        </div>

        <p class="text-danger fs-7">click 'Preview Excel' 2x untuk preview dan step selanjutnya</p>

        <div wire:loading wire:target="previewExcel" class="my-3 text-center">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Sedang memproses Excel...</p>
        </div>

        <div>
            <p class="fw-semibold mt-5">Detail Barang</p>
        </div>

        {{-- Preview & Edit --}}
        @if (count($rows))
            <form wire:submit.prevent="saveAll">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <table class="table-sm table-bordered table-hover table text-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Stock Code</th>
                                    <th>Part Number</th>
                                    <th>Mnemonic</th>
                                    <th>Nama Barang</th>
                                    <th>Location</th>
                                    <th>Warehouse</th>
                                    <th>UOM</th>
                                    <th>Qty</th>
                                    <th>SOH Odoo</th>
                                    <th>Outstanding</th>
                                    <th>Difference</th>
                                    <th>Remarks</th>
                                    <th>Image</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $i => $row)
                                    <tr>
                                        <td><input wire:model="rows.{{ $i }}.stock_code" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.part_number" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.mnemonic" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.nama_barang" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.location" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.warehouse" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.uom" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.qty" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.soh_odoo" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.outstanding_belum_wr" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.difference" class="form-control"></td>
                                        <td><input wire:model="rows.{{ $i }}.remarks" class="form-control"></td>

                                        {{-- IMAGE --}}
                                        <td>
                                            <input type="file" wire:model="images.{{ $i }}" class="form-control mb-1">

                                            @if (isset($images[$i]))
                                                <img src="{{ $images[$i]->temporaryUrl() }}" width="60" class="img-thumbnail">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <button class="btn btn-success mt-3">
                    Save Barang
                </button>
            </form>
        @endif

    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('swal-loading', () => {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang membaca file Excel',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
            })

            Livewire.on('swal-success', (data) => {
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.text,
                    timer: 2000,
                    showConfirmButton: false
                })
            })

            Livewire.on('swal-error', (data) => {
                Swal.fire({
                    icon: 'error',
                    title: data.title,
                    text: data.text
                })
            })

        })
    </script>


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

        .table-wrapper {
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            border-radius: 10px;
            background: #fff;
        }

        /* Biar kolom nggak patah ke bawah */
        .table-wrapper table {
            min-width: 1600px;
        }

        /* Header tetap terlihat rapi */
        .table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 2;
            text-align: center;
            vertical-align: middle;
            font-size: 13px;
        }

        /* Cell */
        .table td {
            padding: 6px;
            vertical-align: middle;
        }

        /* Input lebih kecil & konsisten */
        .table input,
        .table select {
            min-width: 120px;
            font-size: 13px;
            padding: 4px 6px;
        }

        /* Kolom image */
        .table td:last-child {
            min-width: 120px;
            text-align: center;
        }

        /* Thumbnail image */
        .table img {
            max-height: 60px;
            object-fit: cover;
        }
    </style>
</div>
