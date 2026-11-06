<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Analytics Kemiskinan & APBD - {{ $selectedRegObj->name }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        /* Kop Surat / Header Laporan */
        .report-header {
            border-bottom: 3px double #0d6efd;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }

        /* Aturan Khusus Cetak / Simpan PDF */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
            }
            .container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="py-4">

<div class="container bg-white p-4 shadow-sm rounded">

    <!-- Tombol Aksi (Hanya Muncul di Layar Web) -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom no-print">
        <a href="{{ route('time-series') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="d-flex gap-2">
            <!-- Form Filter Wilayah & Tahun -->
            <form method="GET" action="{{ route('report.pdf') }}" class="d-flex gap-2">
                <select name="regency_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($regencies as $reg)
                        <option value="{{ $reg->id }}" {{ $selectedRegency == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                    @endforeach
                </select>
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
            <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold">
                <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- KOP LAPORAN RESMI -->
    <div class="report-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold text-uppercase m-0 text-primary">Laporan Eksekutif Analisis Kemiskinan & APBD</h3>
            <div class="text-muted small fs-6">Pemerintah Provinsi Kepulauan Bangka Belitung • Tahun {{ $selectedYear }}</div>
        </div>
        <div class="text-end">
            <span class="badge bg-primary fs-6 px-3 py-2">{{ $selectedRegObj->name }}</span>
            <div class="text-muted small mt-1">Dicetak pada: {{ date('d-m-Y H:i') }} WIB</div>
        </div>
    </div>

    <!-- BARIS 1: KARTU INDIKATOR MAKRO -->
    <div class="row g-3 mb-4">
        @forelse($indicatorSummary as $item)
            <div class="col-md-3">
                <div class="card p-3 border-0 bg-light">
                    <div class="text-muted small fw-semibold text-uppercase">{{ $item->indicator->name }}</div>
                    <div class="fs-4 fw-bold text-dark mt-1">
                        {{ number_format($item->value, 2) }} <small class="fs-6 text-muted">{{ $item->indicator->unit }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-muted small">Data indikator belum tersedia untuk tahun ini.</div>
        @endforelse
    </div>

    <!-- BARIS 2: GRAFIK TREN & TABEL BELANJA -->
    <div class="row g-4 mb-4">
        <!-- Grafik Tren Kemiskinan 5 Tahun -->
        <div class="col-6">
            <div class="card h-100 border">
                <div class="card-header bg-light fw-bold py-2">
                    📈 Tren Kemiskinan ($P_0$) 5 Tahun Terakhir
                </div>
                <div class="card-body">
                    <canvas id="p0TrendReportChart" style="max-height: 220px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Komposisi Belanja APBD -->
        <div class="col-6">
            <div class="card h-100 border">
                <div class="card-header bg-light fw-bold py-2">
                    🍩 Komposisi Alokasi APBD Sektoral ({{ $selectedYear }})
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="apbdCompositionReportChart" style="max-height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 3: TABEL ALOKASI ANGGARAN SEKTORAL -->
    <div class="card border mb-4">
        <div class="card-header bg-light fw-bold py-2">
            📋 Rincian Realisasi Belanja APBD Sektoral (Tahun {{ $selectedYear }})
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0 text-sm">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Sektor Urusan Belanja</th>
                        <th class="text-end">Total Alokasi Anggaran</th>
                        <th class="text-end">Proporsi (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalBudget = $apbdSummary->sum('total_expense'); @endphp
                    @forelse($apbdSummary as $idx => $row)
                        <tr>
                            <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                            <td>{{ $row->sector->name }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($row->total_expense, 0, ',', '.') }} Jt</td>
                            <td class="text-end">
                                {{ $totalBudget > 0 ? number_format(($row->total_expense / $totalBudget) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Data anggaran tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end">TOTAL BELANJA SEKTORAL:</td>
                        <td class="text-end text-primary">Rp {{ number_format($totalBudget, 0, ',', '.') }} Jt</td>
                        <td class="text-end">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- TANDA TANGAN / KETERANGAN PENUTUP -->
    <div class="row mt-5 pt-3 no-print-break">
        <div class="col-8">
            <div class="small text-muted">
                * Dokumen laporan ini di-generate secara otomatis oleh Sistem Informasi <strong>Poverty Analytics</strong>.<br>
                * Data digunakan untuk bahan evaluasi perencanaan pembangunan daerah.
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="small fw-semibold">Pangkalpinang, {{ date('d F Y') }}</div>
            <div class="small text-muted">Pengelola Data Analytics,</div>
            <br><br><br>
            <div class="fw-bold border-bottom d-inline-block px-4">( Tim Perencana Bappeda )</div>
        </div>
    </div>

</div>

<script>
    // 1. Render Chart Tren Kemiskinan P0
    const p0Years = JSON.parse('{!! json_encode(array_keys($p0Trend->toArray())) !!}');
    const p0Values = JSON.parse('{!! json_encode(array_values($p0Trend->toArray())) !!}');

    new Chart(document.getElementById('p0TrendReportChart'), {
        type: 'line',
        data: {
            labels: p0Years,
            datasets: [{
                label: 'Kemiskinan P0 (%)',
                data: p0Values,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // 2. Render Chart Komposisi APBD
    const apbdSummary = JSON.parse('{!! json_encode($apbdSummary) !!}');
    const sectorNames = apbdSummary.map(item => item.sector.name);
    const sectorExpenses = apbdSummary.map(item => item.total_expense);

    new Chart(document.getElementById('apbdCompositionReportChart'), {
        type: 'pie',
        data: {
            labels: sectorNames,
            datasets: [{
                data: sectorExpenses,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
</body>
</html>