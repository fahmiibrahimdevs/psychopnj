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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('id_tahun')->default('');
            $table->text('nama_project')->default('');
            $table->text('deskripsi')->default('-');
            $table->text('id_leader')->default('');
            $table->text('id_anggota')->default('');
            $table->enum('status', ['draft', 'berjalan', 'selesai', 'ditunda'])->default('draft');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
