<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regency;
use App\Models\Indicator;
use App\Models\RegionalIndicatorData;
use App\Models\MasterApbdAccount;
use App\Models\ApbdAccountRecord;
use App\Models\MasterProgram;
use App\Models\ProgramExpenditure;
use App\Models\ApbdExpenditure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelController extends Controller
{
    public function downloadTemplate(Request $request)
    {
        $regencyId = $request->input('regency_id', Regency::where('type', 'regency')->value('id') ?? 1);
        $year      = (int) $request->input('year', 2026);

        $regency = Regency::findOrFail($regencyId);

        $spreadsheet = new Spreadsheet();

        // Styling Header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];

        // =========================================================================
        // SHEET 1: PANDUAN & REFERENSI WILAYAH
        // =========================================================================
        $guideSheet = $spreadsheet->getActiveSheet();
        $guideSheet->setTitle('Panduan');
        $guideSheet->setCellValue('A1', 'PANDUAN PENGISIAN & IMPOR DATA POVERTY ANALYTICS & APBD');
        $guideSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $guides = [
            ['A3', '1. Jangan mengubah nama Sheet (1_Indikator_Makro, 2_Ringkasan_APBD, 3_Program_Belanja_5_2) maupun urutan kolomnya.'],
            ['A4', '2. Isi angka nominal anggaran dalam satuan JUTA RUPIAH (gunakan titik untuk desimal, contoh: 12500.50).'],
            ['A5', '3. Baris dengan kolom nilai/nominal kosong akan dilewati (tidak menimpa data yang ada). Isi 0 jika memang bernilai nol.'],
            ['A6', '4. Total Belanja Langsung (5.2) per urusan akan otomatis dihitung ulang dari penjumlahan Sheet 3_Program_Belanja_5_2.'],
            ['A8', 'DAFTAR KODE ID WILAYAH (REGENCY_ID):'],
        ];
        foreach ($guides as [$cell, $text]) {
            $guideSheet->setCellValue($cell, $text);
        }
        $guideSheet->getStyle('A8')->getFont()->setBold(true);

        $guideSheet->setCellValue('A9', 'ID Wilayah');
        $guideSheet->setCellValue('B9', 'Nama Wilayah');
        $guideSheet->setCellValue('C9', 'Tingkat');
        $guideSheet->getStyle('A9:C9')->applyFromArray($headerStyle);

        $rowIdx = 10;
        foreach (Regency::all() as $reg) {
            $guideSheet->setCellValue("A{$rowIdx}", $reg->id);
            $guideSheet->setCellValue("B{$rowIdx}", $reg->name);
            $guideSheet->setCellValue("C{$rowIdx}", strtoupper($reg->type));
            $rowIdx++;
        }
        foreach (['A', 'B', 'C'] as $col) {
            $guideSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // =========================================================================
        // SHEET 2: 1_Indikator_Makro
        // =========================================================================
        $indSheet = $spreadsheet->createSheet();
        $indSheet->setTitle('1_Indikator_Makro');
        $indHeaders = ['regency_id', 'wilayah', 'tahun', 'kode_indikator', 'nama_indikator', 'satuan', 'nilai'];
        $indSheet->fromArray($indHeaders, null, 'A1');
        $indSheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        $existingInd = RegionalIndicatorData::where('regency_id', $regencyId)
            ->where('year', $year)
            ->pluck('value', 'indicator_id');

        $r = 2;
        foreach (Indicator::all() as $ind) {
            $indSheet->fromArray([
                $regency->id,
                $regency->name,
                $year,
                $ind->code,
                $ind->name,
                $ind->unit,
                $existingInd[$ind->id] ?? '',
            ], null, "A{$r}");
            $r++;
        }
        foreach (range('A', 'G') as $col) {
            $indSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // =========================================================================
        // SHEET 3: 2_Ringkasan_APBD (Pendapatan 4, Belanja 5.1, Pembiayaan 6)
        // =========================================================================
        $accSheet = $spreadsheet->createSheet();
        $accSheet->setTitle('2_Ringkasan_APBD');
        $accHeaders = ['regency_id', 'wilayah', 'tahun', 'kelompok', 'kode_akun', 'uraian_akun', 'nominal_juta'];
        $accSheet->fromArray($accHeaders, null, 'A1');
        $accSheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        $existingAcc = ApbdAccountRecord::where('regency_id', $regencyId)
            ->where('year', $year)
            ->pluck('amount', 'account_id');

        $r = 2;
        foreach (MasterApbdAccount::orderBy('id')->get() as $acc) {
            $accSheet->fromArray([
                $regency->id,
                $regency->name,
                $year,
                $acc->category_name,
                $acc->code,
                $acc->name,
                $existingAcc[$acc->id] ?? '',
            ], null, "A{$r}");
            $r++;
        }
        foreach (range('A', 'G') as $col) {
            $accSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // =========================================================================
        // SHEET 4: 3_Program_Belanja_5_2 (Sub-Kegiatan Level 2)
        // =========================================================================
        $progSheet = $spreadsheet->createSheet();
        $progSheet->setTitle('3_Program_Belanja_5_2');
        $progHeaders = [
            'regency_id',
            'wilayah',
            'tahun',
            'urusan',
            'kode_rekening',
            'nama_sub_kegiatan',
            'belanja_pegawai',
            'belanja_barang_jasa',
            'belanja_modal',
        ];
        $progSheet->fromArray($progHeaders, null, 'A1');
        $progSheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        $urusanLabels = [
            'Kemiskinan Makro'           => 'Urusan Sosial (1.13)',
            'Pendidikan'                 => 'Urusan Pendidikan (1.01)',
            'Kesehatan'                  => 'Urusan Kesehatan (1.02)',
            'Infrastruktur & Perumahan'  => 'Urusan Infrastruktur (1.03)',
            'Ketenagakerjaan & Ekonomi'  => 'Urusan Pilihan (2.01)',
        ];

        $existingProg = ProgramExpenditure::where('regency_id', $regencyId)
            ->where('year', $year)
            ->get()
            ->keyBy('program_id');

        $subPrograms = MasterProgram::with('sector')
            ->where('level', 2)
            ->orderBy('sector_id')
            ->orderBy('code')
            ->get();

        $r = 2;
        foreach ($subPrograms as $sub) {
            $exp = $existingProg->get($sub->id);
            $secName = $sub->sector->name ?? '-';
            $progSheet->fromArray([
                $regency->id,
                $regency->name,
                $year,
                $urusanLabels[$secName] ?? $secName,
                $sub->code,
                $sub->name,
                $exp ? $exp->pegawai_expense : '',
                $exp ? $exp->barang_jasa_expense : '',
                $exp ? $exp->modal_expense : '',
            ], null, "A{$r}");
            $r++;
        }
        foreach (range('A', 'I') as $col) {
            $progSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set Sheet aktif pertama saat dibuka ke Sheet Indikator atau Ringkasan APBD
        $spreadsheet->setActiveSheetIndex(2);

        $safeRegName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $regency->name);
        $fileName = "Template_APBD_Kemiskinan_{$safeRegName}_{$year}.xlsx";

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $spreadsheet = IOFactory::load($request->file('excel_file')->getPathname());

        $countInd  = 0;
        $countAcc  = 0;
        $countProg = 0;
        $affectedSectorPairs = [];

        // 1. PROSES SHEET 1_Indikator_Makro
        if ($sheet = $spreadsheet->getSheetByName('1_Indikator_Makro')) {
            $rows = $sheet->toArray(null, true, true, true);
            $indicatorsByCode = Indicator::pluck('id', 'code');

            foreach (array_slice($rows, 1) as $row) {
                $regId = trim((string) ($row['A'] ?? ''));
                $year  = trim((string) ($row['C'] ?? ''));
                $code  = trim((string) ($row['D'] ?? ''));
                $val   = $row['G'] ?? null;

                if ($regId === '' || $year === '' || $code === '' || $val === null || $val === '') {
                    continue;
                }

                $indId = $indicatorsByCode[$code] ?? null;
                if ($indId && is_numeric($val)) {
                    RegionalIndicatorData::updateOrCreate(
                        ['regency_id' => (int) $regId, 'indicator_id' => $indId, 'year' => (int) $year],
                        ['value' => (float) $val]
                    );
                    $countInd++;
                }
            }
        }

        // 2. PROSES SHEET 2_Ringkasan_APBD
        if ($sheet = $spreadsheet->getSheetByName('2_Ringkasan_APBD')) {
            $rows = $sheet->toArray(null, true, true, true);
            $accounts = MasterApbdAccount::all();

            foreach (array_slice($rows, 1) as $row) {
                $regId  = trim((string) ($row['A'] ?? ''));
                $year   = trim((string) ($row['C'] ?? ''));
                $code   = trim((string) ($row['E'] ?? ''));
                $name   = trim((string) ($row['F'] ?? ''));
                $amount = $row['G'] ?? null;

                if ($regId === '' || $year === '' || $code === '' || $amount === null || $amount === '') {
                    continue;
                }

                $account = $accounts->first(fn($a) => $a->code === $code && $a->name === $name)
                    ?? $accounts->firstWhere('code', $code);

                if ($account && is_numeric($amount)) {
                    ApbdAccountRecord::updateOrCreate(
                        ['regency_id' => (int) $regId, 'account_id' => $account->id, 'year' => (int) $year],
                        ['amount' => (float) $amount]
                    );
                    $countAcc++;
                }
            }
        }

        // 3. PROSES SHEET 3_Program_Belanja_5_2
        if ($sheet = $spreadsheet->getSheetByName('3_Program_Belanja_5_2')) {
            $rows = $sheet->toArray(null, true, true, true);
            $programsByCode = MasterProgram::where('level', 2)->get()->keyBy('code');

            foreach (array_slice($rows, 1) as $row) {
                $regId   = trim((string) ($row['A'] ?? ''));
                $year    = trim((string) ($row['C'] ?? ''));
                $code    = trim((string) ($row['E'] ?? ''));
                $pegawai = $row['G'] ?? null;
                $barjas  = $row['H'] ?? null;
                $modal   = $row['I'] ?? null;

                // Lewati jika ketiga kolom belanja kosong semua
                if ($regId === '' || $year === '' || $code === '' || (($pegawai === null || $pegawai === '') && ($barjas === null || $barjas === '') && ($modal === null || $modal === ''))) {
                    continue;
                }

                $program = $programsByCode->get($code);
                if ($program) {
                    $pVal = is_numeric($pegawai) ? (float) $pegawai : 0;
                    $bVal = is_numeric($barjas) ? (float) $barjas : 0;
                    $mVal = is_numeric($modal) ? (float) $modal : 0;
                    $tot  = $pVal + $bVal + $mVal;

                    ProgramExpenditure::updateOrCreate(
                        ['regency_id' => (int) $regId, 'program_id' => $program->id, 'year' => (int) $year],
                        [
                            'pegawai_expense'     => $pVal,
                            'barang_jasa_expense' => $bVal,
                            'modal_expense'       => $mVal,
                            'total_expense'       => $tot,
                        ]
                    );

                    $affectedSectorPairs["{$regId}_{$program->sector_id}_{$year}"] = [
                        'regency_id' => (int) $regId,
                        'sector_id'  => (int) $program->sector_id,
                        'year'       => (int) $year,
                    ];
                    $countProg++;
                }
            }
        }

        // Sinkronisasi ulang total belanja langsung per urusan untuk setiap kombinasi wilayah-urusan-tahun yang diimpor
        foreach ($affectedSectorPairs as $pair) {
            $sectorTotal = ProgramExpenditure::where('regency_id', $pair['regency_id'])
                ->where('year', $pair['year'])
                ->whereHas('program', fn($q) => $q->where('sector_id', $pair['sector_id']))
                ->sum('total_expense');

            ApbdExpenditure::updateOrCreate(
                [
                    'regency_id' => $pair['regency_id'],
                    'sector_id'  => $pair['sector_id'],
                    'year'       => $pair['year'],
                ],
                [
                    'direct_expense' => $sectorTotal,
                    'total_expense'  => $sectorTotal,
                ]
            );
        }

        return redirect()->route('data-management')
            ->with('success_excel', "Impor Excel selesai! Berhasil memproses {$countInd} Indikator, {$countAcc} Akun Ringkasan APBD, dan {$countProg} Sub-Kegiatan Program (5.2).");
    }
}