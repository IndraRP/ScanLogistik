<div>
    <div class="utama container">
        <div>
            <div class="d-flex align-items-center">
                <a class="text-dark tab" href="">
                    <p>Categories</p>
                </a>
                <a class="text-dark tab" href="">
                    <p class="ms-4">Show All</p>
                </a>
            </div>


            <div class="row gap-md-4 gap-0">
                <a href="/ScanIntro" class="text-dark text-decoration-none col-md-1 col-6">
                    <div class="rounded-4 card1 p-3 text-center shadow">
                        <div class="d-flex justify-content-center">
                            <img src="https://cdn-icons-png.flaticon.com/128/17410/17410384.png" class="gambar">
                        </div>
                        <p class="fw-semibold text-gambar mb-0 mt-2">Scan Here</p>
                    </div>
                </a>

                <a href="/BarangMasuk" class="text-dark text-decoration-none col-md-1 col-6">
                    <div class="rounded-4 card1 p-3 text-center shadow">
                        <div class="d-flex justify-content-center">
                            <img src="https://cdn-icons-png.flaticon.com/128/4067/4067206.png" class="gambar">
                        </div>
                        <p class="fw-semibold text-gambar mb-0 mt-2">Receive</p>
                    </div>
                </a>

                <a href="/AllBarang" class="text-dark text-decoration-none col-md-1 col-6">
                    <div class="rounded-4 card1 mt-md-0 mt-4 p-3 text-center shadow">
                        <div class="d-flex justify-content-center">
                            <img src="https://cdn-icons-png.flaticon.com/128/10951/10951884.png" class="gambar">
                        </div>
                        <p class="fw-semibold text-gambar mb-0 mt-2">Stock</p>
                    </div>
                </a>

                <a href="/CheckBarang" class="text-dark text-decoration-none col-md-1 col-6">
                    <div class="rounded-4 card1 mt-md-0 mt-4 p-3 text-center shadow">
                        <div class="d-flex justify-content-center">
                            <img src="https://cdn-icons-png.flaticon.com/128/18253/18253997.png" class="gambar">
                        </div>
                        <p class="fw-semibold text-gambar mb-0 mt-2">Check</p>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <style>
        .utama {
            background-color: aliceblue;
            padding-top: 100px;
        }

        .gambar {
            width: 45px;
        }

        .tab {
            text-decoration: none;
        }

        .tab:hover {
            color: #0b1d68 !important;
            transition: color 0.3s ease;
            text-decoration: underline;
        }

        .text-gambar {
            font-size: 15px;
        }

        .card1 {
            background-color: white;
            width: 120px;
        }

        .card1:hover {
            background-color: #c8f3ff;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .utama {
                padding-top: 125px;
            }

            .card1 {
                background-color: white;
                width: 100%;
            }

            .gambar {
                width: 35px;
            }

            .text-gambar {
                font-size: 13px;
            }
        }
    </style>
</div>
