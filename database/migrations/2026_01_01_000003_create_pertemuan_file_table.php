<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table pertemuan_file sudah tidak digunakan, diganti dengan part_file
        // Skip migration ini karena menggunakan sistem part
    }

    public function down(): void
    {
        // Skip
    }
};
