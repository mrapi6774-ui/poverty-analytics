<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Regency;
use App\Models\Sector;
use App\Models\MasterApbdAccount;
use App\Models\ApbdAccountRecord;
use App\Models\MasterProgram;
use App\Models\ProgramExpenditure;
use App\Models\ApbdExpenditure;

class RealScaleApbdSeeder extends Seeder
{
    public function run(): void
    {
        $regencies = Regency::all();
        $sectors   = Sector::all();
        $accounts  = MasterApbdAccount::all();
        $years     = range(2025, 2035);

        // Pengali skala APBD per wilayah (agar tiap Kab/Kota & Provinsi punya postur realistis berbeda)
        $regencyScale = [
            1 => 2.20, // Provinsi Kep. Bangka Belitung (~Rp 2,5 - 3,5 Triliun)
            2 => 1.15, // Kota Pangkalpinang (~Rp 1,2 - 1,7 Triliun)
            3 => 1.25, // Kab. Bangka (~Rp 1,3 - 1,9 Triliun)
            4 => 1.10, // Kab. Belitung (~Rp 1,1 - 1,6 Triliun)
            5 => 1.05, // Kab. Bangka Barat
            6 => 1.00, // Kab. Bangka Tengah
            7 => 1.08, // Kab. Bangka Selatan
            8 => 0.98, // Kab. Belitung Timur
        ];

        // Proporsi nominal dasar (dalam Rupiah penuh) untuk setiap kode akun APBD
        $baseAccountAmounts = [
            // 4.1 Pendapatan Asli Daerah (PAD)
            '4.1.1' => 145000000000, // Pajak daerah: Rp 145 Miliar
            '4.1.2' => 28500000000,  // Retribusi daerah: Rp 28,5 Miliar
            '4.1.3' => 14200000000,  // Hasil pengelolaan kekayaan daerah: Rp 14,2 Miliar
            '4.1.4' => 42300000000,  // Lain-lain PAD yang sah: Rp 42,3 Miliar

            // 4.2 Dana Perimbangan
            '4.2.1' => 185000000000, // DBH: Rp 185 Miliar
            '4.2.2' => 560000000000, // DAU: Rp 560 Miliar
            '4.2.3' => 210000000000, // DAK: Rp 210 Miliar

            // 4.3 Lain-lain Pendapatan Daerah yang Sah
            '4.3.1' => 15000000000,  // Hibah: Rp 15 Miliar
            '4.3.2' => 0,
            '4.3.3' => 48000000000,  // DBH Pajak Provinsi: Rp 48 Miliar
            '4.3.4' => 12000000000,  // Dana penyesuaian: Rp 12 Miliar
            '4.3.5' => 25000000000,  // Bantuan keuangan: Rp 25 Miliar
            '4.3.6' => 8500000000,   // Lain-lain: Rp 8,5 Miliar

            // 5.1 Belanja Tidak Langsung
            '5.1.1' => 420000000000, // Belanja Pegawai Tidak Langsung: Rp 420 Miliar
            '5.1.2' => 1500000000,   // Belanja Bunga: Rp 1,5 Miliar
            '5.1.3' => 8000000000,   // Belanja Subsidi: Rp 8 Miliar
            '5.1.4' => 35000000000,  // Belanja Hibah: Rp 35 Miliar
            '5.1.5' => 27500000000,  // Belanja Bantuan Sosial: Rp 27,5 Miliar
            '5.1.6' => 18000000000,  // Belanja Bagi Hasil: Rp 18 Miliar
            '5.1.7' => 64000000000,  // Belanja Bantuan Keuangan Desa: Rp 64 Miliar
            '5.1.8' => 10000000000,  // Belanja Tidak Terduga: Rp 10 Miliar

            // 6.1 Penerimaan Pembiayaan
            '6.1.1' => 65000000000,  // SiLPA TA sebelumnya: Rp 65 Miliar
            '6.1.2' => 10000000000,  // Pencairan dana cadangan: Rp 10 Miliar

            // 6.2 Pengeluaran Pembiayaan
            '6.2.1' => 12000000000,  // Pembentukan Dana Cadangan: Rp 12 Miliar
            '6.2.2' => 15000000000,  // Penyertaan Modal Daerah: Rp 15 Miliar
        ];

        // Ambil 3 sub-kegiatan pertama per sektor untuk diisi contoh anggaran realistis
        $subProgramsBySector = [];
        foreach ($sectors as $sec) {
            $subProgramsBySector[$sec->id] = MasterProgram::where('sector_id', $sec->id)
                ->where('level', 2)
                ->take(3)
                ->get();
        }

        // Dasar anggaran per sektor (dalam Rupiah penuh, total ~Rp 710 Miliar untuk skala 1.0)
        $sectorBaseBudgets = [
            'Kemiskinan Makro'          => 85000000000,  // Rp 85 Miliar
            'Pendidikan'                => 185000000000, // Rp 185 Miliar
            'Kesehatan'                 => 155000000000, // Rp 155 Miliar
            'Infrastruktur & Perumahan' => 195000000000, // Rp 195 Miliar
            'Ketenagakerjaan & Ekonomi' => 90000000000,  // Rp 90 Miliar
        ];

        foreach ($regencies as $reg) {
            $scale = $regencyScale[$reg->id] ?? 1.0;

            foreach ($years as $idx => $year) {
                // Kenaikan bertahap ~4,5% per tahun dari 2025 ke 2035
                $growth = pow(1.045, $idx);

                // 1. Isi Akun Ringkasan APBD (4, 5.1, 6)
                foreach ($accounts as $acc) {
                    $baseVal = $baseAccountAmounts[$acc->code] ?? 0;
                    $amount  = round($baseVal * $scale * $growth, -6); // Bulatkan ke jutaan terdekat

                    ApbdAccountRecord::updateOrCreate(
                        [
                            'regency_id' => $reg->id,
                            'account_id' => $acc->id,
                            'year'       => $year,
                        ],
                        ['amount' => $amount]
                    );
                }

                // 2. Isi Rincian Sub-Kegiatan (Level 2) & Agregat Sektor (5.2)
                foreach ($sectors as $sec) {
                    $secBase   = $sectorBaseBudgets[$sec->name] ?? 80000000000;
                    $secTotal  = round($secBase * $scale * $growth, -6);
                    $subs      = $subProgramsBySector[$sec->id] ?? collect();

                    if ($subs->isNotEmpty()) {
                        $perSub = round($secTotal / $subs->count(), -6);
                        $actualSecTotal = 0;

                        foreach ($subs as $sub) {
                            $pegawai = round($perSub * 0.15, -6);
                            $barjas  = round($perSub * 0.45, -6);
                            $modal   = $perSub - $pegawai - $barjas;
                            $total   = $pegawai + $barjas + $modal;
                            $actualSecTotal += $total;

                            ProgramExpenditure::updateOrCreate(
                                [
                                    'regency_id' => $reg->id,
                                    'program_id' => $sub->id,
                                    'year'       => $year,
                                ],
                                [
                                    'pegawai_expense'     => $pegawai,
                                    'barang_jasa_expense' => $barjas,
                                    'modal_expense'       => $modal,
                                    'total_expense'       => $total,
                                ]
                            );
                        }
                        $secTotal = $actualSecTotal;
                    }

                    // Sinkronkan ke tabel agregat apbd_expenditures agar grafik & efektivitas selaras
                    ApbdExpenditure::updateOrCreate(
                        [
                            'regency_id' => $reg->id,
                            'sector_id'  => $sec->id,
                            'year'       => $year,
                        ],
                        [
                            'direct_expense'   => $secTotal,
                            'indirect_expense' => round($secTotal * 0.35, -6),
                            'total_expense'    => $secTotal,
                        ]
                    );
                }
            }
        }
    }
}