<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector;
use App\Models\Indicator;
use App\Models\Regency;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Sektor Utama
        $sectors = [
            ['code' => 'SEC01', 'name' => 'Kemiskinan Makro', 'description' => 'Indikator utama tingkat dan kedalaman kemiskinan'],
            ['code' => 'SEC02', 'name' => 'Pendidikan', 'description' => 'Aksesibilitas dan kualitas layanan pendidikan dasar dan menengah'],
            ['code' => 'SEC03', 'name' => 'Kesehatan', 'description' => 'Derajat kesehatan masyarakat, kualitas pelayanan, dan gizi'],
            ['code' => 'SEC04', 'name' => 'Infrastruktur & Perumahan', 'description' => 'Akses terhadap hunian layak dan sarana dasar permukiman'],
            ['code' => 'SEC05', 'name' => 'Ketenagakerjaan & Ekonomi', 'description' => 'Pertumbuhan ekonomi, kesempatan kerja, dan pendapatan'],
        ];

        foreach ($sectors as $s) {
            Sector::updateOrCreate(['code' => $s['code']], $s);
        }

        // 2. Data Indikator per Sektor (26 Indikator)
        $secPoverty = Sector::where('code', 'SEC01')->first();
        $secEdu     = Sector::where('code', 'SEC02')->first();
        $secHealth  = Sector::where('code', 'SEC03')->first();
        $secInfra   = Sector::where('code', 'SEC04')->first();
        $secEcon    = Sector::where('code', 'SEC05')->first();

        $indicators = [
            // Sektor Kemiskinan Makro (SEC01)
            ['sector_id' => $secPoverty->id, 'code' => 'P0', 'name' => 'Persentase Penduduk Miskin', 'unit' => '%', 'direction' => 'negative'],
            ['sector_id' => $secPoverty->id, 'code' => 'GK', 'name' => 'Garis Kemiskinan', 'unit' => 'Rp/kapita/bulan', 'direction' => 'negative'],
            ['sector_id' => $secPoverty->id, 'code' => 'P1', 'name' => 'Indeks Kedalaman Kemiskinan', 'unit' => 'Indeks', 'direction' => 'negative'],
            ['sector_id' => $secPoverty->id, 'code' => 'P2', 'name' => 'Indeks Keparahan Kemiskinan', 'unit' => 'Indeks', 'direction' => 'negative'],
            ['sector_id' => $secPoverty->id, 'code' => 'JML_MISKIN', 'name' => 'Jumlah Penduduk Miskin', 'unit' => 'Ribu Jiwa', 'direction' => 'negative'],

            // Sektor Pendidikan (SEC02)
            ['sector_id' => $secEdu->id, 'code' => 'HLS', 'name' => 'Harapan Lama Sekolah', 'unit' => 'Tahun', 'direction' => 'positive'],
            ['sector_id' => $secEdu->id, 'code' => 'RLS', 'name' => 'Rata-rata Lama Sekolah', 'unit' => 'Tahun', 'direction' => 'positive'],
            ['sector_id' => $secEdu->id, 'code' => 'APM', 'name' => 'Angka Partisipasi Murni', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secEdu->id, 'code' => 'APK', 'name' => 'Angka Partisipasi Kasar', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secEdu->id, 'code' => 'AMH', 'name' => 'Angka Melek Huruf', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secEdu->id, 'code' => 'RASIO_GURU', 'name' => 'Rasio Guru terhadap Murid', 'unit' => 'Rasio', 'direction' => 'positive'],

            // Sektor Kesehatan (SEC03)
            ['sector_id' => $secHealth->id, 'code' => 'AHH', 'name' => 'Angka Harapan Hidup', 'unit' => 'Tahun', 'direction' => 'positive'],
            ['sector_id' => $secHealth->id, 'code' => 'AKB', 'name' => 'Angka Kematian Bayi', 'unit' => 'per 1.000 KH', 'direction' => 'negative'],
            ['sector_id' => $secHealth->id, 'code' => 'STUNT', 'name' => 'Prevalensi Stunting', 'unit' => '%', 'direction' => 'negative'],
            ['sector_id' => $secHealth->id, 'code' => 'NAKES_BIRTH', 'name' => 'Persentase Persalinan oleh Nakes', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secHealth->id, 'code' => 'RASIO_NAKES', 'name' => 'Rasio Nakes / Puskesmas', 'unit' => 'per 10 rb Penduduk', 'direction' => 'positive'],

            // Sektor Infrastruktur & Perumahan (SEC04)
            ['sector_id' => $secInfra->id, 'code' => 'SAN_LAYAK', 'name' => 'Akses Sanitasi Layak', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secInfra->id, 'code' => 'AIR_LAYAK', 'name' => 'Akses Air Minum Layak', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secInfra->id, 'code' => 'RTLH', 'name' => 'Perumahan Layak Huni', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secInfra->id, 'code' => 'JALAN_MANTAP', 'name' => 'Panjang & Kondisi Jalan Mantap', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secInfra->id, 'code' => 'ELEKTRIFIKASI', 'name' => 'Rasio Elektrifikasi', 'unit' => '%', 'direction' => 'positive'],

            // Sektor Ketenagakerjaan & Ekonomi (SEC05)
            ['sector_id' => $secEcon->id, 'code' => 'TPT', 'name' => 'Tingkat Pengangguran Terbuka', 'unit' => '%', 'direction' => 'negative'],
            ['sector_id' => $secEcon->id, 'code' => 'TPAK', 'name' => 'Tingkat Partisipasi Angkatan Kerja', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secEcon->id, 'code' => 'LPE', 'name' => 'Laju Pertumbuhan Ekonomi', 'unit' => '%', 'direction' => 'positive'],
            ['sector_id' => $secEcon->id, 'code' => 'PDRB_KAPITA', 'name' => 'PDRB per Kapita', 'unit' => 'Juta Rp/kapita/tahun', 'direction' => 'positive'],
            ['sector_id' => $secEcon->id, 'code' => 'GINI', 'name' => 'Indeks Gini', 'unit' => 'Indeks', 'direction' => 'negative'],
        ];

        foreach ($indicators as $ind) {
            Indicator::updateOrCreate(['code' => $ind['code']], $ind);
        }

        // 3. Data Wilayah (Provinsi & 7 Kab/Kota Bangka Belitung)
        $regencies = [
            ['code' => '0000', 'name' => 'Nasional', 'type' => 'national'],
            ['code' => '1900', 'name' => 'Prov. Kepulauan Bangka Belitung', 'type' => 'province'],
            ['code' => '1901', 'name' => 'Kab. Bangka', 'type' => 'regency'],
            ['code' => '1902', 'name' => 'Kab. Belitung', 'type' => 'regency'],
            ['code' => '1903', 'name' => 'Kab. Bangka Barat', 'type' => 'regency'],
            ['code' => '1904', 'name' => 'Kab. Bangka Tengah', 'type' => 'regency'],
            ['code' => '1905', 'name' => 'Kab. Bangka Selatan', 'type' => 'regency'],
            ['code' => '1906', 'name' => 'Kab. Belitung Timur', 'type' => 'regency'],
            ['code' => '1971', 'name' => 'Kota Pangkal Pinang', 'type' => 'regency'],
        ];

        foreach ($regencies as $reg) {
            Regency::updateOrCreate(['code' => $reg['code']], $reg);
        }
    }
}