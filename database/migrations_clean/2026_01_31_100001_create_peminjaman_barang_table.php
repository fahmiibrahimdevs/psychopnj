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
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->string('kode_peminjaman')->unique();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam');
            $table->text('keperluan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index('id_anggota');
            $table->index('kode_peminjaman');
            $table->index('status');
            $table->index('tanggal_pinjam');
            $table->index(['status', 'tanggal_kembali_rencana']);
        });

        // Peminjaman Barang Detail (NO timestamps)
        Schema::create('peminjaman_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peminjaman')->constrained('peminjaman_barang')->onDelete('cascade');
            $table->foreignId('id_barang')->constrained('barang')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->enum('kondisi_pinjam', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');
            $table->enum('kondisi_kembali', ['baik', 'rusak ringan', 'rusak berat'])->nullable();
            $table->text('keterangan')->nullable();
            
            $table->index('id_peminjaman');
            $table->index('id_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang_detail');
        Schema::dropIfExists('peminjaman_barang');
    }
};
