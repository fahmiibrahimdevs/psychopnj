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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->text('id_user')->default('');
            $table->text('id_tahun')->default('1');
            $table->text('id_divisi')->default('');
            $table->text('nama_jabatan')->default('');
            $table->text('nama_lengkap')->default('');
            $table->text('kelas')->default('');
            $table->enum('jurusan', ['Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Akuntansi', 'Administrasi Niaga', 'Teknik Grafika Penerbitan', 'Teknik Informatika dan Komputer'])->default('Teknik Elektro');
            $table->text('nim')->default('');
            $table->text('no_hp')->default('62');
            $table->enum('status_anggota', ['anggota', 'pengurus'])->default('anggota');
            $table->enum('status_aktif', ['aktif', 'nonaktif', 'diberhentikan', 'mengundurkan diri'])->default('aktif');
            $table->text('foto')->default('');
            $table->text('motivasi')->nullable();
            $table->text('pengalaman')->nullable();
            $table->text('id_open_recruitment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
