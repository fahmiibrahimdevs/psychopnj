# RBAC Permissions Structure

## Permission Naming Convention

Format: `module_name.action`

### Actions Standard:

- `view` - Lihat list/index page (untuk sidebar menu access)
- `view_detail` - Lihat detail data (tombol eye icon)
- `create` - Buat data baru
- `edit` - Edit data
- `delete` - Hapus data
- `activate` - Aktifkan/nonaktifkan status
- `update_status` - Update status khusus (misal: lulus/gagal)

---

## Module: Organisasi

### 1. Tahun Kepengurusan

**Permissions:**

- `tahun_kepengurusan.view` - View list
- `tahun_kepengurusan.create` - Tombol Create
- `tahun_kepengurusan.edit` - Tombol Edit
- `tahun_kepengurusan.delete` - Tombol Delete
- `tahun_kepengurusan.activate` - Tombol Activate/Deactivate

**Buttons:**

- Create: `tahun_kepengurusan.create`
- Edit: `tahun_kepengurusan.edit`
- Delete: `tahun_kepengurusan.delete`
- Activate: `tahun_kepengurusan.activate`

---

### 2. Profil Organisasi

**Permissions:**

- `profil_organisasi.view` - View page
- `profil_organisasi.edit` - Tombol Edit

**Buttons:**

- Edit: `profil_organisasi.edit`

---

### 3. Department

**Permissions:**

- `department.view` - View list
- `department.create` - Tombol Create
- `department.edit` - Tombol Edit
- `department.delete` - Tombol Delete

**Buttons:**

- Create: `department.create`
- Edit: `department.edit`
- Delete: `department.delete`

---

### 4. Anggota

**Permissions:**

- `anggota.view` - View list
- `anggota.create` - Tombol Create
- `anggota.edit` - Tombol Edit
- `anggota.delete` - Tombol Delete
- `anggota.view_detail` - Tombol View Detail (eye icon)

**Buttons:**

- Create: `anggota.create`
- Edit (pencil): `anggota.edit`
- Delete (trash): `anggota.delete`
- View Detail (eye): `anggota.view_detail`

**Removed:**

- ~~`anggota.export`~~ - Tidak dipakai

---

### 5. Open Recruitment

**Permissions:**

- `open_recruitment.view` - View list
- `open_recruitment.create` - Tombol Create
- `open_recruitment.edit` - Tombol Edit
- `open_recruitment.delete` - Tombol Delete
- `open_recruitment.view_detail` - Tombol View Detail (eye icon)
- `open_recruitment.update_status` - Tombol Lulus/Gagal (check/times icon)

**Buttons:**

- Create: `open_recruitment.create`
- Edit (pencil): `open_recruitment.edit`
- Delete (trash): `open_recruitment.delete`
- View Detail (eye): `open_recruitment.view_detail`
- Tandai Lulus (check): `open_recruitment.update_status`
- Tandai Gagal (times): `open_recruitment.update_status`

**Changed:**

- ~~`open_recruitment.manage_applicants`~~ → `open_recruitment.update_status`

---

### 6. Door Lock

**Permissions:**

- `door_lock.view` - View riwayat

**Changed:**

- ~~`door_lock.view_history`~~ → `door_lock.view`

---

### 7. Control User

**Permissions:**

- `control_user.view` - View list users
- `control_user.update_status` - Tombol Activate/Deactivate user

**Buttons:**

- Activate/Deactivate: `control_user.update_status`

**Access:** Super Admin dan Chairman only

---

### 8. Permission Matrix

**Permissions:**

- `permission_matrix.view` - View role & permission matrix
- `permission_matrix.manage` - Toggle permission untuk roles

**Access:**

- **Super Admin:** Full access (view + toggle switches)
- **Chairman:** Full access (view + toggle switches)
- **Other roles:** No access

---

## Module: PRE (Akademik)

### 1. Program Pembelajaran

**Permissions:**

- `program_pembelajaran.view` - View list
- `program_pembelajaran.create` - Tombol Create
- `program_pembelajaran.edit` - Tombol Edit
- `program_pembelajaran.delete` - Tombol Delete

---

### 2. Pertemuan

**Permissions:**

- `pertemuan.view` - View list
- `pertemuan.create` - Tombol Create
- `pertemuan.edit` - Tombol Edit
- `pertemuan.delete` - Tombol Delete
- `pertemuan.publish` - Tombol Publish

---

### 3. Bank Soal

**Permissions:**

- `bank_soal.view` - View list
- `bank_soal.create` - Tombol Create
- `bank_soal.edit` - Tombol Edit
- `bank_soal.delete` - Tombol Delete

---

### 4. Presensi

**Permissions:**

- `presensi.view` - View list
- `presensi.create` - Tombol Create
- `presensi.edit` - Tombol Edit
- `presensi.delete` - Tombol Delete
- `presensi.scan_qr` - Scan QR Code

---

### 5. Ujian

