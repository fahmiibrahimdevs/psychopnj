<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keuangan / Transaksi (WITH timestamps)
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('kategori'); // From JenisAnggaran.nama_jenis
            $table->foreignId('id_department')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('id_project')->nullable()->constrained('projects')->onDelete('set null');
            $table->text('deskripsi');
            $table->bigInteger('nominal')->default(0)->comment('Nominal dalam Rupiah (integer)');
            $table->text('bukti')->nullable();
            $table->timestamps();
            
            // Optimized indexes based on actual query patterns
            $table->index('id_tahun'); // Primary filter
            $table->index(['id_tahun', 'tanggal', 'id']); // Transaksi.php ordering
            $table->index(['id_tahun', 'jenis']); // Laporan.php aggregations
            $table->index(['id_tahun', 'jenis', 'kategori']); // Transaksi.php filters
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
