# Import Data Barang - Panduan

## Format File Import

File Excel/CSV harus memiliki kolom dengan urutan dan nama berikut:

| Kolom           | Wajib?   | Deskripsi                                           | Contoh                    |
| --------------- | -------- | --------------------------------------------------- | ------------------------- |
| **Nama_Barang** | ✅ Ya    | Nama barang                                         | Socket XT60 Yellow Male   |
| **Kategori**    | ❌ Tidak | Nama kategori (akan dibuat otomatis jika belum ada) | KONEKTOR                  |
| **Jenis**       | ❌ Tidak | Inventaris atau Bahan Habis Pakai                   | Inventaris                |
| **Jumlah**      | ❌ Tidak | Jumlah stok (default: 0)                            | 20                        |
| **Satuan**      | ❌ Tidak | Satuan barang (default: pcs)                        | pcs                       |
| **Kondisi**     | ❌ Tidak | Baik, Rusak Ringan, atau Rusak Berat                | Baik                      |
| **Lokasi**      | ❌ Tidak | Lokasi penyimpanan                                  | Rak A                     |
| **Keterangan**  | ❌ Tidak | Catatan tambahan                                    | Untuk robot line follower |

## Contoh Template

Download template dengan klik tombol **"Template"** di halaman Barang.

Template sudah berisi contoh data:

```
Nama_Barang                | Kategori  | Jenis              | Jumlah | Satuan | Kondisi | Lokasi | Keterangan
---------------------------|-----------|--------------------|---------|---------|---------|---------|-----------
Socket XT60 Yellow Male    | KONEKTOR  | Bahan Habis Pakai | 20      | pcs    | Baik    | Rak A  |
Module TCRT5000           | INPUT     | Bahan Habis Pakai | 10      | pcs    | Baik    | Rak A  |
Servo HiTec HS-5625MG     | OUTPUT    | Inventaris        | 5       | pcs    | Baik    | Rak A  | Untuk robot
```

## Cara Import

1. **Download Template**
    - Klik tombol **"Template"** untuk download file Excel template
    - File akan terdownload dengan nama `Template_Import_Barang.xlsx`

2. **Isi Data**
    - Buka file Excel/CSV
    - **JANGAN hapus baris header (baris pertama)**
    - Isi data mulai dari baris kedua
    - Anda bisa menghapus contoh data dan isi dengan data Anda

3. **Upload & Import**
    - Klik tombol **"Import"**
    - Pilih file Excel/CSV yang sudah diisi
    - Klik **"Import"**
    - Tunggu proses selesai

4. **Auto Sync ke Google Sheets**
    - Setelah import berhasil, semua data otomatis ter-sync ke Google Sheets
    - Cek Google Sheets untuk melihat hasilnya

## Fitur Auto-Create Kategori

Jika kategori yang Anda masukkan **belum ada** di database:

- ✅ Sistem akan **otomatis membuat kategori baru**
- ✅ Data barang akan langsung terhubung ke kategori tersebut
- ✅ Tidak perlu create kategori manual terlebih dahulu

Contoh:

```
Input: Kategori = "SENSOR ULTRASONIK"
Hasil: Kategori baru "SENSOR ULTRASONIK" otomatis dibuat
```

## Validasi Data

### Field Wajib

- ✅ **Nama_Barang** - harus diisi

### Field Optional

Jika tidak diisi, akan menggunakan nilai default:

- Kategori: `-` (tanpa kategori)
- Jenis: `Inventaris`
- Jumlah: `0`
- Satuan: `pcs`
- Kondisi: `Baik`
- Lokasi: kosong
- Keterangan: kosong

## Format Enum

### Jenis

Sistem akan auto-detect dari text yang Anda masukkan:

- **"Inventaris"** → `inventaris`
- **"Bahan Habis Pakai"** / **"Habis Pakai"** / **"Consumable"** → `habis_pakai`

### Kondisi

Sistem akan auto-detect dari text yang Anda masukkan:

- **"Baik"** → `baik`
- **"Rusak Ringan"** → `rusak_ringan`
- **"Rusak Berat"** / **"Rusak Parah"** → `rusak_berat`

## Error Handling

Jika ada error saat import:

- ❌ Baris yang error akan ditampilkan dengan detail error
- ✅ Baris yang valid tetap akan tersimpan
- 📝 Cek pesan error untuk mengetahui baris dan kolom yang bermasalah

Contoh error:

```
Baris 5: Jumlah harus berupa angka
Baris 8: Nama barang wajib diisi
```

## Tips

1. **Pastikan kolom header sama persis** dengan template
2. **Gunakan format Excel** (.xlsx) untuk hasil terbaik
3. **Cek kategori** yang sudah ada di sistem untuk konsistensi penamaan
4. **Backup data** sebelum import dalam jumlah besar
5. **Test dengan sedikit data** terlebih dahulu (5-10 baris)

## Troubleshooting

### Import gagal - "Column not found"

**Solusi**: Pastikan nama kolom header **sama persis** dengan template (termasuk huruf besar/kecil dan underscore)

### Kategori tidak muncul

**Solusi**: Refresh halaman, kategori baru akan muncul di dropdown filter

### Data tidak sync ke Google Sheets

**Solusi**:

1. Cek konfigurasi `GOOGLE_SHEETS_BARANG_ID` di `.env`
2. Lihat log error di `storage/logs/laravel.log`
3. Pastikan Google Sheets sudah di-share dengan service account

### File upload error

**Solusi**: Pastikan file tidak lebih dari 2MB dan format Excel/CSV
