<?php

namespace Database\Seeders;

use App\Models\OpenRecruitment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpenRecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id'                  => 1,
                'id_tahun'            => '1',
                'jenis_oprec'         => 'pengurus',
                'nama_lengkap'        => 'Muhammad Syafiq Aziz',
                'kelas'               => 'IKI-3B',
                'jurusan'             => 'Teknik Elektro',
                'id_department'           => '1',
                'nama_jabatan'        => 'Ketua',
                'motivasi'            => '-',
                'pengalaman'          => '-',
                'status_seleksi'      => 'lulus',
                'id_anggota'          => '1',
                'id_user'             => '1',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'id'                  => 2,
                'id_tahun'            => '1',
                'jenis_oprec'         => 'pengurus',
                'nama_lengkap'        => 'Fahmi Ibrahim',
                'kelas'               => 'EC-3D',
                'jurusan'             => 'Teknik Elektro',
                'id_department'           => '1',
                'nama_jabatan'        => 'Wakil Ketua',
                'motivasi'            => '-',
                'pengalaman'          => '-',
                'status_seleksi'      => 'lulus',
                'id_anggota'          => '2',
                'id_user'             => '2',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        OpenRecruitment::insert($data);
    }
}
