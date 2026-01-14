<div class="utama container">

    <style>
        .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #0d6efd;
        }

        .dataTables_filter label {
            color: #0d6efd;
            font-weight: 600;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        table.dataTable tbody tr:hover {
            background-color: #e9f2ff;
        }

        .utama {
            margin-top: 110px;
            padding-bottom: 50px !important;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding-left: 20px;
        }

        div.dataTables_wrapper div.dataTables_paginate {
            margin-right: 20px;
        }

        /* Search box */
        .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #0d6efd;
            padding: 6px 10px;
        }

        .dataTables_filter label {
            font-weight: 600;
            color: #0d6efd;
        }

        /* Pagination */
        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .page-link {
            color: #0d6efd;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            margin-top: 20px;
            margin-right: 20px;
        }

        /* Hover row */
        table.dataTable tbody tr:hover {
            background-color: #e9f2ff;
        }

        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #barangTable {
            table-layout: fixed;
            min-width: 1500px;
            /* kunci lebar minimum */
        }


        #barangTable td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        #barangTable small {
            font-size: 0.75rem;
        }

        #barangTable img {
            border: 1px solid #dee2e6;
            background: #fff;
        }

        #barangTable thead th {
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .table-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: #0d6efd;
            border-radius: 10px;
        }


        @media (max-width: 576px) {
            .utama {
                margin-top: 70px;
            }
        }
    </style>

    @if (session()->has("message"))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session("message") }}
        </div>
    @endif
    @if (session("success"))
        <div class="alert alert-success">
            {{ session("success") }}
        </div>
    @endif


    <div class="card bg-blue text-dark shadow-lg">
        <div class="card-header d-flex align-items-center" style="height: 50px;">
            <i class="bi bi-clipboard-check me-2"></i>
            <p class="mb-0">Manajemen Barang</p>
        </div>

        <div class="table-responsive table-wrapper">
            <form id="exportForm" method="POST" action="{{ route("barang.exportall") }}">
                @csrf
                <input type="hidden" name="ids" id="exportIds">

                <button type="button" id="exportExcel" class="btn btn-success mb-2">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
            </form>


            <table id="barangTable" class="table-striped table-hover table text-nowrap align-middle" style="width:100%" wire:ignore>
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th>Barang</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Update Stok</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barangs as $barang)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-check" value="{{ $barang->id }}">
                            </td>

                            {{-- BARANG --}}
                            <td>
                                <div class="fw-semibold">{{ $barang->nama_barang }}</div>
                                <small class="text-muted d-block">Kode: {{ $barang->stock_code }}</small>
                                <small class="text-muted d-block">Part: {{ $barang->part_number }}</small>
                                <small class="text-muted d-block">Mnemonic: {{ $barang->mnemonic }}</small>
                            </td>

                            {{-- DESKRIPSI --}}
                            <td style="width:220px">
                                <div>{{ $barang->deskripsi ?? "-" }}</div>
                                <small class="text-muted d-block">Note: {{ $barang->note ?? "-" }}</small>
                                <small class="text-muted d-block">Remarks: {{ $barang->remarks ?? "-" }}</small>
                            </td>

                            {{-- GAMBAR --}}
                            <td>
                                @if ($barang->image)
                                    <img src="{{ asset("storage/" . $barang->image) }}" class="mb-1 rounded" style="width:50px;height:50px;object-fit:cover">
                                @endif

                                @if ($barang->image_barcode)
                                    <img src="{{ asset("storage/" . $barang->image_barcode) }}" class="rounded" style="width:50px;height:50px;object-fit:cover">
                                @endif

                                @if (!$barang->image && !$barang->image_barcode)
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- JUMLAH --}}
                            <td class="text-center">
                                <div class="fw-semibold">{{ $barang->qty }}</div>
                                <small class="text-muted d-block">SOH Odoo: {{ $barang->soh_odoo }}</small>
                            </td>

                            {{-- UPDATE STOK --}}
                            <td class="text-center">
                                <small class="d-block">Created</small>
                                {{ $barang->created_at->format("d-m-Y") }}
                                <hr class="my-1">
                                <small class="d-block">Updated</small>
                                {{ $barang->updated_at->format("d-m-Y") }}
                            </td>

                            {{-- LOKASI --}}
                            <td>
                                <div>{{ $barang->location }}</div>
                                <small class="text-muted">Warehouse: {{ $barang->warehouse }}</small>
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <a href="{{ asset("storage/" . $barang->image_barcode) }}" download class="btn btn-success btn-sm mb-1">
                                    <i class="bi bi-download"></i>
                                </a>

                                <button wire:click="view({{ $barang->id }})" class="btn btn-primary btn-sm mb-1">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button wire:click="edit({{ $barang->id }})" class="btn btn-warning btn-sm mb-1">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button wire:click="$dispatch('confirm-delete', { id: {{ $barang->id }} })" class="btn btn-danger btn-sm mb-1">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if ($.fn.DataTable.isDataTable('#barangTable')) {
                $('#barangTable').DataTable().destroy();
            }

            const table = $('#barangTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthChange: false,
                searching: true,
                paging: true,
                info: true,
                ordering: false,
                dom: 'ftrip',
                language: {
                    search: "Cari Barang:",
                    zeroRecords: "Barang tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ barang",
                    infoEmpty: "Data kosong",
                    paginate: {
                        next: "›",
                        previous: "‹"
                    }
                }
            });

            // ✅ CHECK ALL
            $('#checkAll').on('click', function() {
                $('.row-check').prop('checked', this.checked);
            });

            // ✅ EXPORT EXCEL
            $('#exportExcel').on('click', function() {
                let ids = [];

                $('.row-check:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length === 0) {
                    alert('Pilih minimal 1 barang');
                    return;
                }

                // kirim sebagai JSON string
                $('#exportIds').val(JSON.stringify(ids));

                // submit form
                $('#exportForm').submit();
            });

        });
    </script>


    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('confirm-delete', (data) => {
                if (!data || !data.id) {
                    console.error('Payload confirm-delete invalid:', data)
                    return
                }

                const id = data.id

                Swal.fire({
                    title: 'Yakin hapus barang?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ✅ Kirim sebagai objek
                        Livewire.dispatch('delete-confirmed', {
                            id: id
                        })
                    }
                })
            })
        })
    </script>

    <script>
        window.addEventListener('openEditModal', event => {
            new bootstrap.Modal(document.getElementById('editBarangModal')).show();
        });
    </script>
</div>
