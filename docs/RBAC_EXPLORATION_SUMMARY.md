# RBAC Implementation - Exploration Summary

## 📋 Status Overview

Implementasi RBAC menggunakan **Spatie Laravel Permission** dengan status:

- ✅ **Core & Organization Module**: Fully Implemented
- 🟡 **Other Modules**: Permissions defined, UI protection needed

---

## 🗂️ Struktur yang Sudah Ada

### 1. Database & Migrations

**Migration File**: `database/migrations/2026_02_12_163356_create_permission_tables.php`

Tables yang dibuat:

- `permissions` - Menyimpan semua permission
- `roles` - Menyimpan semua role
- `model_has_permissions` - Polymorphic relation untuk direct permission ke user
- `model_has_roles` - Polymorphic relation untuk role assignment ke user
- `role_has_permissions` - Pivot table untuk permission yang dimiliki role

### 2. User Model

**File**: `app/Models/User.php`

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    // ...
}
```

Model User sudah menggunakan trait `HasRoles` dari Spatie, sehingga bisa:

- `$user->hasRole('super_admin')`
- `$user->can('anggaran.create')`
- `$user->assignRole('admin_keuangan')`
- `$user->givePermissionTo('transaksi.approve')`

### 3. Seeder - Roles & Permissions

**File**: `database/seeders/RolesAndPermissionsSeeder.php`

#### Roles yang Sudah Didefinisikan:

| Role               | Description           | Access Level                      |
| ------------------ | --------------------- | --------------------------------- |
| `super_admin`      | God Mode              | All permissions                   |
| `chairman`         | Ketua Umum            | Almost all permissions            |
| `admin_media`      | Sekretaris Umum/Humas | Organisasi, anggota, rekrutmen    |
| `admin_pengajaran` | Kepala Dept. PRE      | Pembelajaran, presensi, bank soal |
| `admin_keuangan`   | Bendahara             | Anggaran, transaksi, iuran kas    |
| `admin_inventaris` | Perlengkapan          | Barang, peminjaman, pengadaan     |
| `admin_sekretaris` | Sekretaris            | Surat, dokumen                    |
| `admin_project`    | Project Manager       | Project & teams                   |
| `anggota`          | Basic Member          | Read-only access                  |

#### Permissions yang Sudah Didefinisikan:

Permissions menggunakan format: `{module}.{action}`

**Core System:**

- `dashboard.view`
- `user.view`, `user.create`, `user.edit`, `user.delete`
- `role.view`, `role.manage`, `role.assign`

**Organisasi Module:**

- `tahun_kepengurusan.*` (view, create, edit, delete, activate)
- `profil_organisasi.*` (view, edit)
- `department.*` (view, create, edit, delete)
- `anggota.*` (view, create, edit, delete, export)
- `struktur_jabatan.*` (view, manage)
- `open_recruitment.*` (view, create, edit, delete, manage_applicants)
- `door_lock.view_history`

**Akademik Module:**

- `program_pembelajaran.*` (view, create, edit, delete)
- `pertemuan.*` (view, create, edit, delete, publish)
- `presensi.*` (view, create, edit, delete, scan_qr)
- `statistik_kehadiran.*` (view, export)
- `bank_soal.*` (view, create, edit, delete)
- `ujian.*` (view_status, view_hasil, koreksi)

**Project Module:**

- `project.*` (view, create, edit, delete)
- `project_team.*` (view, manage, assign_member)

**Keuangan Module:**

- `anggaran.*` (view, create, edit, delete, approve)
- `jenis_anggaran.*` (view, create, edit, delete)
- `transaksi.*` (view, create, edit, delete, approve, export)
- `iuran_kas.*` (view, create, edit, delete, remind_member)
- `laporan_keuangan.*` (view, export)

**Perlengkapan Module:**

- `barang.*` (view, create, edit, delete)
- `kategori_barang.*` (view, create, edit, delete)
- `peminjaman.*` (view, create, edit, delete, approve, reject, return)
- `pengadaan.*` (view, create, edit, delete, approve, reject)

**Sekretaris Module:**

- `surat.*` (view, create, edit, delete, download, archive)
- `dokumen_organisasi.*` (view, upload, delete)

**Evaluasi Module:**

- `kritik_saran.*` (view, delete, reply)
- `rekap_evaluasi.*` (view, export)

### 4. Routes Protection

**File**: `routes/web.php`

Semua route sudah dilindungi dengan middleware `can:permission_name`:

```php
Route::get('anggaran', \App\Livewire\Keuangan\Anggaran::class)
    ->name('anggaran')
    ->middleware('can:anggaran.view');

Route::get('transaksi-keuangan', \App\Livewire\Keuangan\Transaksi::class)
    ->name('transaksi-keuangan')
    ->middleware('can:transaksi.view');

Route::get('barang', \App\Livewire\Perlengkapan\Barang::class)
    ->name('barang')
    ->middleware('can:barang.view');
