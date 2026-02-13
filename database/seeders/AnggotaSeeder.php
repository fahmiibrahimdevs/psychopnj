<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_lengkap' => 'Ryan',
                'nama_jabatan' => 'Anggota',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'nim' => '2503321073',
                'ttl' => 'Jakarta, 24 November 2005',
                'alamat' => '-',
                'email' => 'ryan.te25@stu.pnj.ac.id',
                'no_hp' => '08123456789',
                'id_department' => 0, // Assuming regular member has no department (0)
                'status_anggota' => 'anggota',
                'status_aktif' => 'aktif',
            ],
        ];

        foreach ($data as $anggota) {
            // Create user account
            $user = User::create([
                'name' => $anggota['nama_lengkap'],
                'email' => $anggota['email'],
                'password' => Hash::make('1'),
                'active' => '1',
            ]);

            // Assign role
            $user->assignRole('anggota');

            // Create anggota record
            Anggota::create([
                'id_user' => $user->id,
                'id_tahun' => '1', // Assuming active year is 1
                'id_department' => $anggota['id_department'],
                'nama_jabatan' => $anggota['nama_jabatan'],
                'nama_lengkap' => $anggota['nama_lengkap'],
                'jurusan_prodi_kelas' => $anggota['jurusan_prodi_kelas'],
                'nim' => $anggota['nim'],
                'ttl' => $anggota['ttl'],
                'alamat' => $anggota['alamat'],
                'email' => $anggota['email'],
                'no_hp' => $anggota['no_hp'],
                'status_anggota' => $anggota['status_anggota'],
                'status_aktif' => $anggota['status_aktif'],
                'foto' => '',
            ]);
        }
    }
}
