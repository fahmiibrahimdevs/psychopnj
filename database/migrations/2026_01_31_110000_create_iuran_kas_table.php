<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Iuran Kas Periode (NO timestamps) - Just definition table
        Schema::create('iuran_kas_periode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nama_periode');
            
            $table->index('id_tahun');
            $table->unique(['id_tahun', 'nama_periode']);
        });

        // Iuran Kas (WITH timestamps) - Simple structure
        Schema::create('iuran_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->string('periode'); // Just string name
            $table->bigInteger('nominal')->comment('Nominal dalam Rupiah (integer)');
            $table->date('tanggal_bayar');
            $table->enum('status', ['lunas', 'belum lunas'])->default('lunas');
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('id_tahun');
            $table->index('id_anggota');
            $table->index('periode');
            $table->index('status');
            $table->index(['id_tahun', 'id_anggota']);
            $table->index(['id_tahun', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iuran_kas');
        Schema::dropIfExists('iuran_kas_periode');
    }
};
