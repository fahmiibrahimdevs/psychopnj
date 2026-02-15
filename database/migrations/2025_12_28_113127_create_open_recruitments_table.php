<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Open Recruitment (NO timestamps)
        Schema::create('open_recruitment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_anggota')->nullable()->constrained('anggota')->onDelete('set null');
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->unsignedBigInteger('id_department')->default(0); // 0 untuk anggota
            $table->enum('jenis_oprec', ['pengurus', 'anggota'])->default('anggota');
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('jurusan_prodi_kelas');
            $table->string('nama_jabatan')->nullable();
            $table->text('alasan')->nullable();
            $table->string('tautan_twibbon')->nullable();
            $table->enum('status_seleksi', ['pending', 'lulus', 'gagal'])->default('pending');
            
            // Optimized indexes based on actual query patterns
            $table->index('id_tahun'); // Primary filter
            $table->index('email'); // Duplicate check in import
            $table->index(['id_tahun', 'jenis_oprec']); // Main filtering pattern
            $table->index(['id_tahun', 'jenis_oprec', 'id_department']); // Pengurus queries
            $table->index(['id_tahun', 'jenis_oprec', 'jurusan_prodi_kelas']); // Anggota grouping
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_recruitment');
    }
};
