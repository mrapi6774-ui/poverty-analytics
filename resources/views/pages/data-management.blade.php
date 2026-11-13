@extends('layouts.app')

@section('title', 'Kelola Data Indikator & APBD')
@section('page-title', '⚙️ Kelola Data Indikator Makro & APBD')

@section('content')

@php
    if (request('active_tab')) {
        $activeTab = request('active_tab');
    } elseif (request()->has('account_page')) {
        $activeTab = 'account';
    } elseif (request()->has('program_page')) {
        $activeTab = 'program';
    } elseif (request()->hasAny(['search_apbd_regency', 'search_sector', 'search_apbd_year', 'apbd_page'])) {
        $activeTab = 'apbd';
    } else {
        $activeTab = 'indicator';
    }

    $urusanLabels = [
        'Kemiskinan Makro'           => 'Urusan Sosial (1.13)',
        'Pendidikan'                 => 'Urusan Pendidikan (1.01)',
        'Kesehatan'                  => 'Urusan Kesehatan (1.02)',
        'Infrastruktur & Perumahan'  => 'Urusan Infrastruktur (1.03)',
        'Ketenagakerjaan & Ekonomi'  => 'Urusan Pilihan (2.01)',
    ];
@endphp

<!-- Nav Tabs Dinamis -->
<ul class="nav nav-pills mb-4 gap-2" id="dataTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab == 'indicator' ? 'active' : '' }} fw-bold px-3" id="indicator-tab" data-bs-toggle="tab" data-bs-target="#indicator-pane" type="button" role="tab">
            <i class="bi bi-bar-chart-line me-1"></i> Indikator Makro
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab == 'account' ? 'active' : '' }} fw-bold px-3" id="account-tab" data-bs-toggle="tab" data-bs-target="#account-pane" type="button" role="tab">
            <i class="bi bi-cash-coin me-1"></i> Pendapatan, Belanja 5.1 & Pembiayaan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab == 'apbd' ? 'active' : '' }} fw-bold px-3" id="apbd-tab" data-bs-toggle="tab" data-bs-target="#apbd-pane" type="button" role="tab">
            <i class="bi bi-wallet2 me-1"></i> Total APBD per Urusan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ $activeTab == 'program' ? 'active' : '' }} fw-bold px-3" id="program-tab" data-bs-toggle="tab" data-bs-target="#program-pane" type="button" role="tab">
            <i class="bi bi-list-nested me-1"></i> Program & Kegiatan (5.2)
        </button>
    </li>
</ul>

