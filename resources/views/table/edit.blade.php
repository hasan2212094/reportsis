<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('sneat') }}/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Edit User - PT.SIS</title>

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

            <!-- Left Side Image -->
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5">
                <div class="w-100 d-flex justify-content-center">
                    <img src="{{ asset('sneat') }}/assets/img/sneat2.png" class="img-fluid" alt="Edit User"
                        width="700">
                </div>
            </div>

            <!-- Right Side Form -->
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

                    <!-- Edit Form -->
                    <form action="{{ route('table.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="role" class="form-control">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div class="mb-3" id="bagian-field"
                            style="{{ $user->role == 'user' ? '' : 'display:none;' }}">
                            <label class="form-label">Bagian</label>
                            <select name="bagian" class="form-control">
                                <option value="">-- Pilih Bagian --</option>
                                <option value="ppic" {{ $user->bagian == 'ppic' ? 'selected' : '' }}>PPIC</option>
                                <option value="produksi" {{ $user->bagian == 'produksi' ? 'selected' : '' }}>Produksi
                                </option>
                                <option value="gudang" {{ $user->bagian == 'gudang' ? 'selected' : '' }}>Gudang
                                </option>
                                <option value="qc" {{ $user->bagian == 'qc' ? 'selected' : '' }}>Quality Control
                                </option>
                                <option value="keuangan" {{ $user->bagian == 'keuangan' ? 'selected' : '' }}>Finance &
                                    Accounting</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Update</button>
                    </form>

                </div>
            </div>
            <!-- /Right Side Form -->

        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('sneat') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>
    <script src="{{ asset('sneat') }}/assets/js/main.js"></script>

    <!-- Script Toggle Bagian -->
    <script>
        document.getElementById("role").addEventListener("change", function() {
            let role = this.value;
            let bagianField = document.getElementById("bagian-field");
            bagianField.style.display = (role === "user") ? "block" : "none";
        });
    </script>
</body>

</html>
