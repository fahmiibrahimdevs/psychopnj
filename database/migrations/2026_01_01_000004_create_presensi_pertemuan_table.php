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
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->timestamp('waktu')->nullable();
            $table->enum('metode', ['manual', 'qr', 'fingerprint', 'rfid'])->default('manual');
            $table->timestamps();
            
            $table->index('id_pertemuan');
            $table->index('id_anggota');
            $table->index('status');
            $table->index(['id_pertemuan', 'id_anggota']);
            $table->index(['id_pertemuan', 'status']);
            
            $table->unique(['id_pertemuan', 'id_anggota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_pertemuan');
    }
};
