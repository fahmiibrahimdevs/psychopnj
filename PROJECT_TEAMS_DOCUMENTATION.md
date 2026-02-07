# Dokumentasi Sistem Kelompok Project

## Overview

Sistem ini memungkinkan satu project untuk memiliki beberapa kelompok (teams), di mana setiap kelompok memiliki:

- 1 Leader
- Multiple Anggota (opsional)

**Contoh Use Case:**
Project: "Robot Line Follower"

- Kelompok 1 (Leader: Ahmad, Anggota: Budi, Citra)
- Kelompok 2 (Leader: Dewi, Anggota: Eko, Fitri)
- Kelompok 3 (Leader: Gita, Anggota: Hadi, Indah, Joko)

## Struktur Database

### 1. Table `projects` (existing)

Table utama untuk menyimpan informasi project/kegiatan.

```sql
- id
- id_tahun
- nama_project
- deskripsi
- status (enum: draft, berjalan, selesai, ditunda)
- tanggal_mulai
- tanggal_selesai
- thumbnail
- link_gdrive
- timestamps
```

### 2. Table `project_teams` (new)

Table untuk menyimpan kelompok-kelompok dalam sebuah project.

```sql
- id
- id_project (foreign key ke projects)
- nama_kelompok (e.g., "Kelompok 1", "Tim Alpha")
- deskripsi (nullable)
- timestamps
```

### 3. Table `project_team_members` (new)

Table pivot untuk menyimpan anggota kelompok dengan role mereka.

```sql
- id
- id_project_team (foreign key ke project_teams)
- id_anggota (foreign key ke anggota)
- role (enum: 'leader', 'anggota')
- timestamps
```

## Relasi Database

```
projects (1) --> (many) project_teams
project_teams (1) --> (many) project_team_members
project_team_members (many) --> (1) anggota
```

**Cascade Delete:**

- Jika project dihapus → semua teams dan members ikut terhapus
- Jika team dihapus → semua members di team tersebut ikut terhapus
- Jika anggota dihapus → record di project_team_members ikut terhapus

## Models & Relasi

### ProjectTeam Model

```php
class ProjectTeam extends Model
{
    public function project() // Belongs to Project
    public function members() // Has many ProjectTeamMember
    public function leader()  // Has one ProjectTeamMember where role = 'leader'
    public function anggotas() // Has many ProjectTeamMember where role = 'anggota'
}
```

### ProjectTeamMember Model

```php
class ProjectTeamMember extends Model
{
    public function team()    // Belongs to ProjectTeam
    public function anggota() // Belongs to Anggota
}
```

### Project Model (updated)

```php
class Project extends Model
{
    public function teams() // Has many ProjectTeam
}
```

## Routes

```php
// List semua project
Route::get('projects', \App\Livewire\Akademik\Project::class)->name('projects');

// Kelola kelompok dalam project tertentu
Route::get('project/{projectId}/teams', \App\Livewire\Akademik\ProjectTeams::class)
    ->name('project.teams');
```

## Fitur-Fitur

### 1. Halaman Projects List

- Tombol hijau dengan icon users untuk "Kelola Kelompok"
- Redirect ke halaman kelompok project

### 2. Halaman Kelompok (`/project/{id}/teams`)

**Features:**

- ✅ View semua kelompok dalam project
- ✅ Tambah kelompok baru
- ✅ Edit kelompok (nama, deskripsi, leader, anggota)
- ✅ Hapus kelompok
- ✅ Validasi: anggota tidak bisa terdaftar di 2 kelompok sekaligus dalam 1 project
- ✅ Leader otomatis ditampilkan dengan badge khusus
- ✅ Count jumlah anggota per kelompok

**View Cards:**

- Kelompok ditampilkan dalam grid responsive
- Setiap card menampilkan:
    - Nama kelompok
    - Deskripsi (jika ada)
    - Leader dengan avatar inisial
    - List anggota dengan avatar inisial
    - Tombol edit & delete

### 3. Modal Tambah/Edit Kelompok

**Form Fields:**

- Nama Kelompok (required)
- Deskripsi (optional)
- Leader (required, dropdown dengan Select2)
- Anggota (optional, multi-select dengan Select2)

**Validasi:**

- Nama kelompok tidak boleh kosong
- Leader harus dipilih
- Leader harus dari list anggota yang belum terdaftar di kelompok lain
- Anggota tidak boleh duplikat dalam project yang sama

## Business Logic

### Saat Tambah Kelompok:

1. Validasi input form
2. Buat record di `project_teams`
3. Insert leader ke `project_team_members` dengan role = 'leader'
4. Loop insert anggota ke `project_team_members` dengan role = 'anggota'
5. Refresh list kelompok

### Saat Edit Kelompok:

1. Load data kelompok existing
2. Tambahkan current members ke dropdown agar bisa dipilih kembali
3. Saat save:
    - Update nama & deskripsi kelompok
    - Hapus semua members existing
    - Insert ulang leader & anggota baru

### Saat Hapus Kelompok:

