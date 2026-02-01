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
        Schema::create('iuran_kas_periodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tahun');
            $table->string('nama_periode');
            $table->timestamps();

            $table->foreign('id_tahun')->references('id')->on('tahun_kepengurusan')->onDelete('cascade');
            
            // Period names should be unique within a year
            $table->unique(['id_tahun', 'nama_periode']);
        });

        // Backfill existing periods from iuran_kas table to avoid data loss
        $existing = DB::table('iuran_kas')
            ->select('id_tahun', 'periode')
            ->distinct()
            ->get();

        foreach ($existing as $row) {
            DB::table('iuran_kas_periodes')->updateOrInsert([
                'id_tahun' => $row->id_tahun,
                'nama_periode' => $row->periode,
            ], [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_kas_periodes');
    }
};
