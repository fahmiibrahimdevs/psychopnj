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
        Schema::create('project_team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_project_team');
            $table->unsignedBigInteger('id_anggota');
            $table->enum('role', ['leader', 'anggota'])->default('anggota');
            $table->timestamps();

            $table->foreign('id_project_team')->references('id')->on('project_teams')->onDelete('cascade');
            $table->foreign('id_anggota')->references('id')->on('anggota')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_team_members');
    }
};
