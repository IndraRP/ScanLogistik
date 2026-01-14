<div>
    <div class="d-flex align-items-center justify-content-center utama p-4" style="background-color: #5f6df5;">
        <i class="fa-solid fa-angle-left fs-3 mb-0 text-white"></i>
        <p class="fw-semibold fs-4 mb-0 ms-4 text-center text-white">Stock Opname Barang</p>
    </div>

    @if (session()->has("message"))
        <div class="alert alert-success">
            {!! session("message") !!}
        </div>
    @endif


    <a href="/">
        <div class="rounded-pill icon-wrapper d-flex justify-content-center align-items-center">
            <i class="bi bi-arrow-left-short icon"></i>
        </div>
    </a>

    <div>
        <div class="container">
            @if ($scanning)
                <!-- Kamera Section -->
                <div id="camera-container">
                    <h1 class="text-dark">QR Scanner</h1>
                    <p class="text-danger">scroll ke section page paling bawah <span><a href="#camera-status" class="text-danger">*petunjuk*</a></span></p>

                    <video id="scanner" autoplay playsinline></video>
                    <p id="result">Menunggu QR Code...</p>
                    <button id="start-camera" class="camera-button">Mulai Kamera</button>
                    <p id="camera-status"></p>
                </div>
            @else
                <section id="product-info" class="content pb-5">
                    <h1 class="text-dark">Detail Barang</h1>

                    @if (session()->has("message"))
                        <div class="alert alert-success">
                            {!! session("message") !!}
                        </div>
                    @endif

                    <!-- Detail Barang -->
                    @if (!empty($barangList))
                        <div class="row g-3 mt-3">
                            @foreach ($barangList as $item)
                                <div class="col-md-4">
                                    <div class="card h-100 {{ isset($selectedBarangIds[$item->id]) && $selectedBarangIds[$item->id] ? "border-success" : "" }} border-0 shadow-sm">
                                        <div class="p-3 text-center">
                                            @if ($item->image)
                                                <img src="{{ asset("storage/" . $item->image) }}" class="img-fluid rounded" style="max-height: 150px;">
                                            @else
                                                <div class="text-muted">No Image</div>
                                            @endif
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title text-dark">{{ $item->nama_barang }}</h5>
                                            <p class="mb-1"><strong>Stock Code:</strong> {{ $item->stock_code }}</p>
                                            <p class="mb-1"><strong>Part Number:</strong> {{ $item->part_number }}</p>
                                            <p class="mb-1"><strong>Location:</strong> {{ $item->location }}</p>
                                            <p class="mb-1"><strong>Warehouse:</strong> {{ $item->warehouse }}</p>
                                            <p class="mb-1"><strong>UOM:</strong> {{ $item->uom }}</p>
                                            <hr>
                                            <p class="mb-1">
                                                <strong>Stok Saat Ini:</strong>
                                                <span class="badge bg-primary">{{ $item->qty }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Chart Section -->
                        <div class="mt-5 pt-5">
                            <h3 class="text-dark mb-3">📊 History Stok Barang</h3>

                            <!-- Summary Cards -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Stock Masuk</h5>
                                            <h2 class="card-text" id="totalMasuk">0</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-danger mt-md-0 mt-4 text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Stock Keluar</h5>
                                            <h2 class="card-text" id="totalKeluar">0</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Controls -->
                            <div class="d-flex mb-3 gap-2">
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
                            <h4 class="text-success mb-3">📥 Barang Masuk</h4>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <strong>Chart Tahun Ini (Per Bulan)</strong>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartYearIn" wire:ignore style="height:250px"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <strong>Chart Bulan Ini (Per Minggu)</strong>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartMonthIn" wire:ignore style="height:250px"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
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
                            <h4 class="text-danger mb-3">📤 Barang Keluar</h4>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-danger text-white">
                                            <strong>Chart Tahun Ini (Per Bulan)</strong>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartYearOut" wire:ignore style="height:250px"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-danger text-white">
                                            <strong>Chart Bulan Ini (Per Minggu)</strong>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartMonthOut" wire:ignore style="height:250px"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
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

                        <div class="w-100 mt-3">
                            <a href="{{ route("barang.export", $item->id) }}">
                                <button class="btn btn-success w-100">Download/Export Excel</button>
                            </a>
                        </div>
                    @else
                        <p class="text-muted">
                            {{ $productDescription ?? "QR Code belum dipindai" }}
                        </p>
                    @endif

                    <button class="resume-scan-button mt-4" wire:click="resumeScanning">Scan QR Code Lain</button>
                </section>
            @endif
        </div>




        @if ($scanning)
            <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const video = document.getElementById('scanner');
                    const result = document.getElementById('result');
                    const cameraStatus = document.getElementById('camera-status');
                    const startCameraButton = document.getElementById('start-camera');
                    let lastDetectedCode = null;
                    let lastDetectionTime = 0;
                    let scanning = true;
                    let stream = null;

                    // Tampilkan pesan status kamera
                    cameraStatus.textContent = 'Klik tombol "Mulai Kamera" untuk mengaktifkan kamera';

                    // Tambahkan event listener untuk tombol mulai kamera
                    startCameraButton.addEventListener('click', function() {
                        startScanner();
                        startCameraButton.style.display = 'none';
                    });

                    // Fungsi untuk memulai akses ke kamera
                    async function startScanner() {
                        try {
                            cameraStatus.textContent = 'Meminta akses kamera...';

                            // Coba akses kamera belakang terlebih dahulu
                            try {
                                stream = await navigator.mediaDevices.getUserMedia({
                                    video: {
                                        facingMode: "environment"
                                    }
                                });
                                setupVideoStream(stream);
                                cameraStatus.textContent = 'Menggunakan kamera belakang';
                            } catch (backCameraErr) {
                                console.log('Gagal mengakses kamera belakang, mencoba kamera depan', backCameraErr);

                                // Jika gagal, coba kamera depan atau kamera default
                                try {
                                    stream = await navigator.mediaDevices.getUserMedia({
                                        video: true
                                    });
                                    setupVideoStream(stream);
                                    cameraStatus.textContent = 'Menggunakan kamera depan/default';
                                } catch (frontCameraErr) {
                                    throw new Error('Gagal mengakses semua kamera');
                                }
                            }
                        } catch (err) {
                            console.error('Kamera tidak dapat diakses: ', err);
                            cameraStatus.textContent = 'Error: Kamera tidak dapat diakses. Pastikan Anda memberikan izin kamera.';
                            startCameraButton.style.display = 'block';
                        }
                    }

                    function setupVideoStream(stream) {
                        video.srcObject = stream;

                        // Mulai pemindaian setelah video dimuat
                        video.onloadedmetadata = function() {
                            video.play();
                            scanning = true;
                            scanQRCode();
                            cameraStatus.textContent = 'Kamera aktif. Arahkan ke QR Code...';
                        };
                    }

                    // Fungsi untuk memindai QR Code
                    function scanQRCode() {
                        if (!scanning) return;

                        if (!video.videoWidth) {
                            requestAnimationFrame(scanQRCode);
                            return;
                        }

                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');

                        // Menyusun gambar dari video
                        canvas.height = video.videoHeight;
                        canvas.width = video.videoWidth;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                        // Menyaring dan membaca QR Code dari gambar
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                        const decoded = jsQR(imageData.data, canvas.width, canvas.height);

                        const now = Date.now();

                        if (decoded) {
                            // Batasi pendeteksian berulang (satu kali per 3 detik)
                            if (decoded.data !== lastDetectedCode || now - lastDetectionTime > 3000) {
                                lastDetectedCode = decoded.data;
                                lastDetectionTime = now;

                                console.log('QR Code Terdeteksi:', decoded.data);
                                result.textContent = 'QR Code Terdeteksi. Memproses...';

                                // Kirim data ke komponen Livewire
                                if (typeof Livewire !== 'undefined') {
                                    Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                                        .call('barcodeDetected', decoded.data);
                                } else {
                                    console.error('Livewire tidak tersedia');
                                    result.textContent = 'Error: Livewire tidak tersedia';
                                }
                            }
                        }

                        // Lanjutkan pemindaian jika masih dalam mode scan
                        if (scanning) {
                            requestAnimationFrame(scanQRCode);
                        }
                    }

                    // Hentikan pemindaian saat meninggalkan halaman
                    window.addEventListener('beforeunload', function() {
                        stopCamera();
                    });

                    // Fungsi untuk menghentikan kamera
                    function stopCamera() {
                        if (stream) {
                            stream.getTracks().forEach(track => track.stop());
                            stream = null;
                            scanning = false;
                        }
                    }

                    // Tambahkan listener untuk event dari Livewire
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('scanningStateChanged', function(data) {
                            console.log('Scanning state changed:', data);
                            scanning = data.scanning;

                            if (!scanning) {
                                // Hentikan kamera ketika produk ditemukan
                                stopCamera();
                            } else {
                                // Mulai ulang kamera jika resume scanning
                                startScanner();
                            }
                        });
                    });
                });
            </script>
        @endif
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            let charts = {};
            let chartsInitialized = false;

            // Initialize charts only when elements are available
            function initCharts() {
                // Check if canvas elements exist
                if (!document.getElementById('chartYearIn')) {
                    return false;
                }

                // Destroy existing charts if any
                Object.values(charts).forEach(chart => {
                    if (chart) chart.destroy();
                });

                charts = {};

                try {
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

                    chartsInitialized = true;
                    console.log('Charts initialized successfully');
                    return true;
                } catch (error) {
                    console.error('Error initializing charts:', error);
                    return false;
                }
            }

            // Listen for chart updates
            Livewire.on('updateAllCharts', (payload) => {
                // Wait for DOM to be ready, then initialize charts
                setTimeout(() => {
                    if (!chartsInitialized) {
                        const initialized = initCharts();
                        if (!initialized) {
                            console.error('Failed to initialize charts');
                            return;
                        }
                    }

                    const data = payload[0];

                    // Update summary cards
                    const totalMasukEl = document.getElementById('totalMasuk');
                    const totalKeluarEl = document.getElementById('totalKeluar');

                    if (totalMasukEl) totalMasukEl.textContent = data.summaryIn.toLocaleString();
                    if (totalKeluarEl) totalKeluarEl.textContent = data.summaryOut.toLocaleString();

                    // Update bar charts - Masuk
                    if (charts.yearIn) {
                        charts.yearIn.data.labels = data.yearInData.labels;
                        charts.yearIn.data.datasets[0].data = data.yearInData.values;
                        charts.yearIn.update();
                    }

                    if (charts.monthIn) {
                        charts.monthIn.data.labels = data.monthInData.labels;
                        charts.monthIn.data.datasets[0].data = data.monthInData.values;
                        charts.monthIn.update();
                    }

                    if (charts.weekIn) {
                        charts.weekIn.data.labels = data.weekInData.labels;
                        charts.weekIn.data.datasets[0].data = data.weekInData.values;
                        charts.weekIn.update();
                    }

                    // Update bar charts - Keluar
                    if (charts.yearOut) {
                        charts.yearOut.data.labels = data.yearOutData.labels;
                        charts.yearOut.data.datasets[0].data = data.yearOutData.values;
                        charts.yearOut.update();
                    }

                    if (charts.monthOut) {
                        charts.monthOut.data.labels = data.monthOutData.labels;
                        charts.monthOut.data.datasets[0].data = data.monthOutData.values;
                        charts.monthOut.update();
                    }

                    if (charts.weekOut) {
                        charts.weekOut.data.labels = data.weekOutData.labels;
                        charts.weekOut.data.datasets[0].data = data.weekOutData.values;
                        charts.weekOut.update();
                    }

                    console.log('Charts updated successfully');
                }, 100); // Small delay to ensure DOM is ready
            });

            // Listen for scanning state changes to reset charts
            Livewire.on('scanningStateChanged', (payload) => {
                if (payload[0].scanning) {
                    // Destroy charts when going back to scanning mode
                    Object.values(charts).forEach(chart => {
                        if (chart) chart.destroy();
                    });
                    charts = {};
                    chartsInitialized = false;
                    console.log('Charts destroyed, ready for new scan');
                }
            });
        });
    </script>

    <style>
        /* Style untuk menampilkan video dari kamera */
        #camera-container {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        video {
            width: 100%;
            height: auto;
            border: 1px solid #ccc;
            background-color: #333;
        }

        .content {
            margin-top: 20px;
        }

        .description {
            margin-top: 15px;
            color: rgb(0, 0, 0);
        }

        .add-stock-button,
        .resume-scan-button,
        .camera-button {
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: rgb(0, 0, 0);
            border: none;
            cursor: pointer;
        }

        .add-stock-button:hover,
        .resume-scan-button:hover,
        .camera-button:hover {
            background-color: #45a049;
        }

        .resume-scan-button,
        .camera-button {
            background-color: #2196F3;
        }

        .resume-scan-button:hover,
        .camera-button:hover {
            background-color: #0b7dda;
        }

        #result,
        #camera-status {
            color: rgb(0, 0, 0);
            margin-top: 10px;
        }

        .qr-data-container {
            margin-bottom: 25px;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 5px;
        }

        .selected-row {
            background-color: rgba(76, 175, 80, 0.3) !important;
        }

        .selected-product {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .stok-form {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }

        .alert-danger {
            background-color: rgba(244, 67, 54, 0.2);
            color: #F44336;
        }

        .alert-info {
            background-color: rgba(33, 150, 243, 0.2);
            color: #2196F3;
        }

        .form-control {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .text-danger {
            color: #F44336;
        }

        /* Tambahan style untuk checkbox */
        .form-check-input {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

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
