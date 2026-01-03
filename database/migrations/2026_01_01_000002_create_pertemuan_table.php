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
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->text('id_program')->default('');
            $table->text('nama_pemateri')->default('');
            $table->text('pertemuan_ke')->default('1');
            $table->text('judul_pertemuan')->default('');
            $table->text('deskripsi')->default('-');
            $table->date('tanggal')->nullable();
            $table->text('minggu_ke')->default('1');
            $table->text('thumbnail')->nullable();
            $table->enum('status', ['hidden', 'visible'])->default('visible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
