<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regency;
use App\Models\Indicator;
use App\Models\Sector;
use App\Models\RegionalIndicatorData;
use App\Models\ApbdExpenditure;
use App\Models\MasterProgram;
use App\Models\ProgramExpenditure;
use App\Models\MasterApbdAccount;
use App\Models\ApbdAccountRecord;

class DataManagementController extends Controller
{
    public function index(Request $request)
    {
        $regencies     = Regency::all();
        $apbdRegencies = Regency::where('type', 'regency')->get();

        $indicators   = Indicator::all();
        $sectors      = Sector::all();
        $subPrograms  = MasterProgram::with(['sector', 'parent'])
            ->where('level', 2)
            ->orderBy('sector_id')
            ->orderBy('code')
            ->get();
        $apbdAccounts = MasterApbdAccount::orderBy('id')->get();
        $years        = range(2025, 2035);

        // 1. Query Filter Data Indikator
        $indicatorQuery = RegionalIndicatorData::with(['regency', 'indicator']);

        if ($request->filled('search_regency')) {
            $indicatorQuery->where('regency_id', $request->search_regency);
        }
        if ($request->filled('search_indicator')) {
            $indicatorQuery->where('indicator_id', $request->search_indicator);
        }
        if ($request->filled('search_year')) {
            $indicatorQuery->where('year', $request->search_year);
        }

        $indicatorDataList = $indicatorQuery->orderBy('year', 'desc')
            ->orderBy('regency_id', 'asc')
            ->paginate(10, ['*'], 'indicator_page')
            ->withQueryString();

        // 2. Query Filter Data APBD (Hanya Kabupaten/Kota)
        $apbdQuery = ApbdExpenditure::with(['regency', 'sector'])
            ->whereHas('regency', fn($q) => $q->where('type', 'regency'));

        if ($request->filled('search_apbd_regency')) {
            $apbdQuery->where('regency_id', $request->search_apbd_regency);
        }
        if ($request->filled('search_sector')) {
            $apbdQuery->where('sector_id', $request->search_sector);
        }
        if ($request->filled('search_apbd_year')) {
            $apbdQuery->where('year', $request->search_apbd_year);
        }

        $apbdDataList = $apbdQuery->orderBy('year', 'desc')
            ->orderBy('regency_id', 'asc')
            ->paginate(10, ['*'], 'apbd_page')
            ->withQueryString();

        // 3. Query Data Rincian Program / Sub-Kegiatan (Belanja Langsung 5.2)
        $programDataList = ProgramExpenditure::with(['regency', 'program.sector'])
            ->whereHas('regency', fn($q) => $q->where('type', 'regency'))
            ->orderBy('year', 'desc')
            ->orderBy('regency_id', 'asc')
            ->paginate(10, ['*'], 'program_page')
            ->withQueryString();

        // 4. Query Data Akun APBD (Pendapatan 4, Belanja Tidak Langsung 5.1, Pembiayaan 6)
        $accountRecordList = ApbdAccountRecord::with(['regency', 'account'])
            ->whereHas('regency', fn($q) => $q->where('type', 'regency'))
            ->orderBy('year', 'desc')
            ->orderBy('regency_id', 'asc')
            ->paginate(10, ['*'], 'account_page')
            ->withQueryString();

        return view('pages.data-management', compact(
            'regencies', 'apbdRegencies', 'indicators', 'sectors', 'subPrograms', 'apbdAccounts', 'years',
            'indicatorDataList', 'apbdDataList', 'programDataList', 'accountRecordList'
        ));
    }

    // Simpan / Update Data Indikator
    public function storeIndicatorData(Request $request)
    {
        $request->validate([
            'regency_id'   => 'required|exists:master_regencies,id',
            'indicator_id' => 'required|exists:master_indicators,id',
            'year'         => 'required|integer|min:2025|max:2035',
            'value'        => 'required|numeric',
        ]);

        RegionalIndicatorData::updateOrCreate(
            [
                'regency_id'   => $request->regency_id,
                'indicator_id' => $request->indicator_id,
                'year'         => $request->year,
            ],
            [
                'value' => $request->value,
            ]
        );

        return redirect()->route('data-management', ['active_tab' => 'indicator'])
            ->with('success_indicator', 'Data Indikator berhasil disimpan/diperbarui!');
    }

    // Hapus Data Indikator
    public function destroyIndicatorData($id)
    {
        $data = RegionalIndicatorData::findOrFail($id);
        $data->delete();

        return redirect()->route('data-management', ['active_tab' => 'indicator'])
            ->with('success_indicator', 'Data Indikator berhasil dihapus!');
    }

