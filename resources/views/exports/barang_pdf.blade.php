<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Export Barang</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        th {
            background: #eee;
            font-weight: bold;
            text-align: center;
        }

        h3 {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h3>SHEET 1 - DATA BARANG</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Stock Code</th>
                <th>Part Number</th>
                <th>Mnemonic</th>
                <th>Nama Barang</th>
                <th>Deskripsi</th>
                <th>Kode Barcode</th>
                <th>Status</th>
                <th>Location</th>
                <th>Warehouse</th>
                <th>UOM</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $barang->id }}</td>
                <td>{{ $barang->stock_code }}</td>
                <td>{{ $barang->part_number }}</td>
                <td>{{ $barang->mnemonic }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->deskripsi }}</td>
                <td>{{ $barang->kode_barcode }}</td>
                <td>{{ $barang->status }}</td>
                <td>{{ $barang->location }}</td>
                <td>{{ $barang->warehouse }}</td>
                <td>{{ $barang->uom }}</td>
            </tr>
        </tbody>
    </table>


    <table>
        <thead>
            <tr>
                <th>Qty</th>
                <th>SOH Odoo</th>
                <th>Outstanding Belum WR</th>
                <th>Difference</th>
                <th>Remarks</th>
                <th>Note</th>
                <th>Verified By</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $barang->qty }}</td>
                <td>{{ $barang->soh_odoo }}</td>
                <td>{{ $barang->outstanding_belum_wr }}</td>
                <td>{{ $barang->difference }}</td>
                <td>{{ $barang->remarks }}</td>
                <td>{{ $barang->note }}</td>
                <td>{{ $barang->verified_by }}</td>
                <td>{{ $barang->created_by }}</td>
                <td>{{ $barang->updated_by }}</td>
                <td>{{ $barang->created_at }}</td>
                <td>{{ $barang->updated_at }}</td>
            </tr>
        </tbody>
    </table>

    <h3>SHEET 2 - STOK HISTORY</h3>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Kerusakan</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($histories as $h)
                <tr>
                    <td>{{ $h->created_at }}</td>
                    <td>{{ $h->jumlah }}</td>
                    <td>{{ $h->status }}</td>
                    <td>{{ $h->kerusakan ?? "-" }}</td>
                    <td>{{ $h->requested_by }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
