<h3>History Barang Masuk</h3>

@if ($barang)
    <p>
        <strong>Stock Code:</strong> {{ $barang->stock_code }} <br>
        <strong>Nama Barang:</strong> {{ $barang->nama_barang }}
    </p>
@endif

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Stock Code</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($histories as $h)
            <tr>
                <td>{{ $h->created_at }}</td>
                <td>{{ $h->barang->stock_code ?? "-" }}</td>
                <td>{{ $h->barang->nama_barang ?? "-" }}</td>
                <td>{{ $h->jumlah }}</td>
                <td>{{ $h->requestedBy->name ?? "-" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
    }
</style>
