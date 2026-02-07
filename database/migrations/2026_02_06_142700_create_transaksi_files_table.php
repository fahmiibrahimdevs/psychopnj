<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->constrained('keuangan')->onDelete('cascade');
            $table->enum('tipe', ['nota', 'reimburse', 'foto']);
            $table->string('file_path');
            $table->string('original_name');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->string('mime_type', 100);
            $table->timestamps();
            
            $table->index('id_transaksi');
            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_files');
    }
};
