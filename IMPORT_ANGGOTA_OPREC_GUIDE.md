# Import Anggota Open Recruitment - Dokumentasi

## Fitur Import Excel untuk Open Recruitment

Fitur ini memungkinkan import data anggota secara massal melalui file Excel (.xlsx, .xls, .csv) di halaman Open Recruitment tab Anggota.

## Lokasi Fitur

- **Halaman**: Open Recruitment
- **Tab**: Anggota
- **Posisi**: Di bagian atas tabel, sebelum Show Entries

## Format File Excel

### Header Kolom yang Diperlukan:

Pastikan file Excel memiliki header (baris pertama) dengan kolom berikut:

| Kolom Header                                                       | Keterangan                                        | Required |
| ------------------------------------------------------------------ | ------------------------------------------------- | -------- |
| `Email Address` atau `email`                                       | Email mahasiswa (akan digunakan sebagai username) | **Ya**   |
| `Nama Lengkap` atau `nama`                                         | Nama lengkap mahasiswa                            | **Ya**   |
| `Jurusan dan Prodi` atau `jurusan_dan_prodi` atau `jurusan`        | Jurusan dan Prodi (contoh: TE/IKI/4B)             | **Ya**   |
| `Alasan Mengikuti Psychorobotic` atau `alasan`                     | Alasan bergabung dengan Psychorobotic             | Tidak    |
| `Nomer Whatsapp` atau `no_hp`                                      | Nomor HP/WhatsApp                                 | Tidak    |
| `Tautan Bukti Unggah Twibbon` atau `tautan_twibbon` atau `twibbon` | Link bukti twibbon peserta                        | Tidak    |

### Contoh Format Excel:

```
Email Address | Nama Lengkap | Alasan Mengikuti Psychorobotic | Jurusan dan Prodi | Tautan Bukti Unggah Twibbon | Nomer Whatsapp
------------- | ------------ | ------------------------------ | ----------------- | --------------------------- | --------------
john@example.com | John Doe | Saya tertarik robotika | TE/IKI/4B | https://twb.nz/xxx | 081234567890
jane@example.com | Jane Smith | Ingin mendalami AI | TM/TMJ/2B | https://twb.nz/yyy | 089876543210
```

## Perilaku Import

### 1. **Validasi Email**

- Jika email kosong → Data **dilewati** (skip)
- Jika email duplikat untuk tahun kepengurusan yang sama → Data **dilewati** (skip)
- **TIDAK ada error**, data duplikat langsung diabaikan

### 2. **Pembuatan Data Otomatis**

Ketika import berhasil, sistem akan membuat 3 data secara otomatis:

#### a. **User (Tabel users)**

- `name`: Nama lengkap
- `email`: Email dari Excel
- `password`: `password123` (default)
- `role`: `anggota`
- `email_verified_at`: Waktu saat ini (sudah terverifikasi)

#### b. **Anggota (Tabel anggota)**

- `id_tahun`: Tahun kepengurusan aktif
- `id_user`: ID user yang baru dibuat
- `id_department`: 0 (bukan pengurus)
- `nama_lengkap`: Dari Excel
- `nama_jabatan`: `anggota`
- `email`: Dari Excel
- `no_hp`: Dari Excel
- `jurusan_prodi_kelas`: Dari Excel
- `status_anggota`: `anggota`
- `status_aktif`: `aktif`

#### c. **Open Recruitment (Tabel open_recruitment)**

- `id_tahun`: Tahun kepengurusan aktif
- `id_user`: ID user yang baru dibuat
- `id_anggota`: ID anggota yang baru dibuat
- `id_department`: 0
- `jenis_oprec`: `anggota`
- `nama_lengkap`: Dari Excel
- `email`: Dari Excel
- `no_hp`: Dari Excel
- `jurusan_prodi_kelas`: Dari Excel
- `nama_jabatan`: `anggota`
- `alasan`: Dari Excel
- `tautan_twibbon`: Dari Excel
- `status_seleksi`: **`lulus`** (langsung lulus)

### 3. **Status Seleksi**

- Semua data yang diimport **LANGSUNG** memiliki status: **`lulus`**
- Tidak perlu seleksi manual lagi

### 4. **Role & Permission**

- Semua user yang diimport otomatis mendapat role: **`anggota`**
- Password default: **`password123`**

## Cara Menggunakan

1. Buka halaman **Open Recruitment**
2. Pilih tab **Anggota**
3. Klik tombol **Choose File** di section "Import Excel Anggota"
4. Pilih file Excel (.xlsx, .xls, atau .csv)
5. Klik tombol **Import**
6. Tunggu proses selesai
7. Akan muncul notifikasi sukses atau error

## Catatan Penting

⚠️ **Perhatian:**

- File maksimal **10MB**
- Format yang didukung: `.xlsx`, `.xls`, `.csv`
- Email yang duplikat akan **diabaikan** tanpa error
- Pastikan header kolom sesuai dengan format yang disebutkan di atas
- Semua data akan masuk ke tahun kepengurusan yang sedang **AKTIF**

## Troubleshooting

### Import Gagal?

1. Periksa format file (harus .xlsx, .xls, atau .csv)
2. Periksa ukuran file (maksimal 10MB)
3. Pastikan header kolom sesuai
4. Pastikan kolom email dan nama lengkap tidak kosong

### User Sudah Ada?

- Jika email sudah terdaftar sebagai user, sistem akan menggunakan user yang ada
- **Tidak akan membuat user duplikat**

### Email Duplikat di Open Recruitment?

- Data akan **dilewati** otomatis
- Tidak akan muncul error
- Hanya data baru yang akan diimport

## Migrasi Database

Jangan lupa jalankan migration setelah deployment:

```bash
php artisan migrate:fresh --seed
```

## Update Migration:

File migration yang diupdate:

- `database/migrations/2025_12_28_113127_create_open_recruitments_table.php`

Kolom baru yang ditambahkan:

- `email` (nullable)
- `no_hp` (nullable)
- `alasan` (text, nullable)
- `tautan_twibbon` (nullable)
