<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PartPertemuan;
use App\Models\Anggota;
use App\Models\NilaiSoalAnggota;
use App\Models\SoalAnggota;
use App\Models\SoalPertemuan;
use Illuminate\Support\Facades\DB;

class NilaiSoalAnggotaDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Get some parts that have bank soal
            $parts = PartPertemuan::has('bankSoal')
                ->with(['bankSoal', 'pertemuan.program'])
                ->limit(5)
                ->get();

            if ($parts->isEmpty()) {
                $this->command->error('No parts with bank soal found. Please run PartPertemuanDemoSeeder first.');
                return;
            }

            // Get some active anggota
            $anggotas = Anggota::where('status_anggota', 'anggota')
                ->limit(10)
                ->get();

            if ($anggotas->isEmpty()) {
                $this->command->error('No active anggota found. Please create anggota first.');
                return;
            }

            $this->command->info('Seeding exam results for parts...');

            foreach ($parts as $part) {
                $bankSoal = $part->bankSoal;
                
                // Only create for 50-70% of anggota (some haven't taken exam yet)
                $anggotaSubset = $anggotas->random(rand(5, 7));

                foreach ($anggotaSubset as $anggota) {
                    // Check if already exists
                    $exists = NilaiSoalAnggota::where('id_part', $part->id)
                        ->where('id_anggota', $anggota->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    // Random status: 80% finished, 20% in progress
                    $isFinished = rand(1, 10) <= 8;
                    
                    $nilai = NilaiSoalAnggota::create([
                        'tanggal' => now(),
                        'id_bank_soal' => $bankSoal->id,
                        'id_part' => $part->id,
                        'id_pertemuan' => $part->id_pertemuan,
                        'id_anggota' => $anggota->id,
                        'pg_benar' => $isFinished ? rand(5, 15) : 0,
                        'pg_salah' => $isFinished ? rand(0, 5) : 0,
                        'nilai_pg' => $isFinished ? rand(60, 100) : 0,
                        'nilai_pk' => $isFinished ? rand(60, 100) : 0,
                        'nilai_jo' => $isFinished ? rand(60, 100) : 0,
                        'nilai_is' => $isFinished ? rand(60, 100) : 0,
                        'nilai_es' => $isFinished ? rand(60, 100) : 0,
                        'status' => $isFinished ? '1' : '0', // 1=finished, 0=in progress
                        'dikoreksi' => $isFinished && rand(1, 10) <= 6 ? '1' : '0', // 60% corrected if finished
                        'mulai' => $isFinished ? now()->subHours(2) : now()->subMinutes(30),
                        'selesai' => $isFinished ? now()->subHours(1) : null,
                        'lama_ujian' => $isFinished ? '01:00:00' : null,
                    ]);

                    // Create SoalAnggota records for questions shown
                    $soals = SoalPertemuan::where('id_bank_soal', $bankSoal->id)
                        ->where('tampilkan', '1')
                        ->get();

                    foreach ($soals as $soal) {
                        $jawaban = null;
                        $benar = 0;

                        if ($isFinished) {
                            // Generate realistic answers
                            if ($soal->jenis == '1') { // PG
                                $jawaban = ['A', 'B', 'C', 'D'][array_rand(['A', 'B', 'C', 'D'])];
                                $benar = ($jawaban == $soal->jawaban) ? 1 : 0;
                            } elseif ($soal->jenis == '2') { // PG Kompleks
                                $jawaban = json_encode(['Pernyataan 1', 'Pernyataan 2']);
                                $benar = rand(0, 1);
                            } elseif ($soal->jenis == '4') { // Isian
                                $jawaban = rand(0, 1) ? $soal->jawaban : 'Jawaban salah';
                                $benar = ($jawaban == $soal->jawaban) ? 1 : 0;
                            } elseif ($soal->jenis == '5') { // Esai
                                $jawaban = 'Jawaban esai dari anggota untuk soal ini...';
                                $benar = rand(0, 1); // Will be manually graded
                            }
                        }

                        SoalAnggota::create([
                            'id_nilai_soal' => $nilai->id,
                            'id_soal' => $soal->id,
                            'jenis_soal' => $soal->jenis,
                            'no_soal_alias' => $soal->nomor_soal,
                            'jawaban_anggota' => $jawaban,
                            'jawaban_benar' => $soal->jawaban,
                            'nilai_otomatis' => $benar ? rand(5, 10) : 0,
                            'nilai_koreksi' => 0,
                        ]);
                    }

                    $statusText = $isFinished ? '✓ Finished' : '⧗ In Progress';
                    $this->command->info("  {$statusText}: {$anggota->nama_lengkap} - Part {$part->urutan} ({$part->nama_part})");
                }
            }

            $this->command->info("\n✅ Nilai seeder completed successfully!");
        });
    }
}
