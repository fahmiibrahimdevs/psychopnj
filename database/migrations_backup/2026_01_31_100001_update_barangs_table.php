<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Check and add columns only if they don't exist
            if (!Schema::hasColumn('barangs', 'kategori_barang_id')) {
                $table->unsignedBigInteger('kategori_barang_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('barangs', 'kode')) {
                $table->string('kode')->unique()->after('kategori_barang_id');
            }
            if (!Schema::hasColumn('barangs', 'nama')) {
                $table->string('nama')->after('kode');
            }
            if (!Schema::hasColumn('barangs', 'nama_barang')) {
                $table->string('nama_barang')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('barangs', 'jumlah')) {
                $table->integer('jumlah')->default(0)->after('nama_barang');
            }
            if (!Schema::hasColumn('barangs', 'satuan')) {
                $table->string('satuan')->default('pcs')->after('jumlah');
            }
            if (!Schema::hasColumn('barangs', 'jenis')) {
                $table->enum('jenis', ['habis_pakai', 'inventaris'])->default('inventaris')->after('satuan');
            }
            if (!Schema::hasColumn('barangs', 'kondisi')) {
                $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik')->after('jenis');
            }
            if (!Schema::hasColumn('barangs', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('kondisi');
            }
            if (!Schema::hasColumn('barangs', 'foto')) {
                $table->string('foto')->nullable()->after('lokasi');
            }
            if (!Schema::hasColumn('barangs', 'keterangan')) {
                $table->text('keterangan')->nullable()->after('foto');
            }
        });

        // Add foreign key if not exists
        if (Schema::hasColumn('barangs', 'kategori_barang_id')) {
            // Check if foreign key already exists
            $foreignKeyExists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND TABLE_NAME = 'barangs' 
                AND CONSTRAINT_NAME LIKE '%kategori_barang_id%'
            ");

            if ($foreignKeyExists[0]->count == 0) {
                Schema::table('barangs', function (Blueprint $table) {
                    $table->foreign('kategori_barang_id')->references('id')->on('kategori_barang')->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            if (Schema::hasColumn('barangs', 'kategori_barang_id')) {
                $table->dropForeign(['kategori_barang_id']);
            }
            
            $columns = ['kategori_barang_id', 'kode', 'nama', 'jumlah', 'satuan', 'jenis', 'kondisi', 'lokasi', 'foto', 'keterangan'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('barangs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
