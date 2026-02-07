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
            $table->unsignedBigInteger('id_user')->nullable()->after('keterangan');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('kategori_barang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('nama');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('catatan');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('pengadaan_barang', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('catatan');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });

        Schema::table('kategori_barang', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });

        Schema::table('peminjaman_barang', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });

        Schema::table('pengadaan_barang', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
};
