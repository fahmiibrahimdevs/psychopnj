<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pertemuan Galeri (WITH timestamps)
        Schema::create('pertemuan_galeri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pertemuan')->constrained('pertemuan')->onDelete('cascade');
            $table->enum('tipe', ['image', 'video'])->default('image');
            $table->string('file_path');
            $table->text('caption')->nullable();
            $table->timestamps();
            
            $table->index('id_pertemuan');
            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan_galeri');
    }
};
