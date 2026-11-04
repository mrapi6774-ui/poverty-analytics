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
    Schema::create('apbd_expenditures', function (Blueprint $table) {
        $table->id();
        $table->foreignId('regency_id')->constrained('master_regencies')->onDelete('cascade');
        $table->foreignId('sector_id')->nullable()->constrained('master_sectors')->onDelete('set null');
        $table->integer('year'); // Tahun anggaran
        $table->decimal('direct_expense', 18, 2)->default(0);   // Belanja Langsung
        $table->decimal('indirect_expense', 18, 2)->default(0); // Belanja Tidak Langsung
        $table->decimal('total_expense', 18, 2)->default(0);    // Total Anggaran Belanja
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apbd_expenditures');
    }
};
