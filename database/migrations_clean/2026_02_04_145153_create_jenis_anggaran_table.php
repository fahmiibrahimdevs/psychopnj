<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jenis Anggaran (NO timestamps)
        Schema::create('jenis_anggaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis')->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('kategori', ['pemasukan', 'pengeluaran']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            
            $table->index('kategori');
            $table->index('status');
            $table->index(['kategori', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_anggaran');
    }
};
