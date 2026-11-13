<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterApbdAccount;

class ApbdAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // 4.1 Pendapatan Asli Daerah (PAD)
            ['type' => 'pendapatan', 'category_code' => '4.1', 'category_name' => 'Pendapatan Asli Daerah (PAD)', 'code' => '4.1.1', 'name' => 'Pajak daerah'],
            ['type' => 'pendapatan', 'category_code' => '4.1', 'category_name' => 'Pendapatan Asli Daerah (PAD)', 'code' => '4.1.2', 'name' => 'Retribusi daerah'],
            ['type' => 'pendapatan', 'category_code' => '4.1', 'category_name' => 'Pendapatan Asli Daerah (PAD)', 'code' => '4.1.3', 'name' => 'Hasil pengelolaan kekayaan daerah yang dipisahkan'],
            ['type' => 'pendapatan', 'category_code' => '4.1', 'category_name' => 'Pendapatan Asli Daerah (PAD)', 'code' => '4.1.4', 'name' => 'Lain lain PAD yang sah'],

            // 4.2 Dana Perimbangan
            ['type' => 'pendapatan', 'category_code' => '4.2', 'category_name' => 'Dana Perimbangan', 'code' => '4.2.1', 'name' => 'DBH (Dana Bagi Hasil)'],
            ['type' => 'pendapatan', 'category_code' => '4.2', 'category_name' => 'Dana Perimbangan', 'code' => '4.2.2', 'name' => 'DAU (Dana Alokasi Umum)'],
            ['type' => 'pendapatan', 'category_code' => '4.2', 'category_name' => 'Dana Perimbangan', 'code' => '4.2.3', 'name' => 'DAK (Dana Alokasi Khusus)'],

            // 4.3 Lain-lain Pendapatan Daerah yang Sah
            ['type' => 'pendapatan', 'category_code' => '4.3', 'category_name' => 'Lain-lain Pendapatan Daerah yang Sah', 'code' => '4.3.1', 'name' => 'Hibah'],
            ['type' => 'pendapatan', 'category_code' => '4.3', 'category_name' => 'Lain-lain Pendapatan Daerah yang Sah', 'code' => '4.3.2', 'name' => 'Dana darurat'],
            ['type' => 'pendapatan', 'category_code' => '4.3', 'category_name' => 'Lain-lain Pendapatan Daerah yang Sah', 'code' => '4.3.3', 'name' => 'Dana bagi hasil pajak dari Provinsi dan Pemda lainnya'],
            ['type' => 'pendapatan', 'category_code' => '4.3', 'category_name' => 'Lain-lain Pendapatan Daerah yang Sah', 'code' => '4.3.4', 'name' => 'Dana penyesuaian dan otonomi khusus'],
            ['type' => 'pendapatan', 'category_code' => '4.3', 'category_name' => 'Lain-lain Pendapatan Daerah yang Sah', 'code' => '4.3.5', 'name' => 'Bantuan keuangan dari Provinsi atau Pemda lainnya'],
            ['type' => 'pendapatan', 'category_code' => '4.3', 'category_name' => 'Lain-lain Pendapatan Daerah yang Sah', 'code' => '4.3.6', 'name' => 'Lain lain'],

            // 5.1 Belanja Tidak Langsung
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.1', 'name' => 'Belanja Pegawai Tidak Langsung'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.2', 'name' => 'Belanja Bunga'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.3', 'name' => 'Belanja Subsidi'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.4', 'name' => 'Belanja Hibah'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.5', 'name' => 'Belanja Bantuan sosial'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.6', 'name' => 'Belanja Bagi hasil kpd Prov/Kab/Kota dan Pemdes'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.7', 'name' => 'Belanja Bantuan keuangan kpd Prov/Kab/Kota dan Pemdes'],
            ['type' => 'belanja_tidak_langsung', 'category_code' => '5.1', 'category_name' => 'Belanja Tidak Langsung', 'code' => '5.1.8', 'name' => 'Belanja tidak terduga'],

            // 6.1 Penerimaan Pembiayaan
            ['type' => 'pembiayaan', 'category_code' => '6.1', 'category_name' => 'Penerimaan Pembiayaan', 'code' => '6.1.1', 'name' => 'SiLPA TA sebelumnya'],
            ['type' => 'pembiayaan', 'category_code' => '6.1', 'category_name' => 'Penerimaan Pembiayaan', 'code' => '6.1.2', 'name' => 'Pencairan dana cadangan'],
            ['type' => 'pembiayaan', 'category_code' => '6.1', 'category_name' => 'Penerimaan Pembiayaan', 'code' => '6.1.3', 'name' => 'Hasil Penjualan Kekayaan Daerah yang Dipisahkan'],
            ['type' => 'pembiayaan', 'category_code' => '6.1', 'category_name' => 'Penerimaan Pembiayaan', 'code' => '6.1.4', 'name' => 'Penerimaan Pinjaman Daerah dan Obligasi Daerah'],
            ['type' => 'pembiayaan', 'category_code' => '6.1', 'category_name' => 'Penerimaan Pembiayaan', 'code' => '6.1.5', 'name' => 'Penerimaan Kembali Pemberian Pinjaman'],

            // 6.2 Pengeluaran Pembiayaan
            ['type' => 'pembiayaan', 'category_code' => '6.2', 'category_name' => 'Pengeluaran Pembiayaan', 'code' => '6.2.1', 'name' => 'Pembentukan Dana Cadangan'],
            ['type' => 'pembiayaan', 'category_code' => '6.2', 'category_name' => 'Pengeluaran Pembiayaan', 'code' => '6.2.2', 'name' => 'Penyertaan Modal (Investasi) Daerah'],
            ['type' => 'pembiayaan', 'category_code' => '6.2', 'category_name' => 'Pengeluaran Pembiayaan', 'code' => '6.2.3', 'name' => 'Pembayaran Pokok Utang'],
            ['type' => 'pembiayaan', 'category_code' => '6.2', 'category_name' => 'Pengeluaran Pembiayaan', 'code' => '6.2.4', 'name' => 'Pemberian Pinjaman Daerah'],
            ['type' => 'pembiayaan', 'category_code' => '6.2', 'category_name' => 'Pengeluaran Pembiayaan', 'code' => '6.2.5', 'name' => 'Pembayaran Kegiatan Lanjutan'],
            ['type' => 'pembiayaan', 'category_code' => '6.2', 'category_name' => 'Pengeluaran Pembiayaan', 'code' => '6.2.6', 'name' => 'Pengeluaran Perhitungan Pihak Ketiga'],
        ];

        foreach ($accounts as $acc) {
            MasterApbdAccount::updateOrCreate(
                ['code' => $acc['code'], 'name' => $acc['name']],
                $acc
            );
        }
    }
}