```

### 5. View Protection - Sudah Terimplementasi

**Files dengan @can directive:**

✅ **Organisasi Module:**

- `resources/views/livewire/organisasi/tahun-kepengurusan.blade.php`
    - ✅ Tombol Create: `@can('tahun_kepengurusan.create')`
    - ✅ Tombol Edit: `@can('tahun_kepengurusan.edit')`
    - ✅ Tombol Delete: `@can('tahun_kepengurusan.delete')`
    - ✅ Tombol Activate: `@can('tahun_kepengurusan.activate')`

- `resources/views/livewire/organisasi/profil-organisasi.blade.php`
    - ✅ Tombol Edit: `@can('profil_organisasi.edit')`

- `resources/views/livewire/organisasi/department.blade.php`
    - ✅ Tombol Create, Edit, Delete

- `resources/views/livewire/organisasi/anggota.blade.php`
    - ✅ Tombol Create, View, Edit, Delete (tab Pengurus & Anggota)

- `resources/views/livewire/organisasi/open-recruitment.blade.php`
    - ✅ Tombol Create, View, Edit, Delete, Update Status

- `resources/views/livewire/organisasi/control-user.blade.php`
    - ✅ Toggle Activate: `@can('user.edit')`

- `resources/views/livewire/organisasi/permission-matrix.blade.php`
    - ✅ Checkbox Permission: `@can('role.manage')`

---

## 🔴 Yang Belum Terimplementasi (UI Protection)

Modules berikut **sudah ada permission di seeder** dan **routes sudah protected**, tapi **view belum dipasang @can directive**:

### 1. Keuangan Module

**Files:**

- ❌ `resources/views/livewire/keuangan/anggaran.blade.php`
- ❌ `resources/views/livewire/keuangan/jenis-anggaran.blade.php`
- ❌ `resources/views/livewire/keuangan/transaksi.blade.php`
- ❌ `resources/views/livewire/keuangan/iuran-kas.blade.php`
- ❌ `resources/views/livewire/keuangan/laporan.blade.php`

**Tombol yang perlu proteksi:**

- Floating Action Button (btn-modal) untuk Create
- Tombol Edit di setiap row
- Tombol Delete di setiap row
- Tombol Export (jika ada)
- Tombol Approve (jika ada)

**Permissions tersedia:**

```php
'anggaran' => ['view', 'create', 'edit', 'delete', 'approve']
'jenis_anggaran' => ['view', 'create', 'edit', 'delete']
'transaksi' => ['view', 'create', 'edit', 'delete', 'approve', 'export']
'iuran_kas' => ['view', 'create', 'edit', 'delete', 'remind_member']
'laporan_keuangan' => ['view', 'export']
```

### 2. Perlengkapan Module

**Files:**

- ❌ `resources/views/livewire/perlengkapan/barang.blade.php`
- ❌ `resources/views/livewire/perlengkapan/kategori-barang.blade.php`
- ❌ `resources/views/livewire/perlengkapan/peminjaman-barang.blade.php`
- ❌ `resources/views/livewire/perlengkapan/pengadaan-barang.blade.php`

**Tombol yang perlu proteksi:**

- Floating Action Button untuk Create
- Tombol Edit
- Tombol Delete
- Tombol View Detail
- Tombol Approve/Reject (peminjaman & pengadaan)
- Tombol Return (peminjaman)
- Tombol Import (jika ada)

**Permissions tersedia:**

```php
'barang' => ['view', 'create', 'edit', 'delete']
'kategori_barang' => ['view', 'create', 'edit', 'delete']
'peminjaman' => ['view', 'create', 'edit', 'delete', 'approve', 'reject', 'return']
'pengadaan' => ['view', 'create', 'edit', 'delete', 'approve', 'reject']
```

### 3. Akademik Module

**Files:**

- ❌ `resources/views/livewire/akademik/program-pembelajaran.blade.php`
- ❌ `resources/views/livewire/akademik/pertemuan.blade.php`
- ❌ `resources/views/livewire/akademik/presensi-pertemuan.blade.php`
- ❌ `resources/views/livewire/akademik/statistik-kehadiran.blade.php`
- ❌ `resources/views/livewire/akademik/project.blade.php`
- ❌ `resources/views/livewire/akademik/project-teams.blade.php`
- ❌ `resources/views/livewire/akademik/status-anggota-ujian.blade.php`
- ❌ `resources/views/livewire/akademik/hasil-ujian-pertemuan.blade.php`

**Tombol yang perlu proteksi:**

- Floating Action Button untuk Create
- Tombol Edit
- Tombol Delete
- Tombol View
- Tombol Publish (pertemuan)
- Tombol Scan QR (presensi)
- Tombol Export (statistik)
- Tombol Koreksi (ujian)
- Tombol Manage Team (project)

**Permissions tersedia:**

```php
'program_pembelajaran' => ['view', 'create', 'edit', 'delete']
'pertemuan' => ['view', 'create', 'edit', 'delete', 'publish']
'presensi' => ['view', 'create', 'edit', 'delete', 'scan_qr']
'statistik_kehadiran' => ['view', 'export']
'bank_soal' => ['view', 'create', 'edit', 'delete']
'ujian' => ['view_status', 'view_hasil', 'koreksi']
'project' => ['view', 'create', 'edit', 'delete']
'project_team' => ['view', 'manage', 'assign_member']
```

### 4. Sekretaris Module

**Files:**

- ❌ `resources/views/livewire/sekretaris/surat.blade.php`

**Tombol yang perlu proteksi:**

- Floating Action Button untuk Create
- Tombol Edit
- Tombol Delete
- Tombol Download
- Tombol Archive
- Upload Document

**Permissions tersedia:**

```php
'surat' => ['view', 'create', 'edit', 'delete', 'download', 'archive']
'dokumen_organisasi' => ['view', 'upload', 'delete']
```

---

## 📝 Pattern Implementasi @can

Berdasarkan file yang sudah terimplementasi, berikut pattern yang digunakan:

### Pattern 1: Tombol Edit

```blade
@can("module_name.edit")
    <button
        wire:click.prevent="edit({{ $row->id }})"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#formDataModal"
    >
        <i class="fas fa-edit"></i>
    </button>
