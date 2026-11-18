@extends('layouts.app')

@section('title', 'Analisis Korelasi (Scatter Plot)')
@section('page-title', '🔵 Analisis Korelasi Spasial (Scatter Plot)')

@section('content')
<!-- Form Filter Dua Variabel -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body bg-white rounded">
        <form method="GET" action="{{ route('scatter-plot') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Variabel X (Sumbu Horizontal):</label>
                <select name="indicator_x" class="form-select">
                    @foreach($sectors as $sec)
                        <optgroup label="{{ $sec->name }}">
                            @foreach($sec->indicators as $ind)
                                <option value="{{ $ind->id }}" {{ $selectedIndX == $ind->id ? 'selected' : '' }}>
                                    {{ $ind->name }} ({{ $ind->unit }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Variabel Y (Sumbu Vertikal):</label>
                <select name="indicator_y" class="form-select">
                    @foreach($sectors as $sec)
                        <optgroup label="{{ $sec->name }}">
                            @foreach($sec->indicators as $ind)
                                <option value="{{ $ind->id }}" {{ $selectedIndY == $ind->id ? 'selected' : '' }}>
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
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="bi bi-diagram-3-fill me-1"></i> Tampilkan Korelasi Spasial
                </button>
            </div>
        </form>
    </div>
</div>

<!-- BARIS 1: Chart Scatter Plot & Tabel Koordinat -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span>📍 Grafik Sebaran Korelasi Kab/Kota ({{ $selectedYear }})</span>
            </div>
            <div class="card-body">
                <div style="height: 400px;">
                    <canvas id="scatterChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                📋 Rincian Koordinat Wilayah (Tahun {{ $selectedYear }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <<thead class="table-light">
                            <tr>
                                <th class="ps-3 text-nowrap align-bottom fw-bold text-dark" style="width: 32%; font-size: 0.85rem;">
                                    Kabupaten / Kota
                                </th>
                                <th class="text-end align-bottom" style="width: 34%;">
                                    <span class="badge bg-primary mb-1" style="font-size: 0.72rem;">X ({{ $indXObj->unit ?? '-' }})</span>
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.82rem;" title="{{ $indXObj->name ?? 'Var X' }}">
                                        {{ $indXObj->name ?? 'Var X' }}
                                    </div>
                                </th>
                                <th class="text-end pe-3 align-bottom" style="width: 34%;">
                                    <span class="badge bg-danger mb-1" style="font-size: 0.72rem;">Y ({{ $indYObj->unit ?? '-' }})</span>
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.82rem;" title="{{ $indYObj->name ?? 'Var Y' }}">
                                        {{ $indYObj->name ?? 'Var Y' }}
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tableData as $row)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark text-nowrap">{{ $row['regency_name'] }}</td>
                                    <td class="text-end text-primary fw-bold text-nowrap" style="font-variant-numeric: tabular-nums;">
                                        {{ number_format($row['val_x'], 2, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-3 text-danger fw-bold text-nowrap" style="font-variant-numeric: tabular-nums;">
                                        {{ number_format($row['val_y'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Data koordinat wilayah belum tersedia.</td>
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
    const scatterPoints = JSON.parse('{!! json_encode($scatterPoints) !!}');

    new Chart(document.getElementById('scatterChart'), {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Sebaran Wilayah Kabupaten/Kota',
                data: scatterPoints,
                backgroundColor: '#0d6efd',
                borderColor: '#0b5ed7',
                borderWidth: 2,
                pointRadius: 7,
                pointHoverRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const point = context.raw;
                            return `${point.label} → X: ${point.x.toFixed(2)}, Y: ${point.y.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Variabel X: {{ $indXObj->name ?? "Variabel X" }} ({{ $indXObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Variabel Y: {{ $indYObj->name ?? "Variabel Y" }} ({{ $indYObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                }
            }
        }
    });
</script>
@endsection