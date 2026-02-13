# Implementasi RBAC (Role-Based Access Control)

# Status: Core, Organization & Akademik Module ✅

## 1. Definisi Peran (Roles)

Role-role berikut telah didefinisikan dalam `Database\Seeders\RolesAndPermissionsSeeder.php` dan tabel `roles`:

| Role                 | Keterangan                                                                |
| :------------------- | :------------------------------------------------------------------------ |
| **super_admin**      | Akses penuh (God Mode). Menggunakan `Permission::all()`.                  |
| **chairman**         | Ketua Umum. Akses hampir setara Super Admin.                              |
| **admin_media**      | Sekretaris Umum / Humas. Mengelola profil organisasi, anggota, rekrutmen. |
| **admin_pengajaran** | Kepala Dept. PRE. Mengelola pembelajaran, pertemuan, presensi, bank soal. |
| **admin_keuangan**   | Bendahara. Mengelola anggaran, transaksi, iuran kas.                      |
| **admin_inventaris** | Perlengkapan. Mengelola barang, peminjaman.                               |
| **admin_sekretaris** | Sekretaris. Mengelola surat & dokumen organisasi.                         |
| **admin_project**    | Project Manager. Mengelola project & tim.                                 |
| **anggota**          | Basic Member. Akses terbatas (lihat materi, presensi sendiri).            |

## 2. Granular Permissions (Hak Akses Terperinci)

Permissions dibuat dengan format `[module_name].[action]` untuk kontrol spesifik.

### Daftar Module & Permission yang Sudah Terimplementasi (UI & Backend):

1.  **Dashboard**: `dashboard.view`
2.  **User**: `user.view`, `user.create`, `user.edit`, `user.delete`
3.  **Role**: `role.view`, `role.manage`, `role.assign`
4.  **Tahun Kepengurusan**:
    - `tahun_kepengurusan.view`, `tahun_kepengurusan.create`, `tahun_kepengurusan.edit`, `tahun_kepengurusan.delete`, `tahun_kepengurusan.activate`
5.  **Profil Organisasi**:
    - `profil_organisasi.view`, `profil_organisasi.edit`
6.  **Department**:
    - `department.view`, `department.create`, `department.edit`, `department.delete`
7.  **Anggota**:
    - `anggota.view`, `anggota.create`, `anggota.edit`, `anggota.delete`, `anggota.export`
8.  **Open Recruitment**:
    - `open_recruitment.view`, `open_recruitment.create`, `open_recruitment.edit` (termasuk update status lulus/gagal), `open_recruitment.delete`, `open_recruitment.manage_applicants`
9.  **Door Lock**:
    - `door_lock.view_history`

## 3. Implementasi Teknis

### A. Middleware pada Route (`routes/web.php`)

Semua route terkait module di atas telah diamankan menggunakan middleware `can:[permission_name]`.
Contoh:

```php
Route::get('/tahun-kepengurusan', \App\Livewire\Organisasi\TahunKepengurusan::class)
    ->middleware('can:tahun_kepengurusan.view');
```

### B. Proteksi Tampilan (Blade Views)

Setiap tombol aksi (Create, Edit, Delete, View, Activate, Update Status) dibungkus dengan direktif Blade `@can`.

**File yang Telah Diupdate:**

1.  `resources/views/livewire/organisasi/tahun-kepengurusan.blade.php`:
    - Tombol "Add Data": `@can('tahun_kepengurusan.create')`
    - Tombol "Edit": `@can('tahun_kepengurusan.edit')`
    - Tombol "Delete": `@can('tahun_kepengurusan.delete')`
    - Tombol "Activate": `@can('tahun_kepengurusan.activate')`

2.  `resources/views/livewire/organisasi/profil-organisasi.blade.php`:
    - Tombol "Edit Profil": `@can('profil_organisasi.edit')`
    - Tombol "Tambah Profil" (Empty State): `@can('profil_organisasi.edit')`

3.  `resources/views/livewire/organisasi/department.blade.php`:
    - Tombol "Add Data (+)" : `@can('department.create')`
    - Tombol "Edit": `@can('department.edit')`
    - Tombol "Delete": `@can('department.delete')`

4.  `resources/views/livewire/organisasi/anggota.blade.php`:
    - Tombol "View Detail": `@can('anggota.view')`
    - Tombol "Edit": `@can('anggota.edit')`
    - Tombol "Delete": `@can('anggota.delete')`
    - Tombol "Add Data (+)" : `@can('anggota.create')` (untuk kedua tab Pengurus & Anggota)

5.  `resources/views/livewire/organisasi/open-recruitment.blade.php`:
    - Tombol "View Detail": `@can('open_recruitment.view')`
    - Tombol "Update Status (Lulus/Gagal)": `@can('open_recruitment.edit')`
    - Tombol "Edit": `@can('open_recruitment.edit')`
    - Tombol "Delete": `@can('open_recruitment.delete')`
    - Tombol "Add Data (+)" : `@can('open_recruitment.create')`

