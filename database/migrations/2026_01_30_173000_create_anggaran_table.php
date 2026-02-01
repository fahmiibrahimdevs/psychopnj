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
        Schema::create('anggaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tahun');
            $table->enum('kategori', ['pemasukan', 'pengeluaran']);
            $table->enum('jenis', ['saldo_awal', 'iuran_kas', 'sponsor', 'dept', 'project', 'lainnya']);
            $table->unsignedBigInteger('id_department')->nullable();
            $table->unsignedBigInteger('id_project')->nullable();
            $table->text('nama');
            $table->decimal('nominal', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_tahun')->references('id')->on('tahun_kepengurusan')->onDelete('cascade');
            $table->foreign('id_department')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran');
    }
};
