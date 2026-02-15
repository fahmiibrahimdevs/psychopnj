<?php

namespace App\Imports;

use App\Models\OpenRecruitment;
use App\Models\Anggota;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OpenRecruitmentImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected $idTahun;

    public function __construct($idTahun)
    {
        $this->idTahun = $idTahun;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip jika nama lengkap kosong
        $namaLengkap = $row['nama_lengkap'] ?? $row['nama'] ?? null;
        if (empty($namaLengkap) || trim($namaLengkap) == '') {
            return null;
        }

        // Ambil email
        $email = $row['email_address'] ?? $row['email'] ?? null;
        if (empty($email) || trim($email) == '') {
            return null;
        }
        $email = trim(strtolower($email));

        // Cek apakah email sudah ada di open_recruitment untuk tahun ini
        $exists = DB::table('open_recruitment')
            ->where('id_tahun', $this->idTahun)
            ->where('email', $email)
            ->exists();

        // Skip jika email duplikat
        if ($exists) {
            return null;
        }

        // Ambil data lainnya
        $namaLengkap = trim($namaLengkap);
        $jurusanProdiKelas = trim($row['jurusan_dan_prodi'] ?? $row['jurusan_prodi_kelas'] ?? $row['jurusan'] ?? $row['prodi'] ?? '-');
        $noHp = trim($row['nomer_whatsapp'] ?? $row['no_hp'] ?? '');
        $alasan = trim($row['alasan_mengikuti_psychorobotic'] ?? $row['alasan'] ?? '');
        $tautanTwibbon = trim($row['tautan_bukti_unggah_twibbon'] ?? $row['tautan_twibbon'] ?? $row['twibbon'] ?? '');

        DB::beginTransaction();
        try {
            // 1. Buat/update user jika email belum ada
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                // Password menggunakan nomor HP, jika kosong gunakan default
                $password = !empty($noHp) ? $noHp : 'password123';
                
                $user = User::create([
                    'name' => $namaLengkap,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]);

                // Assign role anggota
                $user->assignRole('anggota');
            }

            // 2. Buat data anggota
            $anggota = Anggota::create([
                'id_tahun' => $this->idTahun,
                'id_user' => $user->id,
                'id_department' => 0,
                'nama_lengkap' => $namaLengkap,
                'nama_jabatan' => 'anggota',
                'email' => $email,
                'no_hp' => $noHp,
                'jurusan_prodi_kelas' => $jurusanProdiKelas,
                'status_anggota' => 'anggota',
                'status_aktif' => 'aktif',
                'foto' => null,
            ]);

            // 3. Buat data open recruitment dengan status lulus
            $openRecruitment = OpenRecruitment::create([
                'id_tahun' => $this->idTahun,
                'id_user' => $user->id,
                'id_anggota' => $anggota->id,
                'id_department' => 0,
                'jenis_oprec' => 'anggota',
                'nama_lengkap' => $namaLengkap,
                'email' => $email,
                'no_hp' => $noHp,
                'jurusan_prodi_kelas' => $jurusanProdiKelas,
                'nama_jabatan' => 'anggota',
                'alasan' => $alasan,
                'tautan_twibbon' => $tautanTwibbon,
                'status_seleksi' => 'lulus',
            ]);

            DB::commit();
            return $openRecruitment;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error importing open recruitment: ' . $e->getMessage());
            return null;
        }
    }
}
