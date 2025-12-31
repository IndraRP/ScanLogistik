<div class="min-vh-100 text-white" style="background-color: #FFDE59">

    @if (session()->has("error"))
        <div class="alert alert-danger text-center">
            {{ session("error") }}
        </div>
    @endif

    <div class="d-flex justify-content-center">
        <div class="rounded-4 d-flex justify-content-center align-items-center mt-md-5 mt-4 bg-white" style="width: 120px; height: 120px;">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRIGt349U8QknkkKh3N9IuobSr8uJCymiDAaw&s" style="width: 100px; height: 100px;" alt="">
        </div>
    </div>

    <div class="rounded-top-5 card mt-md-5 text-dark mt-4 bg-white">
        <h2 class="mt-md-5 mb-2 mt-4 text-center">Sign Up</h2>
        <p class="text-secondary text-center">Sign Up to continue.</p>

        <form wire:submit.prevent="register">
            <div class="d-flex justify-content-center mx-md-0 mx-4">
                <div class="card-2">

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-fill me-2"></i>Name
                        </label>
                        <input type="text" wire:model.defer="name" class="form-control border-secondary" placeholder="Masukkan nama">
                        @error("name")
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-envelope-fill me-2"></i>Email
                        </label>
                        <input type="email" wire:model.defer="email" class="form-control border-secondary" placeholder="Masukkan email">
                        @error("email")
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Password
                        </label>
                        <input type="password" wire:model.defer="password" class="form-control border-secondary" placeholder="Masukkan password">
                        @error("password")
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Retype Password
                        </label>
                        <input type="password" wire:model.defer="password_confirmation" class="form-control border-secondary" placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-person-plus-fill me-2"></i>Sign Up
                    </button>

                    <div class="d-flex justify-content-center mt-4">
                        <p class="fs-6 text-secondary mb-0">Already have an account?</p>
                        <a href="{{ route("login") }}" class="text-dark ms-2">Sign In</a>
                    </div>

                </div>
            </div>
        </form>
    </div>


    <style>
        .card {
            margin: 0px 100px;
            min-height: calc(100vh - 200px);
        }

        .card-2 {
            width: 350px;
            padding: 20px 0px;
        }

        .form-control {
            background-color: rgb(219, 219, 219)
        }

        @media (max-width: 576px) {
            .card {
                margin: 25px 25px;
            }

            .card-2 {
                width: 100%;
            }
        }
    </style>
</div>
