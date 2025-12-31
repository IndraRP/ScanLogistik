<div class="container">
    @if (!in_array(Route::currentRouteName(), ["login"]))
        @include("layouts.tab")
    @endif

    <div class="pt-5">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success w-100 mt-md-0 text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi Masuk</h5>
                        <h2 class="card-text" id="totalMasuk">{{ $totalBarangMasuk }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger w-100 mt-md-0 mt-4 text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi Keluar</h5>
                        <h2 class="card-text" id="totalKeluar">{{ $totalBarangKeluar }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning w-100 mt-md-0 mt-4 text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Barang</h5>
                        <h2 class="card-text" id="totalKeluar">{{ $totalBarang }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="d-flex pt-md-5 mb-3 gap-2 pt-4">
            <select wire:model.live="year" class="form-select w-auto">
                @for ($y = now()->year; $y >= 2020; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
            <select wire:model.live="month" class="form-select w-auto">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ DateTime::createFromFormat("!m", $m)->format("F") }}</option>
                @endfor
            </select>
            <select wire:model.live="week" class="form-select w-auto">
                @for ($w = 1; $w <= 5; $w++)
                    <option value="{{ $w }}">Minggu {{ $w }}</option>
                @endfor
            </select>
        </div>

        <!-- Barang Masuk Charts -->
        <h4 class="text-success mb-0">📥 Barang Masuk</h4>
        <p class="text-danger mt-2">Untuk mengakses chart dibawah, isi filter diatas terlebih dahulu!</p>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <strong>Chart Tahun Ini (Per Bulan)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartYearIn" wire:ignore style="height:250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <strong>Chart Bulan Ini (Per Minggu)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartMonthIn" wire:ignore style="height:250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <strong>Chart Minggu Ini (Per Hari)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartWeekIn" wire:ignore style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Keluar Charts -->
        <h4 class="text-danger mb-0 pt-3">📤 Barang Keluar</h4>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <strong>Chart Tahun Ini (Per Bulan)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartYearOut" wire:ignore style="height:250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <strong>Chart Bulan Ini (Per Minggu)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartMonthOut" wire:ignore style="height:250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <strong>Chart Minggu Ini (Per Hari)</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartWeekOut" wire:ignore style="height:250px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 10 Barang -->
        <h4 class="mb-0 pt-3">📊 Top 10 Barang</h4>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <strong>Top 10 Barang Masuk</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTopMasuk" wire:ignore style="height:300px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <strong>Top 10 Barang Keluar</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTopKeluar" wire:ignore style="height:300px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</div>

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            let charts = {};

            // Initialize all charts
            function initCharts() {
                // Bar charts for Barang Masuk
                charts.yearIn = new Chart(document.getElementById('chartYearIn'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah',
                            data: [],
                            backgroundColor: '#198754'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                charts.monthIn = new Chart(document.getElementById('chartMonthIn'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah',
                            data: [],
                            backgroundColor: '#198754'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                charts.weekIn = new Chart(document.getElementById('chartWeekIn'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah',
                            data: [],
                            backgroundColor: '#198754'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // Bar charts for Barang Keluar
                charts.yearOut = new Chart(document.getElementById('chartYearOut'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah',
                            data: [],
                            backgroundColor: '#dc3545'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                charts.monthOut = new Chart(document.getElementById('chartMonthOut'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah',
                            data: [],
                            backgroundColor: '#dc3545'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                charts.weekOut = new Chart(document.getElementById('chartWeekOut'), {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Jumlah',
                            data: [],
                            backgroundColor: '#dc3545'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // Donut charts for Top 10
                charts.topMasuk = new Chart(document.getElementById('chartTopMasuk'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: ['#198754', '#20c997', '#0dcaf0', '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545', '#fd7e14', '#ffc107']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });

                charts.topKeluar = new Chart(document.getElementById('chartTopKeluar'), {
                    type: 'doughnut',
                    data: {
                        labels: [],
                        datasets: [{
                            data: [],
                            backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#0dcaf0', '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#198754']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }

            initCharts();

            // Listen for chart updates
            Livewire.on('updateAllCharts', (payload) => {
                const data = payload[0];

                // Update summary cards
                document.getElementById('totalMasuk').textContent = data.summaryIn.toLocaleString();
                document.getElementById('totalKeluar').textContent = data.summaryOut.toLocaleString();

                // Update bar charts - Masuk
                charts.yearIn.data.labels = data.yearInData.labels;
                charts.yearIn.data.datasets[0].data = data.yearInData.values;
                charts.yearIn.update();

                charts.monthIn.data.labels = data.monthInData.labels;
                charts.monthIn.data.datasets[0].data = data.monthInData.values;
                charts.monthIn.update();

                charts.weekIn.data.labels = data.weekInData.labels;
                charts.weekIn.data.datasets[0].data = data.weekInData.values;
                charts.weekIn.update();

                // Update bar charts - Keluar
                charts.yearOut.data.labels = data.yearOutData.labels;
                charts.yearOut.data.datasets[0].data = data.yearOutData.values;
                charts.yearOut.update();

                charts.monthOut.data.labels = data.monthOutData.labels;
                charts.monthOut.data.datasets[0].data = data.monthOutData.values;
                charts.monthOut.update();

                charts.weekOut.data.labels = data.weekOutData.labels;
                charts.weekOut.data.datasets[0].data = data.weekOutData.values;
                charts.weekOut.update();

                // Update donut charts
                charts.topMasuk.data.labels = data.topBarangMasuk.labels;
                charts.topMasuk.data.datasets[0].data = data.topBarangMasuk.values;
                charts.topMasuk.update();

                charts.topKeluar.data.labels = data.topBarangKeluar.labels;
                charts.topKeluar.data.datasets[0].data = data.topBarangKeluar.values;
                charts.topKeluar.update();
            });

            // Load charts on first mount
            Livewire.dispatch('loadCharts');
        });
    </script>
@endpush
