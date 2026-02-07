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
        Schema::create('program_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->text('id_tahun')->default('');
            $table->text('nama_program')->default('');
            $table->enum('jenis_program', ['internal', 'eksternal'])->default('internal');
            $table->text('deskripsi')->default('-');
            $table->text('jumlah_pertemuan')->default('0');
            $table->text('penyelenggara')->default('');
            $table->text('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_pembelajaran');
    }
};
