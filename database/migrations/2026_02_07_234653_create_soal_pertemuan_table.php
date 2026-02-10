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
        Schema::create('soal_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bank_soal');
            $table->enum('jenis', ['1', '2', '3', '4', '5'])->default('1');
            // '1' = PG, '2' = PG Kompleks, '3' = Jodohkan, '4' = Isian, '5' = Esai
            
            $table->integer('nomor_soal')->default(0);
            $table->text('soal'); // Question text (HTML from Summernote)
            
            // Options for multiple choice
            $table->text('opsi_a')->nullable();
            $table->text('opsi_b')->nullable();
            $table->text('opsi_c')->nullable();
            $table->text('opsi_d')->nullable();
            $table->text('opsi_e')->nullable();
            
            $table->text('jawaban'); // Answer (varies by type)
            $table->enum('tampilkan', ['0', '1'])->default('0'); // Display toggle
            
            $table->timestamps();
            
            $table->foreign('id_bank_soal')->references('id')->on('bank_soal_pertemuan')->onDelete('cascade');
            
            // Performance indexes
            $table->index(['id_bank_soal', 'jenis']);
            $table->index('tampilkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pertemuan');
    }
};
