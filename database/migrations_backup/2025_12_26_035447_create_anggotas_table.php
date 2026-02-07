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
            $table->text('jurusan_prodi_kelas')->default(''); // Format: TE/EC/4D
            $table->text('nim')->default('');
            $table->text('ttl')->default(''); // Tempat, Tanggal Lahir
            $table->text('alamat')->default('');
            $table->text('email')->default('');
            $table->text('no_hp')->default('08'); // Awalan 08
            $table->enum('status_anggota', ['anggota', 'pengurus'])->default('anggota');
            $table->enum('status_aktif', ['aktif', 'nonaktif', 'diberhentikan', 'mengundurkan diri'])->default('aktif');
            $table->text('foto')->default('');
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
