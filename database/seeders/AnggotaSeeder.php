<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_user'             => '1',
                'id_tahun'            => '1',
                'id_department'           => '1',
                'nama_jabatan'        => 'Ketua',
                'nama_lengkap'        => 'Muhammad Syafiq Aziz',
                'kelas'               => 'IKI-3B',
                'jurusan'             => 'Teknik Elektro',
                'nim'                 => '2403321001',
                'no_hp'               => '6285712383394',
                'status_anggota'      => 'pengurus',
                'status_aktif'        => 'aktif',
                'foto'                => '',
                'motivasi'            => '-',
                'pengalaman'          => '-',
                'id_open_recruitment' => '1',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],

            [
                'id_user'             => '2',
                'id_tahun'            => '1',
                'id_department'           => '1',
                'nama_jabatan'        => 'Wakil Ketua',
                'nama_lengkap'        => 'Fahmi Ibrahim',
                'kelas'               => 'EC-3D',
                'jurusan'             => 'Teknik Elektro',
                'nim'                 => '2403321073',
                'no_hp'               => '6285691253593',
                'status_anggota'      => 'pengurus',
                'status_aktif'        => 'aktif',
                'foto'                => '',
                'motivasi'            => '-',
                'pengalaman'          => '-',
                'id_open_recruitment' => '2',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        Anggota::insert($data);
    }
}

