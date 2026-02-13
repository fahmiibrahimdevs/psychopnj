<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define ALL Permissions Dynamically
        // Format: [Module Name] => [List of Actions]
        $modules = [
            
            // --- Core System & Dashboard ---
            'dashboard' => ['view'],
            'control_user' => ['view', 'update_status'], // Control User (activate/deactivate)
            'permission_matrix' => ['view', 'manage'], // Role & Permission Management
            
            // --- Modul 1: Organisasi (General) ---
            'tahun_kepengurusan' => ['view', 'create', 'edit', 'delete', 'activate'],
            'profil_organisasi' => ['view', 'edit'],
            'department' => ['view', 'create', 'edit', 'delete'],
            'anggota' => ['view', 'create', 'edit', 'delete', 'view_detail'],
            'struktur_jabatan' => ['view', 'manage'],
            'open_recruitment' => ['view', 'create', 'edit', 'delete', 'view_detail', 'update_status'],
            'door_lock' => ['view'],

            // --- Modul 2: PRE (Akademik & Pengajaran) ---
            'program_kegiatan' => ['view', 'create', 'edit', 'delete', 'view_pertemuan'],
            'pertemuan' => ['view', 'create', 'edit', 'delete', 'gallery', 'bank_soal'],
            'presensi' => ['view', 'action', 'update'],
            'statistik_kehadiran' => ['view'],
            'ujian' => ['view', 'view_status', 'view_hasil', 'koreksi'],

            // --- Modul 3: Project & Kegiatan ---
            'project' => ['view', 'create', 'edit', 'delete', 'view_team', 'kelola_kelompok'],
            'project_team' => ['view', 'create', 'edit', 'delete'],

            // --- Modul 4: Keuangan ---
            'anggaran' => ['view', 'create', 'edit', 'delete'],
            'jenis_anggaran' => ['view', 'create', 'edit', 'delete'],
            'transaksi' => ['view', 'create', 'edit', 'delete', 'approve', 'export'],
            'iuran_kas' => ['view', 'create', 'edit', 'delete', 'approve', 'export'],
            'laporan_keuangan' => ['view', 'export'],

            // --- Modul 5: Perlengkapan (Inventaris) ---
            'barang' => ['view', 'create', 'edit', 'delete', 'export'],
            'kategori_barang' => ['view', 'create', 'edit', 'delete', 'export'],
            'peminjaman_barang' => ['view', 'create', 'delete', 'return'],
            'pengadaan_barang' => ['view', 'create', 'edit', 'delete', 'approve', 'reject'],

            // --- Modul 6: Sekretaris ---
            'surat' => ['view', 'create', 'edit', 'delete', 'download'],

            // --- Modul 7: Evaluasi ---
            'kritik_saran' => ['view', 'delete', 'reply'],
            'rekap_evaluasi' => ['view', 'export'],
        ];

        // Create Permissions
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $module . '.' . $action]);
            }
        }

        // 2. Define Roles
        
        // Role: Super Admin (God Mode)
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Role: Chairman (Ketua Umum)
        // Full access to everything
        $chairman = Role::create(['name' => 'chairman']);
        $chairman->givePermissionTo(Permission::all());

        // Role: Admin Media & Organisasi (Sekretaris Umum / Humas)
        $adminMedia = Role::create(['name' => 'admin_media']);
        $adminMedia->givePermissionTo([
            'dashboard.view',
            'surat.view',
            'control_user.view',
            'permission_matrix.view',
            'tahun_kepengurusan.view',
            'profil_organisasi.view',
            'department.view',
            'anggota.view',
            'struktur_jabatan.view',
            'open_recruitment.view',
            'door_lock.view',
            'program_kegiatan.view', 'program_kegiatan.view_pertemuan',
            'pertemuan.view', 'pertemuan.gallery',
            'presensi.view',
            'statistik_kehadiran.view',
            'ujian.view',
            'project.view', 'project.kelola_kelompok',
            'project_team.view',
            'anggaran.view',
            'jenis_anggaran.view',
            'transaksi.view',
            'iuran_kas.view',
            'laporan_keuangan.view',
            'barang.view',
            'kategori_barang.view',
            'peminjaman_barang.view',
            'pengadaan_barang.view',
            'kritik_saran.view',
            'rekap_evaluasi.view',
        ]);

        // Role: Admin Pengajaran (Kepala Dept. PRE)
        $adminPengajaran = Role::create(['name' => 'admin_pengajaran']);
        $adminPengajaran->givePermissionTo([
            'dashboard.view',
            'surat.view',
            'control_user.view',
            'permission_matrix.view',
            'tahun_kepengurusan.view',
            'profil_organisasi.view',
            'department.view',
            'anggota.view',
            'struktur_jabatan.view',
            'open_recruitment.view',
            'door_lock.view',
            'program_kegiatan.view', 'program_kegiatan.create', 'program_kegiatan.edit', 'program_kegiatan.delete', 'program_kegiatan.view_pertemuan',
            'pertemuan.view', 'pertemuan.create', 'pertemuan.edit', 'pertemuan.delete', 'pertemuan.bank_soal',
            'presensi.view', 'presensi.action', 'presensi.update',
            'statistik_kehadiran.view',
            'ujian.view', 'ujian.view_status', 'ujian.view_hasil', 'ujian.koreksi',
            'project.view', 'project.kelola_kelompok',
            'project_team.view',
            'anggaran.view',
            'jenis_anggaran.view',
            'transaksi.view',
            'iuran_kas.view',
            'laporan_keuangan.view',
            'barang.view',
            'kategori_barang.view',
            'peminjaman_barang.view',
            'pengadaan_barang.view',
            'kritik_saran.view',
            'rekap_evaluasi.view',
        ]);

        // Role: Admin Keuangan (Bendahara)
        $adminKeuangan = Role::create(['name' => 'admin_keuangan']);
        $adminKeuangan->givePermissionTo([
            'dashboard.view',
            'surat.view',
            'control_user.view',
            'permission_matrix.view',
            'tahun_kepengurusan.view',
            'profil_organisasi.view',
            'department.view',
            'anggota.view',
            'struktur_jabatan.view',
            'open_recruitment.view',
            'door_lock.view',
            'program_kegiatan.view', 'program_kegiatan.view_pertemuan',
            'pertemuan.view',
            'presensi.view',
            'statistik_kehadiran.view',
            'ujian.view',
            'project.view', 'project.kelola_kelompok',
            'project_team.view',
            'anggaran.view', 'anggaran.create', 'anggaran.edit', 'anggaran.delete',
            'jenis_anggaran.view', 'jenis_anggaran.create', 'jenis_anggaran.edit', 'jenis_anggaran.delete',
            'transaksi.view', 'transaksi.create', 'transaksi.edit', 'transaksi.delete', 'transaksi.approve', 'transaksi.export',
            'iuran_kas.view', 'iuran_kas.create', 'iuran_kas.edit', 'iuran_kas.delete', 'iuran_kas.approve', 'iuran_kas.export',
            'laporan_keuangan.view', 'laporan_keuangan.export',
            'barang.view',
            'kategori_barang.view',
            'peminjaman_barang.view',
            'pengadaan_barang.view', 'pengadaan_barang.approve', 'pengadaan_barang.reject',
            'kritik_saran.view',
            'rekap_evaluasi.view',
        ]);

        // Role: Admin Inventaris (Perlengkapan)
        $adminInventaris = Role::create(['name' => 'admin_inventaris']);
        $adminInventaris->givePermissionTo([
            'dashboard.view',
            'surat.view',
            'control_user.view',
            'permission_matrix.view',
            'tahun_kepengurusan.view',
            'profil_organisasi.view',
            'department.view',
            'anggota.view',
            'struktur_jabatan.view',
            'open_recruitment.view',
            'door_lock.view',
            'program_kegiatan.view', 'program_kegiatan.view_pertemuan',
            'pertemuan.view',
            'presensi.view',
            'statistik_kehadiran.view',
            'ujian.view',
            'project.view', 'project.kelola_kelompok',
            'project_team.view',
            'anggaran.view',
            'jenis_anggaran.view',
            'transaksi.view',
            'iuran_kas.view',
            'laporan_keuangan.view',
            'barang.view', 'barang.create', 'barang.edit', 'barang.delete', 'barang.export',
            'kategori_barang.view', 'kategori_barang.create', 'kategori_barang.edit', 'kategori_barang.delete', 'kategori_barang.export',
            'peminjaman_barang.view', 'peminjaman_barang.create', 'peminjaman_barang.delete', 'peminjaman_barang.return',
            'pengadaan_barang.view', 'pengadaan_barang.create', 'pengadaan_barang.edit', 'pengadaan_barang.delete',
            'kritik_saran.view',
            'rekap_evaluasi.view',
        ]);

        // Role: Admin Sekretaris (Sekretaris)
        $adminSekretaris = Role::create(['name' => 'admin_sekretaris']);
        $adminSekretaris->givePermissionTo([
            'dashboard.view',
            'surat.view', 'surat.create', 'surat.edit', 'surat.delete', 'surat.download',
            'control_user.view',
            'permission_matrix.view',
            'tahun_kepengurusan.view',
            'profil_organisasi.view',
            'department.view',
            'anggota.view',
            'struktur_jabatan.view',
            'open_recruitment.view',
            'door_lock.view',
            'program_kegiatan.view', 'program_kegiatan.view_pertemuan',
            'pertemuan.view',
            'presensi.view', 'presensi.action', 'presensi.update',
            'statistik_kehadiran.view',
            'ujian.view',
            'project.view', 'project.kelola_kelompok',
            'project_team.view',
            'anggaran.view',
            'jenis_anggaran.view',
            'transaksi.view',
            'iuran_kas.view',
            'laporan_keuangan.view',
            'barang.view',
            'kategori_barang.view',
            'peminjaman_barang.view',
            'pengadaan_barang.view',
            'kritik_saran.view',
            'rekap_evaluasi.view',
        ]);

        // Role: Admin Project (Project Manager)
        $adminProject = Role::create(['name' => 'admin_project']);
        $adminProject->givePermissionTo([
            'dashboard.view',
            'surat.view',
            'control_user.view',
            'permission_matrix.view',
            'tahun_kepengurusan.view',
            'profil_organisasi.view',
            'department.view',
            'anggota.view',
            'struktur_jabatan.view',
            'open_recruitment.view',
            'door_lock.view',
            'program_kegiatan.view', 'program_kegiatan.view_pertemuan',
            'pertemuan.view',
            'presensi.view',
            'statistik_kehadiran.view',
            'ujian.view',
            'project.view', 'project.create', 'project.edit', 'project.delete', 'project.view_team', 'project.kelola_kelompok',
            'project_team.view', 'project_team.create', 'project_team.edit', 'project_team.delete',
            'anggaran.view',
            'jenis_anggaran.view',
            'transaksi.view',
            'iuran_kas.view',
            'laporan_keuangan.view',
            'barang.view',
            'kategori_barang.view',
            'peminjaman_barang.view',
            'pengadaan_barang.view',
            'kritik_saran.view',
            'rekap_evaluasi.view',
        ]);

        // Role: Anggota (Basic Member)
        $anggota = Role::create(['name' => 'anggota']);
        $anggota->givePermissionTo([
            'dashboard.view',
            'program_kegiatan.view',
            'pertemuan.view',
            'presensi.view', // Lihat history presensi sendiri
            'project.view',
            'surat.view', // Jika surat public
        ]);


        // 3. Create Default Users for Testing
        $this->createUser('Super Admin', 'admin@app.com', $superAdmin);
        $this->createUser('Chairman', 'chairman@app.com', $chairman);
        $this->createUser('Admin Pengajaran', 'pengajaran@app.com', $adminPengajaran);
        $this->createUser('Admin Keuangan', 'keuangan@app.com', $adminKeuangan);
        $this->createUser('Admin Media', 'media@app.com', $adminMedia);
        $this->createUser('Admin Inventaris', 'inventaris@app.com', $adminInventaris);
        $this->createUser('Admin Sekretaris', 'sekretaris@app.com', $adminSekretaris);
        $this->createUser('Admin Project', 'project@app.com', $adminProject);
        $this->createUser('Anggota Biasa', 'anggota@app.com', $anggota);
    }

    private function createUser($name, $email, $role)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password'), // Access: password
            'active' => true,
        ]);
        $user->assignRole($role);
    }
}