    // Simpan / Update Data Anggaran APBD
    public function storeApbdData(Request $request)
    {
        $request->validate([
            'regency_id'    => 'required|exists:master_regencies,id',
            'sector_id'     => 'required|exists:master_sectors,id',
            'year'          => 'required|integer|min:2025|max:2035',
            'total_expense' => 'required|numeric|min:0',
        ]);

        ApbdExpenditure::updateOrCreate(
            [
                'regency_id' => $request->regency_id,
                'sector_id'  => $request->sector_id,
                'year'       => $request->year,
            ],
            [
                'total_expense' => $request->total_expense,
            ]
        );

        return redirect()->route('data-management', ['active_tab' => 'apbd'])
            ->with('success_apbd', 'Data Anggaran APBD berhasil disimpan/diperbarui!');
    }

    // Hapus Data APBD
    public function destroyApbdData($id)
    {
        $data = ApbdExpenditure::findOrFail($id);
        $data->delete();

        return redirect()->route('data-management', ['active_tab' => 'apbd'])
            ->with('success_apbd', 'Data APBD berhasil dihapus!');
    }

    // Simpan / Update Rincian Anggaran Sub-Kegiatan Program
    public function storeProgramData(Request $request)
    {
        $request->validate([
            'regency_id'          => 'required|exists:master_regencies,id',
            'program_id'          => 'required|exists:master_programs,id',
            'year'                => 'required|integer|min:2025|max:2035',
            'pegawai_expense'     => 'required|numeric|min:0',
            'barang_jasa_expense' => 'required|numeric|min:0',
            'modal_expense'       => 'required|numeric|min:0',
        ]);

        $total = $request->pegawai_expense + $request->barang_jasa_expense + $request->modal_expense;

        ProgramExpenditure::updateOrCreate(
            [
                'regency_id' => $request->regency_id,
                'program_id' => $request->program_id,
                'year'       => $request->year,
            ],
            [
                'pegawai_expense'     => $request->pegawai_expense,
                'barang_jasa_expense' => $request->barang_jasa_expense,
                'modal_expense'       => $request->modal_expense,
                'total_expense'       => $total,
            ]
        );

        $program = MasterProgram::find($request->program_id);
        if ($program) {
            $sectorTotal = ProgramExpenditure::where('regency_id', $request->regency_id)
                ->where('year', $request->year)
                ->whereHas('program', fn($q) => $q->where('sector_id', $program->sector_id))
                ->sum('total_expense');

            ApbdExpenditure::updateOrCreate(
                [
                    'regency_id' => $request->regency_id,
                    'sector_id'  => $program->sector_id,
                    'year'       => $request->year,
                ],
                [
                    'direct_expense' => $sectorTotal,
                    'total_expense'  => $sectorTotal,
                ]
            );
        }

        return redirect()->route('data-management', ['active_tab' => 'program'])
            ->with('success_program', 'Rincian Anggaran Program berhasil disimpan & disinkronkan!');
    }

    // Hapus Data Rincian Program
    public function destroyProgramData($id)
    {
        $data = ProgramExpenditure::with('program')->findOrFail($id);
        $regencyId = $data->regency_id;
        $year      = $data->year;
        $sectorId  = $data->program->sector_id ?? null;

        $data->delete();

        if ($sectorId) {
            $sectorTotal = ProgramExpenditure::where('regency_id', $regencyId)
                ->where('year', $year)
                ->whereHas('program', fn($q) => $q->where('sector_id', $sectorId))
                ->sum('total_expense');

            ApbdExpenditure::where('regency_id', $regencyId)
                ->where('sector_id', $sectorId)
                ->where('year', $year)
                ->update([
                    'direct_expense' => $sectorTotal,
                    'total_expense'  => $sectorTotal,
                ]);
        }

        return redirect()->route('data-management', ['active_tab' => 'program'])
            ->with('success_program', 'Data Rincian Program berhasil dihapus!');
    }

    // Simpan / Update Akun Pendapatan (4), Belanja Tidak Langsung (5.1), & Pembiayaan (6)
    public function storeAccountRecord(Request $request)
    {
        $request->validate([
            'regency_id' => 'required|exists:master_regencies,id',
            'account_id' => 'required|exists:master_apbd_accounts,id',
            'year'       => 'required|integer|min:2025|max:2035',
            'amount'     => 'required|numeric|min:0',
        ]);

        ApbdAccountRecord::updateOrCreate(
            [
                'regency_id' => $request->regency_id,
                'account_id' => $request->account_id,
                'year'       => $request->year,
            ],
            [
                'amount' => $request->amount,
            ]
        );

        return redirect()->route('data-management', ['active_tab' => 'account'])
            ->with('success_account', 'Data Akun Ringkasan APBD berhasil disimpan/diperbarui!');
    }

    // Hapus Data Akun Ringkasan APBD
    public function destroyAccountRecord($id)
    {
        $data = ApbdAccountRecord::findOrFail($id);
        $data->delete();

        return redirect()->route('data-management', ['active_tab' => 'account'])
            ->with('success_account', 'Data Akun Ringkasan APBD berhasil dihapus!');
    }
}