<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Iuran Kas Periode (NO timestamps)
        Schema::create('iuran_kas_periode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nama_periode');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('nominal_per_anggota', 15, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('deskripsi')->nullable();
            
            $table->index('id_tahun');
            $table->index('status');
            $table->index(['id_tahun', 'status']);
            $table->index('tanggal_mulai');
        });

        // Iuran Kas (WITH timestamps)
        Schema::create('iuran_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_periode')->constrained('iuran_kas_periode')->onDelete('cascade');
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->text('bukti')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index('id_user');
            $table->index('id_periode');
            $table->index('id_anggota');
            $table->index('status');
            $table->index(['id_periode', 'status']);
            $table->index(['id_periode', 'id_anggota']);
            
            $table->unique(['id_periode', 'id_anggota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iuran_kas');
        Schema::dropIfExists('iuran_kas_periode');
    }
};
