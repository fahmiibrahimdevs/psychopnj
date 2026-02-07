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
        Schema::create('kategori_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('dokumen_organisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun_kepengurusan')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('id_kategori_dokumen')->constrained('kategori_dokumen')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->string('nomor_dokumen')->nullable();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_organisasi');
        Schema::dropIfExists('kategori_dokumen');
    }
};
