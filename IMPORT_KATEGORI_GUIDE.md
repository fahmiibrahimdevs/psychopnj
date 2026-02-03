# Panduan Import Kategori Barang dari Excel

## Langkah-langkah Import

### 1. Download Template Excel

- Buka halaman **Kategori Barang**
- Klik tombol **"Download Template"** (hijau dengan icon Excel)
- File `Template_Import_Kategori_Barang.xlsx` akan didownload

### 2. Isi Data pada Template

Template memiliki 2 kolom:

| ID  | Nama Kategori |
| --- | ------------- |
|     | Elektronik    |
|     | Tools         |
|     | ATK           |

#### Aturan Pengisian:

**Kolom ID:**

- **KOSONGKAN kolom ini**
- ID akan dibuat otomatis oleh sistem

**Kolom Nama Kategori:**

- **WAJIB diisi**
- Contoh: Elektronik, Tools, ATK, Furniture, Kendaraan, dll
- Kategori yang sudah ada akan **dilewati** (tidak ada duplikasi)

### 3. Upload File Excel

- Klik tombol **"Import Excel"** (biru dengan icon import)
- Pilih file Excel yang sudah diisi
- Klik **"Import"**

### 4. Proses Import

- Sistem akan memvalidasi data
- Kategori baru akan ditambahkan
- Kategori duplikat akan dilewati
- Data akan otomatis sync ke Google Sheets (sheet "Kategori Barang")

## Format File yang Didukung

- `.xlsx` (Excel 2007+)
- `.xls` (Excel 97-2003)
- `.csv` (Comma Separated Values)

## Contoh Data Import

```
ID | Nama Kategori
---|---------------
   | Elektronik
   | Tools
   | Alat Tulis Kantor
   | Furniture
   | Kendaraan
   | Alat Kesehatan
   | Peralatan Dapur
```

## Error Handling

### Error: "Nama kategori harus diisi"

- Pastikan kolom **Nama Kategori** tidak kosong pada setiap baris

### Error: "File format tidak valid"

- Pastikan file berformat `.xlsx`, `.xls`, atau `.csv`
- Pastikan struktur kolom sesuai template (2 kolom)

### Kategori Duplikat

- Sistem akan **otomatis melewati** kategori yang sudah ada
- Tidak ada error, hanya notifikasi info

## Integrasi Google Sheets

Setelah import berhasil:

1. Data akan otomatis sync ke Google Sheets
2. Sheet: **"Kategori Barang"**
3. Format di Google Sheets:

| ID  | Nama Kategori |
| --- | ------------- |
| 1   | Elektronik    |
| 2   | Tools         |
| 3   | ATK           |

## Tips Import

1. **Persiapan Data:**
    - Rapikan data di Excel sebelum import
    - Hapus baris kosong
    - Pastikan tidak ada spasi berlebih

2. **Import Bertahap:**
    - Untuk data banyak, import bertahap (50-100 baris)
    - Cek hasil import sebelum lanjut

3. **Validasi:**
    - Setelah import, cek di halaman Kategori Barang
    - Pastikan jumlah kategori sesuai
    - Cek juga di Google Sheets

4. **Backup:**
    - Export Excel terlebih dahulu sebelum import data baru
    - Gunakan sebagai backup

## Export untuk Backup

Sebelum import data baru, disarankan untuk:

1. Klik **"Export Excel"** untuk backup data existing
2. Simpan file backup dengan nama jelas (contoh: `backup_kategori_20250103.xlsx`)
3. Lakukan import data baru

## FAQ

**Q: Apakah import akan menghapus data lama?**
A: Tidak, import hanya menambahkan data baru.

**Q: Bagaimana jika nama kategori sudah ada?**
A: Sistem akan melewati kategori tersebut (tidak duplikat).

**Q: Apakah bisa import ribuan data sekaligus?**
A: Bisa, tapi disarankan import bertahap untuk performa optimal.

**Q: Apakah harus sync Google Sheets manual setelah import?**
A: Tidak, sistem otomatis sync ke Google Sheets setelah import berhasil.

**Q: Format tanggal apa yang digunakan?**
A: Tidak ada kolom tanggal di Kategori Barang.

## Troubleshooting

### Import tidak berhasil

1. Cek format file (harus .xlsx, .xls, atau .csv)
2. Cek struktur kolom sesuai template
3. Cek ukuran file (maksimal 2MB)
4. Cek error message untuk detail

### Data tidak muncul setelah import

1. Refresh halaman (F5)
2. Cek filter/search yang aktif
3. Cek pagination (mungkin di halaman lain)

### Google Sheets tidak sync

1. Cek koneksi internet
2. Cek credentials Google Sheets
3. Cek file .env: `GOOGLE_SHEETS_BARANG_ID`
4. Cek permission service account di Google Sheets
5. Lihat log error di `storage/logs/laravel.log`

## Kontak Support

Jika mengalami kendala:

1. Cek dokumentasi: `GOOGLE_SHEETS_SETUP.md`
2. Cek log error: `storage/logs/laravel.log`
3. Hubungi admin sistem
