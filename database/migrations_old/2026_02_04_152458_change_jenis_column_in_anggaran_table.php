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
        Schema::table('anggaran', function (Blueprint $table) {
            $table->string('jenis', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggaran', function (Blueprint $table) {
            $table->enum('jenis', ['saldo_awal', 'iuran_kas', 'sponsor', 'dept', 'project', 'lainnya'])->change();
        });
    }
};
