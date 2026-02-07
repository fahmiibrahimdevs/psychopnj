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
            $table->enum('nama_kategori', ['pemasukan', 'pengeluaran']);
            $table->string('nama_jenis');
            
            $table->index('nama_kategori');
            $table->unique(['nama_kategori', 'nama_jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_anggaran');
    }
};
