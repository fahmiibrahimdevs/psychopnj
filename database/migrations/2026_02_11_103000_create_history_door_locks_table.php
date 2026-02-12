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
        Schema::create('history_door_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_anggota')->nullable()->constrained('anggota')->onDelete('cascade');
            $table->string('rfid_card');
            $table->enum('status_akses', ['granted', 'denied']);
            $table->string('keterangan')->nullable(); // e.g., "Member Non-Aktif", "Kartu Tidak Dikenal"
            $table->timestamp('waktu_akses')->useCurrent();
            $table->timestamps();

            $table->index('rfid_card');
            $table->index('status_akses');
            $table->index('waktu_akses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_door_locks');
    }
};
