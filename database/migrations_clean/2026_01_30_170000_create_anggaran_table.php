<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Keuangan / Anggaran (WITH timestamps)
        Schema::create('anggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_tahun')->constrained('tahun_kepengurusan')->onDelete('cascade');
            $table->foreignId('id_jenis')->constrained('jenis_anggaran')->onDelete('cascade');
            $table->string('nama_anggaran');
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('bukti')->nullable();
            $table->timestamps();
            
            $table->index('id_user');
            $table->index('id_tahun');
            $table->index('id_jenis');
            $table->index('tanggal');
            $table->index('status');
            $table->index(['id_tahun', 'status']);
            $table->index(['id_tahun', 'id_jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran');
    }
};
