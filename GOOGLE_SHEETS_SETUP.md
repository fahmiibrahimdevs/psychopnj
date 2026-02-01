# Setup Google Sheets Sync untuk Barang

## Langkah-langkah Setup:

### 1. Buat Google Spreadsheet

1. Buka [Google Sheets](https://sheets.google.com)
2. Buat spreadsheet baru dengan nama: **Data Barang Psychorobotic**
3. Buat sheet dengan nama: **Barang**
4. Buat header di baris pertama (A1-K1):
    ```
    ID | Kode | Nama Barang | Kategori | Jenis | Jumlah | Satuan | Tersedia | Kondisi | Lokasi | Keterangan
    ```

### 2. Share Spreadsheet dengan Service Account

1. Copy email service account dari file `credentials-sheets.json`:

    ```
    bendaharasheet1@bendaharasheet1.iam.gserviceaccount.com
    ```

2. Di Google Sheets, klik tombol **Share**

3. Paste email service account tersebut

4. Berikan akses **Editor**

5. Klik **Send**

### 3. Dapatkan Spreadsheet ID

1. Lihat URL Google Sheets Anda:

    ```
    https://docs.google.com/spreadsheets/d/SPREADSHEET_ID_DISINI/edit
    ```

2. Copy bagian `SPREADSHEET_ID_DISINI`

3. Contoh URL lengkap:

    ```
    https://docs.google.com/spreadsheets/d/1abc123def456ghi789jkl012mno345pqr678stu/edit
    ```

    Spreadsheet ID adalah: `1abc123def456ghi789jkl012mno345pqr678stu`

### 4. Update File .env

Edit file `.env` dan update baris ini:

```env
GOOGLE_SHEETS_BARANG_ID=paste_spreadsheet_id_anda_disini
```

Contoh:

```env
GOOGLE_SHEETS_BARANG_ID=1abc123def456ghi789jkl012mno345pqr678stu
```

### 5. Clear Cache Laravel

Jalankan command:

```bash
php artisan config:clear
php artisan cache:clear
```

## Cara Kerja

Setiap kali Anda melakukan operasi CRUD di halaman Barang:

- **CREATE**: Data baru akan otomatis ditambahkan ke Google Sheets
- **UPDATE**: Data di Google Sheets akan diupdate sesuai kode barang
- **DELETE**: Baris data di Google Sheets akan dihapus

## Format Data di Google Sheets

| Kolom       | Contoh Data       |
| ----------- | ----------------- |
| ID          | 1                 |
| Kode        | BRG-20260201-0001 |
| Nama Barang | Laptop Dell       |
| Kategori    | Elektronik        |
| Jenis       | Inventaris        |
| Jumlah      | 5                 |
| Satuan      | unit              |
| Tersedia    | 5                 |
| Kondisi     | Baik              |
| Lokasi      | Rak A             |
| Keterangan  | Untuk kegiatan    |

## Troubleshooting

### Error: "The caller does not have permission"

**Solusi**: Pastikan email service account sudah di-share dengan akses Editor di Google Sheets.

### Error: "Requested entity was not found"

**Solusi**:

- Cek GOOGLE_SHEETS_BARANG_ID di file .env sudah benar
- Pastikan sheet dengan nama "Barang" sudah dibuat

### Error: "Unable to parse range"

**Solusi**: Pastikan nama sheet adalah **Barang** (huruf B besar)

### Data tidak muncul di Google Sheets

**Solusi**:

1. Cek log Laravel: `storage/logs/laravel.log`
2. Pastikan koneksi internet aktif
3. Refresh halaman Google Sheets

## Testing

Untuk testing sync:

1. Buat barang baru di aplikasi
2. Buka Google Sheets
3. Refresh halaman
4. Data barang baru seharusnya muncul di baris terakhir

## Notes

- Sync berjalan real-time saat operasi CRUD
- Jika terjadi error sync, data tetap tersimpan di database Laravel
- Error sync hanya dicatat di log, tidak mengganggu aplikasi
- Google Sheets hanya sebagai backup/monitoring, bukan sebagai database utama
