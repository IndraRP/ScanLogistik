<div>
    <div class="d-flex align-items-center justify-content-center utama p-4" style="background-color: #5f6df5;">
        <i class="fa-solid fa-angle-left fs-3 mb-0 text-white"></i>
        <p class="fw-semibold fs-4 mb-0 ms-4 text-center text-white">Barang Masuk</p>
    </div>

    <a href="/">
        <div class="rounded-pill icon-wrapper d-flex justify-content-center align-items-center">
            <i class="bi bi-arrow-left-short icon"></i>
        </div>
    </a>

    <div class="container py-4">
        <div class="row justify-content-center">

            <!-- Scanner -->
            <div class="col-6 col-md-3 text-center">
                <a href="/ScanMasuk">

                    <h4 class="fw-semibold text-decoration-underline mb-3">
                        Scanner
                    </h4>

                    <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto bg-white shadow" style="height:180px; cursor:pointer;">
                        <img src="https://cdn-icons-png.flaticon.com/128/8848/8848876.png" class="gambar" alt="Scanner">
                    </div>
                </a>
            </div>

            <!-- Manual Entry -->
            <div class="col-6 col-md-3 text-center">
                <a href="/createbarang" class="text-decoration-none">

                    <h4 class="fw-semibold text-decoration-underline mb-3">
                        Manual Entry
                    </h4>

                    <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto bg-white shadow" style="height:180px; cursor:pointer;">
                        <img src="https://cdn-icons-png.flaticon.com/128/13635/13635098.png" class="gambar" alt="Manual Entry">
                    </div>
                </a>
            </div>

            <!-- Excel Entry -->
            <div class="col-6 col-md-3 mt-md-0 mt-4 text-center">
                <a href="/barang-excel" class="text-decoration-none">

                    <h4 class="fw-semibold text-decoration-underline mb-3">
                        Import Excel
                    </h4>

                    <div class="rounded-4 d-flex align-items-center justify-content-center mx-auto bg-white shadow" style="height:180px; cursor:pointer;">
                        <img src="https://cdn-icons-png.flaticon.com/128/11689/11689828.png" class="gambar" alt="Manual Entry">
                    </div>
                </a>
            </div>

        </div>
    </div>


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

        .gambar {
            width: 70px;
        }

        @media (max-width: 576px) {
            .gambar {
                width: 50px;
            }
        }
    </style>
</div>
