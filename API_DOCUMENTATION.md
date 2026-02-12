# Dokumentasi API Psychorobotic

Dokumentasi ini menjelaskan cara penggunaan API untuk sistem Absensi dan Door Lock pada aplikasi Psychorobotic.

## Base URL

Semua request harus diarahkan ke URL berikut:
`http://domain-anda.com/api`

## Headers

Setiap request wajib menyertakan header berikut:

- `Accept: application/json`
- `Content-Type: application/json`

---

## 1. API Absensi (Attendance)

Digunakan untuk mencatat kehadiran anggota dalam suatu pertemuan menggunakan kartu RFID.

**Endpoint:**
`POST /absensi`

### Parameter Body (JSON)

| Parameter      | Tipe    | Wajib? | Deskripsi                                                                                  |
| :------------- | :------ | :----- | :----------------------------------------------------------------------------------------- |
| `rfid_card`    | String  | Ya     | UID dari kartu RFID yang ditempelkan.                                                      |
| `pertemuan_id` | Integer | Tidak  | ID pertemuan spesifik. Jika kosong, sistem akan otomatis mencari pertemuan aktif hari ini. |

### Contoh Request

```json
{
    "rfid_card": "A1B2C3D4",
    "pertemuan_id": 12
}
```

### Response

#### 200 OK - Absensi Berhasil

```json
{
    "status": "success",
    "message": "Absensi berhasil dicatat via RFID.",
    "data": {
        "id_presensi": 105,
        "anggota": "Fahmi Ibrahim",
        "pertemuan": "Rapat Rutin Mingguan",
        "waktu": "2024-02-12 09:15:00",
        "metode": "rfid"
    }
}
```

#### 404 Not Found - Kartu Tidak Terdaftar / Pertemuan Tidak Ada

```json
{
    "status": "error",
    "message": "Kartu RFID tidak terdaftar dalam sistem."
}
```

_Atau_

```json
{
    "status": "error",
    "message": "Tidak ada pertemuan aktif hari ini. Silakan tentukan ID pertemuan secara spesifik."
}
```

#### 409 Conflict - Sudah Absen

```json
{
    "status": "error",
    "message": "Anggota sudah melakukan absensi untuk pertemuan ini.",
    "data": {
        "anggota": "Fahmi Ibrahim",
        "waktu_absen_sebelumnya": "2024-02-12 09:00:00"
    }
}
```

#### 403 Forbidden - Salah Jenis Pertemuan

Jika jenis pertemuan tidak sesuai dengan status anggota (misal: Rapat Pengurus tapi yang absen Anggota Biasa).

```json
{
    "status": "error",
    "message": "Absensi ditolak. Jenis pertemuan ini (Pengurus) tidak mencakup status keanggotaan Anda (Anggota)."
}
```

---

## 2. API Door Lock (Akses Pintu)

Digunakan oleh perangkat IoT untuk memverifikasi akses pintu berdasarkan kartu RFID.

**Endpoint:**
`POST /door-lock`

### Parameter Body (JSON)

| Parameter   | Tipe   | Wajib? | Deskripsi                             |
| :---------- | :----- | :----- | :------------------------------------ |
| `rfid_card` | String | Ya     | UID dari kartu RFID yang ditempelkan. |

### Contoh Request (cURL)

```bash
curl -X POST http://domain-anda.com/api/door-lock \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{"rfid_card": "A1B2C3D4"}'
```

### Response

Perangkat IoT disarankan membaca field `access_status` ("GRANTED" atau "DENIED") untuk logika pembukaan kunci.

#### 200 OK - Akses Diterima (GRANTED)

```json
{
    "status": "success",
    "access_status": "GRANTED",
    "message": "Akses Diterima. Silakan masuk.",
    "data": {
        "anggota": "Fahmi Ibrahim",
        "jabatan": "Ketua Umum",
        "waktu": "2024-02-12 10:00:00"
    }
}
```

#### 404 Not Found - Kartu Tidak Dikenal (DENIED)

```json
{
    "status": "error",
    "access_status": "DENIED",
    "message": "Akses Ditolak: Kartu tidak dikenali."
}
```

#### 403 Forbidden - Anggota Non-Aktif (DENIED)

```json
{
    "status": "error",
    "access_status": "DENIED",
    "message": "Akses Ditolak: Status keanggotaan Anda non-aktif."
}
```

#### 422 Unprocessable Entity - Input Salah

```json
{
    "status": "error",
    "access_status": "DENIED",
    "message": "Input RFID Card wajib diisi.",
    "errors": {
        "rfid_card": ["The rfid card field is required."]
    }
}
```
