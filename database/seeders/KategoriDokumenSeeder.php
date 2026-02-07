<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Sekretaris\KategoriDokumen;

class KategoriDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Proposal', 'slug' => 'proposal'],
            ['nama_kategori' => 'Notulensi', 'slug' => 'notulensi'],
            ['nama_kategori' => 'LPJ', 'slug' => 'lpj'],
            ['nama_kategori' => 'MoU / Kerjasama', 'slug' => 'mou'],
        ];

        foreach ($kategori as $cat) {
            KategoriDokumen::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
