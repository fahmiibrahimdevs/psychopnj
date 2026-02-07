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
        Schema::create('pengadaan_barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahun_kepengurusan_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('pengusul_id');
            $table->unsignedBigInteger('keuangan_id')->nullable();
            $table->string('nama_barang');
            $table->integer('jumlah')->default(1);
            $table->integer('harga')->default(0);
            $table->integer('total')->default(0);
            $table->string('link_pembelian')->nullable();
            $table->enum('status', ['diusulkan', 'disetujui', 'ditolak', 'selesai'])->default('diusulkan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('tahun_kepengurusan_id')->references('id')->on('tahun_kepengurusan')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('pengusul_id')->references('id')->on('anggota')->onDelete('cascade');
            $table->foreign('keuangan_id')->references('id')->on('keuangan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_barang');
    }
};
