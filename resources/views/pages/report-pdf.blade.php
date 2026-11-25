<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Kemiskinan & APBD - {{ $selectedRegObj->name }}</title>
    <!-- Bootstrap 5 CSS & Icons -->
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

        .report-header {
            border-bottom: 3px double #0d6efd;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }

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

@php
    $urusanLabels = [
        'Kemiskinan Makro'           => 'Urusan Sosial (1.13)',
        'Pendidikan'                 => 'Urusan Pendidikan (1.01)',
        'Kesehatan'                  => 'Urusan Kesehatan (1.02)',
        'Infrastruktur & Perumahan'  => 'Urusan Infrastruktur (1.03)',
        'Ketenagakerjaan & Ekonomi'  => 'Urusan Pilihan (2.01)',
    ];
@endphp

<div class="container bg-white p-4 shadow-sm rounded">

    <!-- Tombol Aksi (Hanya Muncul di Layar Web) -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom no-print">
        <a href="{{ route('time-series') }}" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <div class="d-flex gap-2">
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
            <div class="text-muted small fs-6">Pemerintah Provinsi Kepulauan Bangka Belitung &bull; Tahun {{ $selectedYear }}</div>
        </div>
        <div class="text-end">
            <span class="badge bg-primary fs-6 px-3 py-2">{{ $selectedRegObj->name }}</span>
            <div class="text-muted small mt-1">Dicetak pada: {{ date('d-m-Y H:i') }} WIB</div>
        </div>
    </div>

    <!-- BARIS 1: KARTU INDIKATOR MAKRO -->
    <div class="row g-3 mb-4">
        @forelse($indicatorSummary as $item)
            <div class="col-md-3 col-6">
                <div class="card p-3 border bg-light h-100">
                    <div class="text-muted small fw-semibold text-uppercase">{{ $item->indicator->name }}</div>
                    <div class="fs-4 fw-bold text-dark mt-1">
                        {{ number_format($item->value, 2, ',', '.') }} <small class="fs-6 text-muted">{{ $item->indicator->unit }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-muted small">Data indikator belum tersedia untuk tahun ini.</div>
        @endforelse
    </div>

    <!-- BARIS 2: GRAFIK TREN & KOMPOSISI BELANJA -->
    <div class="row g-4 mb-4">
        <div class="col-6">
            <div class="card h-100 border">
                <div class="card-header bg-light fw-bold py-2">
                    <i class="bi bi-graph-down-arrow text-primary me-1"></i> Tren Kemiskinan (P0) 5 Tahun Terakhir
                </div>
                <div class="card-body">
                    <canvas id="p0TrendReportChart" style="max-height: 210px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card h-100 border">
                <div class="card-header bg-light fw-bold py-2">
                    <i class="bi bi-pie-chart-fill text-success me-1"></i> Komposisi Belanja Langsung per Urusan ({{ $selectedYear }})
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <canvas id="apbdCompositionReportChart" style="max-height: 200px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 3: RINGKASAN POSTUR APBD (4, 5.1, 5.2, 6) -->
    <div class="card border mb-4">
        <div class="card-header bg-light fw-bold py-2">
            <i class="bi bi-bank2 text-primary me-1"></i> Ringkasan Postur APBD (Tahun {{ $selectedYear }})
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">Kode</th>
                        <th>Uraian Struktur APBD</th>
                        <th class="text-end" style="width: 220px;">Anggaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fw-bold table-success bg-opacity-10">
                        <td class="text-center font-monospace">4</td>
                        <td>PENDAPATAN DAERAH</td>
                        <td class="text-end">{{ number_format($posturApbd['pendapatan'], 2, ',', '.') }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td class="text-center font-monospace">5</td>
                        <td>BELANJA DAERAH (5.1 + 5.2)</td>
                        <td class="text-end">{{ number_format($posturApbd['total_belanja'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center font-monospace text-muted">5.1</td>
                        <td class="ps-4">Belanja Tidak Langsung</td>
                        <td class="text-end">{{ number_format($posturApbd['belanja_51'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center font-monospace text-muted">5.2</td>
                        <td class="ps-4">Belanja Langsung (5 Urusan Prioritas)</td>
                        <td class="text-end">{{ number_format($posturApbd['belanja_52'], 2, ',', '.') }}</td>
                    </tr>
                    <tr class="fw-bold table-light">
                        <td class="text-center font-monospace">-</td>
                        <td>SURPLUS / (DEFISIT) ANGGARAN (4 - 5)</td>
                        <td class="text-end {{ $posturApbd['surplus_defisit'] < 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($posturApbd['surplus_defisit'], 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center font-monospace text-muted">6.1</td>
                        <td class="ps-4">Penerimaan Pembiayaan Daerah</td>
                        <td class="text-end">{{ number_format($posturApbd['penerimaan_pembiayaan'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center font-monospace text-muted">6.2</td>
                        <td class="ps-4">Pengeluaran Pembiayaan Daerah</td>
                        <td class="text-end">{{ number_format($posturApbd['pengeluaran_pembiayaan'], 2, ',', '.') }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td class="text-center font-monospace">6</td>
                        <td>PEMBIAYAAN NETO (6.1 - 6.2)</td>
                        <td class="text-end">{{ number_format($posturApbd['pembiayaan_neto'], 2, ',', '.') }}</td>
                    </tr>
                    <tr class="fw-bold table-primary">
                        <td class="text-center font-monospace">-</td>
                        <td>SISA LEBIH PEMBIAYAAN ANGGARAN (SILPA)</td>
                        <td class="text-end text-primary">{{ number_format($posturApbd['silpa'], 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- BARIS 4: TABEL ALOKASI ANGGARAN BELANJA LANGSUNG (5.2) PER URUSAN -->
    <div class="card border mb-4">
        <div class="card-header bg-light fw-bold py-2">
            <i class="bi bi-list-check text-primary me-1"></i> Rincian Alokasi Belanja Langsung (5.2) per Urusan (Tahun {{ $selectedYear }})
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Urusan Belanja Langsung</th>
                        <th class="text-end">Total Alokasi</th>
                        <th class="text-end" style="width: 120px;">Proporsi (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalBudget = $apbdSummary->sum('total_expense'); @endphp
                    @forelse($apbdSummary as $idx => $row)
                        <tr>
                            <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                            <td>{{ $urusanLabels[$row->sector->name] ?? $row->sector->name }}</td>
                            <td class="text-end fw-semibold">{{ number_format($row->total_expense, 2, ',', '.') }}</td>
                            <td class="text-end">
                                {{ $totalBudget > 0 ? number_format(($row->total_expense / $totalBudget) * 100, 1, ',', '.') : '0,0' }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Data anggaran belanja langsung tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end">TOTAL BELANJA LANGSUNG (5.2):</td>
                        <td class="text-end text-primary">{{ number_format($totalBudget, 2, ',', '.') }}</td>
                        <td class="text-end">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- TANDA TANGAN / KETERANGAN PENUTUP -->
    <div class="row mt-4 pt-3">
        <div class="col-8">
            <div class="small text-muted">
                * Dokumen laporan ini dihasilkan secara otomatis oleh Sistem Informasi <strong>Poverty Analytics</strong>.<br>
                * Data disajikan sebagai bahan evaluasi perencanaan pembangunan daerah.
            </div>
        </div>

</div>

<script>
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

    const urusanMap = {
        'Kemiskinan Makro': 'Urusan Sosial (1.13)',
        'Pendidikan': 'Urusan Pendidikan (1.01)',
        'Kesehatan': 'Urusan Kesehatan (1.02)',
        'Infrastruktur & Perumahan': 'Urusan Infrastruktur (1.03)',
        'Ketenagakerjaan & Ekonomi': 'Urusan Pilihan (2.01)'
    };

    const apbdSummary = JSON.parse('{!! json_encode($apbdSummary) !!}');
    const sectorNames = apbdSummary.map(item => urusanMap[item.sector.name] || item.sector.name);
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