<div class="bg-dark fixed-top">
    <nav class="navbar navbar-expand-lg navbar-primary">
        <div class="w-100 d-flex">
            @auth
                <div class="d-flex align-items-center w-100">
                    <img src="{{ auth()->user()->avatar ? asset("storage/" . auth()->user()->avatar) : "https://icons.veryicon.com/png/o/miscellaneous/user-avatar/user-avatar-male-5.png" }}" class="rounded-pill border-dark border" style="width: 40px; height:40px; object-fit:cover" alt="User Avatar">

                    <div class="d-md-flex align-items-center d-block ms-3">
                        <p class="fs-7-besar mb-0 text-white">Welcome</p>
                        <p class="fs-7-respon ms-md-2 mb-0 ms-0 text-white">{{ auth()->user()->name }}!</p>
                    </div>
                </div>
            @endauth

            @guest
                <div class="d-flex align-items-center">
                    <a class="navbar-brand fw-bolder text-white" href="{{ route("login") }}">
                        Login
                    </a>
                </div>
            @endguest

            <div class="w-100 d-flex align-items-center">
                <a href="/" class="text-decoration-none ms-auto">
                    <p class="mb-0 text-white">Home</p>
                </a>

                <a href="/AllBarang" class="text-decoration-none ms-4">
                    <p class="mb-0 text-white">Barang</p>
                </a>
            </div>
            {{-- <div class="search-wrapper mb-1 mt-2">
                <input type="text" placeholder="Search" class="search-input">
                <span class="search-icon">
                    🔍
                </span>
            </div> --}}
        </div>
    </nav>
</div>

<style>
    .fs-7-besar {
        font-size: 16px;
        font-weight: 600;
    }

    .fs-7-respon {
        font-size: 16px;
        font-weight: 600;
    }

    .navbar-nav .nav-link:hover {
        color: #0b1d68 !important;
        transition: color 0.3s ease;
        text-decoration: underline;
    }

    .navbar-primary {
        background-color: rgb(2, 105, 215);
        padding: 10px 100px;
    }

    .search-wrapper {
        position: relative;
        width: 100%;
    }

    .search-input {
        width: 100%;
        padding: 10px 30px 10px 15px;
        border-radius: 20px;
        border: 2px solid #5f6df5;
        outline: none;
        font-size: 14px;
        color: #5f6df5;
    }

    .search-input::placeholder {
        color: #5f6df5;
    }

    .search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 17px;
        color: #5f6df5;
        cursor: pointer;
    }


    @media (max-width: 576px) {
        .navbar-primary {
            padding: 10px 20px;
        }

        .fs-7-respon {
            font-size: 12px;
            font-weight: 200;
        }

        .fs-7-besar {
            font-size: 14px;
            font-weight: 600;
        }
    }
</style>
