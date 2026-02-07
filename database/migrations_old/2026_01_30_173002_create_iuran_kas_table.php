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
        Schema::create('iuran_kas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tahun');
            $table->unsignedBigInteger('id_anggota');
            $table->string('periode'); // e.g., "Pertemuan 1-2"
            $table->decimal('nominal', 10, 2)->default(5000);
            $table->enum('status', ['lunas', 'belum'])->default('belum');
            $table->date('tanggal_bayar')->nullable();
            $table->unsignedBigInteger('id_keuangan')->nullable();
            $table->timestamps();

            $table->foreign('id_tahun')->references('id')->on('tahun_kepengurusan')->onDelete('cascade');
            $table->foreign('id_anggota')->references('id')->on('anggota')->onDelete('cascade');
            $table->foreign('id_keuangan')->references('id')->on('keuangan')->onDelete('set null');
            
            // Unique constraint: one record per anggota per periode per tahun
            $table->unique(['id_tahun', 'id_anggota', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_kas');
    }
};
