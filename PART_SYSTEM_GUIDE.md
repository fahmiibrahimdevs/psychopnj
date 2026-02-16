# Part-Based Pertemuan System - Implementation Guide

## Overview

The system has been redesigned from **"1 pertemuan = 1 bank soal"** to **"1 pertemuan = multiple parts, each part = 1 bank soal + files"**.

## Architecture

### Database Structure

```
pertemuan (1) ──→ (many) part_pertemuan (1) ──→ (many) part_file
                                        │
                                        ├──→ (1) bank_soal_pertemuan ──→ (many) soal_pertemuan
                                        │
                                        └──→ (many) nilai_soal_anggota ──→ (many) soal_anggota
```

### Key Tables

- **part_pertemuan**: Core table storing parts with `urutan`, `nama_part`, `deskripsi`
- **part_file**: Files uploaded per part (PDFs, presentations, etc.)
- **bank_soal_pertemuan**: Bank soal now belongs to `id_part` (FK with cascade delete)
- **nilai_soal_anggota**: Exam results now track `id_part` instead of `id_pertemuan`

### File Storage Path

```
{tahun}/Dept. PRE/{program}/Pertemuan {ke}/Part {urutan}/filename.pdf
```

Example: `2024/Dept. PRE/Basic Programming/Pertemuan 1/Part 1/module.pdf`

## Admin Workflow

### 1. Create Pertemuan

- Navigate to **Akademik → Pertemuan**
- Click "Tambah Pertemuan"
- Fill: Program, Judul, Tanggal, Status, Thumbnail
- Save

### 2. Manage Parts

- Edit the pertemuan
- Go to **"Kelola Part"** tab
- Add parts:
    - **Nama Part**: e.g., "Teori Dasar", "Praktik", "Studi Kasus"
    - **Deskripsi**: Optional description
    - **Urutan**: Auto-assigned (1, 2, 3...)
- Upload files per part (PDFs, presentations, modules)

### 3. Create Bank Soal

- Click **"Kelola Bank Soal"** button on a part
- Configure question types and weights:
    - Pilihan Ganda (PG)
    - PG Kompleks
    - Menjodohkan
    - Isian Singkat
    - Esai
- Set how many questions to display per type
- Save bank soal

### 4. Add Questions

- After creating bank soal, add questions for each type
- Mark which questions should be displayed to anggota
- Use rich text editor for formatting

### 5. Monitor Exam Status

- **Akademik → Status Anggota Ujian**
- Select part from dropdown: "Pertemuan X - Part Y: name"
- View which anggota are:
    - ✓ Selesai (finished)
    - ⧗ Sedang Mengerjakan (in progress)
    - ✗ Belum Dimulai (not started)
- Actions:
    - **Paksa Selesai**: Force-finish an in-progress exam
    - **Ulangi Ujian**: Allow anggota to retake

### 6. View & Grade Results

- **Akademik → Hasil Ujian Pertemuan**
- Select part from dropdown
- View all anggota scores for that part
- Click **"Koreksi"** to manually grade essay questions
- Mark as corrected when done

## Anggota Workflow

### 1. Browse Pertemuans

- Navigate to **Anggota → Daftar Pertemuan**
- View pertemuans grouped by program
- Each pertemuan shows its parts

### 2. Access Part Materials

- Click **"Lihat File"** to view/download materials
- Files are organized by part

### 3. Take Exam

- Find the part you want to take
- Check status badge:
    - **Terkunci** 🔒: Not yet available
    - **Belum Dimulai** ⏰: Available but not started
    - **Dalam Proses** ⚠️: Currently taking exam
    - **Selesai** ✓: Exam completed
- Click **"Mulai Ujian"** button
- Confirm exam details
- Answer questions
- Submit exam

### 4. View Results

- Navigate to **Anggota → Hasil Soal**
- View scores grouped by program
- Each part shows:
    - Part number and name
    - Total score
    - Correction status

## Testing the System

### 1. Fresh Migration with Demo Data

```bash
php artisan migrate:fresh --seed
```

This will:

- Create all tables
- Seed roles, departments, tahun kepengurusan
- Seed anggota accounts
- **Create demo parts** for existing pertemuans
- **Create sample exam results** for testing

### 2. Demo Data Structure

The seeders create:

- **2-4 parts per pertemuan** with names like:
    - "Pengenalan Konsep", "Teori Dasar", "Fundamental"
    - "Praktik Dasar", "Implementasi", "Application"
    - "Studi Kasus", "Case Study", "Problem Solving"
    - "Advanced Topics", "Lanjutan", "Final Project"
