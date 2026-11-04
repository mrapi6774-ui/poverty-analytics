@extends('layouts.app')

@section('title', 'Trend Waktu Indikator & APBD')
@section('page-title', '📈 Analisis Trend Waktu (2025–2035)')

@section('content')
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body bg-white rounded">
        <form method="GET" action="{{ route('time-series') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Pilih Wilayah / Kab-Kota:</label>
                <select name="regency_id" class="form-select">
                    @foreach($regencies as $reg)
                        <option value="{{ $reg->id }}" {{ $selectedRegency == $reg->id ? 'selected' : '' }}>
                            {{ $reg->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
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
                <button type="submit" class="btn btn-primary w-100 fw-bold">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white fw-bold py-3">📈 Trend Perkembangan Indikator</div>
            <div class="card-body"><canvas id="indicatorChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white fw-bold py-3">💰 Trend Total Anggaran APBD (Juta Rp)</div>
            <div class="card-body"><canvas id="apbdChart"></canvas></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const timeSeriesData = JSON.parse('{!! json_encode($timeSeriesData) !!}');
    const apbdData       = JSON.parse('{!! json_encode($apbdData) !!}');

    const years = timeSeriesData.map(item => item.year);
    const indicatorValues = timeSeriesData.map(item => item.value);
    const apbdValues = apbdData.map(item => item.total_budget);

    new Chart(document.getElementById('indicatorChart'), {
        type: 'line',
        data: { labels: years, datasets: [{ label: 'Nilai Indikator', data: indicatorValues, borderColor: '#0d6efd', backgroundColor: 'rgba(13, 110, 253, 0.1)', fill: true, tension: 0.3 }] },
        options: { responsive: true }
    });

    new Chart(document.getElementById('apbdChart'), {
        type: 'bar',
        data: { labels: years, datasets: [{ label: 'Total APBD (Juta Rp)', data: apbdValues, backgroundColor: '#198754' }] },
        options: { responsive: true }
    });
</script>
@endsection