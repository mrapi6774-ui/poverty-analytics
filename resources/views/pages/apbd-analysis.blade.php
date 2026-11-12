@extends('layouts.app')

@section('title', 'Analisis APBD & Efektivitas')
@section('page-title', '💰 Analisis Pendapatan, Belanja APBD & Efektivitas Alokasi')

@section('content')
<!-- Form Filter -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body bg-white rounded">
        <form method="GET" action="{{ route('apbd-analysis') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Pilih Wilayah / Daerah:</label>
                <select name="regency_id" class="form-select">
                    @foreach($regencies as $reg)
                        <option value="{{ $reg->id }}" {{ $selectedRegency == $reg->id ? 'selected' : '' }}>
                            {{ $reg->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun Analisis:</label>
                <select name="year" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Info APBD -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 border-start border-4 border-primary bg-white h-100 p-3">
            <div class="text-muted small fw-semibold text-uppercase">Total Belanja APBD ({{ $selectedYear }})</div>
            <div class="fs-4 fw-bold text-primary mt-1">
                Rp {{ number_format($sectorComposition->sum('total_expense'), 0, ',', '.') }} Jt
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 border-start border-4 border-success bg-white h-100 p-3">
            <div class="text-muted small fw-semibold text-uppercase">Wilayah Analisis</div>
            <div class="fs-5 fw-bold text-dark mt-1">{{ $selectedRegObj->name ?? '-' }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 border-start border-4 border-info bg-white h-100 p-3">
            <div class="text-muted small fw-semibold text-uppercase">Jumlah Sektor Terdata</div>
            <div class="fs-4 fw-bold text-info mt-1">{{ $sectorComposition->count() }} Sektor</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm border-0 border-start border-4 border-warning bg-white h-100 p-3">
            <div class="text-muted small fw-semibold text-uppercase">Periode Tren Waktu</div>
            <div class="fs-4 fw-bold text-dark mt-1">2025 – 2035</div>
        </div>
    </div>
</div>

<!-- BARIS 1: Grafik Tren Waktu APBD & Doughnut Komposisi Belanja -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                📈 Tren Total Belanja APBD (2025–2035)
            </div>
            <div class="card-body">
                <canvas id="apbdTrendChart" style="max-height: 320px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                🍩 Komposisi Belanja per Sektor ({{ $selectedYear }})
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="sectorCompositionChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- BARIS 2: Tabel Matriks Relevansi Belanja per Sektor -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span>🎯 Matriks Relevansi Belanja per Sektor (Tahun {{ $selectedYear }})</span>
                <span class="badge bg-primary">Keterkaitan Alokasi & Capaian Indikator</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Sektor Program</th>
                                <th class="text-end">Total Belanja (Juta Rp)</th>
                                <th>Indikator Utama Sektor</th>
                                <th class="text-center">Capaian Indikator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($relevanceData as $index => $rel)
                                <tr>
                                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $rel['sector_name'] }}</td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp {{ number_format($rel['budget'], 0, ',', '.') }} Jt
                                    </td>
                                    <td>
                                        <small class="text-muted d-block">{{ $rel['indicator_name'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark px-3 py-2">{{ $rel['indicator_value'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Data relevansi belanja tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BARIS 3: Tabel Analisis Efektivitas Alokasi Anggaran -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span>⚡ Tabel Analisis Efektivitas Alokasi Anggaran Sektoral (Tahun {{ $selectedYear }})</span>
                <span class="badge bg-secondary">Target: Penurunan Kemiskinan (P0)</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Sektor Belanja</th>
                                <th class="text-end">Alokasi Anggaran (Juta Rp)</th>
                                <th class="text-end">Perubahan Anggaran vs T-1</th>
                                <th class="text-center">Dampak Kemiskinan ($P_0$)</th>
                                <th class="text-center">Status Efektivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($effectivenessData as $index => $row)
                                <tr>
                                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                    <td>{{ $row['sector_name'] }}</td>
                                    <td class="text-end fw-bold text-success">
                                        Rp {{ number_format($row['current_exp'], 0, ',', '.') }} Jt
                                    </td>
                                    <td class="text-end">
                                        @if($row['delta_exp'] > 0)
                                            <span class="text-success"><i class="bi bi-arrow-up-right"></i> +Rp {{ number_format($row['delta_exp'], 0, ',', '.') }} Jt</span>
                                        @elseif($row['delta_exp'] < 0)
                                            <span class="text-danger"><i class="bi bi-arrow-down-right"></i> -Rp {{ number_format(abs($row['delta_exp']), 0, ',', '.') }} Jt</span>
                                        @else
                                            <span class="text-muted">Tetap</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($row['delta_p0'] < 0)
                                            <span class="badge bg-success"><i class="bi bi-graph-down-arrow"></i> Turun {{ number_format(abs($row['delta_p0']), 2) }}%</span>
                                        @elseif($row['delta_p0'] > 0)
                                            <span class="badge bg-danger"><i class="bi bi-graph-up-arrow"></i> Naik {{ number_format($row['delta_p0'], 2) }}%</span>
                                        @else
                                            <span class="badge bg-secondary">Stagnan</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($row['status'] === 'Sangat Efektif')
                                            <span class="badge bg-success p-2"><i class="bi bi-check-circle-fill me-1"></i> Sangat Efektif</span>
                                        @elseif($row['status'] === 'Kurang Efektif')
                                            <span class="badge bg-warning text-dark p-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kurang Efektif</span>
                                        @else
                                            <span class="badge bg-light text-dark border p-2">Netral</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Data belanja APBD tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data Grafik Tren APBD
    const years = JSON.parse('{!! json_encode($years) !!}');
    const apbdTrendValues = JSON.parse('{!! json_encode(array_values($apbdTrend->toArray())) !!}');

    // Render Grafik Batang Tren APBD
    new Chart(document.getElementById('apbdTrendChart'), {
        type: 'bar',
        data: {
            labels: years,
            datasets: [{
                label: 'Total Belanja APBD (Juta Rp)',
                data: apbdTrendValues,
                backgroundColor: '#198754',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Data Grafik Komposisi Sektor
    const sectorComposition = JSON.parse('{!! json_encode($sectorComposition) !!}');
    const sectorLabels = sectorComposition.map(item => item.sector.name);
    const sectorValues = sectorComposition.map(item => item.total_expense);

    // Render Doughnut Chart Komposisi Sektor
    new Chart(document.getElementById('sectorCompositionChart'), {
        type: 'doughnut',
        data: {
            labels: sectorLabels,
            datasets: [{
                data: sectorValues,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endsection