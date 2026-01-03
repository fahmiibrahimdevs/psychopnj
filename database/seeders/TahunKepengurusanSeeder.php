<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TahunKepengurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TahunKepengurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_tahun'          => '2026/2027',
                'mulai'               => '2026',
                'akhir'               => '2027',
                'status'              => 'aktif',
                'deskripsi'           => 'Angkatan tahun 2026/2027',
            ],
        ];

        TahunKepengurusan::insert($data);
    }
}
