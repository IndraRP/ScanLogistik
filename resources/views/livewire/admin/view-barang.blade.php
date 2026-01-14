<div class="utama container">

    <!-- HEADER -->
    <div class="card rounded-4 mb-4 border-0 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-semibold text-primary mb-1">
                    Detail Barang
                </h4>
                <p class="text-muted fs-8 mb-0">
                    Informasi lengkap barang gudang
                </p>
            </div>

            <div>
                <a href="{{ route("barang.export", $barang->id) }}" class="btn btn-success">
                    Export Excel
                </a>
            </div>


        </div>
    </div>

    <!-- MAIN INFO -->
    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-md-8">
            <div class="card rounded-4 h-100 border-0 shadow-sm">
                <div class="card-body">

                    <h6 class="fw-semibold text-primary mb-3">
                        Informasi Utama
                    </h6>

                    <div class="row fs-6">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Stock Code</label>
                            <div class="fw-semibold">{{ $barang->stock_code }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Part Number</label>
                            <div class="fw-semibold">{{ $barang->part_number }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Mnemonic</label>
                            <div class="fw-semibold">{{ $barang->mnemonic }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Nama Barang</label>
                            <div class="fw-semibold">{{ $barang->nama_barang }}</div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="text-muted">Deskripsi</label>
                            <div class="fw-semibold">
                                {{ $barang->deskripsi ?? "-" }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-md-4">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body text-center">

                    <h6 class="fw-semibold text-primary mb-3">
                        Barcode
                    </h6>

                    @if ($barang->image_barcode)
                        <img src="{{ asset("storage/" . $barang->image_barcode) }}" class="img-fluid mb-3" style="max-height: 160px;">
                    @else
                        <p class="text-muted fs-8">Barcode tidak tersedia</p>
                    @endif

                    <div class="fs-6">
                        <div class="text-muted">Kode Barcode</div>
                        <div class="fw-semibold">{{ $barang->kode_barcode }}</div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- STOCK INFO -->
    <div class="row">
        <div class="col-md-8 col-12">
            <div class="card rounded-4 mt-4 border-0 shadow-sm">
                <div class="card-body">

                    <h6 class="fw-semibold text-primary mb-3">
                        Informasi Stok
                    </h6>

                    <div class="row fs-6">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted">Qty</label>
                            <div class="fw-semibold">{{ number_format($barang->qty) }}</div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">Stock on Hand</label>
                            <div class="fw-semibold">{{ $barang->soh_odoo }}</div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">Status</label>
                            <div>
                                <span class="badge @if ($barang->status === "Available") bg-success
                                    @elseif ($barang->status === "In use") bg-primary
                                    @elseif ($barang->status === "Damaged") bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ $barang->status }}
                                </span>
                            </div>
                        </div>

                        {{-- <div class="col-md-3 mb-3">
                            <label class="text-muted">Outstanding</label>
                            <div class="fw-semibold">{{ $barang->outstanding_belum_wr }}</div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="text-muted">Difference</label>
                            <div class="fw-semibold text-danger">
                                {{ $barang->difference }}
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card rounded-4 mt-4 border-0 shadow-sm">
                <div class="card-body text-center">

                    <h6 class="fw-semibold text-primary mb-3">
                        Gambar Barang
                    </h6>

                    @if ($barang->image)
                        <img src="{{ asset("storage/" . $barang->image) }}" class="img-fluid mb-3" style="max-height: 160px;">
                    @else
                        <p class="text-muted fs-8">Image Barang tidak tersedia</p>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- LOCATION -->
    <div class="card rounded-4 mt-4 border-0 shadow-sm">
        <div class="card-body">

            <h6 class="fw-semibold text-primary mb-3">
                Lokasi & Catatan
            </h6>

            <div class="row fs-6">
                <div class="col-md-4 mb-3">
                    <label class="text-muted">Warehouse/Vendor</label>
                    <div class="fw-semibold">{{ $barang->warehouse }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="text-muted">Location/Rak</label>
                    <div class="fw-semibold">{{ $barang->location }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="text-muted">UOM (Unit of Measure)</label>
                    <div class="fw-semibold">{{ $barang->uom }}</div>
                </div>

                <div class="col-md-12">
                    <label class="text-muted">Note / Remarks</label>
                    <div class="fw-semibold">
                        {{ $barang->deskripsi ?? "-" }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="rounded-4 mt-5 bg-white p-4" wire:ignore>
        <h5 class="mb-3">Detail History Masuk dan Keluar</h5>
        <table id="stokHistoryTable" class="table-striped table-bordered w-100 table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Kerusakan</th>
                    <th>Image</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stokHistories as $index => $history)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $history->created_at->format("d-m-Y H:i") }}</td>
                        <td>
                            <span class="badge {{ $history->status === "masuk" ? "bg-success" : "bg-danger" }}">
                                {{ ucfirst($history->status) }}
                            </span>
                        </td>
                        <td>{{ $history->jumlah }}</td>
                        <td>{{ $history->kerusakan ?? "-" }}</td>
                        <td>
                            @if ($history->image)
                                <img src="{{ asset("storage/" . $history->image) }}" width="60" class="img-thumbnail">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $history->requestedBy->name ?? "-" }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        .utama {
            padding-top: 80px;
            padding-bottom: 70px;
        }

        .dataTables_filter,
        .dataTables_paginate,
        .dataTables_length,
        .dataTables_info {
            display: block !important;
        }
    </style>


</div>

@push("scripts")
    <script>
        let stokTable;

        function initTable() {
            stokTable = $('#stokHistoryTable').DataTable({
                dom: 'lfrtip',
                paging: true,
                searching: true,
                ordering: true,
                pageLength: 10
            });
        }

        document.addEventListener('livewire:load', function() {
            initTable();
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('message.processed', () => {
                if ($.fn.DataTable.isDataTable('#stokHistoryTable')) {
                    stokTable.destroy();
                }
                initTable();
            });
        });
    </script>
@endpush
