<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing anggaran records to new format
        DB::table('anggaran')->where('jenis', 'saldo_awal')->update(['jenis' => 'Saldo Awal']);
        DB::table('anggaran')->where('jenis', 'iuran_kas')->update(['jenis' => 'Iuran Kas']);
        DB::table('anggaran')->where('jenis', 'sponsor')->update(['jenis' => 'Sponsor']);
        DB::table('anggaran')->where('jenis', 'dept')->update(['jenis' => 'Departemen']);
        DB::table('anggaran')->where('jenis', 'project')->update(['jenis' => 'Project']);
        DB::table('anggaran')->where('jenis', 'lainnya')->update(['jenis' => 'Lainnya']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old format
        DB::table('anggaran')->where('jenis', 'Saldo Awal')->update(['jenis' => 'saldo_awal']);
        DB::table('anggaran')->where('jenis', 'Iuran Kas')->update(['jenis' => 'iuran_kas']);
        DB::table('anggaran')->where('jenis', 'Sponsor')->update(['jenis' => 'sponsor']);
        DB::table('anggaran')->where('jenis', 'Departemen')->update(['jenis' => 'dept']);
        DB::table('anggaran')->where('jenis', 'Project')->update(['jenis' => 'project']);
        DB::table('anggaran')->where('jenis', 'Lainnya')->update(['jenis' => 'lainnya']);
    }
};
