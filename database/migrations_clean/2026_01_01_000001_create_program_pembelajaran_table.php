<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Program Pembelajaran (WITH timestamps)
        Schema::create('program_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            
            $table->index('status');
            $table->index('nama_program');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_pembelajaran');
    }
};
