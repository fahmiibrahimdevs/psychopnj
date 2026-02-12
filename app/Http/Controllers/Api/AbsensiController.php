<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Pertemuan;
use App\Models\PresensiPertemuan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Catat absensi menggunakan kartu RFID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'rfid_card' => 'required|string',
            'pertemuan_id' => 'nullable|exists:pertemuan,id', // Opsional, jika tidak ada akan otomatis mencari pertemuan aktif hari ini
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari anggota berdasarkan RFID
        $anggota = Anggota::where('rfid_card', $request->rfid_card)->first();

        if (!$anggota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu RFID tidak terdaftar dalam sistem.',
            ], 404);
        }

        // Tentukan pertemuan
        $pertemuanId = $request->pertemuan_id;
        $pertemuan = null;

        if (!$pertemuanId) {
            // Jika ID pertemuan tidak diberikan, cari pertemuan yang aktif (visible) dan tanggalnya hari ini
            $today = Carbon::today();
            $pertemuan = Pertemuan::where('status', 'visible')
                                ->whereDate('tanggal', $today)
                                ->first();
            
            if (!$pertemuan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada pertemuan aktif hari ini. Silakan tentukan ID pertemuan secara spesifik.',
                ], 404);
            }
            $pertemuanId = $pertemuan->id;
        } else {
            $pertemuan = Pertemuan::find($pertemuanId);
            
            // Validasi tambahan: Pastikan pertemuan statusnya visible/aktif? 
            // Opsional, tapi disarankan untuk keamanan agar tidak absen di pertemuan yang sudah ditutup/hidden
            if ($pertemuan->status !== 'visible') {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Pertemuan ini tidak aktif atau disembunyikan.',
                ], 403);
            }
        }

        // Cek apakah anggota sudah absen di pertemuan ini
        $existingPresensi = PresensiPertemuan::where('id_pertemuan', $pertemuanId)
                                            ->where('id_anggota', $anggota->id)
                                            ->first();

        if ($existingPresensi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota sudah melakukan absensi untuk pertemuan ini.',
                'data' => [
                    'anggota' => $anggota->nama_lengkap,
                    'waktu_absen_sebelumnya' => $existingPresensi->created_at->format('Y-m-d H:i:s'),
                ]
            ], 409); // Conflict status code
        }

        // Validasi jenis presensi
        // Pastikan status anggota (pengurus/anggota) diperbolehkan absen di pertemuan ini
        $allowedTypes = array_map('trim', explode(',', $pertemuan->jenis_presensi));
        if (!in_array($anggota->status_anggota, $allowedTypes)) {
             return response()->json([
                'status' => 'error',
                'message' => 'Absensi ditolak. Jenis pertemuan ini (' . $pertemuan->jenis_presensi . ') tidak mencakup status keanggotaan Anda (' . $anggota->status_anggota . ').',
            ], 403);
        }

        // Simpan data presensi
        try {
            $presensi = PresensiPertemuan::create([
                'id_pertemuan' => $pertemuanId,
                'id_anggota' => $anggota->id,
                'status' => 'hadir',
                'waktu' => now(),
                'metode' => 'rfid',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat via RFID.',
                'data' => [
                    'id_presensi' => $presensi->id,
                    'anggota' => $anggota->nama_lengkap,
                    'pertemuan' => $pertemuan->judul_pertemuan,
                    'waktu' => $presensi->waktu->format('Y-m-d H:i:s'),
                    'metode' => $presensi->metode,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data absensi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
