<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('sneat') }}/assets/">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login SIS</title>

    <!-- Sneat Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="{{ asset('sneat') }}/assets/vendor/css/pages/page-auth.css" />

    <style>
        body {
            background: #f5f6fa;
        }

        .mobile-login {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }
    </style>
</head>

<body>

    <div class="mobile-login">
        <div class="card login-card">
            <div class="card-body p-4">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('sneat/assets/img/logo/logo-sis.png') }}" width="48">
                    <h4 class="mt-2 mb-1">Welcome 👋</h4>
                    <p class="text-muted small">Login ke Webbase SIS</p>
                </div>

                {{-- NOTIFIKASI ERROR LOGIN --}}
                @if ($errors->has('email'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Login Gagal!</strong><br>
                        Email atau Password salah.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Password" required>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember">
                            <label class="form-check-label">Remember</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small">Lupa?</a>
                    </div>

                    <button class="btn btn-primary w-100 py-2">
                        Login
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Auto hide alert -->
    <script>
        setTimeout(function() {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 3000);
    </script>

</body>

</html>
