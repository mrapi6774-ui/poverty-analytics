@extends('layouts.app')

@section('title', 'Kelola Data Indikator & APBD')
@section('page-title', '⚙️ Kelola Data Indikator Makro & APBD')

@section('content')

<!-- Nav Tabs -->
<ul class="nav nav-pills mb-4" id="dataTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold px-4" id="indicator-tab" data-bs-toggle="tab" data-bs-target="#indicator-pane" type="button" role="tab">
            <i class="bi bi-bar-chart-line me-2"></i> Data Indikator Makro
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4" id="apbd-tab" data-bs-toggle="tab" data-bs-target="#apbd-pane" type="button" role="tab">
            <i class="bi bi-wallet2 me-2"></i> Data Anggaran APBD
        </button>
    </li>
</ul>

<div class="tab-content" id="dataTabContent">
    <!-- TAB 1: KELOLA INDIKATOR MAKRO -->
    <div class="tab-pane fade show active" id="indicator-pane" role="tabpanel">
        @if(session('success_indicator'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_indicator') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Input Indikator -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">
                        ➕ Input / Update Indikator
                    </div>
                    <div class="card-body">
                        <form action="{{ route('data-management.store-indicator') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wilayah (Nasional/Prov/Kab):</label>
                                <select name="regency_id" class="form-select" required>
                                    @foreach($regencies as $reg)
                                        <option value="{{ $reg->id }}">{{ $reg->name }} ({{ strtoupper($reg->type) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Indikator Sektoral:</label>
                                <select name="indicator_id" class="form-select" required>
                                    @foreach($indicators as $ind)
                                        <option value="{{ $ind->id }}">{{ $ind->name }} ({{ $ind->unit }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tahun Periode:</label>
                                <select name="year" class="form-select" required>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nilai Angka Indikator:</label>
                                <input type="number" step="0.01" name="value" class="form-control" placeholder="Contoh: 4.85" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Data Indikator</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Data Indikator -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>📋 Daftar Data Indikator Tersimpan</span>
                        <span class="badge bg-secondary">Total: {{ $indicatorDataList->total() }} Record</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Wilayah</th>
                                        <th>Indikator</th>
                                        <th class="text-end">Capaian</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($indicatorDataList as $row)
                                        <tr>
                                            <td class="fw-bold">{{ $row->year }}</td>
                                            <td>{{ $row->regency->name }}</td>
                                            <td>{{ $row->indicator->name }}</td>
                                            <td class="text-end fw-bold text-primary">{{ number_format($row->value, 2) }} {{ $row->indicator->unit }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('data-management.destroy-indicator', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white pt-3">
                        {{ $indicatorDataList->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: KELOLA ANGGARAN APBD -->
    <div class="tab-pane fade" id="apbd-pane" role="tabpanel">
        @if(session('success_apbd'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_apbd') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Input APBD -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">
                        ➕ Input / Update Anggaran APBD
                    </div>
                    <div class="card-body">
                        <form action="{{ route('data-management.store-apbd') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wilayah / Daerah:</label>
                                <select name="regency_id" class="form-select" required>
                                    @foreach($regencies as $reg)
                                        <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sektor Urusan Belanja:</label>
                                <select name="sector_id" class="form-select" required>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tahun Periode:</label>
                                <select name="year" class="form-select" required>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Total Alokasi Anggaran (Juta Rp):</label>
                                <input type="number" step="0.01" name="total_expense" class="form-control" placeholder="Contoh: 150000" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Data APBD</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Data APBD -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>📋 Daftar Realisasi APBD Tersimpan</span>
                        <span class="badge bg-secondary">Total: {{ $apbdDataList->total() }} Record</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Wilayah</th>
                                        <th>Sektor Belanja</th>
                                        <th class="text-end">Total Anggaran</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($apbdDataList as $row)
                                        <tr>
                                            <td class="fw-bold">{{ $row->year }}</td>
                                            <td>{{ $row->regency->name }}</td>
                                            <td>{{ $row->sector->name }}</td>
                                            <td class="text-end fw-bold text-success">Rp {{ number_format($row->total_expense, 0, ',', '.') }} Jt</td>
                                            <td class="text-center">
                                                <form action="{{ route('data-management.destroy-apbd', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white pt-3">
                        {{ $apbdDataList->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle untuk memfungsikan Nav Tabs -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection