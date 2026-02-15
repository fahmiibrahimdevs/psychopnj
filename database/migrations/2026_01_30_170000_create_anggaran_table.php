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
            $table->bigInteger('nominal')->default(0)->comment('Nominal dalam Rupiah (integer)');
            $table->timestamps();
            
            // Optimized indexes based on actual query patterns
            $table->index('id_tahun'); // Primary filter
            $table->index(['id_tahun', 'kategori']); // Laporan.php filtering
            $table->index(['id_tahun', 'kategori', 'jenis']); // Anggaran.php ordering
            $table->index(['id_tahun', 'kategori', 'jenis', 'id']); // Covering index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran');
    }
};
