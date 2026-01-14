<div>
    <div class="d-flex align-items-center justify-content-center utama p-4" style="background-color: #5f6df5;">
        <i class="fa-solid fa-angle-left fs-3 mb-0 text-white"></i>
        <p class="fw-semibold fs-4 mb-0 ms-4 text-center text-white">Input Code Barang</p>
    </div>


    @if (session()->has("message"))
        <div class="alert alert-success">
            {!! session("message") !!}
        </div>
    @endif


    <div class="rounded-pill icon-wrapper d-flex justify-content-center align-items-center">
        <a href="/ScanIntro">
            <i class="bi bi-arrow-left-short icon"></i>
        </a>
    </div>

    @if ($step === 1)
        <div class="container mt-4">
            <form wire:submit.prevent="searchBarang">
                <label class="form-label">Input Stock Code</label>
                <input type="text" class="form-control" wire:model="stock_code">
                <button class="btn btn-primary w-100 mt-3">
                    Cari Barang
                </button>
            </form>
        </div>
    @endif

    @if ($step === 2 && $barang)
        <section class="content container mt-4 pb-4">
            <h5 class="fw-bold">Detail Barang</h5>

            <div class="card mt-3 shadow-sm">
                <div class="p-3 text-center">
                    @if ($barang->image)
                        <img src="{{ asset("storage/" . $barang->image) }}" class="img-fluid" style="max-height:150px;">
                    @endif
                </div>

                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $barang->nama_barang }}</p>
                    <p><strong>Stock Code:</strong> {{ $barang->stock_code }}</p>
                    <p><strong>Warehouse:</strong> {{ $barang->warehouse }}</p>
                    <p><strong>Location:</strong> {{ $barang->location }}</p>
                    <p>
                        <strong>Stok:</strong>
                        <span class="badge bg-primary">{{ $barang->qty }}</span>
                    </p>
                </div>
            </div>

            <div class="d-flex mt-4 gap-2">
                <button class="btn btn-success w-100" wire:click="pilihAksi('masuk')">
                    Barang Masuk
                </button>

                <button class="btn btn-danger w-100" wire:click="pilihAksi('keluar')">
                    Barang Keluar
                </button>
            </div>
        </section>
    @endif

    @if ($step === 3 && $action === "masuk")
        <div class="container mt-4">
            <label class="form-label">Qty Barang Masuk</label>
            <input type="number" class="form-control" required wire:model="qtyMasuk">

            <hr>
            <h5>Data Detail (Opsional)</h5>
            <label class="form-label mt-3">Foto Kondisi Barang</label>
            <input type="file" class="form-control" wire:model="imageKondisi">

            <label class="form-label mt-3">Kondisi/kerusakan Barang</label>
            <input type="text" class="form-control" wire:model="kerusakanBarang">

            @error("imageKondisi")
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <button class="btn btn-success w-100 mt-3" wire:click="submitMasuk">
                Konfirmasi Barang Masuk
            </button>
        </div>
    @endif


    @if ($step === 3 && $action === "keluar")
        <div class="container mt-4">
            <label class="form-label">Qty Barang Keluar</label>
            <input type="number" class="form-control" required wire:model="qtyKeluar">

            <hr>
            <h5>Data Detail (Opsional)</h5>
            <label class="form-label mt-3">Foto Kondisi Barang</label>
            <input type="file" class="form-control" wire:model="imageKondisi">


            <label class="form-label mt-3">Kondisi/kerusakan Barang</label>
            <input type="text" class="form-control" wire:model="kerusakanBarang">

            @error("imageKondisi")
                <small class="text-danger">{{ $message }}</small>
            @enderror

            <button class="btn btn-danger w-100 mt-3" wire:click="submitKeluar">
                Konfirmasi Barang Keluar
            </button>
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
    </style>
</div>