@endcan
```

### Pattern 2: Tombol Delete

```blade
@can("module_name.delete")
    <button
        wire:click.prevent="deleteConfirm({{ $row->id }})"
        class="btn btn-danger"
    >
        <i class="fas fa-trash"></i>
    </button>
@endcan
```

### Pattern 3: Floating Action Button (Create)

```blade
@can("module_name.create")
    <button
        wire:click.prevent="isEditingMode(false)"
        class="btn-modal"
        data-toggle="modal"
        data-backdrop="static"
        data-keyboard="false"
        data-target="#formDataModal"
    >
        <i class="far fa-plus"></i>
    </button>
@endcan
```

### Pattern 4: Tombol View

```blade
@can("module_name.view")
    <button
        wire:click.prevent="view({{ $row->id }})"
        class="btn btn-info"
        data-toggle="modal"
        data-target="#viewModal"
    >
        <i class="fas fa-eye"></i>
    </button>
@endcan
```

### Pattern 5: Action Button dengan Custom Permission

```blade
@can("module_name.approve")
    <button wire:click="approve({{ $row->id }})" class="btn btn-success">
        Approve
    </button>
@endcan
```

---

## 🎯 Next Steps - Action Plan

### Priority 1: Keuangan Module (High Priority)

1. ✅ Anggaran - Protect Create, Edit, Delete buttons
2. ✅ Jenis Anggaran - Protect CRUD buttons
3. ✅ Transaksi - Protect CRUD + Approve + Export
4. ✅ Iuran Kas - Protect CRUD + Remind
5. ✅ Laporan - Protect View + Export

### Priority 2: Perlengkapan Module

1. ✅ Kategori Barang - Protect CRUD + Import
2. ✅ Barang - Protect CRUD + Import
3. ✅ Peminjaman - Protect CRUD + Approve/Reject/Return
4. ✅ Pengadaan - Protect CRUD + Approve/Reject

### Priority 3: Akademik Module

1. ✅ Program Pembelajaran - Protect CRUD
2. ✅ Pertemuan - Protect CRUD + Publish
3. ✅ Presensi - Protect CRUD + Scan QR
4. ✅ Statistik - Protect View + Export
5. ✅ Bank Soal - Protect CRUD
6. ✅ Project - Protect CRUD
7. ✅ Project Teams - Protect Manage + Assign

### Priority 4: Sekretaris Module

1. ✅ Surat - Protect CRUD + Download + Archive

---

## 🔧 Testing Accounts

Seeder sudah membuat test users:

| Email              | Password | Role             |
| ------------------ | -------- | ---------------- |
| admin@app.com      | password | super_admin      |
| chairman@app.com   | password | chairman         |
| pengajaran@app.com | password | admin_pengajaran |
| keuangan@app.com   | password | admin_keuangan   |
| media@app.com      | password | admin_media      |
| inventaris@app.com | password | admin_inventaris |
| sekretaris@app.com | password | admin_sekretaris |
| project@app.com    | password | admin_project    |
| anggota@app.com    | password | anggota          |

---

## 📚 Referensi

- **Spatie Laravel Permission Docs**: https://spatie.be/docs/laravel-permission
- **Main Status Doc**: `docs/RBAC_IMPLEMENTATION_STATUS.md`
- **Seeder**: `database/seeders/RolesAndPermissionsSeeder.php`
- **Routes**: `routes/web.php`

---

## 💡 Tips untuk Development

1. **Clear cache setelah update permission:**

    ```bash
    php artisan cache:forget spatie.permission.cache
    php artisan permission:cache-reset
    ```

2. **Reseed jika menambah permission baru:**

    ```bash
    php artisan db:seed --class=RolesAndPermissionsSeeder
    ```

3. **Check permission user:**

    ```php
    auth()->user()->getAllPermissions()->pluck('name');
    auth()->user()->can('anggaran.create');
    ```

4. **Test dengan berbagai role:**
    - Login dengan berbagai test account
    - Pastikan tombol muncul/hilang sesuai permission
    - Test route access (harus return 403 jika tidak punya permission)

---

**Last Updated**: February 12, 2026
**Status**: Ready for implementation of remaining modules
