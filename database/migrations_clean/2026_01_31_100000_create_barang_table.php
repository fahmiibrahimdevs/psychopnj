<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Barang (NO timestamps)
        Schema::create('kategori_barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            
            $table->index('nama_kategori');
            $table->index('status');
        });

        // Barang (WITH timestamps)
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->constrained('kategori_barang')->onDelete('cascade');
            $table->string('nama_barang');
            $table->string('kode_barang')->unique();
            $table->integer('jumlah')->default(0);
            $table->enum('satuan', ['unit', 'pcs', 'set', 'box', 'pack'])->default('unit');
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi_penyimpanan')->nullable();
            $table->text('foto')->nullable();
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak'])->default('tersedia');
            $table->timestamps();
            
            $table->index('id_kategori');
            $table->index('kode_barang');
            $table->index('nama_barang');
            $table->index('status');
            $table->index(['id_kategori', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
        Schema::dropIfExists('kategori_barang');
    }
};