6.  `resources/views/livewire/organisasi/control-user.blade.php`:
    - Tombol "Toggle Activate": `@can('user.edit')`

7.  `resources/views/livewire/organisasi/permission-matrix.blade.php`:
    - Checkbox Toggle Permission: `@can('role.manage')`
    - Tampilan Read-only (ikon check/cross): Jika user tidak punya `role.manage`.

8.  **Modul Akademik (PRE)** - ✅ **COMPLETED**:
    - `resources/views/livewire/akademik/program-pembelajaran.blade.php`:
        - Tombol "Add Data (+)": `@can('program_pembelajaran.create')`
        - Tombol "View Pertemuan": `@can('program_pembelajaran.view')`
        - Tombol "Edit": `@can('program_pembelajaran.edit')`
        - Tombol "Delete": `@can('program_pembelajaran.delete')`
    - `resources/views/livewire/akademik/pertemuan.blade.php`:
        - Tombol "Add Data (+)": `@can('pertemuan.create')`
        - Tombol "Kelola Bank Soal": `@can('bank_soal.view')`
        - Tombol "Gallery": `@can('pertemuan.view')`
        - Tombol "Edit": `@can('pertemuan.edit')`
        - Tombol "Delete": `@can('pertemuan.delete')`
    - `resources/views/livewire/akademik/presensi-pertemuan.blade.php`:
        - Tombol "Update Presensi": `@can('presensi.edit')`
    - `resources/views/livewire/akademik/project.blade.php`:
        - Tombol "Add Data (+)": `@can('project.create')`
        - Tombol "Kelola Kelompok": `@can('project_team.view')`
        - Tombol "View": `@can('project.view')`
        - Tombol "Edit": `@can('project.edit')`
        - Tombol "Delete": `@can('project.delete')`
    - `resources/views/livewire/akademik/project-teams.blade.php`:
        - Tombol "Tambah Kelompok": `@can('project_team.manage')`
        - Tombol "Edit Team": `@can('project_team.manage')`
        - Tombol "Delete Team": `@can('project_team.manage')`
    - `resources/views/livewire/akademik/status-anggota-ujian.blade.php`:
        - Tombol "Refresh": `@can('ujian.view_status')`
        - Tombol "Terapkan Aksi": `@can('ujian.view_status')`
    - `resources/views/livewire/akademik/hasil-ujian-pertemuan.blade.php`:
        - Tombol "Refresh": `@can('ujian.view_hasil')`
        - Tombol "Tandai Semua Dikoreksi": `@can('ujian.koreksi')`
        - Tombol "Input Nilai": `@can('ujian.koreksi')`
    - `resources/views/livewire/akademik/bank-soal/soal-pertemuan.blade.php`:
        - Toggle "Status Bank Soal": `@can('bank_soal.edit')`
        - Tombol "Add Data (+)": `@can('bank_soal.create')`
        - Tombol "Edit Soal": `@can('bank_soal.edit')`
        - Tombol "Delete Soal": `@can('bank_soal.delete')`
    - `resources/views/livewire/akademik/hasil-ujian/koreksi.blade.php`:
        - Tombol "Tandai Sudah Dikoreksi": `@can('ujian.koreksi')`
        - Tombol "Edit Nilai": `@can('ujian.koreksi')`

### C. Permission Matrix UI (`PermissionMatrix.php`)

- Fitur pencarian permission.
- Grouping permission berdasarkan modul.
- Update permission secara real-time via Livewire.
- Indikator visual status permission untuk setiap role.

## 4. Next Steps (Belum Diimplementasi UI-nya)

Module-module berikut **sudah ada permission-nya di database/seeder**, tetapi view-nya **belum dipasang `@can`** untuk tombol aksi:

1.  **Modul Keuangan**:
    - Anggaran (`anggaran.blade.php`)
    - Jenis Anggaran (`jenis-anggaran.blade.php`)
    - Transaksi (`transaksi.blade.php`)
    - Iuran Kas (`iuran-kas.blade.php`)
    - Laporan Keuangan (`laporan.blade.php`)
2.  **Modul Perlengkapan/Inventaris**:
    - Kategori Barang (`kategori-barang.blade.php`)
    - Barang (`barang.blade.php`)
    - Peminjaman (`peminjaman-barang.blade.php`)
    - Pengadaan (`pengadaan-barang.blade.php`)
3.  **Modul Sekretaris**:
    - Surat (`surat.blade.php`)

## Perintah untuk Melanjutkan di Chat Baru

Gunakan perintah ini untuk melanjutkan pengerjaan:

> "Lanjutkan implementasi RBAC UI pada modul [Nama Module]. Referensi implementasi ada di `docs/RBAC_IMPLEMENTATION_STATUS.md`. Pastikan tombol aksi dibungkus `@can` sesuai permission yang terdefinisi."
