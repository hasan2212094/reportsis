<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('sneat') }}/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Register User - PT.SIS</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat') }}/assets/img/favicon/favicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="{{ asset('sneat') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('sneat') }}/assets/js/config.js"></script>
</head>

<body>
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">

            <!-- Left Image -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('sneat') }}/assets/img/sneat2.png" class="img-fluid" alt="Register User"
                        width="700">
                </div>
            </div>
            <!-- /Left Image -->

            <!-- Right Form -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                <div class="w-px-400 mx-auto">

                    <!-- Logo -->
                    <div class="app-brand mb-5 d-flex align-items-center">
                        <a href="{{ route('home') }}" class="app-brand-link gap-2">
                            <img src="{{ asset('sneat') }}/assets/img/logo/logo-sis.png" alt="Logo SIS"
                                style="width: 50px;">
                            <span class="app-brand-text demo text-body fw-bold">
                                Sribaru Indah Sejahtera
                            </span>
                        </a>
                    </div>

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('table.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                placeholder="Enter your username" required autofocus>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                placeholder="Enter your email" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3" id="bagian-field" style="display:none;">
                            <label class="form-label">Bagian</label>
                            <select class="form-control" name="bagian">
                                <option value="">-- Pilih Bagian --</option>
                                <option value="ppic">PPIC</option>
                                <option value="produksi">Produksi</option>
                                <option value="gudang">Gudang</option>
                                <option value="qc">Quality Control</option>
                                <option value="keuangan">Finance & Accounting</option>
                            </select>
                            @error('bagian')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label">Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" name="password" required
                                    placeholder="••••••••••••" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <label class="form-label">Password Confirmation</label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control" name="password_confirmation" required
                                    placeholder="••••••••••••" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms-conditions" required>
                                <label class="form-check-label" for="terms-conditions">
                                    I agree to <a href="#">privacy policy & terms</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Sign up</button>
                    </form>

                    <p class="text-center mt-3">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}">Sign in instead</a>
                    </p>
                </div>
            </div>
            <!-- /Right Form -->

        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('sneat') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>
    <script src="{{ asset('sneat') }}/assets/js/main.js"></script>

    <!-- Toggle Bagian -->
    <script>
        document.getElementById("role").addEventListener("change", function() {
            let bagianField = document.getElementById("bagian-field");
            bagianField.style.display = (this.value === "user") ? "block" : "none";
        });
    </script>
</body>

</html>
