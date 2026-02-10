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
            
            $table->index('id_tahun');
            $table->index('id_user');
            $table->index('tanggal');
            $table->index('jenis');
            $table->index('kategori');
            $table->index('id_department');
            $table->index('id_project');
            $table->index(['id_tahun', 'jenis']);
            $table->index(['id_tahun', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
