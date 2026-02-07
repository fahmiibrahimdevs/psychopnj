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
        // Rename table
        Schema::rename('divisi', 'departments');

        // Update departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->renameColumn('nama_divisi', 'nama_department');
            $table->integer('max_members')->nullable()->after('status');
        });

        // Update anggota table
        Schema::table('anggota', function (Blueprint $table) {
            $table->renameColumn('id_divisi', 'id_department');
        });

        // Update open_recruitment table
        Schema::table('open_recruitment', function (Blueprint $table) {
            $table->renameColumn('id_divisi', 'id_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert anggota table
        Schema::table('anggota', function (Blueprint $table) {
            $table->renameColumn('id_department', 'id_divisi');
        });

        // Revert open_recruitment table
        Schema::table('open_recruitment', function (Blueprint $table) {
            $table->renameColumn('id_department', 'id_divisi');
        });

        // Revert departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('max_members');
            $table->renameColumn('nama_department', 'nama_divisi');
        });

        // Rename table back
        Schema::rename('departments', 'divisi');
    }
};
