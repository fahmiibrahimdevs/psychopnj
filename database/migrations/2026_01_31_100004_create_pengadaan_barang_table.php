<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengadaan Barang (WITH timestamps)
        Schema::create('pengadaan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_kepengurusan_id')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('pengusul_id')->constrained('anggota')->onDelete('cascade');
            $table->foreignId('keuangan_id')->nullable()->constrained('keuangan')->onDelete('set null');
            $table->string('nama_barang');
            $table->integer('jumlah')->default(1);
            $table->integer('harga')->default(0);
            $table->integer('total')->default(0);
            $table->integer('biaya_lainnya')->default(0);
            $table->string('link_pembelian', 500)->nullable();
            $table->enum('status', ['diusulkan', 'disetujui', 'ditolak', 'selesai'])->default('diusulkan');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');
            $table->text('catatan')->nullable();
            $table->string('keterangan')->default('-');
            $table->string('nama_toko')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('tahun_kepengurusan_id');
            $table->index('department_id');
            $table->index('project_id');
            $table->index('pengusul_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barang');
    }
};
