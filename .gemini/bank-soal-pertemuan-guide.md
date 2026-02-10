# Bank Soal Pertemuan - Replicating CBT UI

## Konteks

Fitur Bank Soal untuk modul Pertemuan yang mereplikasi UI/UX dari sistem CBT yang sudah ada. Bank Soal memungkinkan pembuatan soal untuk setiap pertemuan dengan 5 jenis soal yang berbeda.

## Status Implementasi

✅ **SELESAI** - Backend dan Frontend sudah fully functional

## Arsitektur

### Database Tables

1. **pertemuan** - Tambah field `has_bank_soal` (boolean)
2. **bank_soal_pertemuan** - Tabel konfigurasi bank soal per pertemuan
    - Menyimpan: jml_pg, jml_kompleks, jml_jodohkan, jml_isian, jml_esai
    - Menyimpan: bobot_pg, bobot_kompleks, bobot_jodohkan, bobot_isian, bobot_esai
    - Menyimpan: opsi (3/4/5 untuk pilihan ganda)
    - Menyimpan: tampil_pg, tampil_kompleks, tampil_jodohkan, tampil_isian, tampil_esai
3. **soal_pertemuan** - Tabel soal dengan semua jenisnya

### Models & Relationships

- **Pertemuan** → hasOne → BankSoalPertemuan
- **BankSoalPertemuan** → hasMany → SoalPertemuan
- **SoalPertemuan** model di-alias sebagai `SoalPertemuanModel` di Livewire component untuk menghindari naming conflict

### Routes

```php
Route::get('/pertemuan/{pertemuanId}/soal', SoalPertemuan::class)->name('pertemuan.soal');
```

## UI Components

### 1. Pertemuan Modal - Tab Bank Soal

**File:** `resources/views/livewire/akademik/pertemuan.blade.php`

Fitur:

- Checkbox untuk enable/disable bank soal
- Form konfigurasi yang muncul conditional
- Input jumlah soal untuk 5 jenis (PG, PG Kompleks, Jodohkan, Isian, Esai)
- Input bobot untuk semua jenis soal
- Dropdown opsi untuk PG (3/4/5 pilihan)

### 2. Pertemuan Card - Tombol Buat Soal

**File:** `resources/views/livewire/akademik/pertemuan.blade.php`

Fitur:

- Tombol "Buat Soal" dengan icon `fa-clipboard-question`
- Conditional display: hanya muncul jika `has_bank_soal == true`
- Link ke route `pertemuan.soal`

### 3. Halaman Kelola Bank Soal

**File:** `resources/views/livewire/akademik/bank-soal/soal-pertemuan.blade.php`

**Sumber:** Copied & adapted dari `/var/www/projects/laravel/cbt/resources/views/livewire/ujian/bank-soal/detail.blade.php` (781 lines)

**Struktur:**

1. **Header Section**
    - Informasi Pertemuan (Judul, Program, Pemateri, Tanggal)
    - Status badge (BANK SOAL SIAP DIGUNAKAN / PEMBUATAN SOAL MASIH KURANG)
    - Stats: Total Seharusnya / Dibuat / Ditampilkan

2. **Tabs Jenis Soal**
    - Button untuk 5 jenis soal
    - Badge menampilkan jumlah soal per jenis
    - Tabel stats per jenis (Seharusnya / Telah Dibuat / Ditampilkan / Bobot / Point Per-nomor)

3. **Daftar Soal (Card Columns)**
    - Toggle switch untuk tampilkan/sembunyikan soal
    - Display soal dengan opsi (khusus PG)
    - Display kunci jawaban
    - Tombol Edit & Delete

4. **Floating Action Button**
    - Tombol `+` di pojok kanan bawah
    - Selalu visible (tidak conditional)
    - Opens modal untuk tambah soal

5. **Modal Form (Add/Edit)**
    - Summernote editor dengan KaTeX support untuk soal
    - Dynamic form berdasarkan jenis soal:
        - **PG:** Summernote untuk setiap opsi A-E (sesuai config), dropdown jawaban
        - **PG Kompleks:** Dynamic opsi dengan checkbox multiple jawaban
        - **Jodohkan:** Linker-list.js integration
        - **Isian:** Input text untuk jawaban
        - **Esai:** Summernote untuk jawaban/pembahasan