**Permissions:**

- `ujian.view_status` - View Status Anggota Ujian
- `ujian.view_hasil` - View Hasil Ujian
- `ujian.koreksi` - Koreksi Ujian

---

## Module: Project

**Permissions:**

- `project.view` - View list
- `project.create` - Tombol Create
- `project.edit` - Tombol Edit
- `project.delete` - Tombol Delete

**Project Team:**

- `project_team.view` - View teams
- `project_team.manage` - Manage teams
- `project_team.assign_member` - Assign members

---

## Module: Keuangan

### 1. Transaksi

**Permissions:**

- `transaksi.view` - View list
- `transaksi.create` - Tombol Create
- `transaksi.edit` - Tombol Edit
- `transaksi.delete` - Tombol Delete
- `transaksi.approve` - Approve transaksi
- `transaksi.export` - Export laporan

### 2. Iuran Kas

**Permissions:**

- `iuran_kas.view` - View list
- `iuran_kas.create` - Tombol Create
- `iuran_kas.edit` - Tombol Edit
- `iuran_kas.delete` - Tombol Delete
- `iuran_kas.remind_member` - Kirim reminder

---

## Module: Perlengkapan

### 1. Barang

**Permissions:**

- `barang.view` - View list
- `barang.create` - Tombol Create
- `barang.edit` - Tombol Edit
- `barang.delete` - Tombol Delete

### 2. Kategori Barang

**Permissions:**

- `kategori_barang.view` - View list
- `kategori_barang.create` - Tombol Create
- `kategori_barang.edit` - Tombol Edit
- `kategori_barang.delete` - Tombol Delete

### 3. Peminjaman

**Permissions:**

- `peminjaman.view` - View list
- `peminjaman.create` - Tombol Create
- `peminjaman.edit` - Tombol Edit
- `peminjaman.delete` - Tombol Delete
- `peminjaman.approve` - Approve peminjaman
- `peminjaman.reject` - Reject peminjaman
- `peminjaman.return` - Tandai sudah dikembalikan

---

## Module: Sekretaris

### 1. Surat

**Permissions:**

- `surat.view` - View list
- `surat.create` - Tombol Create
- `surat.edit` - Tombol Edit
- `surat.delete` - Tombol Delete
- `surat.download` - Download surat
- `surat.archive` - Archive surat

### 2. Dokumen Organisasi

**Permissions:**

- `dokumen_organisasi.view` - View list
- `dokumen_organisasi.upload` - Upload dokumen
- `dokumen_organisasi.delete` - Delete dokumen

---

## Blade Implementation

### Pattern Standar:

```blade
{{-- View List (untuk akses menu) --}}
@if ($isSuperAdmin || in_array("module.view", $userPermissions))
    <li class="menu-item">...</li>
@endif

{{-- Create Button --}}
@if ($this->can("module.create"))
    <button>Create</button>
@endif

{{-- View Detail Button --}}
@if ($this->can("module.view_detail"))
    <button><i class="fas fa-eye"></i></button>
@endif

{{-- Edit Button --}}
@if ($this->can("module.edit"))
    <button><i class="fas fa-edit"></i></button>
@endif

{{-- Delete Button --}}
@if ($this->can("module.delete"))
    <button><i class="fas fa-trash"></i></button>
@endif

{{-- Special Action (misal: Activate) --}}
@if ($this->can("module.activate"))
    <button>Activate</button>
@endif
```

---

## Role Assignments

### Super Admin

- **All permissions** (God Mode)

### Chairman

- **All permissions** (sama seperti Super Admin)

### Admin Media

- Dashboard, Organisasi (limited), Door Lock, Kritik Saran

### Admin Pengajaran

- Dashboard, PRE Module (full), Project (view only)

### Admin Keuangan

- Dashboard, Keuangan Module (full)

### Admin Inventaris

- Dashboard, Perlengkapan Module (full)

### Admin Sekretaris

- Dashboard, Sekretaris Module (full), Presensi (view), Pertemuan (view)

### Admin Project

- Dashboard, Project Module (full)

### Anggota

- Dashboard, View-only access (Program, Pertemuan, Project, Surat)

---

## Migration Command

```bash
php artisan migrate:fresh --seed
```

Seeder akan otomatis create:

- All permissions sesuai structure ini
- All roles dengan permission assignments
- Default users untuk testing (password: `password`)

---

## Testing Accounts

| Role             | Email              | Password |
| ---------------- | ------------------ | -------- |
| Super Admin      | admin@app.com      | password |
| Chairman         | chairman@app.com   | password |
| Admin Pengajaran | pengajaran@app.com | password |
| Admin Keuangan   | keuangan@app.com   | password |
| Admin Media      | media@app.com      | password |
| Admin Inventaris | inventaris@app.com | password |
| Admin Sekretaris | sekretaris@app.com | password |
| Admin Project    | project@app.com    | password |
| Anggota          | anggota@app.com    | password |
