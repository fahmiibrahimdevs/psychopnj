<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Anggota (WITH timestamps)
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->unsignedBigInteger('id_department')->default(0); // 0 untuk anggota non-pengurus
            $table->string('nama_lengkap');
            $table->string('nama_jabatan');
            $table->string('jurusan_prodi_kelas');
            $table->string('nim')->nullable();
            $table->string('ttl')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email');
            $table->string('no_hp')->nullable();
            $table->enum('status_anggota', ['pengurus', 'anggota'])->default('anggota');
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('foto')->nullable();
            $table->timestamps();
            
            $table->index('id_user');
            $table->index('id_tahun');
            $table->index('id_department');
            $table->index(['id_tahun', 'status_anggota']);
            $table->index(['id_tahun', 'status_aktif']);
            $table->index('nama_lengkap');
            $table->index('status_anggota');
            $table->index('status_aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