- **1-3 files per part** (PDF placeholders)
- **Bank soal per part** with:
    - 5-15 PG questions
    - 2-5 PG Kompleks questions
    - 2-5 Menjodohkan questions
    - 3-8 Isian questions
    - 1-3 Esai questions
- **Sample exam results** with:
    - 80% finished exams
    - 20% in-progress exams
    - 60% corrected (for finished exams)

### 3. Test Accounts

Use existing anggota accounts created by `AnggotaSeeder`.

## Migration from Old System

### What Changed

**Before:**

```
pertemuan → bank_soal_pertemuan → soal_pertemuan
pertemuan → pertemuan_file
```

**After:**

```
pertemuan → part_pertemuan → bank_soal_pertemuan → soal_pertemuan
                           → part_file
```

### Breaking Changes

1. **Bank soal FK changed**: `id_pertemuan` → `id_part`
2. **Exam results FK changed**: `nilai_soal_anggota` now has `id_part`
3. **Unique constraint changed**: `[id_pertemuan, id_anggota]` → `[id_part, id_anggota]`
4. **Routes changed**:
    - Admin bank soal: `pertemuan/{pertemuanId}/soal` → `part/{partId}/soal`
    - Anggota exam: `konfirmasi/{pertemuanId}` → `konfirmasi/{partId}`
    - Koreksi: `koreksi/{id_pertemuan}/{id_anggota}` → `koreksi/{id_part}/{id_anggota}`

### Permissions

All permissions remain at pertemuan level:

- `pertemuan.edit`: Can manage pertemuan AND its parts
- `pertemuan.bank_soal`: Can manage bank soal for all parts
- `ujian.view`: Can view exam status for all parts
- `ujian.koreksi`: Can grade exams for all parts

## Features

### Part Management

- ✅ Create/Edit/Delete parts
- ✅ Reorder parts via drag-and-drop
- ✅ Upload multiple files per part
- ✅ Delete individual files
- ✅ Sequential urutan (1, 2, 3...)

### Bank Soal per Part

- ✅ Independent bank soal per part
- ✅ All question types supported
- ✅ Rich text editor
- ✅ Question visibility toggle

### Exam System per Part

- ✅ Anggota can take exams per part
- ✅ Status tracking per part
- ✅ Results stored per part
- ✅ Force finish per part
- ✅ Retake exam per part

### Admin Reporting

- ✅ Dropdown shows "Pertemuan X - Part Y: name"
- ✅ Filter by part
- ✅ View status per part
- ✅ Grade essays per part
- ✅ Mark as corrected per part

## Cascade Delete Behavior

When a **pertemuan** is deleted:

- All **part_pertemuan** records are deleted (cascade)
- All **part_file** records are deleted (cascade)
- All **bank_soal_pertemuan** records are deleted (cascade)
- All **soal_pertemuan** records are deleted (cascade)
- All **nilai_soal_anggota** records are deleted (cascade)
- All **soal_anggota** records are deleted (cascade)

When a **part** is deleted:

- All **part_file** records for that part are deleted (cascade)
- **bank_soal_pertemuan** for that part is deleted (cascade)
- All **soal_pertemuan** for that bank soal are deleted (cascade)
- All **nilai_soal_anggota** for that part are deleted (cascade)
- All **soal_anggota** for those nilai records are deleted (cascade)
- Remaining parts are reordered (urutan recalculated)

## Troubleshooting

### Issue: "No parts with bank soal found"

**Solution**: Run `PartPertemuanDemoSeeder` first, then `NilaiSoalAnggotaDemoSeeder`

### Issue: Parts not showing in dropdown

**Solution**: Ensure parts have bank soal created. Check `BankSoalPertemuan` has `id_part` FK.

### Issue: Exam button not appearing

**Solution**:

1. Check part has bank soal with `status = '1'`
2. Check bank soal has questions with `tampilkan = '1'`
3. Check pertemuan `status_open = '1'`

### Issue: Files not uploading

**Solution**:

1. Check storage permissions: `php artisan storage:link`
2. Verify `config/filesystems.php` public disk configuration
3. Check upload max file size in `php.ini`

## Performance Considerations

- Parts query uses `with()` eager loading to avoid N+1 queries
- Admin dropdowns load parts grouped by program
- Anggota view uses `groupBy('id_pertemuan')` to organize parts efficiently
- Cascade deletes handled at database level for performance

## Future Enhancements

- [ ] Bulk part creation from template
- [ ] Copy parts from other pertemuans
- [ ] Part prerequisites (e.g., Part 2 locked until Part 1 completed)
- [ ] Time limits per part
- [ ] Analytics dashboard per part

## Support

For issues or questions, contact the development team or check the main project documentation.
