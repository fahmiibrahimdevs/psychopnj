<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Projects (WITH timestamps)
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nama_project');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('link_gdrive')->nullable();
            $table->enum('status', ['draft', 'berjalan', 'selesai', 'ditunda'])->default('draft');
            $table->timestamps();
            
            $table->index('id_tahun');
            $table->index('status');
            $table->index(['id_tahun', 'status']);
            $table->index('tanggal_mulai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
