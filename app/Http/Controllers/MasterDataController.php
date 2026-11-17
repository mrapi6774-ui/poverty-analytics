<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Regency;
use App\Models\Indicator;
use App\Models\Sector;
use App\Models\MasterProgram;
use App\Models\MasterApbdAccount;

class MasterDataController extends Controller
{
    public function index()
    {
        $regencies   = Regency::orderBy('id')->get();
        $indicators  = Indicator::with('sector')->orderBy('id')->get();
        $sectors     = Sector::orderBy('id')->get();
        $programs    = MasterProgram::with(['sector', 'parent'])
            ->orderBy('sector_id')
            ->orderBy('code')
            ->get();
        $parentProgs = MasterProgram::where('level', 1)->orderBy('code')->get();
        $accounts    = MasterApbdAccount::orderBy('id')->get();

        return view('pages.master-data', compact(
            'regencies',
            'indicators',
            'sectors',
            'programs',
            'parentProgs',
            'accounts'
        ));
    }

    // 1. MASTER WILAYAH
    public function storeRegency(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:regency,province,national',
            'code' => 'nullable|string|max:50',
        ]);

        $payload = [
            'name' => $request->name,
            'type' => $request->type,
        ];

        if (Schema::hasColumn('master_regencies', 'code')) {
            $payload['code'] = $request->code ?: strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $request->name), 0, 8));
        }

        Regency::create($payload);

        return redirect()->route('master-data', ['active_tab' => 'regency'])
            ->with('success_master', 'Master Wilayah berhasil ditambahkan!');
    }

    public function destroyRegency($id)
    {
        Regency::findOrFail($id)->delete();

        return redirect()->route('master-data', ['active_tab' => 'regency'])
            ->with('success_master', 'Master Wilayah berhasil dihapus!');
    }

    // 2. MASTER INDIKATOR MAKRO & SEKTORAL
    public function storeIndicator(Request $request)
    {
        $request->validate([
            'code'      => 'required|string|max:50',
            'name'      => 'required|string|max:255',
            'unit'      => 'required|string|max:50',
            'sector_id' => 'nullable|exists:master_sectors,id',
        ]);

        $payload = [
            'code' => $request->code,
            'name' => $request->name,
            'unit' => $request->unit,
        ];

        if (Schema::hasColumn('master_indicators', 'sector_id')) {
            $payload['sector_id'] = $request->sector_id ?: null;
        }

        Indicator::updateOrCreate(['code' => $request->code], $payload);

        return redirect()->route('master-data', ['active_tab' => 'indicator'])
            ->with('success_master', 'Master Indikator berhasil disimpan!');
    }

    public function destroyIndicator($id)
    {
        Indicator::findOrFail($id)->delete();

        return redirect()->route('master-data', ['active_tab' => 'indicator'])
            ->with('success_master', 'Master Indikator berhasil dihapus!');
    }

    // 3. MASTER SEKTOR / URUSAN
    public function storeSector(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);

        $payload = ['name' => $request->name];

        if (Schema::hasColumn('master_sectors', 'code') && $request->filled('code')) {
            $payload['code'] = $request->code;
        }

        Sector::create($payload);

        return redirect()->route('master-data', ['active_tab' => 'sector'])
            ->with('success_master', 'Master Sektor / Urusan berhasil ditambahkan!');
    }

    public function destroySector($id)
    {
        Sector::findOrFail($id)->delete();

        return redirect()->route('master-data', ['active_tab' => 'sector'])
            ->with('success_master', 'Master Sektor / Urusan berhasil dihapus!');
    }

    // 4. MASTER PROGRAM (LEVEL 1) & SUB-KEGIATAN (LEVEL 2)
    public function storeProgram(Request $request)
    {
        $request->validate([
            'sector_id' => 'required|exists:master_sectors,id',
            'level'     => 'required|in:1,2',
            'parent_id' => 'nullable|exists:master_programs,id',
            'code'      => 'required|string|max:100',
            'name'      => 'required|string|max:500',
        ]);

        MasterProgram::updateOrCreate(
            ['code' => $request->code],
            [
                'sector_id' => $request->sector_id,
                'level'     => (int) $request->level,
                'parent_id' => $request->level == 2 ? $request->parent_id : null,
                'name'      => $request->name,
            ]
        );

        return redirect()->route('master-data', ['active_tab' => 'program'])
            ->with('success_master', 'Master Program / Sub-Kegiatan berhasil disimpan!');
    }

    public function destroyProgram($id)
    {
        MasterProgram::findOrFail($id)->delete();

        return redirect()->route('master-data', ['active_tab' => 'program'])
            ->with('success_master', 'Master Program / Sub-Kegiatan berhasil dihapus!');
    }

    // 5. MASTER AKUN RINGKASAN APBD (4, 5.1, 6)
    public function storeAccount(Request $request)
    {
        $request->validate([
            'type'          => 'required|in:pendapatan,belanja_tidak_langsung,pembiayaan',
            'category_code' => 'required|string|max:20',
            'category_name' => 'required|string|max:255',
            'code'          => 'required|string|max:20',
            'name'          => 'required|string|max:255',
        ]);

        MasterApbdAccount::updateOrCreate(
            ['code' => $request->code, 'name' => $request->name],
            [
                'type'          => $request->type,
                'category_code' => $request->category_code,
                'category_name' => $request->category_name,
            ]
        );

        return redirect()->route('master-data', ['active_tab' => 'account'])
            ->with('success_master', 'Master Akun APBD berhasil disimpan!');
    }

    public function destroyAccount($id)
    {
        MasterApbdAccount::findOrFail($id)->delete();

        return redirect()->route('master-data', ['active_tab' => 'account'])
            ->with('success_master', 'Master Akun APBD berhasil dihapus!');
    }
}