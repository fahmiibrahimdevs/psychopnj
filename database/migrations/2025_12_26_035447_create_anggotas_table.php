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
            $table->string('rfid_card')->nullable()->unique();
            $table->enum('status_anggota', ['pengurus', 'anggota'])->default('anggota');
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('foto')->nullable();
            $table->unsignedBigInteger('id_open_recruitment')->nullable()->index();
            $table->timestamps();
            
            // Optimized indexes based on actual query patterns
            $table->index('id_user'); // Dashboard single lookups
            $table->index('id_tahun'); // Primary filter in all queries
            $table->index(['id_tahun', 'status_anggota']); // Anggota.php filtering
            $table->index(['id_tahun', 'status_aktif']); // IuranKas, TentangKami
            $table->index(['id_tahun', 'id_department']); // Department filtering
            $table->index(['id_tahun', 'status_aktif', 'nama_lengkap']); // IuranKas ordering
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
