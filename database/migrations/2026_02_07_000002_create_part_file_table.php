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
        Schema::create('part_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_part')->constrained('part_pertemuan')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_name');
            $table->bigInteger('ukuran_file')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
            
            $table->index('id_part');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_file');
    }
};
