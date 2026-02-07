<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Anggaran (WITH timestamps)
        Schema::create('anggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('kategori', ['pemasukan', 'pengeluaran']);
            $table->string('jenis'); // Free text untuk jenis anggaran
            $table->foreignId('id_department')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('id_project')->nullable()->constrained('projects')->onDelete('set null');
            $table->text('nama');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->timestamps();
            
            $table->index('id_tahun');
            $table->index('id_user');
            $table->index('kategori');
            $table->index('jenis');
            $table->index('id_department');
            $table->index('id_project');
            $table->index(['id_tahun', 'kategori']);
            $table->index(['id_tahun', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran');
    }
};
