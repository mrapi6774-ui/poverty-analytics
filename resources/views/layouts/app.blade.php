<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Poverty Analytics')</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; }
        .sidebar { width: 275px; height: 100vh; position: fixed; top: 0; left: 0; background-color: #0f172a; color: #fff; z-index: 100; padding-top: 1rem; display: flex; flex-direction: column; }
        .sidebar .brand { font-size: 1.2rem; font-weight: bold; padding: 0.75rem 1.35rem; color: #38bdf8; display: block; text-decoration: none; border-bottom: 1px solid #1e293b; margin-bottom: 1rem; }
        .sidebar .nav-link { color: #94a3b8; padding: 0.75rem 1.35rem; font-size: 0.94rem; font-weight: 500; display: flex; align-items: center; gap: 0.65rem; white-space: nowrap; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background-color: #1e293b; border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 275px; padding: 2rem; min-height: 100vh; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 0.9rem 2rem; margin-left: 275px; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    </style>
</head>
<body>

<!-- Sidebar Navigasi -->
<div class="sidebar">
    @if(request()->is('admin*'))
        <!-- SIDEBAR PANEL ADMIN -->
        <a href="{{ route('data-management') }}" class="brand">
            <i class="bi bi-shield-lock-fill"></i> Admin Poverty Analytics
        </a>
        <div class="px-3 mb-2 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.06em; color: #64748b;">
            Manajemen Database
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('data-management') ? 'active' : '' }}" href="{{ route('data-management') }}">
                    <i class="bi bi-database-gear"></i> Kelola Data Tahunan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master-data') ? 'active' : '' }}" href="{{ route('master-data') }}">
                    <i class="bi bi-folder2-open"></i> Kelola Data Master
                </a>
            </li>
        </ul>

        <div class="px-3 mt-4 mb-2 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.06em; color: #64748b;">
            Portal Visualisasi
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('time-series') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Buka Portal Publik
                </a>
            </li>
        </ul>

        @auth
        <div class="mt-auto p-3 border-top border-secondary border-opacity-25">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                    <i class="bi bi-box-arrow-left me-1"></i> Keluar (Logout)
                </button>
            </form>
        </div>
        @endauth
    @else
        <!-- SIDEBAR PORTAL PUBLIK -->
        <a href="{{ route('time-series') }}" class="brand">
            <i class="bi bi-graph-up-arrow"></i> Poverty Analytics
        </a>
        <div class="px-3 mb-2 text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.06em; color: #64748b;">
            Analisis & Visualisasi
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('time-series') ? 'active' : '' }}" href="{{ route('time-series') }}">
                    <i class="bi bi-graph-up"></i> Trend Waktu (Series)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cross-section') ? 'active' : '' }}" href="{{ route('cross-section') }}">
                    <i class="bi bi-bar-chart-steps"></i> Antar Wilayah (Cross)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('apbd-analysis') ? 'active' : '' }}" href="{{ route('apbd-analysis') }}">
                    <i class="bi bi-wallet2"></i> Analisis APBD & Efektivitas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('scatter-plot') ? 'active' : '' }}" href="{{ route('scatter-plot') }}">
                    <i class="bi bi-diagram-3"></i> Korelasi (Scatter Plot)
                </a>
            </li>
        </ul>
    @endif
</div>

<!-- Header Atas -->
<div class="topbar shadow-sm">
    <h5 class="m-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h5>
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <button type="button" class="btn btn-outline-primary btn-sm fw-bold text-nowrap open-guide-modal-btn">
            <i class="bi bi-question-circle-fill me-1"></i> Panduan & Kamus
        </button>

        @if(request()->is('admin*') && Auth::check())
            <div class="dropdown ms-1" id="adminDropdownWrapper">
                <button id="adminProfileBtn" class="btn btn-dark btn-sm dropdown-toggle fw-bold text-nowrap" type="button" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                </button>
                <ul id="adminDropdownMenu" class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><span class="dropdown-item-text text-muted small">Role: Administrator</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-bold">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('report.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm fw-bold text-nowrap">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan (PDF)
            </a>
            <span class="badge bg-primary fs-6 text-nowrap">Provinsi Kepulauan Bangka Belitung</span>
        @endif
    </div>
</div>

<!-- Area Konten Utama -->
<div class="main-content">
    @yield('content')
</div>

<!-- Script tambahan khusus tiap halaman -->
@yield('scripts')

@include('partials.indicator-guide-modal')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('adminProfileBtn');
        const menu = document.getElementById('adminDropdownMenu');
        if (btn && menu) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                menu.classList.toggle('show');
            });
            document.addEventListener('click', function () {
                menu.classList.remove('show');
            });
        }
    });
</script>
</body>
</html>