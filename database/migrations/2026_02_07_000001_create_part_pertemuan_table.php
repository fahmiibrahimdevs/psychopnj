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
        Schema::create('part_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pertemuan')->constrained('pertemuan')->onDelete('cascade');
            $table->integer('urutan')->default(1);
            $table->string('nama_part');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index('id_pertemuan');
            $table->index(['id_pertemuan', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_pertemuan');
    }
};
