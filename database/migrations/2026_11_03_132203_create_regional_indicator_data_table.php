<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('regional_indicator_data', function (Blueprint $table) {
        $table->id();
        $table->foreignId('regency_id')->constrained('master_regencies')->onDelete('cascade');
        $table->foreignId('indicator_id')->constrained('master_indicators')->onDelete('cascade');
        $table->integer('year'); // Tahun analisis (2025-2035)
        $table->decimal('value', 15, 2)->nullable(); // Nilai angka indikator
        $table->timestamps();

        // Mencegah duplikasi data per wilayah, indikator, dan tahun
        $table->unique(['regency_id', 'indicator_id', 'year'], 'regency_indicator_year_unique');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regional_indicator_data');
    }
};