1. Konfirmasi dengan SweetAlert
2. Delete record `project_teams`
3. Cascade delete akan otomatis hapus semua `project_team_members`

### Load Available Anggota:

```php
// Get anggota yang BELUM terdaftar di kelompok lain (dalam project yang sama)
- Query semua project_team_members yang terkait dengan project ini
- Exclude anggota yang sudah terdaftar
- Return anggota yang available
```

**Special Case saat Edit:**

- Current members ditambahkan ke list available
- Sehingga bisa di-select kembali atau diganti

## Query Optimization

### Load Teams dengan Members:

```php
ProjectTeam::where('id_project', $projectId)
    ->with(['members.anggota'])
    ->orderBy('nama_kelompok')
    ->get();
```

### Load Available Anggota:

```php
$assignedAnggotaIds = ProjectTeamMember::whereHas('team', function($query) {
    $query->where('id_project', $this->projectId);
})->pluck('id_anggota')->toArray();

Anggota::where('id_tahun', $tahunId)
    ->where('status_aktif', 'aktif')
    ->whereNotIn('id', $assignedAnggotaIds)
    ->get();
```

## UI Components

### Technologies Used:

- **Livewire**: For reactive components
- **Alpine.js**: For frontend interactivity
- **Select2**: For searchable dropdowns
- **SweetAlert2**: For confirmations
- **TailwindCSS**: For styling
- **FontAwesome**: For icons

### Responsive Design:

- Mobile: 1 kolom
- Tablet: 2 kolom
- Desktop: 3 kolom

### Color Scheme:

- Leader badge: Blue (bg-blue-100, text-blue-600)
- Anggota avatar: Gray (bg-gray-100, text-gray-600)
- Action buttons:
    - Edit: Blue
    - Delete: Red
    - Add: Primary

## Security & Validation

### Backend Validation:

```php
protected $rules = [
    'nama_kelompok' => 'required|string|max:255',
    'deskripsi_kelompok' => 'nullable|string',
    'id_leader' => 'required|exists:anggota,id',
    'id_anggota' => 'array',
    'id_anggota.*' => 'exists:anggota,id',
];
```

### Authorization:

- Route protected dengan middleware `role:pengurus`
- Only pengurus can access project teams management

## Example Usage Flow

1. **User membuka halaman Projects**
    - Klik tombol hijau "Users" icon pada project "Robot Line Follower"

2. **Redirect ke halaman Kelompok**
    - URL: `/project/5/teams`
    - Menampilkan semua kelompok dalam project tersebut

3. **User klik "Tambah Kelompok"**
    - Modal muncul
    - Isi form:
        - Nama: "Kelompok 1"
        - Deskripsi: "Kelompok robot line follower dengan sensor infrared"
        - Leader: Ahmad
        - Anggota: Budi, Citra
    - Klik "Simpan"

4. **Sistem menyimpan data**
    - Insert ke `project_teams`
    - Insert Ahmad sebagai leader
    - Insert Budi & Citra sebagai anggota

5. **Card kelompok muncul**
    - Menampilkan info kelompok
    - Leader: Ahmad dengan badge khusus
    - Anggota: Budi, Citra

6. **User bisa edit/delete kelompok kapan saja**

## Migration Commands

Untuk membuat struktur database:

```bash
# Create migrations
php artisan make:migration create_project_teams_table
php artisan make:migration create_project_team_members_table

# Run migrations
php artisan migrate

# Or run specific migration
php artisan migrate --path=database/migrations/2026_02_04_155158_create_project_teams_table.php
```

## Troubleshooting

### Issue: Select2 tidak initialize

**Solution:** Pastikan event `initSelect2` di-dispatch:

```php
$this->dispatch('initSelect2');
```

### Issue: Anggota sudah terdaftar masih muncul di dropdown

**Solution:** Cek query `loadAvailableAnggotas()`, pastikan exclude logic benar

### Issue: Cascade delete tidak work

**Solution:** Cek foreign key constraint di migration:

```php
$table->foreign('id_project_team')
    ->references('id')->on('project_teams')
    ->onDelete('cascade');
```

## Future Enhancements

1. **Team Performance Tracking**
    - Tambah field progress (%)
    - Tambah field notes per kelompok

2. **Team Timeline**
    - Track aktivitas kelompok
    - Log perubahan member

3. **Team Resources**
    - Upload file per kelompok
    - Link dokumentasi per kelompok

4. **Team Communication**
    - Internal chat per kelompok
    - Announcement board

5. **Export Reports**
    - PDF report per kelompok
    - Excel export daftar kelompok

## Kesimpulan

Sistem kelompok project ini memberikan fleksibilitas untuk:

- ✅ Mengelola multiple kelompok dalam satu project
- ✅ Assign leader dan anggota secara terpisah
- ✅ Prevent duplikasi anggota dalam project yang sama
- ✅ UI yang clean dan responsive
- ✅ Validasi data yang ketat
- ✅ Cascade delete untuk data integrity

Struktur ini scalable dan bisa dikembangkan untuk fitur-fitur lebih advance di masa depan.
