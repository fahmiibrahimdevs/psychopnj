<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pertemuan;
use App\Models\PartPertemuan;
use App\Models\PartFile;
use App\Models\BankSoalPertemuan;
use App\Models\SoalPertemuan;
use Illuminate\Support\Facades\DB;

class PartPertemuanDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Find or create a test pertemuan
            $pertemuans = Pertemuan::with('program')->orderBy('tanggal', 'DESC')->limit(3)->get();

            if ($pertemuans->isEmpty()) {
                $this->command->error('No pertemuan found. Please create at least one pertemuan first.');
                return;
            }

            $this->command->info('Seeding parts for ' . $pertemuans->count() . ' pertemuans...');

            foreach ($pertemuans as $pertemuan) {
                // Check if pertemuan already has parts
                if ($pertemuan->parts()->count() > 0) {
                    $this->command->warn("Pertemuan '{$pertemuan->judul_pertemuan}' already has parts. Skipping...");
                    continue;
                }

                $this->command->info("Creating parts for: {$pertemuan->judul_pertemuan}");

                // Create 2-4 parts per pertemuan
                $partCount = rand(2, 4);

                for ($i = 1; $i <= $partCount; $i++) {
                    $partNames = [
                        1 => ['Pengenalan Konsep', 'Teori Dasar', 'Fundamental', 'Introduction'],
                        2 => ['Praktik Dasar', 'Implementasi', 'Application', 'Hands-On'],
                        3 => ['Studi Kasus', 'Case Study', 'Problem Solving', 'Real World'],
                        4 => ['Advanced Topics', 'Lanjutan', 'Final Project', 'Wrap Up']
                    ];

                    $part = PartPertemuan::create([
                        'id_pertemuan' => $pertemuan->id,
                        'urutan' => $i,
                        'nama_part' => $partNames[$i][array_rand($partNames[$i])],
                        'deskripsi' => "Bagian {$i} dari materi {$pertemuan->judul_pertemuan}",
                    ]);

                    // Create 1-3 sample files per part
                    $fileCount = rand(1, 3);
                    for ($f = 1; $f <= $fileCount; $f++) {
                        $fileTypes = ['presentation', 'module', 'exercise'];
                        $fileType = $fileTypes[array_rand($fileTypes)];
                        
                        PartFile::create([
                            'id_part' => $part->id,
                            'file_path' => "demo/part_{$part->id}_{$fileType}_{$f}.pdf",
                            'original_name' => ucfirst($fileType) . "_{$i}_{$f}.pdf",
                            'ukuran_file' => rand(100000, 5000000),
                            'mime_type' => 'application/pdf',
                        ]);
                    }

                    // Create bank soal for this part
                    $bankSoal = BankSoalPertemuan::create([
                        'id_part' => $part->id,
                        'id_tahun' => $pertemuan->program->id_tahun,
                        'jml_pg' => rand(5, 15),
                        'jml_kompleks' => rand(2, 5),
                        'jml_jodohkan' => rand(2, 5),
                        'jml_isian' => rand(3, 8),
                        'jml_esai' => rand(1, 3),
                        'bobot_pg' => 30,
                        'bobot_kompleks' => 20,
                        'bobot_jodohkan' => 15,
                        'bobot_isian' => 20,
                        'bobot_esai' => 15,
                        'opsi' => '4',
                        'tampil_pg' => rand(5, 10),
                        'tampil_kompleks' => rand(2, 3),
                        'tampil_jodohkan' => rand(2, 3),
                        'tampil_isian' => rand(3, 5),
                        'tampil_esai' => rand(1, 2),
                        'status' => '1', // Active
                    ]);

                    // Create sample questions for PG
                    $pgCount = $bankSoal->jml_pg;
                    for ($q = 1; $q <= $pgCount; $q++) {
                        SoalPertemuan::create([
                            'id_bank_soal' => $bankSoal->id,
                            'jenis' => '1',
                            'nomor_soal' => $q,
                            'soal' => "<p>Soal Pilihan Ganda #{$q} untuk Part {$i}: Manakah pernyataan yang benar?</p>",
                            'opsi_a' => 'Opsi A - Jawaban pertama',
                            'opsi_b' => 'Opsi B - Jawaban kedua',
                            'opsi_c' => 'Opsi C - Jawaban ketiga',
                            'opsi_d' => 'Opsi D - Jawaban keempat',
                            'jawaban' => ['A', 'B', 'C', 'D'][array_rand(['A', 'B', 'C', 'D'])],
                            'tampilkan' => $q <= $bankSoal->tampil_pg ? '1' : '0',
                        ]);
                    }

                    // Create sample PG Kompleks
                    $kompleksCount = $bankSoal->jml_kompleks;
                    for ($q = 1; $q <= $kompleksCount; $q++) {
                        SoalPertemuan::create([
                            'id_bank_soal' => $bankSoal->id,
                            'jenis' => '2',
                            'nomor_soal' => $q,
                            'soal' => "<p>Soal PG Kompleks #{$q} untuk Part {$i}: Pilih semua pernyataan yang benar!</p>",
                            'opsi_a' => json_encode(['Pernyataan 1', 'Pernyataan 2', 'Pernyataan 3', 'Pernyataan 4']),
                            'jawaban' => json_encode(['Pernyataan 1', 'Pernyataan 3']),
                            'tampilkan' => $q <= $bankSoal->tampil_kompleks ? '1' : '0',
                        ]);
                    }

                    // Create sample Isian
                    $isianCount = $bankSoal->jml_isian;
                    for ($q = 1; $q <= $isianCount; $q++) {
                        SoalPertemuan::create([
                            'id_bank_soal' => $bankSoal->id,
                            'jenis' => '4',
                            'nomor_soal' => $q,
                            'soal' => "<p>Soal Isian #{$q} untuk Part {$i}: Apa nama ibukota Indonesia?</p>",
                            'jawaban' => 'Jakarta',
                            'tampilkan' => $q <= $bankSoal->tampil_isian ? '1' : '0',
                        ]);
                    }

                    // Create sample Esai
                    $esaiCount = $bankSoal->jml_esai;
                    for ($q = 1; $q <= $esaiCount; $q++) {
                        SoalPertemuan::create([
                            'id_bank_soal' => $bankSoal->id,
                            'jenis' => '5',
                            'nomor_soal' => $q,
                            'soal' => "<p>Soal Esai #{$q} untuk Part {$i}: Jelaskan dengan lengkap konsep yang telah dipelajari!</p>",
                            'jawaban' => '<p>Jawaban lengkap dari soal esai ini...</p>',
                            'tampilkan' => $q <= $bankSoal->tampil_esai ? '1' : '0',
                        ]);
                    }

                    $this->command->info("  ✓ Part {$i}: {$part->nama_part} ({$fileCount} files, {$pgCount} PG + {$kompleksCount} PK + {$isianCount} IS + {$esaiCount} ES)");
                }

                $this->command->info("  Total: {$partCount} parts created for '{$pertemuan->judul_pertemuan}'\n");
            }

            $this->command->info('✅ Part seeder completed successfully!');
        });
    }
}
