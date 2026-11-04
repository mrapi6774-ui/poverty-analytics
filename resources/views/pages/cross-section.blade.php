@extends('layouts.app')

@section('title', 'Analisis Perbandingan Antar Wilayah')
@section('page-title', '📊 Analisis Antar Wilayah (Cross-Section)')

@section('content')
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

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between">
                <span>Grafik Perbandingan Kabupaten/Kota</span>
                <span class="badge bg-info text-dark">{{ $indicatorObj->name ?? '' }}</span>
            </div>
            <div class="card-body"><canvas id="crossSectionChart"></canvas></div>
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
@endsection

@section('scripts')
<script>
    const crossSectionData = JSON.parse('{!! json_encode($crossSectionData) !!}');
    const regencyNames     = crossSectionData.map(item => item.regency.name);
    const crossValues      = crossSectionData.map(item => item.value);

    new Chart(document.getElementById('crossSectionChart'), {
        type: 'bar',
        data: {
            labels: regencyNames,
            datasets: [{ label: 'Capaian (Tahun ' + '{{ $selectedYear }}' + ')', data: crossValues, backgroundColor: '#0d6efd' }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endsection