<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iuran_kas', function (Blueprint $table) {
            $table->index(['id_tahun', 'id_anggota', 'periode'], 'iuran_kas_tahun_anggota_periode_idx');
            $table->index(['id_tahun', 'tanggal_bayar'], 'iuran_kas_tahun_tanggal_bayar_idx');
        });

        Schema::table('iuran_kas_periode', function (Blueprint $table) {
            $table->index(['id_tahun', 'id'], 'iuran_kas_periode_tahun_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('iuran_kas', function (Blueprint $table) {
            $table->dropIndex('iuran_kas_tahun_anggota_periode_idx');
            $table->dropIndex('iuran_kas_tahun_tanggal_bayar_idx');
        });

        Schema::table('iuran_kas_periode', function (Blueprint $table) {
            $table->dropIndex('iuran_kas_periode_tahun_id_idx');
        });
    }
};
