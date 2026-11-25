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
        .sidebar { width: 275px; height: 100vh; position: fixed; top: 0; left: 0; background-color: #0f172a; color: #fff; z-index: 100; padding-top: 1rem; }
        .sidebar .brand { font-size: 1.25rem; font-weight: bold; padding: 0.75rem 1.35rem; color: #38bdf8; display: block; text-decoration: none; border-bottom: 1px solid #1e293b; margin-bottom: 1rem; }
        .sidebar .nav-link { color: #94a3b8; padding: 0.75rem 1.35rem; font-size: 0.94rem; font-weight: 500; display: flex; align-items: center; gap: 0.65rem; white-space: nowrap; transition: all 0.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background-color: #1e293b; border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 275px; padding: 2rem; min-height: 100vh; }
        .topbar { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 0.9rem 2rem; margin-left: 275px; display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    </style>
</head>
<body>

<!-- Sidebar Navigasi -->
<div class="sidebar">
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
</div>

<!-- Header Atas -->
<div class="topbar shadow-sm">
    <h5 class="m-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h5>
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <button type="button" class="btn btn-outline-primary btn-sm fw-bold text-nowrap open-guide-modal-btn">
            <i class="bi bi-question-circle-fill me-1"></i> Panduan & Kamus
        </button>
        <a href="{{ route('report.pdf') }}" target="_blank" class="btn btn-outline-danger btn-sm fw-bold text-nowrap">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan (PDF)
        </a>
        <span class="badge bg-primary fs-6 text-nowrap">Provinsi Kepulauan Bangka Belitung</span>
    </div>
</div>

<!-- Area Konten Utama -->
<div class="main-content">
    @yield('content')
</div>

<!-- Script tambahan khusus tiap halaman -->
@yield('scripts')

@include('partials.indicator-guide-modal')
</body>
</html>