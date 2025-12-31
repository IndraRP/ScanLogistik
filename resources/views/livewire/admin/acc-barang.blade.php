<div class="container" style="margin-top: 100px; margin-bottom:100px;">
    @if (session()->has("message"))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session("message") }}
        </div>
    @endif

    <div class="card bg-dark text-white shadow-lg">
        <div class="card-header d-flex align-items-center" style="height: 50px">
            <i class="bi bi-clipboard-check me-2"></i>
            <p class="mb-0">Persetujuan Stok Barang</p>
        </div>

        <div class="card-body table-responsive">
            <table class="table-dark table-hover table align-middle">
                <thead class="table-dark text-light">
                    <tr>
                        <th scope="col">Barang</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Requested By</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stokPendingList as $stok)
                        <tr>
                            <td>{{ $stok->barang->nama_barang }}</td>
                            <td>{{ $stok->jumlah }}</td>
                            <td>{{ $stok->requestedBy->name ?? "-" }}</td>
                            <td>
                                <button wire:click="approve({{ $stok->id }})" class="btn btn-success btn-sm me-2">
                                    <i class="bi bi-check-lg me-1"></i> Approve
                                </button>
                                <button wire:click="reject({{ $stok->id }})" class="btn btn-danger lg-mt-0 btn-sm mt-2">
                                    <i class="bi bi-x-lg me-1"></i> Reject
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">Tidak ada data stok pending.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
