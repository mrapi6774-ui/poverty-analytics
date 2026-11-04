@extends('layouts.app')

@section('title', 'Analisis Korelasi Scatter Plot')
@section('page-title', '🔵 Analisis Korelasi / Hubungan Indikator (Scatter Plot)')

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('scatter-plot') }}" class="row g-3 bg-light p-3 rounded align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sumbu X (Determinan):</label>
                <select name="indicator_x" class="form-select">
                    @foreach($indicators as $ind)
                        <option value="{{ $ind->id }}" {{ $selectedIndicatorX == $ind->id ? 'selected' : '' }}>
                            {{ $ind->name }} ({{ $ind->unit }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sumbu Y (Capaian):</label>
                <select name="indicator_y" class="form-select">
                    @foreach($indicators as $ind)
                        <option value="{{ $ind->id }}" {{ $selectedIndicatorY == $ind->id ? 'selected' : '' }}>
                            {{ $ind->name }} ({{ $ind->unit }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Tahun:</label>
                <select name="year" class="form-select">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Update Chart</button>
            </div>
        </form>

        <div style="height: 450px;" class="mt-4">
            <canvas id="scatterChart"></canvas>
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
                label: 'Sebaran Kabupaten / Kota',
                data: scatterPoints.map(p => ({ x: p.x, y: p.y })),
                backgroundColor: '#dc3545',
                borderColor: '#b02a37',
                pointRadius: 8
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
                x: { title: { display: true, text: '{{ $indicatorXObj->name ?? "Sumbu X" }} ({{ $indicatorXObj->unit ?? "" }})', font: { weight: 'bold' } } },
                y: { title: { display: true, text: '{{ $indicatorYObj->name ?? "Sumbu Y" }} ({{ $indicatorYObj->unit ?? "" }})', font: { weight: 'bold' } } }
            }
        }
    });
</script>
@endsection