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
        Schema::create('bank_soal_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_part')->constrained('part_pertemuan')->onDelete('cascade');
            $table->unsignedBigInteger('id_tahun'); // from program_kegiatan.id_tahun
            
            // Question counts per type
            $table->integer('jml_pg')->default(0);
            $table->integer('jml_kompleks')->default(0);
            $table->integer('jml_jodohkan')->default(0);
            $table->integer('jml_isian')->default(0);
            $table->integer('jml_esai')->default(0);
            
            // Display counts (for random selection)
            $table->integer('tampil_pg')->default(0);
            $table->integer('tampil_kompleks')->default(0);
            $table->integer('tampil_jodohkan')->default(0);
            $table->integer('tampil_isian')->default(0);
            $table->integer('tampil_esai')->default(0);
            
            // Weights per type
            $table->integer('bobot_pg')->default(0);
            $table->integer('bobot_kompleks')->default(0);
            $table->integer('bobot_jodohkan')->default(0);
            $table->integer('bobot_isian')->default(0);
            $table->integer('bobot_esai')->default(0);
            
            // Multiple choice options count (3, 4, or 5)
            $table->enum('opsi', ['3', '4', '5'])->default('5');
            
            $table->enum('status', ['0', '1'])->default('0'); // 0: Tidak Aktif, 1: Aktif
            
            $table->timestamps();
            
            $table->index('id_part');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal_pertemuan');
    }
};
