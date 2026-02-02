<div class="container py-5">
    <div class="mb-3 mt-5">
        <h3>History Masuk</h1>
    </div>
    <table class="table-bordered table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Requested By</th>
                <th>Kerusakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $row)
                <tr>
                    <td>{{ $row->created_at?->format("d-m-Y H:i") }}</td>
                    <td>{{ $row->barang->nama_barang ?? "-" }}</td>
                    <td>
                        <span class="badge bg-{{ $row->status == "masuk" ? "success" : "danger" }}">
                            {{ strtoupper($row->status) }}
                        </span>
                    </td>
                    <td>{{ $row->jumlah }}</td>
                    <td>{{ $row->requestedBy->name ?? "-" }}</td>
                    <td>{{ $row->kerusakan ?? "-" }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
