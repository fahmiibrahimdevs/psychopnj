<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Surat Masuk
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun_kepengurusan')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nomor_surat');
            $table->string('perihal');
            $table->string('pengirim');
            $table->string('ditujukan_kepada'); // Specific request
            $table->date('tanggal_masuk');
            $table->string('file_path')->nullable();
            $table->timestamps(); // Created at, Updated at

            $table->index('id_tahun_kepengurusan');
            $table->index('nomor_surat');
        });

        // Surat Keluar
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun_kepengurusan')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nomor_surat');
            $table->string('perihal');
            $table->string('penerima');
            $table->date('tanggal_keluar');
            $table->enum('status', ['Draft', 'Pending', 'Disetujui', 'Terkirim', 'Ditolak'])->default('Draft');
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->index('id_tahun_kepengurusan');
            $table->index('nomor_surat');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
        Schema::dropIfExists('surat_masuk');
    }
};
