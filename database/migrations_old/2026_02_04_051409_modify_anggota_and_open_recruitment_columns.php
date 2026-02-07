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
        // Modifikasi tabel anggota
        Schema::table('anggota', function (Blueprint $table) {
            // Hapus kolom kelas dan jurusan jika ada
            if (Schema::hasColumn('anggota', 'kelas')) {
                $table->dropColumn('kelas');
            }
            if (Schema::hasColumn('anggota', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
            
            // Hapus kolom motivasi dan pengalaman
            if (Schema::hasColumn('anggota', 'motivasi')) {
                $table->dropColumn('motivasi');
            }
            if (Schema::hasColumn('anggota', 'pengalaman')) {
                $table->dropColumn('pengalaman');
            }
            
            // Tambah kolom baru jika belum ada
            if (!Schema::hasColumn('anggota', 'jurusan_prodi_kelas')) {
                $table->text('jurusan_prodi_kelas')->default('')->after('nama_lengkap');
            }
            if (!Schema::hasColumn('anggota', 'ttl')) {
                $table->text('ttl')->default('')->after('nim');
            }
            if (!Schema::hasColumn('anggota', 'alamat')) {
                $table->text('alamat')->default('')->after('ttl');
            }
            if (!Schema::hasColumn('anggota', 'email')) {
                $table->text('email')->default('')->after('alamat');
            }
            
            // Modify no_hp default value
            if (Schema::hasColumn('anggota', 'no_hp')) {
                $table->text('no_hp')->default('08')->change();
            }
        });
        
        // Modifikasi tabel open_recruitment
        Schema::table('open_recruitment', function (Blueprint $table) {
            // Hapus kolom kelas dan jurusan jika ada
            if (Schema::hasColumn('open_recruitment', 'kelas')) {
                $table->dropColumn('kelas');
            }
            if (Schema::hasColumn('open_recruitment', 'jurusan')) {
                $table->dropColumn('jurusan');
            }
            
            // Hapus kolom motivasi dan pengalaman
            if (Schema::hasColumn('open_recruitment', 'motivasi')) {
                $table->dropColumn('motivasi');
            }
            if (Schema::hasColumn('open_recruitment', 'pengalaman')) {
                $table->dropColumn('pengalaman');
            }
            
            // Tambah kolom jurusan_prodi_kelas jika belum ada
            if (!Schema::hasColumn('open_recruitment', 'jurusan_prodi_kelas')) {
                $table->text('jurusan_prodi_kelas')->default('')->after('nama_lengkap');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback tabel anggota
        Schema::table('anggota', function (Blueprint $table) {
            // Hapus kolom baru
            if (Schema::hasColumn('anggota', 'jurusan_prodi_kelas')) {
                $table->dropColumn('jurusan_prodi_kelas');
            }
            if (Schema::hasColumn('anggota', 'ttl')) {
                $table->dropColumn('ttl');
            }
            if (Schema::hasColumn('anggota', 'alamat')) {
                $table->dropColumn('alamat');
            }
            if (Schema::hasColumn('anggota', 'email')) {
                $table->dropColumn('email');
            }
            
            // Kembalikan kolom lama
            if (!Schema::hasColumn('anggota', 'kelas')) {
                $table->text('kelas')->default('')->after('nama_lengkap');
            }
            if (!Schema::hasColumn('anggota', 'jurusan')) {
                $table->enum('jurusan', ['Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Akuntansi', 'Administrasi Niaga', 'Teknik Grafika Penerbitan', 'Teknik Informatika dan Komputer'])->default('Teknik Elektro')->after('kelas');
            }
            if (!Schema::hasColumn('anggota', 'motivasi')) {
                $table->text('motivasi')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('anggota', 'pengalaman')) {
                $table->text('pengalaman')->nullable()->after('motivasi');
            }
            
            // Revert no_hp default
            $table->text('no_hp')->default('62')->change();
        });
        
        // Rollback tabel open_recruitment
        Schema::table('open_recruitment', function (Blueprint $table) {
            // Hapus kolom baru
            if (Schema::hasColumn('open_recruitment', 'jurusan_prodi_kelas')) {
                $table->dropColumn('jurusan_prodi_kelas');
            }
            
            // Kembalikan kolom lama
            if (!Schema::hasColumn('open_recruitment', 'kelas')) {
                $table->text('kelas')->default('')->after('nama_lengkap');
            }
            if (!Schema::hasColumn('open_recruitment', 'jurusan')) {
                $table->enum('jurusan', ['Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Akuntansi', 'Administrasi Niaga', 'Teknik Grafika Penerbitan', 'Teknik Informatika dan Komputer'])->default('Teknik Elektro')->after('kelas');
            }
            if (!Schema::hasColumn('open_recruitment', 'motivasi')) {
                $table->text('motivasi')->nullable();
            }
            if (!Schema::hasColumn('open_recruitment', 'pengalaman')) {
                $table->text('pengalaman')->nullable();
            }
        });
    }
};
