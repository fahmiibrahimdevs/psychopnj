<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pertemuan (WITH timestamps)
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_program')->constrained('program_pembelajaran')->onDelete('cascade');
            $table->string('nama_pertemuan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('link_meet')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();
            
            $table->index('id_program');
            $table->index('tanggal');
            $table->index('status');
            $table->index(['id_program', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
