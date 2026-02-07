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
                'nama_tahun'          => '2026',
                'status'              => 'aktif',
                'deskripsi'           => 'Angkatan tahun 2024',
            ],
        ];

        TahunKepengurusan::insert($data);
    }
}