<div class="tab-content" id="dataTabContent">
    <!-- TAB 1: KELOLA INDIKATOR MAKRO -->
    <div class="tab-pane fade {{ $activeTab == 'indicator' ? 'show active' : '' }}" id="indicator-pane" role="tabpanel">
        @if(session('success_indicator'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_indicator') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Input / Update Indikator</div>
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

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-3 bg-light">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('data-management') }}" class="row g-2 align-items-end">
                            <input type="hidden" name="active_tab" value="indicator">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-semibold m-0">Cari Wilayah:</label>
                                <select name="search_regency" class="form-select form-select-sm">
                                    <option value="">-- Semua Wilayah --</option>
                                    @foreach($regencies as $reg)
                                        <option value="{{ $reg->id }}" {{ request('search_regency') == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-semibold m-0">Cari Indikator:</label>
                                <select name="search_indicator" class="form-select form-select-sm">
                                    <option value="">-- Semua Indikator --</option>
                                    @foreach($indicators as $ind)
                                        <option value="{{ $ind->id }}" {{ request('search_indicator') == $ind->id ? 'selected' : '' }}>{{ $ind->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label form-label-sm fw-semibold m-0">Tahun:</label>
                                <select name="search_year" class="form-select form-select-sm">
                                    <option value="">-- Semua --</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('search_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold"><i class="bi bi-search"></i> Cari</button>
                                <a href="{{ route('data-management', ['active_tab' => 'indicator']) }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                            </div>
                        </form>
                    </div>
                </div>

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
                                    @forelse($indicatorDataList as $row)
                                        <tr>
                                            <td class="fw-bold">{{ $row->year }}</td>
                                            <td>{{ $row->regency->name }}</td>
                                            <td>{{ $row->indicator->name }}</td>
                                            <td class="text-end fw-bold text-primary">{{ number_format($row->value, 2) }} {{ $row->indicator->unit }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('data-management.destroy-indicator', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Data tidak ditemukan sesuai filter pencarian.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white pt-3">{{ $indicatorDataList->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: KELOLA PENDAPATAN (4), BELANJA TIDAK LANGSUNG (5.1) & PEMBIAYAAN (6) -->
    <div class="tab-pane fade {{ $activeTab == 'account' ? 'show active' : '' }}" id="account-pane" role="tabpanel">
        @if(session('success_account'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_account') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Input / Update Akun Ringkasan APBD</div>
                    <div class="card-body">
                        <form action="{{ route('data-management.store-account') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wilayah / Daerah:</label>
                                <select name="regency_id" class="form-select" required>
                                    @foreach($apbdRegencies as $reg)
                                        <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode & Uraian Akun:</label>
                                <select name="account_id" class="form-select" required>
                                    @foreach($apbdAccounts->groupBy('category_name') as $catName => $items)
                                        <optgroup label="{{ $catName }}">
                                            @foreach($items as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                                            @endforeach
                                        </optgroup>
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
                                <label class="form-label fw-semibold">Nominal Anggaran (Juta Rp):</label>
                                <input type="number" step="0.01" min="0" name="amount" class="form-control" placeholder="Contoh: 250000" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Akun APBD</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>📋 Daftar Pendapatan, Belanja 5.1 & Pembiayaan Tersimpan</span>
                        <span class="badge bg-primary">Total: {{ $accountRecordList->total() }} Record</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Wilayah</th>
                                        <th>Kelompok</th>
                                        <th>Kode & Uraian Akun</th>
                                        <th class="text-end">Nominal (Juta Rp)</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accountRecordList as $row)
                                        <tr>
                                            <td class="fw-bold">{{ $row->year }}</td>
                                            <td>{{ $row->regency->name }}</td>
                                            <td>
                                                @if($row->account->type === 'pendapatan')
                                                    <span class="badge bg-success">Pendapatan (4)</span>
                                                @elseif($row->account->type === 'belanja_tidak_langsung')
                                                    <span class="badge bg-warning text-dark">Belanja 5.1</span>
                                                @else
                                                    <span class="badge bg-info text-dark">Pembiayaan (6)</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted font-monospace d-block">{{ $row->account->code }} ({{ $row->account->category_name }})</small>
                                                <span class="fw-semibold">{{ $row->account->name }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-primary">Rp {{ number_format($row->amount, 2, ',', '.') }} Jt</td>
                                            <td class="text-center">
                                                <form action="{{ route('data-management.destroy-account', $row->id) }}" method="POST" onsubmit="return confirm('Hapus data akun APBD ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data Pendapatan, Belanja Tidak Langsung (5.1), atau Pembiayaan yang diinput.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white pt-3">{{ $accountRecordList->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: KELOLA TOTAL ANGGARAN APBD PER URUSAN -->
    <div class="tab-pane fade {{ $activeTab == 'apbd' ? 'show active' : '' }}" id="apbd-pane" role="tabpanel">
        @if(session('success_apbd'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_apbd') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Input / Update Total APBD Urusan</div>
                    <div class="card-body">
                        <form action="{{ route('data-management.store-apbd') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wilayah / Daerah:</label>
                                <select name="regency_id" class="form-select" required>
                                    @foreach($apbdRegencies as $reg)
                                        <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sektor Urusan Belanja:</label>
                                <select name="sector_id" class="form-select" required>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}">{{ $urusanLabels[$sec->name] ?? $sec->name }}</option>
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

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-3 bg-light">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('data-management') }}" class="row g-2 align-items-end">
                            <input type="hidden" name="active_tab" value="apbd">
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-semibold m-0">Cari Daerah:</label>
                                <select name="search_apbd_regency" class="form-select form-select-sm">
                                    <option value="">-- Semua Daerah --</option>
                                    @foreach($apbdRegencies as $reg)
                                        <option value="{{ $reg->id }}" {{ request('search_apbd_regency') == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm fw-semibold m-0">Cari Urusan Belanja:</label>
                                <select name="search_sector" class="form-select form-select-sm">
                                    <option value="">-- Semua Urusan --</option>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}" {{ request('search_sector') == $sec->id ? 'selected' : '' }}>{{ $urusanLabels[$sec->name] ?? $sec->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label form-label-sm fw-semibold m-0">Tahun:</label>
                                <select name="search_apbd_year" class="form-select form-select-sm">
                                    <option value="">-- Semua --</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('search_apbd_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-sm btn-success w-100 fw-bold"><i class="bi bi-search"></i> Cari</button>
                                <a href="{{ route('data-management', ['active_tab' => 'apbd']) }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>📋 Daftar Realisasi APBD per Urusan</span>
                        <span class="badge bg-secondary">Total: {{ $apbdDataList->total() }} Record</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Wilayah</th>
                                        <th>Urusan Belanja</th>
                                        <th class="text-end">Total Anggaran</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($apbdDataList as $row)
                                        <tr>
                                            <td class="fw-bold">{{ $row->year }}</td>
                                            <td>{{ $row->regency->name }}</td>
                                            <td>{{ $urusanLabels[$row->sector->name] ?? $row->sector->name }}</td>
                                            <td class="text-end fw-bold text-success">Rp {{ number_format($row->total_expense, 0, ',', '.') }} Jt</td>
                                            <td class="text-center">
                                                <form action="{{ route('data-management.destroy-apbd', $row->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Data anggaran tidak ditemukan sesuai filter pencarian.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white pt-3">{{ $apbdDataList->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 4: KELOLA RINCIAN PROGRAM & SUB-KEGIATAN (BELANJA LANGSUNG 5.2) -->
    <div class="tab-pane fade {{ $activeTab == 'program' ? 'show active' : '' }}" id="program-pane" role="tabpanel">
        @if(session('success_program'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_program') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Input Anggaran Sub-Kegiatan</div>
                    <div class="card-body">
                        <form action="{{ route('data-management.store-program') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Wilayah / Daerah:</label>
                                <select name="regency_id" class="form-select" required>
                                    @foreach($apbdRegencies as $reg)
                                        <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Sub-Kegiatan (Level 2):</label>
                                <select name="program_id" class="form-select" required>
                                    @foreach($subPrograms as $sub)
                                        @php
                                            $secLabel = $urusanLabels[$sub->sector->name ?? ''] ?? ($sub->sector->name ?? '-');
                                        @endphp
                                        <option value="{{ $sub->id }}">[{{ $secLabel }}] {{ $sub->code }} - {{ $sub->name }}</option>
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
                            <div class="mb-2">
                                <label class="form-label fw-semibold">B. Pegawai (Juta Rp):</label>
                                <input type="number" step="0.01" min="0" name="pegawai_expense" class="form-control" value="0" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-semibold">B. Barang & Jasa (Juta Rp):</label>
                                <input type="number" step="0.01" min="0" name="barang_jasa_expense" class="form-control" value="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">B. Modal (Juta Rp):</label>
                                <input type="number" step="0.01" min="0" name="modal_expense" class="form-control" value="0" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 fw-bold">Simpan & Sinkronkan Anggaran</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                        <span>📋 Rincian Anggaran Sub-Kegiatan Tersimpan</span>
                        <span class="badge bg-dark">Total: {{ $programDataList->total() }} Record</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Wilayah</th>
                                        <th>Kode & Sub-Kegiatan</th>
                                        <th class="text-end">B. Pegawai</th>
                                        <th class="text-end">B. Barjas</th>
                                        <th class="text-end">B. Modal</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($programDataList as $row)
                                        <tr>
                                            <td class="fw-bold">{{ $row->year }}</td>
                                            <td>{{ $row->regency->name }}</td>
                                            <td>
                                                <small class="text-muted font-monospace d-block">{{ $row->program->code ?? '-' }}</small>
                                                <span class="fw-semibold">{{ $row->program->name ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($row->pegawai_expense, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($row->barang_jasa_expense, 0, ',', '.') }}</td>
                                            <td class="text-end">{{ number_format($row->modal_expense, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold text-primary">Rp {{ number_format($row->total_expense, 0, ',', '.') }} Jt</td>
                                            <td class="text-center">
                                                <form action="{{ route('data-management.destroy-program', $row->id) }}" method="POST" onsubmit="return confirm('Hapus rincian anggaran kegiatan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">Belum ada rincian anggaran sub-kegiatan yang diinput.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white pt-3">{{ $programDataList->links('pagination::bootstrap-5') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection