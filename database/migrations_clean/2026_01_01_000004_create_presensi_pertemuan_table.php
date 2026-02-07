<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Presensi Pertemuan (WITH timestamps)
        Schema::create('presensi_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pertemuan')->constrained('pertemuan')->onDelete('cascade');
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alfa'])->default('alfa');
            $table->text('keterangan')->nullable();
            $table->timestamp('waktu_presensi')->nullable();
            $table->timestamps();
            
            $table->index('id_pertemuan');
            $table->index('id_anggota');
            $table->index(['id_pertemuan', 'id_anggota']);
            $table->index('status_kehadiran');
            
            $table->unique(['id_pertemuan', 'id_anggota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_pertemuan');
    }
};
