<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Departments (NO timestamps)
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('nama_department');
            $table->string('kategori')->default('');
            $table->text('deskripsi')->nullable();
            $table->string('ikon')->default('');
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('max_members')->nullable();
            
            $table->index('id_tahun');
            $table->index('status');
            $table->index(['id_tahun', 'status']);
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
