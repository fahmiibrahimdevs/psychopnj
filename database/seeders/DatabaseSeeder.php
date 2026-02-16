<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RolesAndPermissionsSeeder::class,
            // AdminSeeder::class,
            TahunKepengurusanSeeder::class,
            DepartmentSeeder::class,
            OpenRecruitmentSeeder::class,
            PengurusSeeder::class,
            AnggotaSeeder::class,
            ProfilOrganisasiSeeder::class,
            JenisAnggaranSeeder::class,
            KategoriDokumenSeeder::class,
            // Part & Exam Demo Seeders (uncomment if needed for testing)
            // PertemuanDemoSeeder::class,
            // PartPertemuanDemoSeeder::class,
            // NilaiSoalAnggotaDemoSeeder::class,
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