## Livewire Components

### 1. Pertemuan Component

**File:** `app/Livewire/Akademik/Pertemuan.php`

**Properties tambahan:**

```php
public $has_bank_soal = false;
public $jml_pg, $jml_kompleks, $jml_jodohkan, $jml_isian, $jml_esai;
public $bobot_pg, $bobot_kompleks, $bobot_jodohkan, $bobot_isian, $bobot_esai;
public $opsi = 4;
```

**Methods diupdate:**

- `store()` - Create BankSoalPertemuan jika `has_bank_soal == true`
- `edit()` - Load bank soal config
- `update()` - Update bank soal config
- `delete()` - Cascade delete bank soal
- `redirectToSoal()` - Navigation ke halaman soal

### 2. SoalPertemuan Component

**File:** `app/Livewire/Akademik/BankSoal/SoalPertemuan.php`

**Properties:**

```php
public $pertemuanId;
public $pertemuan;
public $bankSoal;
public $selectedJenis = '1';
public $datas = [];
public $isEditing = false;
public $dataId;
// Soal fields
public $soal, $opsi_a, $opsi_b, $opsi_c, $opsi_d, $opsi_e;
public $jawaban;
// PG Kompleks
public $opsi_kompleks = [];
public $opsi_benar_kompleks = [];
// Jodohkan
public $jawaban_jodohkan;
// Stats
public $seharusnya, $total_dibuat, $ditampilkan;
public $seluruh_total_seharusnya, $seluruh_total_dibuat, $seluruh_total_ditampilkan;
```

**Key Methods:**

- `mount($pertemuanId)` - Load pertemuan & bank soal
- `jenis($jenis)` - Switch jenis soal & load data
- `store()` - Create soal baru dengan validasi per jenis
- `edit($id)` - Load soal untuk edit
- `update()` - Update soal
- `delete()` - Hapus soal
- `status($id, $active)` - Toggle tampilkan dengan validasi limit
- `isEditingMode($state)` - Toggle modal & dispatch Summernote init events

## Adaptasi dari CBT

### Yang Diubah:

1. **Variable Names:**
    - `$bank_soal` → `$bankSoal` (camelCase consistency)
    - Kode Bank Soal → Judul Pertemuan
    - Mata Pelajaran → Program Kegiatan
    - Guru → Pemateri
    - Kelas → Tanggal

2. **Removed Features:**
    - `nilai_count` checks (CBT punya sistem grading, pertemuan tidak)
    - Conditional edit/delete buttons based on exam status
    - `getTanggal()` / `getKelas()` methods

3. **Updated Logic:**
    - Floating button selalu visible (bukan conditional on empty)
    - Toggle validation: `>=` diubah jadi `>` (bisa enable tepat sesuai limit)
    - Alert message lebih user-friendly

### Yang Tetap Sama:

- Full UI structure (781 lines)
- Card-columns layout
- Toggle switch design
- Modal form structure
- Summernote + KaTeX + linker-list.js integration
- Button styles dan positioning
- Stats table layout

## Issues yang Sudah Diperbaiki

### 1. Variable Naming

**Error:** `Undefined variable $bank_soal`
**Fix:** Global search & replace `$bank_soal` → `$bankSoal`

### 2. Nilai Count

**Error:** `Undefined array key "nilai_count"`
**Fix:** Removed semua kondisi `@if (!$row['nilai_count'] > 0)` karena pertemuan tidak punya sistem grading

### 3. Orphaned @endif

**Error:** `syntax error, unexpected token "endif"`
**Fix:** Cleanup orphaned `@endif` statements dari removal nilai_count conditions

### 4. Toggle Validation Too Strict

**Problem:** User tidak bisa enable soal sampai limit (seharusnya 2, cuma bisa enable 1)
**Fix:** Changed `>=` to `>` in validation check (line 387)

### 5. Floating Button Hidden

**Problem:** Tombol tambah hilang setelah ada soal
**Fix:** Removed `@if ($empty)` condition, button selalu visible

### 6. Alert Message

**Problem:** Alert message kurang jelas
**Fix:** Updated message: "Batas Tercapai! Anda sudah menampilkan X soal. Maksimal yang diperbolehkan: Y soal. Silakan matikan salah satu soal terlebih dahulu."

