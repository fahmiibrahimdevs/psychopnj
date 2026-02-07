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
        Schema::create('open_recruitment', function (Blueprint $table) {
            $table->id();
            $table->text('id_tahun');
            $table->enum('jenis_oprec', ['pengurus', 'anggota'])->default('anggota');
            $table->text('nama_lengkap')->default('');
            $table->text('jurusan_prodi_kelas')->default(''); // Format: TE/EC/4D
            $table->text('id_divisi')->default('');
            $table->text('nama_jabatan')->default('');
            $table->enum('status_seleksi', ['pending', 'lulus', 'gagal'])->default('pending');
            $table->text('id_anggota')->nullable();
            $table->text('id_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_recruitment');
    }
};
