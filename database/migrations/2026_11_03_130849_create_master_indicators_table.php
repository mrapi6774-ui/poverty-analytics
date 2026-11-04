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
    Schema::create('master_indicators', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sector_id')->constrained('master_sectors')->onDelete('cascade');
        $table->string('code')->unique(); // Kode indikator (misal: P0, HLS, AHH)
        $table->string('name');           // Nama indikator (Persentase Penduduk Miskin, dll)
        $table->string('unit');           // Satuan (%, Rp, Tahun, Jiwa)
        $table->enum('direction', ['positive', 'negative'])->default('positive'); 
        // positive: makin tinggi makin baik (misal: HLS, AHH)
        // negative: makin tinggi makin buruk (misal: P0, TPT, Stunting)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_indicators');
    }
};
