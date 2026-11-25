<!-- Modal Pop-Up Panduan & Kamus Indikator -->
<div id="indicatorGuideModal" class="guide-modal-backdrop d-none" role="dialog" aria-modal="true" aria-labelledby="guideModalTitle">
    <div class="guide-modal-dialog shadow-lg">
        <!-- Header Modal -->
        <div class="guide-modal-header bg-primary text-white px-4 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="m-0 fw-bold" id="guideModalTitle">
                    <i class="bi bi-book-half me-2"></i>Panduan & Kamus Indikator Makro / APBD
                </h5>
                <small class="opacity-75">Referensi Definisi Operasional (26 Indikator), Rumus Analisis, dan Cara Membaca Grafik</small>
            </div>
            <button type="button" class="btn-close btn-close-white" id="closeGuideModalBtn" aria-label="Tutup"></button>
        </div>

        <!-- Navigasi 3 Tab Panduan -->
        <div class="bg-light border-bottom px-4 pt-2">
            <ul class="nav nav-tabs border-bottom-0 gap-1" id="guideModalTabs">
                <li class="nav-item">
                    <button type="button" class="nav-link fw-semibold active guide-tab-btn" data-target="#guide-tab-kamus">
                        <i class="bi bi-list-ul me-1"></i> 1. Kamus 26 Indikator
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link fw-semibold guide-tab-btn" data-target="#guide-tab-rumus">
                        <i class="bi bi-calculator me-1"></i> 2. Istilah & Rumus Analisis
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link fw-semibold guide-tab-btn" data-target="#guide-tab-grafik">
                        <i class="bi bi-bar-chart-steps me-1"></i> 3. Cara Membaca Grafik
                    </button>
                </li>
            </ul>
        </div>

        <!-- Isi Konten Modal -->
        <div class="guide-modal-body p-4">

            <!-- TAB 1: KAMUS 26 INDIKATOR (5 SEKTOR) -->
            <div class="guide-tab-pane" id="guide-tab-kamus">
                <div class="mb-3">
                    <input type="text" id="guideSearchInput" class="form-control form-control-sm" placeholder="🔍 Cari nama indikator atau istilah (misal: P0, Stunting, Gini, RLS)...">
                </div>

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-sm align-middle mb-0" id="guideIndicatorTable">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th style="width: 170px;">Sektor</th>
                                <th style="width: 220px;">Nama Indikator</th>
                                <th>Definisi Operasional</th>
                                <th style="width: 130px;">Satuan</th>
                                <th style="width: 115px;" class="text-center">Arah Baik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sektor 1: Kemiskinan Makro -->
                            <tr>
                                <td><span class="badge bg-danger">Kemiskinan Makro</span></td>
                                <td class="fw-semibold">Persentase Penduduk Miskin (P0)</td>
                                <td>Proporsi penduduk yang memiliki rata-rata pengeluaran per kapita per bulan di bawah Garis Kemiskinan.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Kemiskinan Makro</span></td>
                                <td class="fw-semibold">Garis Kemiskinan (GK)</td>
                                <td>Nilai rupiah pengeluaran minimum untuk memenuhi kebutuhan dasar makanan (setara 2.100 kkal/hari) dan non-makanan.</td>
                                <td>Rp/kapita/bulan</td>
                                <td class="text-center text-muted">Referensi</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Kemiskinan Makro</span></td>
                                <td class="fw-semibold">Indeks Kedalaman Kemiskinan (P1)</td>
                                <td>Ukuran rata-rata kesenjangan pengeluaran masing-masing penduduk miskin terhadap Garis Kemiskinan.</td>
                                <td>Indeks</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Kemiskinan Makro</span></td>
                                <td class="fw-semibold">Indeks Keparahan Kemiskinan (P2)</td>
                                <td>Gambaran mengenai penyebaran atau ketimpangan pengeluaran di antara sesama penduduk miskin.</td>
                                <td>Indeks</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Kemiskinan Makro</span></td>
                                <td class="fw-semibold">Jumlah Penduduk Miskin</td>
                                <td>Total populasi penduduk yang berada di bawah garis kemiskinan pada wilayah dan tahun tertentu.</td>
                                <td>Ribu Jiwa</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>

                            <!-- Sektor 2: Pendidikan -->
                            <tr>
                                <td><span class="badge bg-primary">Pendidikan</span></td>
                                <td class="fw-semibold">Harapan Lama Sekolah (HLS)</td>
                                <td>Lamanya sekolah (dalam tahun) yang diharapkan akan dirasakan oleh anak pada umur tertentu di masa mendatang.</td>
                                <td>Tahun</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pendidikan</span></td>
                                <td class="fw-semibold">Rata-rata Lama Sekolah (RLS)</td>
                                <td>Jumlah tahun yang digunakan oleh penduduk usia 25 tahun ke atas dalam menjalani pendidikan formal.</td>
                                <td>Tahun</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pendidikan</span></td>
                                <td class="fw-semibold">Angka Partisipasi Murni (APM)</td>
                                <td>Proporsi anak sekolah pada satu kelompok umur tertentu yang bersekolah pada jenjang sesuai kelompok umurnya.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pendidikan</span></td>
                                <td class="fw-semibold">Angka Partisipasi Kasar (APK)</td>
                                <td>Perbandingan jumlah siswa pada jenjang pendidikan tertentu terhadap penduduk kelompok usia sekolah jenjang tersebut.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pendidikan</span></td>
                                <td class="fw-semibold">Angka Melek Huruf (AMH)</td>
                                <td>Persentase penduduk usia 15 tahun ke atas yang dapat membaca dan menulis huruf latin atau huruf lainnya.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Pendidikan</span></td>
                                <td class="fw-semibold">Rasio Guru terhadap Murid</td>
                                <td>Perbandingan jumlah guru terhadap jumlah murid pada jenjang pendidikan dasar dan menengah.</td>
                                <td>Rasio</td>
                                <td class="text-center text-muted">Ideal</td>
                            </tr>

                            <!-- Sektor 3: Kesehatan -->
                            <tr>
                                <td><span class="badge bg-success">Kesehatan</span></td>
                                <td class="fw-semibold">Angka Harapan Hidup (AHH)</td>
                                <td>Perkiraan rata-rata tambahan umur seseorang yang diharapkan dapat terus hidup sejak dilahirkan.</td>
                                <td>Tahun</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Kesehatan</span></td>
                                <td class="fw-semibold">Angka Kematian Bayi (AKB)</td>
                                <td>Jumlah kematian bayi di bawah usia 1 tahun per 1.000 kelahiran hidup pada tahun tertentu.</td>
                                <td>per 1.000 KH</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Kesehatan</span></td>
                                <td class="fw-semibold">Prevalensi Stunting</td>
                                <td>Persentase balita yang mengalami gangguan pertumbuhan kronis akibat kekurangan gizi dalam jangka panjang.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Kesehatan</span></td>
                                <td class="fw-semibold">Persentase Persalinan oleh Nakes</td>
                                <td>Proporsi ibu bersalin yang proses persalinannya ditolong oleh tenaga kesehatan terlatih (dokter/bidan).</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Kesehatan</span></td>
                                <td class="fw-semibold">Rasio Nakes / Puskesmas</td>
                                <td>Ketersediaan tenaga kesehatan/puskesmas dibandingkan setiap 10.000 penduduk di suatu wilayah.</td>
                                <td>per 10 rb Pddk</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>

                            <!-- Sektor 4: Infrastruktur & Perumahan -->
                            <tr>
                                <td><span class="badge bg-warning text-dark">Infrastruktur</span></td>
                                <td class="fw-semibold">Akses Sanitasi Layak</td>
                                <td>Persentase rumah tangga yang menggunakan fasilitas tempat buang air besar yang memenuhi syarat kesehatan.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">Infrastruktur</span></td>
                                <td class="fw-semibold">Akses Air Minum Layak</td>
                                <td>Persentase rumah tangga yang memperoleh sumber air minum terlindung dan layak konsumsi.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">Infrastruktur</span></td>
                                <td class="fw-semibold">Perumahan Layak Huni</td>
                                <td>Persentase rumah tangga yang menempati rumah dengan ketahanan bangunan, luas lantai, dan akses dasar memadai.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">Infrastruktur</span></td>
                                <td class="fw-semibold">Panjang & Kondisi Jalan Mantap</td>
                                <td>Persentase panjang jalan kabupaten/kota/provinsi yang berada dalam kondisi baik dan sedang.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-warning text-dark">Infrastruktur</span></td>
                                <td class="fw-semibold">Rasio Elektrifikasi</td>
                                <td>Perbandingan jumlah rumah tangga berlistrik terhadap total seluruh rumah tangga di wilayah tersebut.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>

                            <!-- Sektor 5: Ketenagakerjaan & Ekonomi -->
                            <tr>
                                <td><span class="badge bg-info text-dark">Ekonomi & Kerja</span></td>
                                <td class="fw-semibold">Tingkat Pengangguran Terbuka (TPT)</td>
                                <td>Persentase jumlah pencari kerja/penganggur terhadap total angkatan kerja.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info text-dark">Ekonomi & Kerja</span></td>
                                <td class="fw-semibold">Laju Pertumbuhan Ekonomi (LPE)</td>
                                <td>Persentase kenaikan nilai tambah barang dan jasa (PDRB atas dasar harga konstan) dibanding tahun sebelumnya.</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info text-dark">Ekonomi & Kerja</span></td>
                                <td class="fw-semibold">Tingkat Partisipasi Angkatan Kerja (TPAK)</td>
                                <td>Persentase penduduk usia kerja (15 tahun ke atas) yang aktif secara ekonomi (bekerja atau mencari kerja).</td>
                                <td>Persen (%)</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info text-dark">Ekonomi & Kerja</span></td>
                                <td class="fw-semibold">PDRB per Kapita</td>
                                <td>Nilai PDRB dibagi jumlah penduduk pertengahan tahun, mencerminkan rata-rata pendapatan per penduduk.</td>
                                <td>Juta Rp/kap/thn</td>
                                <td class="text-center text-primary fw-bold">↑ Meningkat</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info text-dark">Ekonomi & Kerja</span></td>
                                <td class="fw-semibold">Indeks Gini (Gini Ratio)</td>
                                <td>Ukuran ketimpangan distribusi pendapatan/pengeluaran penduduk bernilai 0 (merata sempurna) hingga 1 (timpang sempurna).</td>
                                <td>Indeks (0–1)</td>
                                <td class="text-center text-success fw-bold">↓ Menurun</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: ISTILAH & RUMUS ANALISIS -->
            <div class="guide-tab-pane d-none" id="guide-tab-rumus">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-header bg-light fw-bold">1. Koefisien Korelasi Pearson (r)</div>
                            <div class="card-body small">
                                <p class="mb-2">Mengukur kekuatan dan arah hubungan linier antara variabel <strong>X</strong> dan variabel <strong>Y</strong> lintas kabupaten/kota (rentang <strong>-1,00</strong> s.d. <strong>+1,00</strong>):</p>
                                <ul class="mb-0 ps-3">
                                    <li><strong>r &lt; 0 (Negatif):</strong> Jika X naik, Y cenderung turun (misal: <em>Lama Sekolah naik &rarr; Kemiskinan P0 turun</em>).</li>
                                    <li><strong>r &gt; 0 (Positif):</strong> Jika X naik, Y ikut naik (misal: <em>Pengangguran naik &rarr; Kemiskinan P0 naik</em>).</li>
                                    <li><strong>|r| &ge; 0,70:</strong> Korelasi Kuat &bull; <strong>0,40–0,69:</strong> Sedang &bull; <strong>&lt; 0,40:</strong> Lemah.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-header bg-light fw-bold">2. Pemetaan Kuadran Wilayah (Scatter Plot)</div>
                            <div class="card-body small">
                                <p class="mb-2">Membagi kabupaten/kota ke dalam 4 kuadran berdasarkan garis potong <strong>Rata-rata Provinsi (X̄ dan Ȳ)</strong>:</p>
                                <ul class="mb-0 ps-3">
                                    <li><span class="badge bg-danger">Kuadran I</span> <strong>Prioritas Utama:</strong> Nilai Y &ge; rata-rata dan X &ge; rata-rata.</li>
                                    <li><span class="badge bg-warning text-dark">Kuadran II</span> <strong>Perhatian Khusus:</strong> Y &ge; rata-rata namun X &lt; rata-rata.</li>
                                    <li><span class="badge bg-info text-dark">Kuadran III</span> <strong>Relatif Baik:</strong> Y &lt; rata-rata dan X &lt; rata-rata.</li>
                                    <li><span class="badge bg-success">Kuadran IV</span> <strong>Ideal / Unggul:</strong> Y &lt; rata-rata dan X &ge; rata-rata (jika Y = P0).</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-header bg-light fw-bold">3. Struktur Kode Rekening APBD</div>
                            <div class="card-body small">
                                <ul class="mb-0 ps-3">
                                    <li><strong>Kode 4 (Pendapatan Daerah):</strong> PAD (4.1), Dana Perimbangan (4.2), Lain-lain Pendapatan Sah (4.3).</li>
                                    <li><strong>Kode 5.1 (Belanja Tidak Langsung):</strong> Pegawai, Bunga, Subsidi, Hibah, Bansos, Bagi Hasil, Bantuan Keuangan, Tak Terduga.</li>
                                    <li><strong>Kode 5.2 (Belanja Langsung):</strong> Akumulasi belanja program & sub-kegiatan per urusan (Pegawai, Barang/Jasa, Modal).</li>
                                    <li><strong>Kode 6 (Pembiayaan Neto):</strong> Penerimaan Pembiayaan (6.1) dikurangi Pengeluaran Pembiayaan (6.2).</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border">
                            <div class="card-header bg-light fw-bold">4. Evaluasi Efektivitas Anggaran (vs T-1)</div>
                            <div class="card-body small">
                                <p class="mb-2">Membandingkan perubahan anggaran tahun berjalan terhadap tahun sebelumnya (<strong>T-1</strong>) dengan dampak perubahan Kemiskinan (<strong>P0</strong>):</p>
                                <ul class="mb-0 ps-3">
                                    <li><span class="badge bg-success">Sangat Efektif</span> Anggaran meningkat (&Delta; &gt; 0) dan persentase kemiskinan <strong>menurun (&Delta;P0 &lt; 0)</strong>.</li>
                                    <li><span class="badge bg-warning text-dark">Kurang Efektif</span> Anggaran meningkat (&Delta; &gt; 0), tetapi kemiskinan justru naik atau stagnan.</li>
                                    <li><span class="badge bg-light text-dark border">Netral</span> Tidak ada perubahan alokasi anggaran dibanding tahun sebelumnya.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CARA MEMBACA GRAFIK & FITUR -->
            <div class="guide-tab-pane d-none" id="guide-tab-grafik">
                <div class="list-group list-group-flush small">
                    <div class="list-group-item px-0">
                        <div class="fw-bold text-primary mb-1"><i class="bi bi-graph-up me-1"></i> 1. Trend Waktu (Time Series)</div>
                        Menampilkan arah perkembangan indikator dari tahun <strong>2025 hingga 2035</strong> pada wilayah yang dipilih, lengkap dengan garis pembanding <strong>Provinsi Kep. Bangka Belitung</strong> dan <strong>Nasional</strong>.
                    </div>
                    <div class="list-group-item px-0">
                        <div class="fw-bold text-primary mb-1"><i class="bi bi-bar-chart-steps me-1"></i> 2. Antar Wilayah (Cross-Section)</div>
                        Membandingkan peringkat capaian seluruh <strong>7 Kabupaten/Kota</strong> secara berdampingan pada satu tahun analisis terhadap garis <em>benchmark</em> rata-rata Provinsi.
                    </div>
                    <div class="list-group-item px-0">
                        <div class="fw-bold text-primary mb-1"><i class="bi bi-wallet2 me-1"></i> 3. Analisis APBD & Efektivitas</div>
                        Terdiri atas 4 sub-tampilan sinkron: <strong>Postur Umum APBD (4–6)</strong>, <strong>Tren & Komposisi Urusan</strong>, <strong>Relevansi & Efektivitas Belanja</strong>, serta <strong>Rincian Program (Level 1) & Sub-Kegiatan (Level 2)</strong>.
                    </div>
                    <div class="list-group-item px-0">
                        <div class="fw-bold text-primary mb-1"><i class="bi bi-diagram-3 me-1"></i> 4. Korelasi (Scatter Plot) & Cetak Laporan PDF</div>
                        Menguji hubungan dua indikator sekaligus dalam 4 kuadran prioritas. Gunakan tombol merah <strong>Cetak Laporan (PDF)</strong> di bar atas untuk mencetak ringkasan eksekutif siap rapat.
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Modal -->
        <div class="guide-modal-footer bg-light px-4 py-2 border-top d-flex justify-content-between align-items-center">
            <small class="text-muted">Sumber Acuan: Metodologi BPS & Struktur Analisis Kemiskinan Daerah (TNP2K)</small>
            <button type="button" class="btn btn-secondary btn-sm fw-bold" id="closeGuideModalFooterBtn">Tutup Panduan</button>
        </div>
    </div>
