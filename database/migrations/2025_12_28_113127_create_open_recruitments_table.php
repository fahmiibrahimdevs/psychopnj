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
            $table->string('jurusan_prodi_kelas');
            $table->string('nama_jabatan')->nullable();
            $table->enum('status_seleksi', ['pending', 'lulus', 'gagal'])->default('pending');
            
            $table->index('id_user');
            $table->index('id_anggota');
            $table->index('id_tahun');
            $table->index('id_department');
            $table->index(['id_tahun', 'jenis_oprec']);
            $table->index(['id_tahun', 'status_seleksi']);
            $table->index('jenis_oprec');
            $table->index('status_seleksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_recruitment');
    }
};
