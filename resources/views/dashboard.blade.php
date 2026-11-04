<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poverty Analytics - Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">📊 Poverty Analytics</a>
        <span class="navbar-text text-white">Provinsi Kepulauan Bangka Belitung (2025–2035)</span>
    </div>
</nav>

<div class="container-fluid px-4">
    <!-- Form Filter Utama -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-white rounded">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pilih Wilayah (Time Series):</label>
                    <select name="regency_id" class="form-select">
                        @foreach($regencies as $reg)
                            <option value="{{ $reg->id }}" {{ $selectedRegency == $reg->id ? 'selected' : '' }}>
                                {{ $reg->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pilih Indikator Analisis:</label>
                    <select name="indicator_id" class="form-select">
                        @foreach($sectors as $sec)
                            <optgroup label="{{ $sec->name }}">
                                @foreach($sec->indicators as $ind)
                                    <option value="{{ $ind->id }}" {{ $selectedIndicator == $ind->id ? 'selected' : '' }}>
                                        {{ $ind->name }} ({{ $ind->unit }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tahun Analisis:</label>
                    <select name="year" class="form-select">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Tampilkan Semua Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- BLOK 1: Trend Waktu & Total APBD -->
    <div class="row g-4 mb-4">
        <!-- Chart 1: Trend Indikator Capaian -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white fw-bold py-3">
                    📈 Trend Perkembangan Indikator (2025–2035)
                </div>
                <div class="card-body">
                    <canvas id="indicatorChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 2: Realisasi Anggaran APBD -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white fw-bold py-3">
                    💰 Trend Total Anggaran APBD (Juta Rp)
                </div>
                <div class="card-body">
                    <canvas id="apbdChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOK 2: Analisis APBD - Komposisi Belanja Sektor & Efektivitas -->
    <div class="row g-4 mb-4">
        <!-- Chart 3: Komposisi Belanja per Sektor (Stacked Bar Chart) -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    📊 Komposisi Belanja Daerah Menurut Sektor (2025–2035)
                </div>
                <div class="card-body">
                    <canvas id="stackedApbdChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Evaluasi Efektivitas Anggaran -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span>⚡ Analisis Efektivitas Anggaran Sektor</span>
                    <span class="badge bg-secondary">{{ $indicatorObj->name ?? '' }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 380px;">
                        <table class="table table-hover align-middle mb-0 text-sm">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Tahun</th>
                                    <th>Capaian</th>
                                    <th>$\Delta$ Indikator</th>
                                    <th>Anggaran Sektor</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($effectivityTable as $row)
                                    <tr>
                                        <td class="fw-bold">{{ $row['year'] }}</td>
                                        <td>{{ number_format($row['val_curr'], 2) }}</td>
                                        <td class="{{ $row['delta_val'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $row['delta_val'] > 0 ? '+' : '' }}{{ number_format($row['delta_val'], 2) }}
                                        </td>
                                        <td>Rp {{ number_format($row['budget_curr'], 0, ',', '.') }} Jt</td>
                                        <td class="text-center">
                                            @if($row['status'] === 'Efektif')
                                                <span class="badge bg-success">Efektif</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Kurang Efektif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOK 3: Analisis Antar Wilayah (Cross-Section) -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span>📊 Analisis Perbandingan Antar Wilayah (Tahun {{ $selectedYear }})</span>
                    <span class="badge bg-info text-dark">{{ $indicatorObj->name ?? '' }}</span>
                </div>
                <div class="card-body">
                    <canvas id="crossSectionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Peringkat Wilayah -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    🏆 Tabel Peringkat Wilayah (Tahun {{ $selectedYear }})
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px;">Rank</th>
                                    <th>Kabupaten / Kota</th>
                                    <th class="text-end">Capaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($crossSectionData as $index => $row)
                                    <tr class="{{ $row->regency_id == $selectedRegency ? 'table-warning fw-bold' : '' }}">
                                        <td class="text-center">
                                            @if($index == 0)
                                                <span class="badge bg-success rounded-circle fs-6">1</span>
                                            @elseif($index == 1)
                                                <span class="badge bg-primary rounded-circle fs-6">2</span>
                                            @elseif($index == 2)
                                                <span class="badge bg-info text-dark rounded-circle fs-6">3</span>
                                            @else
                                                <span class="badge bg-secondary rounded-circle fs-6">{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->regency->name }}</td>
                                        <td class="text-end fw-bold text-primary">
                                            {{ number_format($row->value, 2) }} {{ $indicatorObj->unit ?? '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOK 4: Analisis Korelasi (Scatter Plot) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">
                    🔵 Analisis Korelasi / Hubungan Antar Indikator (Scatter Plot - Tahun {{ $selectedYear }})
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}" class="row g-3 mb-4 bg-light p-3 rounded">
                        <input type="hidden" name="regency_id" value="{{ $selectedRegency }}">
                        <input type="hidden" name="indicator_id" value="{{ $selectedIndicator }}">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Sumbu X (Variabel Penentu/Determinan):</label>
                            <select name="indicator_x" class="form-select">
                                @foreach($indicators as $ind)
                                    <option value="{{ $ind->id }}" {{ $selectedIndicatorX == $ind->id ? 'selected' : '' }}>
                                        {{ $ind->name }} ({{ $ind->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Sumbu Y (Variabel Capaian):</label>
                            <select name="indicator_y" class="form-select">
                                @foreach($indicators as $ind)
                                    <option value="{{ $ind->id }}" {{ $selectedIndicatorY == $ind->id ? 'selected' : '' }}>
                                        {{ $ind->name }} ({{ $ind->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="submit" class="btn btn-secondary w-100 fw-bold">Update Scatter Plot</button>
                        </div>
                    </form>

                    <div style="height: 400px;">
                        <canvas id="scatterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Data JSON dari Controller
    const timeSeriesData    = JSON.parse('{!! json_encode($timeSeriesData) !!}');
    const apbdData          = JSON.parse('{!! json_encode($apbdData) !!}');
    const crossSectionData  = JSON.parse('{!! json_encode($crossSectionData) !!}');
    const scatterPoints     = JSON.parse('{!! json_encode($scatterPoints) !!}');
    const stackedDatasets   = JSON.parse('{!! json_encode($stackedDatasets) !!}');

    const years = timeSeriesData.map(item => item.year);
    const indicatorValues = timeSeriesData.map(item => item.value);
    const apbdValues = apbdData.map(item => item.total_budget);

    // Data Cross-Section
    const regencyNames = crossSectionData.map(item => item.regency.name);
    const crossValues  = crossSectionData.map(item => item.value);

    // 1. Render Chart Time Series Indikator
    new Chart(document.getElementById('indicatorChart'), {
        type: 'line',
        data: {
            labels: years,
            datasets: [{
                label: 'Nilai Indikator',
                data: indicatorValues,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true, plugins: { legend: { display: true } } }
    });

    // 2. Render Chart APBD Total
    new Chart(document.getElementById('apbdChart'), {
        type: 'bar',
        data: {
            labels: years,
            datasets: [{
                label: 'Total APBD (Juta Rp)',
                data: apbdValues,
                backgroundColor: '#198754'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: true } } }
    });

    // 3. Render Chart Komposisi Belanja Sektor (Stacked Bar Chart)
    new Chart(document.getElementById('stackedApbdChart'), {
        type: 'bar',
        data: {
            labels: years,
            datasets: stackedDatasets
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                x: { stacked: true },
                y: { stacked: true, title: { display: true, text: 'Juta Rp' } }
            }
        }
    });

    // 4. Render Chart Cross-Section
    new Chart(document.getElementById('crossSectionChart'), {
        type: 'bar',
        data: {
            labels: regencyNames,
            datasets: [{
                label: 'Capaian (Tahun ' + '{{ $selectedYear }}' + ')',
                data: crossValues,
                backgroundColor: [
                    '#0d6efd', '#198754', '#ffc107', '#0dcaf0', 
                    '#6610f2', '#fd7e14', '#20c997', '#6c757d'
                ]
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // 5. Render Chart Scatter Plot
    new Chart(document.getElementById('scatterChart'), {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Sebaran Kabupaten / Kota',
                data: scatterPoints.map(p => ({ x: p.x, y: p.y })),
                backgroundColor: '#dc3545',
                borderColor: '#b02a37',
                pointRadius: 7,
                pointHoverRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const pt = scatterPoints[context.dataIndex];
                            return `${pt.regency}: (${pt.x}, ${pt.y})`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: '{{ $indicatorXObj->name ?? "Sumbu X" }} ({{ $indicatorXObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: '{{ $indicatorYObj->name ?? "Sumbu Y" }} ({{ $indicatorYObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                }
            }
        }
    });
</script>

</body>
</html>