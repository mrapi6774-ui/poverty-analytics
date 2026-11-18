<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - Poverty Analytics</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #090d16 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 920px;
        }

        .brand-section {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .form-section {
            padding: 3rem 2.5rem;
            color: #f8fafc;
        }

        .form-control {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #f8fafc;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .form-control:focus {
            background-color: #0f172a;
            border-color: #38bdf8;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .input-group-text {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #94a3b8;
        }

        .btn-cyan {
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            color: #ffffff;
            border: none;
            padding: 0.75rem;
            font-weight: 700;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-cyan:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
            color: #ffffff;
        }
    </style>
</head>
<body>

<div class="container px-3">
    <div class="login-card mx-auto">
        <div class="row g-0">
            <!-- SISI KIRI: BRANDING PORTAL -->
            <div class="col-lg-5 brand-section d-none d-lg-flex">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-graph-up-arrow fs-2 text-warning"></i>
                        <span class="fs-4 fw-bold">Poverty Analytics</span>
                    </div>
                    <h3 class="fw-bold text-white mb-3">Portal Pengelola Data & APBD</h3>
                    <p class="text-white-50 mb-4">
                        Panel administrasi terpadu untuk pengelolaan indikator makro kemiskinan, struktur postur APBD, dan impor data Excel Provinsi Kepulauan Bangka Belitung.
                    </p>
                </div>
                <div class="pt-4 border-top border-white border-opacity-25">
                    <div class="d-flex align-items-center gap-2 small text-white-50">
                        <i class="bi bi-shield-check text-warning fs-5"></i>
                        <span>Akses Terproteksi Administrator</span>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: FORM LOGIN -->
            <div class="col-lg-7 form-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-white mb-1">Masuk Portal Admin</h4>
                        <p class="text-secondary small m-0">Masukkan kredensial akun staf administrator</p>
                    </div>
                    <a href="{{ route('time-series') }}" class="btn btn-outline-light btn-sm opacity-75">
                        <i class="bi bi-arrow-left me-1"></i> Portal Publik
                    </a>
                </div>

                @if(session('success_logout'))
                    <div class="alert alert-info alert-dismissible fade show small py-2" role="alert">
                        <i class="bi bi-info-circle me-1"></i> {{ session('success_logout') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Alamat Email Administrator</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@bappeda.go.id" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            <button class="btn btn-outline-secondary border-secondary" type="button" onclick="togglePassword()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-secondary small" for="remember">
                                Ingat Saya di Perangkat Ini
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-cyan w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Panel Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>