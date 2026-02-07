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
        Schema::create('surat_files', function (Blueprint $table) {
            $table->id();
            $table->morphs('suratable'); // Creates suratable_id & suratable_type
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->timestamps();
        });

        // Drop old columns if they exist
        if (Schema::hasColumn('surat_masuk', 'file_path')) {
            Schema::table('surat_masuk', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }

        if (Schema::hasColumn('surat_keluar', 'file_path')) {
            Schema::table('surat_keluar', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_files');

        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('file_path')->nullable();
        });

        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->string('file_path')->nullable();
        });
    }
};
