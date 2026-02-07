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
        Schema::create('presensi_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->text('id_pertemuan')->default('');
            $table->text('id_anggota')->default('');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->timestamp('waktu')->nullable();
            $table->enum('metode', ['manual', 'qr', 'fingerprint', 'rfid'])->default('manual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_pertemuan');
    }
};
