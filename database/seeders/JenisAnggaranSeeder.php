<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisAnggaran;

class JenisAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisAnggaran = [
            // Pemasukan
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Saldo Awal'],
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Iuran Kas'],
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Sponsor'],
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Dana Usaha'],
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Dana Direktorat'],
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Alumni'],
            ['nama_kategori' => 'pemasukan', 'nama_jenis' => 'Pemasukan Lainnya'],
            
            // Pengeluaran
            ['nama_kategori' => 'pengeluaran', 'nama_jenis' => 'Departemen'],
            ['nama_kategori' => 'pengeluaran', 'nama_jenis' => 'Project'],
            ['nama_kategori' => 'pengeluaran', 'nama_jenis' => 'Pengeluaran Lainnya'],
        ];

        foreach ($jenisAnggaran as $jenis) {
            JenisAnggaran::create($jenis);
        }
    }
}
