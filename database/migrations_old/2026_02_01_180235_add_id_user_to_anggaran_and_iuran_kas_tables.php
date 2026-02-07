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
            $table->unsignedBigInteger('id_user')->nullable()->after('nominal');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });

        Schema::table('iuran_kas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('id_keuangan');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggaran', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });

        Schema::table('iuran_kas', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropColumn('id_user');
        });
    }
};
