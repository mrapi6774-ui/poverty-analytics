@extends('layouts.app')

@section('title', 'Analisis APBD & Efektivitas')
@section('page-title', '💰 Analisis Komposisi APBD & Efektivitas Belanja')

@section('content')
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body bg-white rounded">
        <form method="GET" action="{{ route('apbd-analysis') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Pilih Wilayah / Kab-Kota:</label>
                <select name="regency_id" class="form-select">
                    @foreach($regencies as $reg)
                        <option value="{{ $reg->id }}" {{ $selectedRegency == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Pilih Indikator Evaluasi Efektivitas:</label>
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
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">📊 Komposisi Belanja Daerah Menurut Sektor</div>
            <div class="card-body"><canvas id="stackedApbdChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">⚡ Tabel Efektivitas Anggaran Sektor</div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 380px;">
                    <table class="table table-hover align-middle mb-0 text-sm">
                        <thead class="table-light sticky-top">
                            <tr><th>Tahun</th><th>Capaian</th><th>$\Delta$ Indikator</th><th>Anggaran Sektor</th><th class="text-center">Status</th></tr>
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
                                        <span class="badge {{ $row['status'] === 'Efektif' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $row['status'] }}
                                        </span>
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
@endsection

@section('scripts')
<script>
    const stackedDatasets = JSON.parse('{!! json_encode($stackedDatasets) !!}');
    const years           = [2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035];

    new Chart(document.getElementById('stackedApbdChart'), {
        type: 'bar',
        data: { labels: years, datasets: stackedDatasets },
        options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true } } }
    });
</script>
@endsection