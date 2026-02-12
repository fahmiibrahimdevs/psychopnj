<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\HistoryDoorLock;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DoorLockController extends Controller
{
    /**
     * Handle door lock access requests from IoT device.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'rfid_card' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'access_status' => 'DENIED', // Standard protocol response for IoT
                'message' => 'Input RFID Card wajib diisi.',
                'errors' => $validator->errors()
            ], 422);
        }

        $rfidCard = $request->rfid_card;

        // 2. Cek apakah kartu terdaftar
        $anggota = Anggota::where('rfid_card', $rfidCard)->first();

        // Skenario 1: Kartu Tidak Dikenal
        if (!$anggota) {
            $this->logHistory(null, $rfidCard, 'denied', 'Kartu Tidak Terdaftar');
            
            return response()->json([
                'status' => 'error',
                'access_status' => 'DENIED', 
                'message' => 'Akses Ditolak: Kartu tidak dikenali.'
            ], 404);
        }

        // Skenario 2: Anggota Non-Aktif
        if ($anggota->status_aktif !== 'aktif') {
            $this->logHistory($anggota->id, $rfidCard, 'denied', 'Anggota Status Non-Aktif');

            return response()->json([
                'status' => 'error',
                'access_status' => 'DENIED',
                'message' => 'Akses Ditolak: Status keanggotaan Anda non-aktif.'
            ], 403);
        }

        // Skenario 3: Akses Diterima (GRANTED)
        $this->logHistory($anggota->id, $rfidCard, 'granted', 'Akses Diberikan');

        return response()->json([
            'status' => 'success',
            'access_status' => 'GRANTED',
            'message' => 'Akses Diterima. Silakan masuk.',
            'data' => [
                'anggota' => $anggota->nama_lengkap,
                'jabatan' => $anggota->nama_jabatan,
                'waktu' => now()->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    /**
     * Private helper to log access history.
     */
    private function logHistory($anggotaId, $rfidCard, $status, $keterangan)
    {
        try {
            HistoryDoorLock::create([
                'id_anggota' => $anggotaId,
                'rfid_card' => $rfidCard,
                'status_akses' => $status, // 'granted' or 'denied'
                'keterangan' => $keterangan,
                'waktu_akses' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error silently, jangan gagalkan proses utama akses pintu hanya karena log gagal
            Log::error("Gagal menyimpan log door lock: " . $e->getMessage());
        }
    }
}