## File Locations

### Backend

- `app/Models/BankSoalPertemuan.php`
- `app/Models/SoalPertemuan.php`
- `app/Livewire/Akademik/Pertemuan.php`
- `app/Livewire/Akademik/BankSoal/SoalPertemuan.php`

### Frontend

- `resources/views/livewire/akademik/pertemuan.blade.php`
- `resources/views/livewire/akademik/bank-soal/soal-pertemuan.blade.php`

### Migrations

- `database/migrations/2026_01_01_000002_create_pertemuan_table.php` (updated)
- `database/migrations/2026_01_01_000003_create_bank_soal_pertemuan_table.php`
- `database/migrations/2026_01_01_000004_create_soal_pertemuan_table.php`

### Routes

- `routes/web.php` - Added route `pertemuan.soal`

## Assets Dependencies

- Summernote Lite
- KaTeX
- Summernote-math.js
- linker-list.js (untuk jodohkan)
- ResizeSensor.js
- jQuery

## Workflow Pengguna

1. **Buat Pertemuan dengan Bank Soal:**
    - Buka modal Pertemuan
    - Pilih tab "Bank Soal"
    - Centang "Aktifkan Bank Soal"
    - Set jumlah & bobot untuk setiap jenis soal
    - Set opsi untuk PG (3/4/5)
    - Save

2. **Kelola Soal:**
    - Klik tombol "Buat Soal" di card pertemuan
    - Pilih jenis soal dari tabs
    - Klik tombol `+` untuk tambah soal
    - Isi soal dengan Summernote (support formula KaTeX)
    - Isi opsi (PG) atau jawaban (Isian/Esai)
    - Save

3. **Toggle Soal:**
    - Toggle switch ON/OFF untuk menampilkan/sembunyikan soal
    - Sistem validasi: tidak bisa enable lebih dari limit
    - Jika limit tercapai, matikan soal lain dulu

4. **Edit/Delete Soal:**
    - Klik tombol edit (biru) untuk edit
    - Klik tombol delete (merah) untuk hapus
    - Soal bisa diedit/dihapus kapan saja (tidak ada lock seperti di CBT)

## Catatan Implementasi

### PG Kompleks & Jodohkan

- UI sudah ada dan functional di CBT
- Butuh tambahan testing untuk pertemuan context
- Jodohkan perlu library `linker-list.js` yang sudah included

### Summernote Integration

- Dispatch events: `initSummernotePG`, `initSummernotePGK`, `initSummernoteJDH`, `initSummernoteIS`, `initSummernoteES`
- Auto-initialize saat modal dibuka
- Support image upload (existing route `/summernote/file/upload`)

### Validation

- Backend validation di `store()` dan `update()` methods
- Frontend validation via toggle switch
- Alert menggunakan SweetAlert2

## Next Steps (Jika Ada Enhancement)

1. **Auto-disable oldest question:** Saat user toggle ON soal baru dan limit tercapai, otomatis disable soal paling lama
2. **Bulk operations:** Select multiple soal untuk delete atau toggle
3. **Question preview:** Modal preview soal sebelum save
4. **Duplicate question:** Copy existing soal untuk jadi template
5. **Import/Export:** Import soal dari file atau export bank soal

## Command untuk Fresh Migration

```bash
php artisan migrate:fresh --seed
```

## Testing Checklist

- [x] Create pertemuan dengan bank soal enabled
- [x] Buat soal PG dengan berbagai opsi (3/4/5)
- [x] Test toggle soal sampai limit
- [x] Test edit soal existing
- [x] Test delete soal
- [x] Buat soal Isian
- [x] Buat soal Esai
- [ ] Test PG Kompleks (perlu testing lebih lanjut)
- [ ] Test Jodohkan (perlu testing lebih lanjut)
- [x] Verifikasi stats calculations
- [x] Test update bank soal config dari pertemuan
- [x] Test delete pertemuan (cascade delete bank soal)

---

**Tanggal Implementasi:** 7-8 Februari 2026
**Referensi CBT:** `/var/www/projects/laravel/cbt/resources/views/livewire/ujian/bank-soal/detail.blade.php`
