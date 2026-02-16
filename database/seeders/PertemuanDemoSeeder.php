<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramKegiatan;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\DB;

class PertemuanDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Ambil program pembelajaran yang ada
            $programs = ProgramKegiatan::limit(3)->get();

            if ($programs->isEmpty()) {
                $this->command->warn('Tidak ada program pembelajaran. Membuat program demo...');
                
                // Buat program demo jika belum ada
                $tahun = \App\Models\TahunKepengurusan::first();
                if (!$tahun) {
                    $this->command->error('Tidak ada tahun kepengurusan. Silakan jalankan seeder tahun kepengurusan terlebih dahulu.');
                    return;
                }

                $program = ProgramKegiatan::create([
                    'id_tahun' => $tahun->id,
                    'nama_program' => 'Basic Programming',
                    'deskripsi' => 'Program pembelajaran dasar pemrograman',
                    'jenis_program' => 'internal',
                    'penyelenggara' => 'Dept. PRE',
                    'jumlah_pertemuan' => 5,
                    'status' => 'aktif',
                    'untuk_anggota' => true,
                ]);

                $programs = collect([$program]);
                $this->command->info('Program demo "Basic Programming" berhasil dibuat.');
            }

            $this->command->info('Membuat pertemuan demo untuk ' . $programs->count() . ' program...');

            foreach ($programs as $program) {
                // Buat 3-5 pertemuan per program
                $pertemuanCount = rand(3, 5);

                for ($i = 1; $i <= $pertemuanCount; $i++) {
                    $judulOptions = [
                        'Pengenalan dan Fundamental',
                        'Konsep Dasar dan Praktik',
                        'Implementasi dan Studi Kasus',
                        'Advanced Topics',
                        'Project dan Evaluasi'
                    ];

                    $tanggal = now()->subDays(30 - ($i * 7));

                    Pertemuan::create([
                        'id_program' => $program->id,
                        'nama_pemateri' => 'Instruktur ' . $i,
                        'pertemuan_ke' => $i,
                        'judul_pertemuan' => $judulOptions[$i - 1] ?? "Pertemuan {$i}",
                        'deskripsi' => "Materi pertemuan ke-{$i} untuk program {$program->nama_program}",
                        'tanggal' => $tanggal,
                        'minggu_ke' => ceil($i / 1),
                        'status' => 'visible',
                        'jenis_presensi' => json_encode(['manual']),
                    ]);

                    $this->command->info("  ✓ Pertemuan {$i}: {$judulOptions[$i - 1]} ({$tanggal->format('Y-m-d')})");
                }

                $this->command->info("  Total: {$pertemuanCount} pertemuan untuk '{$program->nama_program}'\n");
            }

            $this->command->info('✅ Pertemuan demo berhasil dibuat!');
        });
    }
}
