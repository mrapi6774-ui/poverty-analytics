<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sector;
use App\Models\MasterProgram;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID sektor yang tersedia di tabel master_sectors
        $secPendidikan    = Sector::where('name', 'like', '%Pendidikan%')->first()?->id ?? 2;
        $secKesehatan     = Sector::where('name', 'like', '%Kesehatan%')->first()?->id ?? 3;
        $secInfrastruktur = Sector::where('name', 'like', '%Infrastruktur%')->first()?->id ?? 4;
        $secSosial        = Sector::where('name', 'like', '%Kemiskinan%')->orWhere('name', 'like', '%Sosial%')->first()?->id ?? 1;
        $secPilihan       = Sector::where('name', 'like', '%Ketenagakerjaan%')->orWhere('name', 'like', '%Pilihan%')->first()?->id ?? 5;

        $data = [
            // ==========================================
            // 1. URUSAN PENDIDIKAN (1.01)
            // ==========================================
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.01',
                'name' => 'Program Pelayanan Administrasi Perkantoran',
                'children' => [
                    ['code' => '1.01.1.01.01.01.01', 'name' => 'Penyediaan jasa surat menyurat'],
                    ['code' => '1.01.1.01.01.01.02', 'name' => 'Penyediaan jasa komunikasi; sumber daya air dan listrik'],
                    ['code' => '1.01.1.01.01.01.06', 'name' => 'Penyediaan jasa pemeliharaan dan perizinan kendaraan dinas/operasional'],
                    ['code' => '1.01.1.01.01.01.07', 'name' => 'Penyediaan jasa administrasi keuangan'],
                    ['code' => '1.01.1.01.01.01.08', 'name' => 'Penyediaan jasa kebersihan/caraka/peramu/keamanan/sopir kantor'],
                    ['code' => '1.01.1.01.01.01.09', 'name' => 'Penyediaan jasa perbaikan peralatan kerja'],
                    ['code' => '1.01.1.01.01.01.10', 'name' => 'Penyediaan alat tulis kantor'],
                    ['code' => '1.01.1.01.01.01.11', 'name' => 'Penyediaan barang cetakan dan penggandaan'],
                    ['code' => '1.01.1.01.01.01.12', 'name' => 'Penyediaan komponen instalasi listrik/penerangan bangunan kantor'],
                    ['code' => '1.01.1.01.01.01.15', 'name' => 'Penyediaan bahan bacaan'],
                    ['code' => '1.01.1.01.01.01.17', 'name' => 'Penyediaan makanan dan minuman'],
                    ['code' => '1.01.1.01.01.01.18', 'name' => 'Rapat-rapat koordinasi dan konsultasi ke dalam dan luar daerah'],
                    ['code' => '1.01.1.01.01.01.28', 'name' => 'Penyediaan Administrasi Kepegawaian dll'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.02',
                'name' => 'Program Peningkatan Sarana dan Prasarana Aparatur',
                'children' => [
                    ['code' => '1.01.1.01.01.02.03', 'name' => 'Pembangunan gedung kantor'],
                    ['code' => '1.01.1.01.01.02.05', 'name' => 'Pengadaan kendaraan dinas/operasional'],
                    ['code' => '1.01.1.01.01.02.07', 'name' => 'Pengadaan perlengkapan gedung kantor'],
                    ['code' => '1.01.1.01.01.02.10', 'name' => 'Pengadaan mebeulair'],
                    ['code' => '1.01.1.01.01.02.14', 'name' => 'Pengadaan Komputer'],
                    ['code' => '1.01.1.01.01.02.19', 'name' => 'Pengadaan Alat Kebersihan'],
                    ['code' => '1.01.1.01.01.02.22', 'name' => 'Pemeliharaan rutin/berkala gedung kantor'],
                    ['code' => '1.01.1.01.01.02.24', 'name' => 'Pemeliharaan rutin/berkala kendaraan dinas/operasional'],
                    ['code' => '1.01.1.01.01.02.46', 'name' => 'Penyediaan Sarana Prasarana UPTD/ KCD'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.05',
                'name' => 'Program Peningkatan Kapasitas Sumber Daya Aparatur',
                'children' => [
                    ['code' => '1.01.1.01.01.05.02', 'name' => 'Sosialisasi peraturan perundang-undangan'],
                    ['code' => '1.01.1.01.01.05.04', 'name' => 'Bimbingan dan Pelatihan Teknis'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.06',
                'name' => 'Program peningkatan pengembangan sistem pelaporan capaian',
                'children' => [
                    ['code' => '1.01.1.01.01.06.01', 'name' => 'Penyusunan laporan capaian kinerja dan ikhtisar realisasi kinerja SKPD'],
                    ['code' => '1.01.1.01.01.06.02', 'name' => 'Penyusunan pelaporan keuangan semesteran'],
                    ['code' => '1.01.1.01.01.06.03', 'name' => 'Penyusunan pelaporan prognosis realisasi anggaran'],
                    ['code' => '1.01.1.01.01.06.04', 'name' => 'Penyusunan pelaporan keuangan akhir tahun'],
                    ['code' => '1.01.1.01.01.06.08', 'name' => 'Monitoring, Evaluasi dan Pelaporan'],
                    ['code' => '1.01.1.01.01.06.10', 'name' => 'Penyusunan KUA, PPAS, RKA, DPA dan DPPA'],
                    ['code' => '1.01.1.01.01.06.11', 'name' => 'Penyusunan Data Base, Pemutakhiran dan Pengolahan Data Statistik'],
                    ['code' => '1.01.1.01.01.06.14', 'name' => 'Penyusunan Renstra dan Renja SKPD'],
                    ['code' => '1.01.1.01.01.06.16', 'name' => 'Penyusunan Laporan Akuntabilitas Kinerja Instansi Pemerintah (LAKIP)'],
                    ['code' => '1.01.1.01.01.06.17', 'name' => 'Penyusunan Laporan Program/Kegiatan'],
                    ['code' => '1.01.1.01.01.06.18', 'name' => 'Pembuatan Profil Monografi'],
                    ['code' => '1.01.1.01.01.06.19', 'name' => 'Penyusunan Laporan Rencana Kerja Program/Kegiatan'],
                    ['code' => '1.01.1.01.01.06.21', 'name' => 'Pelaksanaan Asistensi RKA dan DPA'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.15',
                'name' => 'Program Pendidikan Anak Usia Dini',
                'children' => [
                    ['code' => '1.01.1.01.01.15.01', 'name' => 'Pembangunan gedung sekolah'],
                    ['code' => '1.01.1.01.01.15.06', 'name' => 'Pengembangan data dan informasi Pendidikan Anak Usia Dini'],
                    ['code' => '1.01.1.01.01.15.18', 'name' => 'Pengadaan alat praktik dan peraga siswa'],
                    ['code' => '1.01.1.01.01.15.19', 'name' => 'Pengadaan mebeluer sekolah'],
                    ['code' => '1.01.1.01.01.15.57', 'name' => 'Pelatihan Kompetensi tenaga pendidik'],
                    ['code' => '1.01.1.01.01.15.59', 'name' => 'Penyelenggaraan Pendidikan Anak Usia Dini'],
                    ['code' => '1.01.1.01.01.15.61', 'name' => 'Penyusunan kebijakan Pendidikan Anak Usia Dini'],
                    ['code' => '1.01.1.01.01.15.63', 'name' => 'Penyelenggaraan koordinasi dan kerjasama Pendidikan Anak Usia Dini'],
                    ['code' => '1.01.1.01.01.15.65', 'name' => 'Publikasi dan sosialisasi Pendidikan Anak Usia Dini'],
                    ['code' => '1.01.1.01.01.15.67', 'name' => 'Peringatan Hari Anak Nasional'],
                    ['code' => '1.01.1.01.01.15.68', 'name' => 'Pekan Apresiasi PAUD'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.16',
                'name' => 'Program Wajib Belajar Pendidikan Dasar Sembilan Tahun',
                'children' => [
                    ['code' => '1.01.1.01.01.16.02', 'name' => 'Pembangunan rumah dinas kepala sekolah; guru; penjaga sekolah'],
                    ['code' => '1.01.1.01.01.16.03', 'name' => 'Penambahan ruang kelas sekolah'],
                    ['code' => '1.01.1.01.01.16.05', 'name' => 'Pembangunan laboratorium dan ruang praktikum sekolah'],
                    ['code' => '1.01.1.01.01.16.12', 'name' => 'Pembangunan perpustakaan sekolah'],
                    ['code' => '1.01.1.01.01.16.15', 'name' => 'Pengadaan buku-buku dan alat tulis siswa'],
                    ['code' => '1.01.1.01.01.16.18', 'name' => 'Pengadaan alat praktik dan peraga siswa'],
                    ['code' => '1.01.1.01.01.16.19', 'name' => 'Pengadaan mebeluer sekolah'],
                    ['code' => '1.01.1.01.01.16.20', 'name' => 'Pengadaan perlengkapan sekolah'],
                    ['code' => '1.01.1.01.01.16.41', 'name' => 'Rehabilitasi sedang/berat bangunan sekolah'],
                    ['code' => '1.01.1.01.01.16.44', 'name' => 'Rehabilitasi sedang/berat ruang kelas sekolah'],
                    ['code' => '1.01.1.01.01.16.57', 'name' => 'Pelatihan Kompetensi tenaga pendidik'],
                    ['code' => '1.01.1.01.01.16.59', 'name' => 'Pelatihan Penyusunan kurikulum'],
                    ['code' => '1.01.1.01.01.16.63', 'name' => 'Penyediaan Bantuan Operasional Sekolah (BOS) jenjang SD/MI/SDLB dan SMP'],
                    ['code' => '1.01.1.01.01.16.67', 'name' => 'Penyelenggaraan Paket A Setara SD'],
                    ['code' => '1.01.1.01.01.16.68', 'name' => 'Penyelenggaraan Paket B Setara SMP'],
                    ['code' => '1.01.1.01.01.16.69', 'name' => 'Pembinaan kelembagaan dan manajemen sekolah dengan penerapan MBS'],
                    ['code' => '1.01.1.01.01.16.70', 'name' => 'Pembinaan minat; bakat; dan kreativitas siswa'],
                    ['code' => '1.01.1.01.01.16.73', 'name' => 'Penyebarluasan dan sosialisasi berbagai informasi pendidikan dasar'],
                    ['code' => '1.01.1.01.01.16.74', 'name' => 'Penyediaan beasiswa retrieval untuk anak putus sekolah'],
                    ['code' => '1.01.1.01.01.16.79', 'name' => 'Dukungan Penyelenggaraan Pendidikan untuk Sekolah/Madrasah Swasta'],
                    ['code' => '1.01.1.01.01.16.86', 'name' => 'Operasional TK, SLB, SKB, dan SMP'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.17',
                'name' => 'Program Pendidikan Menengah',
                'children' => [
                    ['code' => '1.01.1.01.01.17.01', 'name' => 'Pembangunan gedung sekolah'],
                    ['code' => '1.01.1.01.01.17.03', 'name' => 'Penambahan ruang kelas sekolah'],
                    ['code' => '1.01.1.01.01.17.05', 'name' => 'Pembangunan laboratorium dan ruang praktikum sekolah'],
                    ['code' => '1.01.1.01.01.17.07', 'name' => 'Pembangunan sarana dan prasarana olahraga'],
                    ['code' => '1.01.1.01.01.17.09', 'name' => 'Pembangunan taman; lapangan upacara dan fasilitas parkir'],
                    ['code' => '1.01.1.01.01.17.12', 'name' => 'Pembangunan perpustakaan sekolah'],
                    ['code' => '1.01.1.01.01.17.15', 'name' => 'Pengadaan buku-buku dan alat tulis siswa'],
                    ['code' => '1.01.1.01.01.17.18', 'name' => 'Pengadaan alat praktik dan peraga siswa'],
                    ['code' => '1.01.1.01.01.17.19', 'name' => 'Pengadaan mebeluer sekolah'],
                    ['code' => '1.01.1.01.01.17.20', 'name' => 'Pengadaan perlengkapan sekolah'],
                    ['code' => '1.01.1.01.01.17.44', 'name' => 'Rehabilitasi sedang/berat ruang kelas sekolah'],
                    ['code' => '1.01.1.01.01.17.53', 'name' => 'Rehabilitasi sedang/berat perpustakaan sekolah'],
                    ['code' => '1.01.1.01.01.17.57', 'name' => 'Pelatihan Kompetensi tenaga pendidik'],
                    ['code' => '1.01.1.01.01.17.58', 'name' => 'Pelatihan penyusunan kurikulum'],
                    ['code' => '1.01.1.01.01.17.62', 'name' => 'Penyediaan beasiswa bagi keluarga tidak mampu'],
                    ['code' => '1.01.1.01.01.17.63', 'name' => 'Penyelenggaraan Paket C setara SMU'],
                    ['code' => '1.01.1.01.01.17.64', 'name' => 'Pembinaan kelembagaan dan manajemen sekolah dengan penerapan MBS'],
                    ['code' => '1.01.1.01.01.17.65', 'name' => 'Pengembangan metode belajar dan mengajar'],
                    ['code' => '1.01.1.01.01.17.67', 'name' => 'Penyebarluasan dan sosialisasi berbagai informasi pendidikan menengah'],
                    ['code' => '1.01.1.01.01.17.69', 'name' => 'Monitoring; evaluasi dan pelaporan'],
                    ['code' => '1.01.1.01.01.17.70', 'name' => 'Pembinaan dan perkembangan kreatifitas siswa'],
                    ['code' => '1.01.1.01.01.17.71', 'name' => 'Lomba Kompetensi Siswa (LKS) dan OSN Siswa SMA'],
                    ['code' => '1.01.1.01.01.17.72', 'name' => 'Operasional SMA/SMK'],
                    ['code' => '1.01.1.01.01.17.73', 'name' => 'Bimbingan Profesional Guru dan Siswa Kelas 3 SMP/SMA Khusus'],
                    ['code' => '1.01.1.01.01.17.74', 'name' => 'Pekan Olah Raga dan Seni Qasidah (O2SN)'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.18',
                'name' => 'Program Pendidikan Non Formal',
                'children' => [
                    ['code' => '1.01.1.01.01.18.01', 'name' => 'Pemberdayaan tenaga pendidik non formal'],
                    ['code' => '1.01.1.01.01.18.02', 'name' => 'Pemberian bantuan operasional pendidikan non formal'],
                    ['code' => '1.01.1.01.01.18.03', 'name' => 'Pembinaan pendidikan kursus dan kelembagaan'],
                    ['code' => '1.01.1.01.01.18.04', 'name' => 'Pengembangan pendidikan keaksaraan'],
                    ['code' => '1.01.1.01.01.18.12', 'name' => 'Publikasi dan sosialisasi pendidikan non formal'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.20',
                'name' => 'Program Peningkatan Mutu Pendidik dan Tenaga Kependidikan',
                'children' => [
                    ['code' => '1.01.1.01.01.20.01', 'name' => 'Pelaksanaan sertifikasi pendidik'],
                    ['code' => '1.01.1.01.01.20.03', 'name' => 'Pelatihan bagi pendidik untuk memenuhi standar kompetensi'],
                    ['code' => '1.01.1.01.01.20.04', 'name' => 'Pembinaan Kelompok Kerja Guru (KKG)'],
                    ['code' => '1.01.1.01.01.20.07', 'name' => 'Pendidikan lanjutan bagi pendidik untuk memenuhi standar kualifikasi'],
                    ['code' => '1.01.1.01.01.20.08', 'name' => 'Pengembangan mutu dan kualitas program pendidikan dan pelatihan'],
                    ['code' => '1.01.1.01.01.20.11', 'name' => 'Pengembangan sistem perencanaan dan pengendalian program profesi'],
                ],
            ],
            [
                'sector_id' => $secPendidikan,
                'code' => '1.01.1.01.01.22',
                'name' => 'Program Manajemen Pelayanan Pendidikan',
                'children' => [
                    ['code' => '1.01.1.01.01.22.01', 'name' => 'Pelaksanaan evaluasi hasil kinerja bidang pendidikan'],
                    ['code' => '1.01.1.01.01.22.02', 'name' => 'Pelaksanaan kerjasama secara kelembagaan di bidang pendidikan'],
                    ['code' => '1.01.1.01.01.22.05', 'name' => 'Pembinaan Dewan Pendidikan'],
                    ['code' => '1.01.1.01.01.22.07', 'name' => 'Penerapan sistem dan informasi manajemen pendidikan'],
                    ['code' => '1.01.1.01.01.22.08', 'name' => 'Penyelenggaraan pelatihan; seminar dan lokakarya; serta diskusi ilmiah'],
                    ['code' => '1.01.1.01.01.22.09', 'name' => 'Monitoring; evaluasi dan pelaporan'],
                    ['code' => '1.01.1.01.01.22.13', 'name' => 'Pembinaan siswa OSN SD/MI; SLTP dan SLTA'],
                    ['code' => '1.01.1.01.01.22.18', 'name' => 'Penggandaan naskah; Pendistribusian; Pencetakan LJK UNAS SLB; SMP/MTs'],
                    ['code' => '1.01.1.01.01.22.19', 'name' => 'Dukungan siswa berprestasi dan guru pendamping siswa berprestasi SD/MI'],
                    ['code' => '1.01.1.01.01.22.23', 'name' => 'HUT dan Rakornas PGRI'],
                ],
            ],

            // ==========================================
            // 2. URUSAN KESEHATAN (1.02 - Dinkes & RSUD)
            // ==========================================
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.01',
                'name' => 'Program Pelayanan Administrasi Perkantoran (Dinkes)',
                'children' => [
                    ['code' => '1.02.1.02.01.01.01', 'name' => 'Penyediaan jasa surat menyurat'],
                    ['code' => '1.02.1.02.01.01.02', 'name' => 'Penyediaan jasa komunikasi; sumber daya air dan listrik'],
                    ['code' => '1.02.1.02.01.01.06', 'name' => 'Penyediaan jasa pemeliharaan dan perizinan kendaraan dinas/operasional'],
                    ['code' => '1.02.1.02.01.01.07', 'name' => 'Penyediaan jasa administrasi keuangan'],
                    ['code' => '1.02.1.02.01.01.08', 'name' => 'Penyediaan jasa kebersihan/caraka/peramu/keamanan/sopir kantor'],
                    ['code' => '1.02.1.02.01.01.10', 'name' => 'Penyediaan alat tulis kantor'],
                    ['code' => '1.02.1.02.01.01.11', 'name' => 'Penyediaan barang cetakan dan penggandaan'],
                    ['code' => '1.02.1.02.01.01.12', 'name' => 'Penyediaan komponen instalasi listrik/penerangan bangunan kantor'],
                    ['code' => '1.02.1.02.01.01.15', 'name' => 'Penyediaan bahan bacaan'],
                    ['code' => '1.02.1.02.01.01.18', 'name' => 'Rapat-rapat koordinasi dan konsultasi ke dalam dan luar daerah'],
                    ['code' => '1.02.1.02.01.01.19', 'name' => 'Penyediaan jasa administrasi dan teknis perkantoran'],
                    ['code' => '1.02.1.02.01.01.27', 'name' => 'Penyelesaian Produk Hukum (Perda, Perbup) atas inisiatif eksekutif & Legislatif'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.15',
                'name' => 'Program Obat dan Perbekalan Kesehatan',
                'children' => [
                    ['code' => '1.02.1.02.01.15.01', 'name' => 'Pengadaan Obat dan Perbekalan Kesehatan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.16',
                'name' => 'Program Upaya Kesehatan Masyarakat',
                'children' => [
                    ['code' => '1.02.1.02.01.16.01', 'name' => 'Pelayanan kesehatan penduduk miskin di puskesmas jaringannya'],
                    ['code' => '1.02.1.02.01.16.09', 'name' => 'Peningkatan kesehatan masyarakat'],
                    ['code' => '1.02.1.02.01.16.11', 'name' => 'Peningkatan pelayanan dan penanggulangan masalah kesehatan'],
                    ['code' => '1.02.1.02.01.16.13', 'name' => 'Penyediaan biaya operasional dan pemeliharaan'],
                    ['code' => '1.02.1.02.01.16.15', 'name' => 'Monitoring, evaluasi dan pelaporan'],
                    ['code' => '1.02.1.02.01.16.17', 'name' => 'Pembinaan dan fasilitasi kesehatan remaja'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.17',
                'name' => 'Program Pengawasan Obat dan Makanan',
                'children' => [
                    ['code' => '1.02.1.02.01.17.01', 'name' => 'Peningkatan pemberdayaan konsumen/masyarakat di bidang obat dan makanan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.19',
                'name' => 'Program Promosi Kesehatan dan Pemberdayaan Masyarakat',
                'children' => [
                    ['code' => '1.02.1.02.01.19.01', 'name' => 'Pengembangan media promosi dan informasi sadar hidup sehat'],
                    ['code' => '1.02.1.02.01.19.02', 'name' => 'Penyuluhan masyarakat pola hidup sehat'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.20',
                'name' => 'Program Perbaikan Gizi Masyarakat',
                'children' => [
                    ['code' => '1.02.1.02.01.20.01', 'name' => 'Penyusunan peta informasi masyarakat kurang gizi'],
                    ['code' => '1.02.1.02.01.20.02', 'name' => 'Pemberian tambahan makanan dan vitamin'],
                    ['code' => '1.02.1.02.01.20.03', 'name' => 'Penanggulangan Kurang Energi Protein (KEP); Anemia Gizi Besi; GAKY'],
                    ['code' => '1.02.1.02.01.20.04', 'name' => 'Pemberdayaan masyarakat untuk pencapaian keluarga sadar gizi'],
                    ['code' => '1.02.1.02.01.20.06', 'name' => 'Monitoring; evaluasi dan pelaporan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.21',
                'name' => 'Program pengembangan lingkungan sehat',
                'children' => [
                    ['code' => '1.02.1.02.01.21.01', 'name' => 'Pengkajian pengembangan lingkungan sehat'],
                    ['code' => '1.02.1.02.01.21.02', 'name' => 'Penyuluhan menciptakan lingkungan sehat'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.22',
                'name' => 'Program pencegahan dan penanggulangan penyakit menular',
                'children' => [
                    ['code' => '1.02.1.02.01.22.01', 'name' => 'Penyemprotan/fogging sarang nyamuk'],
                    ['code' => '1.02.1.02.01.22.02', 'name' => 'Pengadaan alat fogging dan bahan-bahan fogging'],
                    ['code' => '1.02.1.02.01.22.06', 'name' => 'Pencegahan penularan penyakit Endemik/Epidemik'],
                    ['code' => '1.02.1.02.01.22.08', 'name' => 'Peningkatan Imunisasi'],
                    ['code' => '1.02.1.02.01.22.09', 'name' => 'Peningkatan Surveillance Epidemiologi dan Penanggulangan Wabah'],
                    ['code' => '1.02.1.02.01.22.10', 'name' => 'Peningkatan komunikasi; informasi dan edukasi (KIE) pencegahan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.23',
                'name' => 'Program standarisasi pelayanan kesehatan',
                'children' => [
                    ['code' => '1.02.1.02.01.23.01', 'name' => 'Penyusunan standar pelayanan kesehatan'],
                    ['code' => '1.02.1.02.01.23.03', 'name' => 'Pembangunan dan pemutakhiran data dasar standar pelayanan kesehatan'],
                    ['code' => '1.02.1.02.01.23.05', 'name' => 'Penyusunan standar analisis belanja pelayanan kesehatan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.25',
                'name' => 'Program pengadaan, peningkatan dan perbaikan sarana dan prasarana',
                'children' => [
                    ['code' => '1.02.1.02.01.25.01', 'name' => 'Pembangunan puskesmas'],
                    ['code' => '1.02.1.02.01.25.02', 'name' => 'Pembangunan puskesmas pembantu'],
                    ['code' => '1.02.1.02.01.25.04', 'name' => 'Pengadaan puskesmas keliling'],
                    ['code' => '1.02.1.02.01.25.06', 'name' => 'Pengadaan sarana dan prasarana puskesmas'],
                    ['code' => '1.02.1.02.01.25.13', 'name' => 'Pemeliharaan rutin/berkala sarana dan prasarana puskesmas'],
                    ['code' => '1.02.1.02.01.25.16', 'name' => 'Pemeliharaan rutin/berkala sarana dan prasarana puskesmas keliling'],
                    ['code' => '1.02.1.02.01.25.20', 'name' => 'Rehabilitasi sedang/berat puskesmas pembantu'],
                    ['code' => '1.02.1.02.01.25.22', 'name' => 'Monitoring; evaluasi dan pelaporan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.28',
                'name' => 'Program kemitraan peningkatan pelayanan kesehatan',
                'children' => [
                    ['code' => '1.02.1.02.01.28.01', 'name' => 'Kemitraan asuransi kesehatan masyarakat'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.30',
                'name' => 'Program peningkatan pelayanan kesehatan lansia',
                'children' => [
                    ['code' => '1.02.1.02.01.30.01', 'name' => 'Pelayanan pemeliharaan kesehatan'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.01.32',
                'name' => 'Program peningkatan keselamatan ibu melahirkan dan anak',
                'children' => [
                    ['code' => '1.02.1.02.01.32.01', 'name' => 'Penyuluhan kesehatan bagi ibu hamil dari keluarga kurang mampu'],
                    ['code' => '1.02.1.02.01.32.02', 'name' => 'Perawatan secara berkala bagi ibu hamil bagi keluarga kurang mampu'],
                    ['code' => '1.02.1.02.01.32.03', 'name' => 'Pertolongan persalinan bagi ibu dari keluarga kurang mampu'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.02.24',
                'name' => 'Program pelayanan kesehatan penduduk miskin (RSUD)',
                'children' => [
                    ['code' => '1.02.1.02.02.24.11', 'name' => 'Penyelenggaraan Pelayanan Pasien Miskin dan gelandangan psikotik'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.02.26',
                'name' => 'Program pengadaan; peningkatan sarana dan prasarana rumah sakit',
                'children' => [
                    ['code' => '1.02.1.02.02.26.01', 'name' => 'Pembangunan rumah sakit'],
                    ['code' => '1.02.1.02.02.26.18', 'name' => 'Pengadaan alat-alat kesehatan rumah sakit'],
                    ['code' => '1.02.1.02.02.26.19', 'name' => 'Pengadaan obat-obatan rumah sakit'],
                    ['code' => '1.02.1.02.02.26.20', 'name' => 'Pengadaan ambulance/mobil jenazah'],
                    ['code' => '1.02.1.02.02.26.21', 'name' => 'Pengadaan mebeulair rumah sakit'],
                    ['code' => '1.02.1.02.02.26.22', 'name' => 'Pengadaan perlengkapan rumah tangga rumah sakit'],
                    ['code' => '1.02.1.02.02.26.23', 'name' => 'Pengadaan bahan-bahan logistik rumah sakit'],
                    ['code' => '1.02.1.02.02.26.24', 'name' => 'Pengadaan pencetakan administrasi dan surat menyurat rumah sakit'],
                ],
            ],
            [
                'sector_id' => $secKesehatan,
                'code' => '1.02.1.02.02.40',
                'name' => 'Badan Layanan Umum Daerah (BLUD)',
                'children' => [
                    ['code' => '1.02.1.02.02.40.01', 'name' => 'Badan Layanan Umum Daerah (BLUD)'],
                ],
            ],

            // ==========================================
            // 3. URUSAN INFRASTRUKTUR / PEKERJAAN UMUM (1.03)
            // ==========================================
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.01',
                'name' => 'Program Pelayanan Administrasi Perkantoran',
                'children' => [
                    ['code' => '1.03.1.03.01.01.01', 'name' => 'Penyediaan jasa surat menyurat'],
                    ['code' => '1.03.1.03.01.01.02', 'name' => 'Penyediaan jasa komunikasi; sumber daya air dan listrik'],
                    ['code' => '1.03.1.03.01.01.06', 'name' => 'Penyediaan jasa pemeliharaan dan perizinan kendaraan dinas/operasional'],
                    ['code' => '1.03.1.03.01.01.07', 'name' => 'Penyediaan jasa administrasi keuangan'],
                    ['code' => '1.03.1.03.01.01.08', 'name' => 'Penyediaan jasa kebersihan/caraka/peramu/keamanan/sopir kantor'],
                    ['code' => '1.03.1.03.01.01.09', 'name' => 'Penyediaan jasa perbaikan peralatan kerja'],
                    ['code' => '1.03.1.03.01.01.10', 'name' => 'Penyediaan alat tulis kantor'],
                    ['code' => '1.03.1.03.01.01.11', 'name' => 'Penyediaan barang cetakan dan penggandaan'],
                    ['code' => '1.03.1.03.01.01.12', 'name' => 'Penyediaan komponen instalasi listrik/penerangan bangunan kantor'],
                    ['code' => '1.03.1.03.01.01.13', 'name' => 'Penyediaan peralatan dan perlengkapan kantor'],
                    ['code' => '1.03.1.03.01.01.15', 'name' => 'Penyediaan bahan bacaan'],
                    ['code' => '1.03.1.03.01.01.17', 'name' => 'Penyediaan makanan dan minuman'],
                    ['code' => '1.03.1.03.01.01.18', 'name' => 'Rapat-rapat koordinasi dan konsultasi ke dalam dan luar daerah'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.15',
                'name' => 'Program pembangunan jalan dan jembatan',
                'children' => [
                    ['code' => '1.03.1.03.01.15.01', 'name' => 'Perencanaan pembangunan jalan'],
                    ['code' => '1.03.1.03.01.15.03', 'name' => 'Pembangunan jalan'],
                    ['code' => '1.03.1.03.01.15.05', 'name' => 'Pembangunan jembatan'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.18',
                'name' => 'Program rehabilitasi/pemeliharaan jalan dan jembatan',
                'children' => [
                    ['code' => '1.03.1.03.01.18.01', 'name' => 'Perencanaan rehabilitasi/pemeliharaan jalan'],
                    ['code' => '1.03.1.03.01.18.03', 'name' => 'Rehabilitasi/pemeliharaan jalan'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.24',
                'name' => 'Program Pengembangan dan Pengelolaan Jaringan Irigasi; Rawa dan Jaringan Pengairan Lainnya',
                'children' => [
                    ['code' => '1.03.1.03.01.24.01', 'name' => 'Perencanaan pembangunan jaringan irigasi'],
                    ['code' => '1.03.1.03.01.24.10', 'name' => 'Rehabilitasi/pemeliharaan jaringan irigasi'],
                    ['code' => '1.03.1.03.01.24.14', 'name' => 'Rehabilitasi/pemeliharaan normalisasi saluran sungai'],
                    ['code' => '1.03.1.03.01.24.18', 'name' => 'Pembangunan jaringan irigasi'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.26',
                'name' => 'Program Pengembangan; Pengelolaan; dan Konservasi Sungai; Danau dan Sumber Daya Air Lainnya',
                'children' => [
                    ['code' => '1.03.1.03.01.26.01', 'name' => 'Pembangunan embung; dan bangunan penampung air lainnya'],
                    ['code' => '1.03.1.03.01.26.02', 'name' => 'Pemeliharaan dan rehabilitasi embung; dan bangunan penampung air lainnya'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.27',
                'name' => 'Program Pengembangan Kinerja Pengelolaan Air Minum dan Air Limbah',
                'children' => [
                    ['code' => '1.03.1.03.01.27.01', 'name' => 'Penyediaan prasarana dan sarana air minum bagi masyarakat berpenghasilan rendah'],
                    ['code' => '1.03.1.03.01.27.02', 'name' => 'Penyediaan prasarana dan sarana air limbah'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.28',
                'name' => 'Program Pengendalian Banjir',
                'children' => [
                    ['code' => '1.03.1.03.01.28.09', 'name' => 'Pembangunan prasarana pengaman pantai'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.29',
                'name' => 'Program Pengembangan Wilayah Strategis dan Cepat Tumbuh',
                'children' => [
                    ['code' => '1.03.1.03.01.29.02', 'name' => 'Pembangunan/peningkatan infrastruktur'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.30',
                'name' => 'Program pembangunan infrastruktur perdesaaan',
                'children' => [
                    ['code' => '1.03.1.03.01.30.01', 'name' => 'Penataan lingkungan pemukiman penduduk perdesaaan'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.31',
                'name' => 'Program pengembangan Sistem Informasi Ke-PU-an',
                'children' => [
                    ['code' => '1.03.1.03.01.31.01', 'name' => 'Pengumpulan Updating dan Analisa Data/Sistem kePUan'],
                    ['code' => '1.03.1.03.01.31.02', 'name' => 'Monitoring, evaluasi dan pelaporan'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.33',
                'name' => 'Program Pemberdayaan Jasa Konstruksi',
                'children' => [
                    ['code' => '1.03.1.03.01.33.02', 'name' => 'Pemberdayaan Pengguna Jasa Konstruksi'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.36',
                'name' => 'Program dan Pengembangan Bidang Ketenagalistrikan',
                'children' => [
                    ['code' => '1.03.1.03.01.36.01', 'name' => 'Pengembangan Pembangkit Listrik Tenaga Surya'],
                ],
            ],
            [
                'sector_id' => $secInfrastruktur,
                'code' => '1.03.1.03.01.37',
                'name' => 'Program Perencanaan Tata Ruang',
                'children' => [
                    ['code' => '1.03.1.03.01.37.05', 'name' => 'Penyusunan Rencana Detail Tata Ruang Kawasan'],
                ],
            ],

            // ==========================================
            // 4. URUSAN SOSIAL (1.13)
            // ==========================================
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.15',
                'name' => 'Program Pemberdayaan Fakir Miskin; Komunitas Adat Terpencil',
                'children' => [
                    ['code' => '1.13.1.13.01.15.01', 'name' => 'Peningkatan Kemampuan (Capacity Building) petugas dan pendamping sosial'],
                    ['code' => '1.13.1.13.01.15.02', 'name' => 'Pelatihan ketrampilan berusaha bagi keluarga miskin'],
                    ['code' => '1.13.1.13.01.15.04', 'name' => 'Pengadaan sarana dan prasarana pendukung usaha bagi keluarga miskin'],
                ],
            ],
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.16',
                'name' => 'Program Pelayanan dan Rehabilitasi Kesejahteraan Sosial',
                'children' => [
                    ['code' => '1.13.1.13.01.16.02', 'name' => 'Pelayanan dan perlindungan sosial; hukum bagi korban eksploitasi'],
                    ['code' => '1.13.1.13.01.16.03', 'name' => 'Pelaksanaan KIE konseling dan kampanye sosial bagi Penyandang Masalah'],
                    ['code' => '1.13.1.13.01.16.07', 'name' => 'Peningkatan kualitas pelayanan; sarana; dan prasarana rehabilitasi'],
                    ['code' => '1.13.1.13.01.16.09', 'name' => 'Koordinasi perumusan kebijakan dan sinkronisasi pelaksanaan upaya-upaya'],
                    ['code' => '1.13.1.13.01.16.10', 'name' => 'Bantuan Rehabilitasi Sosial Rumah Tidak Layak Huni'],
                    ['code' => '1.13.1.13.01.16.14', 'name' => 'Pembinaan Lanjut Usia'],
                    ['code' => '1.13.1.13.01.16.15', 'name' => 'Pelayanan dan rehabilitasi sosial Paca'],
                    ['code' => '1.13.1.13.01.16.16', 'name' => 'Pelayanan dan rehabilitasi sosial Tuna Sosial'],
                    ['code' => '1.13.1.13.01.16.18', 'name' => 'Penanganan masalah-masalah strategis yang menyangkut tanggap cepat'],
                ],
            ],
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.17',
                'name' => 'Program pembinaan anak terlantar',
                'children' => [
                    ['code' => '1.13.1.13.01.17.02', 'name' => 'Pelatihan ketrampilan dan praktek belajar kerja bagi anak terlantar'],
                    ['code' => '1.13.1.13.01.17.03', 'name' => 'Penyusunan data dan analisis permasalahan anak terlantar'],
                    ['code' => '1.13.1.13.01.17.04', 'name' => 'Pengembangan bakat dan ketrampilan anak terlantar'],
                ],
            ],
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.18',
                'name' => 'Program pembinaan para penyandang cacat dan trauma',
                'children' => [
                    ['code' => '1.13.1.13.01.18.03', 'name' => 'Pendidikan dan pelatihan bagi penyandang cacat dan eks trauma'],
                ],
            ],
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.21',
                'name' => 'Program Pemberdayaan Kelembagaan Kesejahteraan Sosial',
                'children' => [
                    ['code' => '1.13.1.13.01.21.01', 'name' => 'Peningkatan peran aktif masyarakat dan dunia usaha'],
                    ['code' => '1.13.1.13.01.21.02', 'name' => 'Peningkatan jejaring kerjasama pelaku-pelaku usaha kesejahteraan sosial'],
                    ['code' => '1.13.1.13.01.21.03', 'name' => 'Peningkatan kualitas SDM kesejahteraan sosial masyarakat'],
                    ['code' => '1.13.1.13.01.21.04', 'name' => 'Pengembangan model kelembagaan perlindungan sosial'],
                    ['code' => '1.13.1.13.01.21.05', 'name' => 'Pemberdayaan Karang taruna'],
                    ['code' => '1.13.1.13.01.21.06', 'name' => 'Pemberdayaan PSM'],
                    ['code' => '1.13.1.13.01.21.07', 'name' => 'Pemberdayaan WKSBM'],
                    ['code' => '1.13.1.13.01.21.09', 'name' => 'Pemberdayaan kelembagaan Sosial masyarakat'],
                    ['code' => '1.13.1.13.01.21.10', 'name' => 'Pemutakhiran Data PMKS dan PSKS'],
                    ['code' => '1.13.1.13.01.21.18', 'name' => 'Peningkatan Nilai-nilai Kepahlawanan, Keperintisan dan Kesetiakawanan Sosial'],
                ],
            ],
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.22',
                'name' => 'Program Penanganan Masalah-masalah yang menyangkut Tanggap Darurat',
                'children' => [
                    ['code' => '1.13.1.13.01.22.01', 'name' => 'Jaminan Sosial Korban Bencana Alam'],
                    ['code' => '1.13.1.13.01.22.02', 'name' => 'Jaminan Sosial Korban Bencana Sosial'],
                    ['code' => '1.13.1.13.01.22.04', 'name' => 'Pengumpulan Pengelolaan SDS'],
                    ['code' => '1.13.1.13.01.22.06', 'name' => 'Pengadaan Sarana dan Prasarana Pendukung Bagi Penanggulangan Bencana'],
                ],
            ],
            [
                'sector_id' => $secSosial,
                'code' => '1.13.1.13.01.24',
                'name' => 'Program Pemantapan Pelaksanaan Program Keluarga Harapan',
                'children' => [
                    ['code' => '1.13.1.13.01.24.05', 'name' => 'Dukungan Program Pengembangan Program Keluarga Harapan'],
                ],
            ],

            // ==========================================
            // 5. URUSAN PILIHAN (2.01)
            // ==========================================
            [
                'sector_id' => $secPilihan,
                'code' => '1.13.01.13',
                'name' => 'Program Pelayanan Administrasi Perkantoran (Urusan Pilihan)',
                'children' => [
                    ['code' => '1.13.01.13.01', 'name' => 'Penyediaan jasa surat menyurat'],
                    ['code' => '1.13.01.13.02', 'name' => 'Penyediaan jasa komunikasi, sumber daya air dan listrik'],
                    ['code' => '1.13.01.13.03', 'name' => 'Penyediaan jasa peralatan dan perlengkapan kantor'],
                    ['code' => '1.13.01.13.04', 'name' => 'Penyediaan jasa jaminan pemeliharaan kesehatan PNS'],
                    ['code' => '1.13.01.13.05', 'name' => 'Penyediaan jasa jaminan barang milik daerah'],
                    ['code' => '1.13.01.13.06', 'name' => 'Penyediaan jasa pemeliharaan dan perizinan kendaraan dinas/operasional'],
                    ['code' => '1.13.01.13.07', 'name' => 'Penyediaan jasa administrasi keuangan'],
                    ['code' => '1.13.01.13.08', 'name' => 'Penyediaan jasa kebersihan kantor'],
                    ['code' => '1.13.01.13.09', 'name' => 'Penyediaan jasa perbaikan peralatan kerja'],
                    ['code' => '1.13.01.13.10', 'name' => 'Penyediaan alat tulis kantor'],
                    ['code' => '1.13.01.13.11', 'name' => 'Penyediaan barang cetakan dan penggandaan'],
                    ['code' => '1.13.01.13.12', 'name' => 'Penyediaan komponen instalasi listrik/penerangan bangunan kantor'],
                    ['code' => '1.13.01.13.13', 'name' => 'Penyediaan peralatan dan perlengkapan kantor'],
                    ['code' => '1.13.01.13.14', 'name' => 'Penyediaan peralatan rumah tangga'],
                    ['code' => '1.13.01.13.15', 'name' => 'Penyediaan bahan bacaan dan peraturan perundang-undangan'],
                    ['code' => '1.13.01.13.16', 'name' => 'Penyediaan bahan logistik kantor'],
                    ['code' => '1.13.01.13.17', 'name' => 'Penyediaan makanan dan minuman'],
                    ['code' => '1.13.01.13.18', 'name' => 'Rapat-rapat koordinasi dan konsultasi ke luar daerah'],
                ],
            ],
            [
                'sector_id' => $secPilihan,
                'code' => '1.13.01.04',
                'name' => 'Program fasilitas pindah/purna tugas PNS',
                'children' => [
                    ['code' => '1.13.01.04.01', 'name' => 'Pemulangan pegawai yang pensiun'],
                    ['code' => '1.13.01.04.02', 'name' => 'Pemulangan pegawai yang tewas dalam melaksanakan tugas'],
                    ['code' => '1.13.01.04.03', 'name' => 'Pemindahan tugas PNS'],
                ],
            ],
            [
                'sector_id' => $secPilihan,
                'code' => '1.13.01.05',
                'name' => 'Program peningkatan kapasitas sumber daya aparatur (Pilihan)',
                'children' => [
                    ['code' => '1.13.01.05.01', 'name' => 'Pendidikan dan pelatihan formal'],
                    ['code' => '1.13.01.05.02', 'name' => 'Sosialisasi peraturan perundang-undangan'],
                    ['code' => '1.13.01.05.03', 'name' => 'Bimtek implementasi peraturan perundang-undangan'],
                ],
            ],
            [
                'sector_id' => $secPilihan,
                'code' => '1.13.01.06',
                'name' => 'Program peningkatan pengembangan sistem pelaporan capaian kinerja dan keuangan',
                'children' => [
                    ['code' => '1.13.01.06.01', 'name' => 'Penyusunan laporan capaian kinerja dan ikhtisar realisasi kinerja SKPD'],
                    ['code' => '1.13.01.06.02', 'name' => 'Penyusunan pelaporan keuangan semesteran'],
                    ['code' => '1.13.01.06.03', 'name' => 'Penyusunan pelaporan prognosis realisasi anggaran'],
                    ['code' => '1.13.01.06.04', 'name' => 'Penyusunan pelaporan keuangan akhir tahun'],
                ],
            ],
        ];

        // Eksekusi simpan Program Utama (Level 1) & Sub-Kegiatan (Level 2)
        foreach ($data as $progData) {
            $parent = MasterProgram::updateOrCreate(
                ['code' => $progData['code'], 'sector_id' => $progData['sector_id']],
                [
                    'parent_id' => null,
                    'name'      => $progData['name'],
                    'level'     => 1,
                ]
            );

            foreach ($progData['children'] as $child) {
                MasterProgram::updateOrCreate(
                    ['code' => $child['code'], 'sector_id' => $progData['sector_id']],
                    [
                        'parent_id' => $parent->id,
                        'name'      => $child['name'],
                        'level'     => 2,
                    ]
                );
            }
        }
    }
}