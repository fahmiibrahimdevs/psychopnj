<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Drop kolom kategori yang lama (konflik dengan relationship)
            if (Schema::hasColumn('barangs', 'kategori')) {
                $table->dropColumn('kategori');
            }
            
            // Drop kolom kode_barang yang duplikat (sudah ada kolom kode)
            if (Schema::hasColumn('barangs', 'kode_barang')) {
                $table->dropColumn('kode_barang');
            }
            
            // Drop kolom deskripsi yang duplikat (sudah ada kolom keterangan)
            if (Schema::hasColumn('barangs', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('kategori')->default('Lainnya');
            $table->string('kode_barang')->nullable()->unique();
            $table->text('deskripsi')->nullable();
        });
    }
};
