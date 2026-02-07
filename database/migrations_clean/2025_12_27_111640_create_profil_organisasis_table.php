<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profil Organisasi (WITH timestamps)
        Schema::create('profil_organisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->string('headline')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('foto')->nullable();
            $table->string('tagline')->nullable();
            $table->timestamps();
            
            $table->index('id_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_organisasi');
    }
};
