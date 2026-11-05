@extends('layouts.app')

@section('title', 'Analisis Perbandingan Antar Wilayah')
@section('page-title', '📊 Analisis Antar Wilayah (Cross-Section)')

@section('content')
<!-- Form Filter -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body bg-white rounded">
        <form method="GET" action="{{ route('cross-section') }}" class="row g-3 align-items-end">
            <div class="col-md-6">
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
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun Analisis:</label>
                <select name="year" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Indicator Benchmark Info Badges -->
<div class="d-flex gap-3 mb-4">
    <div class="badge bg-primary fs-6 p-2">Rata-rata Provinsi ({{ $selectedYear }}): {{ number_format($provValue, 2) }} {{ $indicatorObj->unit ?? '' }}</div>
    <div class="badge bg-dark fs-6 p-2">Rata-rata Nasional ({{ $selectedYear }}): {{ number_format($natValue, 2) }} {{ $indicatorObj->unit ?? '' }}</div>
</div>

<!-- BARIS 1: Grafik Kab/Kota & Tabel Peringkat -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between">
                <span>📊 Grafik Perbandingan Kabupaten/Kota (Tahun {{ $selectedYear }})</span>
                <span class="badge bg-info text-dark">{{ $indicatorObj->name ?? '' }}</span>
            </div>
            <div class="card-body">
                <canvas id="crossSectionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">🏆 Tabel Peringkat Wilayah ({{ $selectedYear }})</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="text-center">Rank</th><th>Kabupaten / Kota</th><th class="text-end">Capaian</th></tr>
                    </thead>
                    <tbody>
                        @foreach($crossSectionData as $index => $row)
                            <tr>
                                <td class="text-center"><span class="badge bg-secondary rounded-circle">{{ $index + 1 }}</span></td>
                                <td>{{ $row->regency->name }}</td>
                                <td class="text-end fw-bold text-primary">{{ number_format($row->value, 2) }} {{ $indicatorObj->unit ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- BARIS 2: Grafik Kurva Khusus Provinsi vs Nasional -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3">
                📈 Kurva Tren & Perbandingan: Provinsi vs Nasional (2025–2035)
            </div>
            <div class="card-body">
                <div style="height: 350px;">
                    <canvas id="benchmarkCurveChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data Grafik 1 (Kabupaten/Kota)
    const crossSectionData = JSON.parse('{!! json_encode($crossSectionData) !!}');
    const regencyNames     = crossSectionData.map(item => item.regency.name);
    const crossValues      = crossSectionData.map(item => item.value);

    // Data Grafik 2 (Kurva Provinsi vs Nasional)
    const years      = JSON.parse('{!! json_encode($years) !!}');
    const provTrend  = JSON.parse('{!! json_encode(array_values($provTrend->toArray())) !!}');
    const natTrend   = JSON.parse('{!! json_encode(array_values($natTrend->toArray())) !!}');

    // 1. Render Grafik Batang 7 Kab/Kota (Biru Murni)
    new Chart(document.getElementById('crossSectionChart'), {
        type: 'bar',
        data: {
            labels: regencyNames,
            datasets: [{
                label: 'Capaian Kab/Kota',
                data: crossValues,
                backgroundColor: '#0d6efd',
                borderRadius: 4,
                barThickness: 22
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Render Grafik Kurva Melengkung Provinsi vs Nasional
    new Chart(document.getElementById('benchmarkCurveChart'), {
        type: 'line',
        data: {
            labels: years,
            datasets: [
                {
                    label: 'Prov. Kep. Bangka Belitung',
                    data: provTrend,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.08)',
                    borderWidth: 3,
                    tension: 0.4, // Membuat garis kurva melengkung halus
                    fill: true
                },
                {
                    label: 'Rata-Rata Nasional',
                    data: natTrend,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.08)',
                    borderWidth: 3,
                    borderDash: [5, 5],
                    tension: 0.4, // Membuat garis kurva melengkung halus
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: '{{ $indicatorObj->name ?? "Capaian" }} ({{ $indicatorObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                }
            }
        }
    });
</script>
@endsection