</div>

<style>
    .guide-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        z-index: 1055;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .guide-modal-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 960px;
        border-radius: 0.65rem;
        overflow: hidden;
        animation: guideFadeIn 0.18s ease-out;
    }
    .guide-modal-body {
        max-height: 75vh;
        overflow-y: auto;
    }
    @keyframes guideFadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('indicatorGuideModal');
        const openBtns = document.querySelectorAll('.open-guide-modal-btn');
        const closeBtns = [
            document.getElementById('closeGuideModalBtn'),
            document.getElementById('closeGuideModalFooterBtn')
        ];

        openBtns.forEach(btn => {
            btn.addEventListener('click', () => modal && modal.classList.remove('d-none'));
        });

        closeBtns.forEach(btn => {
            if (btn) btn.addEventListener('click', () => modal && modal.classList.add('d-none'));
        });

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('d-none');
            });
        }

        // Pengalih Tab di dalam Modal Panduan
        document.querySelectorAll('.guide-tab-btn').forEach(tabBtn => {
            tabBtn.addEventListener('click', function () {
                document.querySelectorAll('.guide-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.guide-tab-pane').forEach(p => p.classList.add('d-none'));

                this.classList.add('active');
                const target = document.querySelector(this.getAttribute('data-target'));
                if (target) target.classList.remove('d-none');
            });
        });

        // Pencarian Cepat di Tabel Kamus 26 Indikator
        const searchInput = document.getElementById('guideSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const keyword = this.value.toLowerCase();
                document.querySelectorAll('#guideIndicatorTable tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? '' : 'none';
                });
            });
        }
    });
</script>