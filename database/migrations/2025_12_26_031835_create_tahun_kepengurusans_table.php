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
        Schema::create('tahun_kepengurusan', function (Blueprint $table) {
            $table->id();
            $table->text('nama_tahun')->default('');
            $table->text('mulai')->default('');
            $table->text('akhir')->default('');
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->text('deskripsi')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_kepengurusan');
    }
};
