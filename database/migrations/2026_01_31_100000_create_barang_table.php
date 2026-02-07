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
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            
            $table->index('nama_kategori');
            $table->index('status');
        });

        // Barangs (WITH timestamps) - plural to match original
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_barang_id')->constrained('kategori_barang')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('nama_barang')->nullable();
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->default('pcs');
            $table->enum('jenis', ['habis_pakai', 'inventaris'])->default('inventaris');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('lokasi')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index('kategori_barang_id');
            $table->index('kode');
            $table->index('nama');
            $table->index('jenis');
            $table->index('kondisi');
            $table->index(['kategori_barang_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
        Schema::dropIfExists('kategori_barang');
    }
};
