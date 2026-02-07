<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tahun Kepengurusan (NO timestamps)
        Schema::create('tahun_kepengurusan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun');
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->text('deskripsi')->nullable();
            
            $table->index('status');
            $table->index('nama_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_kepengurusan');
    }
};
