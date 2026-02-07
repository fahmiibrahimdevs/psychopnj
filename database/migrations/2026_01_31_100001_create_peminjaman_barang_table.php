<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peminjaman Barang (WITH timestamps)
        Schema::create('peminjaman_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_kepengurusan_id')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('pencatat_id')->constrained('anggota')->onDelete('cascade');
            $table->string('nama_peminjam');
            $table->string('kontak_peminjam')->nullable();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->text('keperluan')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->text('catatan')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('tahun_kepengurusan_id');
            $table->index('pencatat_id');
            $table->index('status');
            $table->index('tanggal_pinjam');
        });

        // Peminjaman Barang Detail (NO timestamps)
        Schema::create('peminjaman_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_barang_id')->constrained('peminjaman_barang')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->enum('kondisi_pinjam', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');
            $table->enum('kondisi_kembali', ['baik', 'rusak ringan', 'rusak berat'])->nullable();
            $table->text('keterangan')->nullable();
            
            $table->index('peminjaman_barang_id');
            $table->index('barang_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang_detail');
        Schema::dropIfExists('peminjaman_barang');
    }
};
