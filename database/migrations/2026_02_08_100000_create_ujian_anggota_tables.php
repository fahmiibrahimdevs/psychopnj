<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nilai Soal Anggota - stores exam sessions per anggota per pertemuan
        Schema::create('nilai_soal_anggota', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('id_bank_soal')->constrained('bank_soal_pertemuan')->onDelete('cascade');
            $table->foreignId('id_pertemuan')->constrained('pertemuan')->onDelete('cascade');
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            
            // Score fields per type
            $table->integer('pg_benar')->default(0);
            $table->integer('pg_salah')->default(0);
            $table->integer('nilai_pg')->default(0);
            $table->integer('nilai_pk')->default(0); // PG Kompleks
            $table->integer('nilai_jo')->default(0); // Jodohkan
            $table->integer('nilai_is')->default(0); // Isian
            $table->integer('nilai_es')->default(0); // Esai
            
            $table->enum('status', ['0', '1'])->default('0'); // 0=sedang mengerjakan, 1=selesai
            $table->enum('dikoreksi', ['0', '1'])->default('0'); // 0=belum, 1=sudah
            
            $table->timestamp('mulai')->nullable();
            $table->timestamp('selesai')->nullable();
            $table->string('lama_ujian')->nullable();
            
            $table->timestamps();
            
            $table->index(['id_bank_soal', 'id_anggota']);
            $table->index(['id_pertemuan', 'id_anggota']);
            $table->unique(['id_pertemuan', 'id_anggota']);
        });

        // Soal Anggota - stores individual answers per question
        Schema::create('soal_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nilai_soal')->constrained('nilai_soal_anggota')->onDelete('cascade');
            $table->foreignId('id_soal')->constrained('soal_pertemuan')->onDelete('cascade');
            $table->enum('jenis_soal', ['1', '2', '3', '4', '5'])->default('1');
            
            $table->integer('no_soal_alias')->default(0);
            
            // Alias options (for randomized PG)
            $table->string('opsi_alias_a')->nullable();
            $table->string('opsi_alias_b')->nullable();
            $table->string('opsi_alias_c')->nullable();
            $table->string('opsi_alias_d')->nullable();
            $table->string('opsi_alias_e')->nullable();
            
            $table->text('jawaban_alias')->nullable(); // Correct answer (aliased)
            $table->text('jawaban_anggota')->nullable(); // Student's answer
            $table->text('jawaban_benar')->nullable(); // Original correct answer
            
            $table->integer('point_soal')->default(0);
            $table->integer('point_essai')->default(0);
            $table->integer('nilai_koreksi')->default(0);
            $table->integer('nilai_otomatis')->default(0);
            
            $table->enum('ragu', ['0', '1'])->default('0');
            $table->enum('soal_end', ['0', '1'])->default('0');
            
            $table->timestamps();
            
            $table->index(['id_nilai_soal', 'id_soal']);
            $table->index('jenis_soal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_anggota');
        Schema::dropIfExists('nilai_soal_anggota');
    }
};
