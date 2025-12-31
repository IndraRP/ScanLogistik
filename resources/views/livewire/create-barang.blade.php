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

        <div class="py-md-4 container py-0">
            <form wire:submit.prevent="submit" enctype="multipart/form-data">

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Stock Code" wire:model="stock_code" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Part Number" wire:model="part_number" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Mnemonic" wire:model="mnemonic" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Nama Barang" wire:model="nama_barang" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Warehouse" wire:model="warehouse" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="UOM" wire:model="uom" required>
                </div>

                <div class="mb-3">
                    <input type="number" class="form-control" placeholder="Qty" wire:model="qty" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Location" wire:model="location" required>
                </div>

                <div class="mb-3">
                    <textarea class="form-control" placeholder="Detail Description" wire:model="deskripsi" required></textarea>
                </div>

                <div class="mb-3">
                    <input type="file" class="form-control" wire:model="image" required>
                </div>

                <button class="btn btn-primary w-100">SAVE</button>

            </form>

        </div>
    </div>


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
