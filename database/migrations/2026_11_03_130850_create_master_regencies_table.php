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
    Schema::create('master_regencies', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // Kode BPS Wilayah (misal: 1900, 1901)
        $table->string('name');           // Nama wilayah (misal: Kab. Bangka, Prov. Kep. Bangka Belitung)
        $table->enum('type', ['province', 'regency'])->default('regency'); // Tingkat wilayah
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_regencies');
    }
};
