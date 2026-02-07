<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Program Pembelajaran (WITH timestamps)
        Schema::create('program_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nama_program');
            $table->enum('jenis_program', ['internal', 'eksternal'])->default('internal');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_pertemuan')->default(0);
            $table->string('penyelenggara');
            $table->text('thumbnail')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            
            $table->index('id_tahun');
            $table->index('status');
            $table->index('jenis_program');
            $table->index('nama_program');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_pembelajaran');
    }
};
