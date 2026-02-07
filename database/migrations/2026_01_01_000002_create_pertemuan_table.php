<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pertemuan (WITH timestamps)
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_program')->constrained('program_pembelajaran')->onDelete('cascade');
            $table->string('nama_pemateri')->nullable();
            $table->integer('pertemuan_ke')->default(1);
            $table->string('judul_pertemuan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('minggu_ke')->default(1);
            $table->text('thumbnail')->nullable();
            $table->string('jenis_presensi')->nullable(); // comma separated: pengurus,anggota
            $table->enum('status', ['hidden', 'visible'])->default('visible');
            $table->timestamps();
            
            $table->index('id_program');
            $table->index('pertemuan_ke');
            $table->index('tanggal');
            $table->index('status');
            $table->index(['id_program', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
