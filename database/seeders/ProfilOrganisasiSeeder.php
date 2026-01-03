<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfilOrganisasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProfilOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_tahun'            => '1',
                'headline'            => 'Nexa Persevera',
                'deskripsi'           => 'Nexa berarti Next Era, era baru bagi Psychorobotic yang menandai langkah maju menuju organisasi yang lebih terarah, adaptif, dan inovatif. Persevera melambangkan kegigihan, yaitu semangat untuk menjalankan setiap program, proses belajar, dan kegiatan kompetisi dengan konsistensi dan ketekunan.',
                'visi'                => 'Terwujudnya KSM Psychorobotic yang berdaya saing unggul, produktif, dan berdampak, serta siap berpartisipasi dalam berbagai kompetisi robotika melalui budaya kolaboratif, regenerasi berkelanjutan, dan kontribusi nyata bagi kemajuan kampus maupun masyarakat.',
                'misi'                => 'Mengembangkan kompetensi anggota di bidang robotika, pemrograman, dan teknologi pendukung melalui pelatihan dan kerjasama terstruktur serta kegiatan pembinaan internal. Memperluas jaringan dan kolaborasi strategis dengan komunitas, lembaga profesional, dan perusahaan industri di bidang teknologi dan robotika guna mendorong produktivitas organisasi melalui penguatan riset, pengembangan karya, serta pembangunan kerja sama yang berkelanjutan. Menyelenggarakan program regenerasi yang berkelanjutan guna melahirkan penerus yang kompeten, adaptif, dan mampu menjaga serta meningkatkan capaian organisasi. Mempersiapkan KSM Psychorobotic untuk meraih prestasi dalam berbagai kompetisi robotika melalui pembinaan yang terarah, ekosistem latihan yang sistematis, serta riset dan evaluasi berstandar kompetisi.',
                'foto'                => '',
                'tagline'             => 'Renew Through Innovation. Toward for Elevation. Psychorobotic, Action!',
            ]
        ];

        ProfilOrganisasi::insert($data);
    }
}
