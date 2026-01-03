<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Ketua Umum & Wakil Ketua Umum',
                'kategori'            => 'Pimpinan Tertinggi Organisasi',
                'deskripsi'           => 'Menjadi perwakilan lembaga ketika keberadaan dari Ketua Umum sedang berhalangan Menjaga dan menilai ketercapaian dari Visi Misi lembaga bersama Ketua, Berkoordinasi dengan Chairman perihal pembagian penjagaan divisi di KSM Psychorobotic, Memantau timeline Psychorobotic 2026 bersama Ketua dan divisi Kestari',
                'ikon'                => 'fas fa-crown',
                'urutan'              => '1',
                'status'              => 'aktif',
            ],

            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Sekretaris',
                'kategori'            => 'Administrasi & Dokumentasi',
                'deskripsi'           => 'Berkoordinasi dengan masing-masing divisi perihal timeline Sebagai pusat dari segala jenis arsip dan berkas dari program kerja dan kegiatan, Mengumpulkan notulensi dan presensi kehadiran baik rapat maupun pertemuan, Mencerdaskan divisi lain perihal SOP dan hal administratif, Mengembangkan SOP Kesekretariatan KSM Psychorobotic',
                'ikon'                => 'fas fa-file-alt',
                'urutan'              => '2',
                'status'              => 'aktif',
            ],

            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Bendahara Umum',
                'kategori'            => 'Pengelolaan Keuangan',
                'deskripsi'           => 'Bertanggung jawab atas uang masuk dan keluar kepengurusan serta Transparansi dana, Memberikan pencerdasan kepada seluruh divisi mengenai berkas berkas keuangan yang diperlukan, Memantau dan mengatur cashflow KSM Psychorobotic agar sesuai dengan AD/ART, Menerima dan memberikan dana turunan kepada kepengurusan sebelum atau selanjutnya.',
                'ikon'                => 'fas fa-wallet',
                'urutan'              => '3',
                'status'              => 'aktif',
            ],

            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Kominfo',
                'kategori'            => 'Konten & Publikasi',
                'deskripsi'           => 'Menjaga kelancaran penyebaran informasi dari internal dan eksternal kepada mahasiswa PNJ melalui kanal komunikasi online maupun offline. Memelihara hubungan dan koordinasi dengan seluruh stakeholder KSM Psychorobotic untuk memastikan keterhubungan informasi yang relevan dan akurat. Menyusun dan menerapkan SOP publikasi untuk kebutuhan internal maupun eksternal sebagai standar komunikasi organisasi. Bertanggung jawab atas pengelolaan seluruh media sosial resmi KSM Psychorobotic, termasuk perencanaan konten, publikasi, dan pemeliharaan citra organisasi. Mengelola dan mengarsipkan dokumentasi digital organisasi agar informasi tersimpan rapi, aman, dan mudah diakses.',
                'ikon'                => 'fas fa-camera',
                'urutan'              => '4',
                'status'              => 'aktif',
            ],
            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Pengajaran, Riset, dan Eksplorasi (PRE)',
                'kategori'            => 'Pelatihan & Pembinaan',
                'deskripsi'           => 'Mengelola perencanaan, pelaksanaan, dan evaluasi seluruh program pengembangan kompetensi anggota, baik pada aspek dasar maupun lanjutan di bidang robotika dan teknologi terkait.Mengelola perencanaan, pelaksanaan, dan evaluasi seluruh program pengembangan kompetensi anggota, baik pada aspek dasar maupun lanjutan di bidang robotika dan teknologi terkait. Menyusun kurikulum internal sebagai standar pembelajaran yang berkesinambungan untuk mendukung regenerasi dan peningkatan kualitas anggota. Mengembangkan modul, panduan teknis, serta materi ajar yang terstruktur untuk digunakan dalam pelatihan, workshop, dan pendampingan kompetisi. Melaksanakan kegiatan pengajaran terstruktur yang mencakup materi dasar hingga lanjutan, melalui kelas rutin, workshop, dan sesi pendampingan untuk memastikan transfer pengetahuan berjalan konsisten dan merata di seluruh anggota.',
                'ikon'                => 'fas fa-chalkboard-teacher',
                'urutan'              => '5',
                'status'              => 'aktif',
            ],
            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Perlengkapan',
                'kategori'            => 'Inventaris & Fasilitas',
                'deskripsi'           => 'Mengelola seluruh aset, peralatan, dan inventaris KSM Psychorobotic melalui proses pendataan, pengecekan rutin, perawatan, dan pembaruan kebutuhan logistik organisasi. Menyusun serta menerapkan standar operasional peminjaman, penggunaan, dan pengembalian peralatan untuk memastikan ketersediaan dan keamanan seluruh aset. Menjamin kesiapan fasilitas dan utilitas ruang sekretariat, termasuk kebersihan, tata ruang, kelistrikan, dan kebutuhan teknis lainnya agar kegiatan organisasi dapat berjalan optimal. Berkoordinasi dengan divisi lain untuk memastikan kebutuhan sumber daya, peralatan, dan fasilitas terpenuhi sesuai standar keselamatan dan efisiensi. Melakukan monitoring kondisi peralatan secara berkala dan menyusun rekomendasi pengadaan, perbaikan, atau penggantian aset berdasarkan tingkat kebutuhan dan umur pakai.',
                'ikon'                => 'fas fa-inventory',
                'urutan'              => '6',
                'status'              => 'aktif',
            ],
            [
                'id_tahun'            => '1',
                'nama_divisi'         => 'Polybot',
                'kategori'            => 'Prestasi & Kompetisi',
                'deskripsi'           => 'Mengelola proses pembentukan, pembinaan, dan pengembangan tim kompetisi KSM Psychorobotic untuk memastikan kesiapan teknis maupun nonteknis dalam seluruh agenda lomba. Menyusun rencana pelatihan, pendampingan teknis, serta timeline persiapan kompetisi yang terstruktur, mulai dari desain, riset, perakitan, hingga pengujian robot. Mengkoordinasikan kebutuhan logistik tim, termasuk peralatan, komponen, ruang kerja, dokumentasi perkembangan, dan dukungan teknis dari divisi terkait. Membangun jejaring dengan panitia kompetisi, komunitas robotika, serta pihak eksternal lain yang relevan guna memperluas peluang partisipasi dan meningkatkan kapasitas tim. Melakukan evaluasi berkala terhadap progres tim kompetisi untuk memastikan pengembangan kemampuan anggota berjalan sesuai target.',
                'ikon'                => 'fas fa-trophy',
                'urutan'              => '7',
                'status'              => 'aktif',
            ],
            
        ];

        Divisi::insert($data);
    }
}
