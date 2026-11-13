<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Akun Ringkasan APBD (Pendapatan 4, Belanja Tidak Langsung 5.1, Pembiayaan 6)
        Schema::create('master_apbd_accounts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['pendapatan', 'belanja_tidak_langsung', 'pembiayaan']);
            $table->string('category_code', 20); // Contoh: 4.1, 4.2, 5.1, 6.1
            $table->string('category_name');     // Contoh: Pendapatan Asli Daerah (PAD)
            $table->string('code', 20);          // Contoh: 4.1.1, 5.1.1
            $table->string('name');              // Contoh: Pajak daerah, Belanja Pegawai Tidak Langsung
            $table->timestamps();
        });

        // 2. Tabel Realisasi / Anggaran per Akun per Wilayah per Tahun
        Schema::create('apbd_account_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regency_id')->constrained('master_regencies')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('master_apbd_accounts')->onDelete('cascade');
            $table->year('year');
            $table->decimal('amount', 15, 2)->default(0); // Nominal (Juta Rp)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbd_account_records');
        Schema::dropIfExists('master_apbd_accounts');
    }
};