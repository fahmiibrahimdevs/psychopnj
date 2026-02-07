<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Project Teams (WITH timestamps)
        Schema::create('project_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_project')->constrained('projects')->onDelete('cascade');
            $table->string('nama_kelompok');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index('id_project');
        });

        // Project Team Members (NO timestamps)
        Schema::create('project_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_project_team')->constrained('project_teams')->onDelete('cascade');
            $table->foreignId('id_anggota')->constrained('anggota')->onDelete('cascade');
            $table->enum('role', ['leader', 'anggota'])->default('anggota');
            
            $table->index('id_project_team');
            $table->index('id_anggota');
            $table->index(['id_project_team', 'role']);
            
            $table->unique(['id_project_team', 'id_anggota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_team_members');
        Schema::dropIfExists('project_teams');
    }
};
