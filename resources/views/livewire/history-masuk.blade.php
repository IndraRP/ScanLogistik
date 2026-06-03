<div class="container py-5">
    <div class="d-block d-md-flex mb-3 mt-5">
        <h3>History Masuk</h1>

            <div class="d-block d-md-flex ms-auto">
                    <div class="p-3 me-3 bg-secondary text-white rounded-3">
                        <label for="bdaymonth">Filter Month :</label>
                        <input type="month" wire:model.live="bulan">
                        <button wire:click="filterData"  wire:loading.attr="disabled" class="btn btn-primary">
                            Filter
                            <div wire:loading wire:target="filterData">
                                Loading data...
                            </div>
                        </button>
                    </div>

                <button wire:click="exportExcel" wire:loading.attr="disabled" class="btn btn-success">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel 
                    <div wire:loading wire:target="exportExcel">
                        Loading data...
                    </div>
                </button>

                <button wire:click="exportPdf" class="btn btn-danger ms-2">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                    <div wire:loading wire:target="exportPdf" wire:loading.attr="disabled">
                        Loading data...
                    </div>
                </button>
            </div>
    </div>
    <div class="table-responsive">
        <table class="table-bordered table-striped table">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Requested By</th>
                    <th>Kerusakan</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $row)
                    <tr>
                        <td data-label="Tanggal">
                            {{ $row->created_at?->format("d-m-Y H:i") }}
                        </td>
                        <td data-label="Barang">
                            {{ $row->barang->nama_barang ?? "-" }}
                        </td>
                        <td data-label="Status">
                            <span class="badge bg-{{ $row->status == "masuk" ? "success" : "danger" }}">
                                {{ strtoupper($row->status) }}
                            </span>
                        </td>
                        <td data-label="Jumlah">
                            {{ $row->jumlah }}
                        </td>
                        <td data-label="Requested By">
                            {{ $row->requestedBy->name ?? "-" }}
                        </td>
                        <td data-label="Kerusakan">
                            {{ $row->kerusakan ?? "-" }}
                        </td>
                        <td data-label="Image">
                            <img src="{{ asset("storage/" . $row->image) }}" class="img-fluid mb-3" style="max-height: 150px;">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
