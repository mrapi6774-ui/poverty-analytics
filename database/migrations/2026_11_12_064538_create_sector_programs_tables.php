<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Master Program (Level 1) & Sub-Kegiatan (Level 2)
        Schema::create('master_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('master_sectors')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('master_programs')->onDelete('cascade');
            $table->string('code', 50); // Contoh: 1.01.1.01.01.01
            $table->string('name');     // Nama Program atau Sub-Kegiatan
            $table->tinyInteger('level')->default(1); // 1 = Program Utama, 2 = Sub-Kegiatan
            $table->timestamps();
        });

        // 2. Tabel Rincian Anggaran per Program/Kegiatan per Tahun
        Schema::create('program_expenditures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regency_id')->constrained('master_regencies')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('master_programs')->onDelete('cascade');
            $table->year('year');
            $table->decimal('pegawai_expense', 15, 2)->default(0);     // B. Pegawai
            $table->decimal('barang_jasa_expense', 15, 2)->default(0); // B. Barang Jasa
            $table->decimal('modal_expense', 15, 2)->default(0);       // B. Modal
            $table->decimal('total_expense', 15, 2)->default(0);       // Jumlah Total
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_expenditures');
        Schema::dropIfExists('master_programs');
    }
};