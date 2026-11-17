@extends('layouts.app')

@section('title', 'Kelola Data Master Referensi')
@section('page-title', '🛠️ Kelola Data Master Referensi Publik')

@section('content')

@php
    $activeTab = request('active_tab', 'regency');
@endphp

@if(session('success_master'))
    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_master') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Nav Tabs 5 Data Master -->
<ul class="nav nav-pills mb-4 gap-2" role="tablist">
    <li class="nav-item">
        <button class="nav-link {{ $activeTab == 'regency' ? 'active' : '' }} fw-bold" data-bs-toggle="tab" data-bs-target="#tab-regency" type="button">
            <i class="bi bi-geo-alt-fill me-1"></i> Wilayah ({{ $regencies->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab == 'indicator' ? 'active' : '' }} fw-bold" data-bs-toggle="tab" data-bs-target="#tab-indicator" type="button">
            <i class="bi bi-graph-up me-1"></i> Indikator ({{ $indicators->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab == 'sector' ? 'active' : '' }} fw-bold" data-bs-toggle="tab" data-bs-target="#tab-sector" type="button">
            <i class="bi bi-layers-fill me-1"></i> Sektor / Urusan ({{ $sectors->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab == 'program' ? 'active' : '' }} fw-bold" data-bs-toggle="tab" data-bs-target="#tab-program" type="button">
            <i class="bi bi-diagram-2-fill me-1"></i> Program & Kegiatan ({{ $programs->count() }})
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link {{ $activeTab == 'account' ? 'active' : '' }} fw-bold" data-bs-toggle="tab" data-bs-target="#tab-account" type="button">
            <i class="bi bi-journal-text me-1"></i> Akun APBD ({{ $accounts->count() }})
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB 1: MASTER WILAYAH -->
    <div class="tab-pane fade {{ $activeTab == 'regency' ? 'show active' : '' }}" id="tab-regency">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Tambah Wilayah Baru</div>
                    <div class="card-body">
                        <form action="{{ route('master-data.store-regency') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Wilayah:</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Kab. Belitung Timur" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tingkat Wilayah:</label>
                                <select name="type" class="form-select" required>
                                    <option value="regency">Kabupaten / Kota (Regency)</option>
                                    <option value="province">Provinsi (Province)</option>
                                    <option value="national">Nasional (Pembanding)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Wilayah</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">📋 Daftar Master Wilayah</div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Wilayah</th>
                                    <th>Tingkat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($regencies as $reg)
                                    <tr>
                                        <td class="fw-bold">{{ $reg->id }}</td>
                                        <td>{{ $reg->name }}</td>
                                        <td><span class="badge bg-secondary">{{ strtoupper($reg->type) }}</span></td>
                                        <td class="text-center">
                                            <form action="{{ route('master-data.destroy-regency', $reg->id) }}" method="POST" onsubmit="return confirm('Hapus wilayah ini? Semua data terkait wilayah ini juga akan terhapus!')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
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

    <!-- TAB 2: MASTER INDIKATOR -->
    <div class="tab-pane fade {{ $activeTab == 'indicator' ? 'show active' : '' }}" id="tab-indicator">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Tambah / Update Indikator</div>
                    <div class="card-body">
                        <form action="{{ route('master-data.store-indicator') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Indikator:</label>
                                <input type="text" name="code" class="form-control" placeholder="Contoh: P0, IPM, RLS" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Indikator:</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Persentase Penduduk Miskin" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Satuan:</label>
                                <input type="text" name="unit" class="form-control" placeholder="Contoh: %, Tahun, Poin" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sektor Terkait (Opsional):</label>
                                <select name="sector_id" class="form-select">
                                    <option value="">-- Umum / Lintas Sektor --</option>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Indikator</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">📋 Daftar Master Indikator</div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Indikator</th>
                                    <th>Satuan</th>
                                    <th>Sektor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($indicators as $ind)
                                    <tr>
                                        <td class="font-monospace fw-bold">{{ $ind->code }}</td>
                                        <td>{{ $ind->name }}</td>
                                        <td><span class="badge bg-info text-dark">{{ $ind->unit }}</span></td>
                                        <td>{{ $ind->sector->name ?? '-' }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('master-data.destroy-indicator', $ind->id) }}" method="POST" onsubmit="return confirm('Hapus master indikator ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
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

    <!-- TAB 3: MASTER SEKTOR / URUSAN -->
    <div class="tab-pane fade {{ $activeTab == 'sector' ? 'show active' : '' }}" id="tab-sector">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Tambah Sektor / Urusan</div>
                    <div class="card-body">
                        <form action="{{ route('master-data.store-sector') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Sektor / Urusan:</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Pariwisata & UMKM" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Sektor</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">📋 Daftar Master Sektor</div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Sektor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectors as $sec)
                                    <tr>
                                        <td class="fw-bold">{{ $sec->id }}</td>
                                        <td>{{ $sec->name }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('master-data.destroy-sector', $sec->id) }}" method="POST" onsubmit="return confirm('Hapus sektor ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
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

    <!-- TAB 4: MASTER PROGRAM & SUB-KEGIATAN -->
    <div class="tab-pane fade {{ $activeTab == 'program' ? 'show active' : '' }}" id="tab-program">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Tambah Program / Sub-Kegiatan</div>
                    <div class="card-body">
                        <form action="{{ route('master-data.store-program') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sektor Urusan:</label>
                                <select name="sector_id" class="form-select" required>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Level Hierarki:</label>
                                <select name="level" class="form-select" required>
                                    <option value="1">Level 1 — Program Utama</option>
                                    <option value="2">Level 2 — Sub-Kegiatan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Induk Program (Khusus Level 2):</label>
                                <select name="parent_id" class="form-select">
                                    <option value="">-- Pilih Program Induk --</option>
                                    @foreach($parentProgs as $pp)
                                        <option value="{{ $pp->id }}">{{ $pp->code }} - {{ $pp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Rekening:</label>
                                <input type="text" name="code" class="form-control" placeholder="Contoh: 1.01.02.2.01.01" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Program / Sub-Kegiatan:</label>
                                <textarea name="name" rows="2" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Program / Kegiatan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">📋 Daftar Master Program & Sub-Kegiatan</div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                            <table class="table table-hover table-sm align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Level</th>
                                        <th>Kode</th>
                                        <th>Uraian Program / Sub-Kegiatan</th>
                                        <th>Sektor</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($programs as $prog)
                                        <tr class="{{ $prog->level == 1 ? 'table-primary fw-bold' : '' }}">
                                            <td><span class="badge {{ $prog->level == 1 ? 'bg-primary' : 'bg-secondary' }}">Lv {{ $prog->level }}</span></td>
                                            <td class="font-monospace small">{{ $prog->code }}</td>
                                            <td>{{ $prog->name }}</td>
                                            <td class="small">{{ $prog->sector->name ?? '-' }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('master-data.destroy-program', $prog->id) }}" method="POST" onsubmit="return confirm('Hapus program/kegiatan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
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
    </div>

    <!-- TAB 5: MASTER AKUN RINGKASAN APBD -->
    <div class="tab-pane fade {{ $activeTab == 'account' ? 'show active' : '' }}" id="tab-account">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">➕ Tambah Akun Ringkasan APBD</div>
                    <div class="card-body">
                        <form action="{{ route('master-data.store-account') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Kelompok:</label>
                                <select name="type" class="form-select" required>
                                    <option value="pendapatan">Pendapatan Daerah (4)</option>
                                    <option value="belanja_tidak_langsung">Belanja Tidak Langsung (5.1)</option>
                                    <option value="pembiayaan">Pembiayaan Daerah (6)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Kategori Induk:</label>
                                <input type="text" name="category_code" class="form-control" placeholder="Contoh: 4.1, 5.1, 6.1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Kategori Induk:</label>
                                <input type="text" name="category_name" class="form-control" placeholder="Contoh: Pendapatan Asli Daerah (PAD)" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kode Akun Rincian:</label>
                                <input type="text" name="code" class="form-control" placeholder="Contoh: 4.1.5" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Uraian Akun:</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Akun APBD</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold py-3">📋 Daftar Master Akun APBD</div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                            <table class="table table-hover table-sm align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Kategori</th>
                                        <th>Uraian Akun</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accounts as $acc)
                                        <tr>
                                            <td class="font-monospace fw-bold">{{ $acc->code }}</td>
                                            <td class="small text-muted">{{ $acc->category_name }}</td>
                                            <td>{{ $acc->name }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('master-data.destroy-account', $acc->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
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
    </div>
</div>

@endsection