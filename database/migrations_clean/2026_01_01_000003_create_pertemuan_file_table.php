<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pertemuan Files (WITH timestamps)
        Schema::create('pertemuan_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pertemuan')->constrained('pertemuan')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->timestamps();
            
            $table->index('id_pertemuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan_file');
    }
};
