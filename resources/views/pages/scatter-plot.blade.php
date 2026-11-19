@extends('layouts.app')

@section('title', 'Analisis Korelasi & Kuadran Prioritas')
@section('page-title', '🔵 Analisis Korelasi & Kuadran Prioritas Wilayah')

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
                    <i class="bi bi-diagram-3-fill me-1"></i> Tampilkan Korelasi & Kuadran Wilayah
                </button>
            </div>
        </form>
    </div>
</div>

<!-- KARTU RINGKASAN STATISTIK KORELASI & THRESHOLD KUADRAN -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-{{ $corrBadge }}">
            <div class="card-body py-3">
                <div class="text-muted small fw-semibold text-uppercase">Koefisien Korelasi Pearson (r)</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="fs-4 fw-bold text-dark">{{ number_format($correlationR, 2, ',', '.') }}</span>
                    <span class="badge bg-{{ $corrBadge }}">{{ $corrLabel }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
            <div class="card-body py-3">
                <div class="text-muted small fw-semibold text-uppercase">Garis Batas Rata-Rata X</div>
                <div class="fs-4 fw-bold text-primary mt-1">
                    {{ number_format($avgX, 2, ',', '.') }} <small class="fs-6 text-muted fw-normal">{{ $indXObj->unit ?? '' }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
            <div class="card-body py-3">
                <div class="text-muted small fw-semibold text-uppercase">Garis Batas Rata-Rata Y</div>
                <div class="fs-4 fw-bold text-danger mt-1">
                    {{ number_format($avgY, 2, ',', '.') }} <small class="fs-6 text-muted fw-normal">{{ $indYObj->unit ?? '' }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BARIS UTAMA: Chart Scatter Plot Kuadran & Tabel Koordinat -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span>📍 Grafik Kuadran Prioritas Wilayah ({{ $selectedYear }})</span>
                <span class="badge bg-light text-dark border small">Garis Putus = Rata-rata</span>
            </div>
            <div class="card-body">
                <div style="height: 410px;">
                    <canvas id="scatterChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                📋 Rincian Koordinat & Kuadran Wilayah (Tahun {{ $selectedYear }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-nowrap align-bottom fw-bold text-dark">
                                    Kabupaten / Kota
                                </th>
                                <th class="text-end align-bottom">
                                    <span class="badge bg-primary mb-1" style="font-size: 0.68rem;">X ({{ $indXObj->unit ?? '-' }})</span>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 120px; margin-left: auto; font-size: 0.76rem;" title="{{ $indXObj->name ?? 'Var X' }}">
                                        {{ $indXObj->name ?? 'Var X' }}
                                    </div>
                                </th>
                                <th class="text-end align-bottom">
                                    <span class="badge bg-danger mb-1" style="font-size: 0.68rem;">Y ({{ $indYObj->unit ?? '-' }})</span>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 120px; margin-left: auto; font-size: 0.76rem;" title="{{ $indYObj->name ?? 'Var Y' }}">
                                        {{ $indYObj->name ?? 'Var Y' }}
                                    </div>
                                </th>
                                <th class="text-center pe-3 align-bottom fw-bold text-dark" style="font-size: 0.78rem;">
                                    Kuadran
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
                                    <td class="text-end text-danger fw-bold text-nowrap" style="font-variant-numeric: tabular-nums;">
                                        {{ number_format($row['val_y'], 2, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-3 text-nowrap">
                                        <span class="badge bg-{{ $row['q_badge'] }} bg-opacity-10 text-{{ $row['q_badge'] }} border border-{{ $row['q_badge'] }}" style="font-size: 0.72rem;">
                                            {{ $row['quadrant'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Data koordinat wilayah belum tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light small text-muted py-2">
                <strong>Keterangan Kuadran:</strong>
                <span class="text-danger fw-semibold">I</span> (X Tinggi, Y Tinggi) &bull;
                <span class="text-warning fw-semibold">II</span> (X Rendah, Y Tinggi) &bull;
                <span class="text-success fw-semibold">III</span> (X Rendah, Y Rendah) &bull;
                <span class="text-info fw-semibold">IV</span> (X Tinggi, Y Rendah)
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const scatterPoints = JSON.parse('{!! json_encode($scatterPoints) !!}');
    const avgX = Number("{{ (float) $avgX }}");
    const avgY = Number("{{ (float) $avgY }}");

    // Plugin khusus untuk menggambar garis silang kuadran rata-rata & label titik wilayah
    const quadrantPlugin = {
        id: 'quadrantPlugin',
        beforeDatasetsDraw(chart) {
            const { ctx, chartArea: { left, right, top, bottom }, scales: { x, y } } = chart;
            const xPixel = x.getPixelForValue(avgX);
            const yPixel = y.getPixelForValue(avgY);

            ctx.save();
            ctx.strokeStyle = '#64748b';
            ctx.lineWidth = 1.5;
            ctx.setLineDash([6, 4]);

            // Garis Vertikal Rata-rata X
            if (xPixel >= left && xPixel <= right) {
                ctx.beginPath();
                ctx.moveTo(xPixel, top);
                ctx.lineTo(xPixel, bottom);
                ctx.stroke();
            }

            // Garis Horizontal Rata-rata Y
            if (yPixel >= top && yPixel <= bottom) {
                ctx.beginPath();
                ctx.moveTo(left, yPixel);
                ctx.lineTo(right, yPixel);
                ctx.stroke();
            }

            // Label Kuadran di 4 Sudut Grafik
            ctx.setLineDash([]);
            ctx.font = 'bold 11px Segoe UI, sans-serif';
            ctx.fillStyle = 'rgba(100, 116, 139, 0.75)';
            ctx.fillText('KUADRAN II (X < Avg, Y ≥ Avg)', left + 8, top + 16);
            ctx.textAlign = 'right';
            ctx.fillText('KUADRAN I (X ≥ Avg, Y ≥ Avg)', right - 8, top + 16);
            ctx.fillText('KUADRAN IV (X ≥ Avg, Y < Avg)', right - 8, bottom - 8);
            ctx.textAlign = 'left';
            ctx.fillText('KUADRAN III (X < Avg, Y < Avg)', left + 8, bottom - 8);

            ctx.restore();
        },
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            const meta = chart.getDatasetMeta(0);

            ctx.save();
            ctx.font = '600 11px Segoe UI, sans-serif';
            ctx.fillStyle = '#1e293b';

            meta.data.forEach((element, index) => {
                const point = scatterPoints[index];
                if (point) {
                    const shortName = point.label.replace('Kab. ', '').replace('Kota ', '');
                    ctx.fillText(shortName, element.x + 9, element.y + 4);
                }
            });

            ctx.restore();
        }
    };

    new Chart(document.getElementById('scatterChart'), {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Kabupaten / Kota',
                data: scatterPoints,
                backgroundColor: scatterPoints.map(p => {
                    if (p.quadrant === 'Kuadran I') return '#dc3545';
                    if (p.quadrant === 'Kuadran II') return '#ffc107';
                    if (p.quadrant === 'Kuadran III') return '#198754';
                    return '#0dcaf0';
                }),
                borderColor: '#0f172a',
                borderWidth: 1.5,
                pointRadius: 7,
                pointHoverRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { right: 75, top: 10 }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const point = context.raw;
                            return `${point.label} (${point.quadrant}) → X: ${point.x.toFixed(2)}, Y: ${point.y.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grace: '10%',
                    title: {
                        display: true,
                        text: 'Variabel X: {{ $indXObj->name ?? "Variabel X" }} ({{ $indXObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                },
                y: {
                    grace: '12%',
                    title: {
                        display: true,
                        text: 'Variabel Y: {{ $indYObj->name ?? "Variabel Y" }} ({{ $indYObj->unit ?? "" }})',
                        font: { weight: 'bold' }
                    }
                }
            }
        },
        plugins: [quadrantPlugin]
    });
</script>
@endsection