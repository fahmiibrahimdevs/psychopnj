<?php

namespace Database\Seeders;

use App\Models\OpenRecruitment;
use Illuminate\Database\Seeder;

class OpenRecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Ketua
            [
                'nama_lengkap' => 'Muhammad Syafiq Aziz',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '1',
                'nama_jabatan' => 'Ketua',
                'status_seleksi' => 'lulus',
            ],
            // Wakil Ketua
            [
                'nama_lengkap' => 'Fahmi Ibrahim',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'id_department' => '1',
                'nama_jabatan' => 'Wakil Ketua',
                'status_seleksi' => 'lulus',
            ],
            // Kepala Department Kesekretariatan
            [
                'nama_lengkap' => 'Mutiya Laila Fitri',
                'jurusan_prodi_kelas' => 'TE/BM/4A',
                'id_department' => '2',
                'nama_jabatan' => 'Kepala Department',
                'status_seleksi' => 'lulus',
            ],
            // Staff Kesekretariatan
            [
                'nama_lengkap' => 'Dian Bestari',
                'jurusan_prodi_kelas' => 'TIK/TMD/4A',
                'id_department' => '2',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            // Kepala Department Bendahara Umum
            [
                'nama_lengkap' => 'Beryl Ghanim Setiyoko',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '3',
                'nama_jabatan' => 'Kepala Department',
                'status_seleksi' => 'lulus',
            ],
            // Staff Bendahara Umum
            [
                'nama_lengkap' => 'David Vernando Hutagalung',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '3',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            // Kepala Department PRE
            [
                'nama_lengkap' => 'Firhan Maulana Sopian',
                'jurusan_prodi_kelas' => 'TE/TOLI/4A',
                'id_department' => '5',
                'nama_jabatan' => 'Kepala Department',
                'status_seleksi' => 'lulus',
            ],
            // Staff PRE
            [
                'nama_lengkap' => 'Abdul Latif',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Muhammad Haikal',
                'jurusan_prodi_kelas' => 'TE/EC/4C',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Diaz Marcelo Firman Gandi',
                'jurusan_prodi_kelas' => 'TE/EC/4C',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Faiz Dwi Jatnika',
                'jurusan_prodi_kelas' => 'TM/TRM/4B',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Daffa Firmansyah',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Rakha Alif Ibrahim',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Mohammad Bintang Satya Guna',
                'jurusan_prodi_kelas' => 'TM/TM/MPRN4A',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Arsa Danish Fadillah',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Fatih Ahmad Ridho',
                'jurusan_prodi_kelas' => 'TM/TRM/4B',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Putrama Abi Aryasatya',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Muhammad Ikhwanul Muslim',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'id_department' => '5',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            // Kepala Department Kominfo
            [
                'nama_lengkap' => 'Isnursyah Yudha Prawira',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '4',
                'nama_jabatan' => 'Kepala Department',
                'status_seleksi' => 'lulus',
            ],
            // Staff Kominfo
            [
                'nama_lengkap' => 'Elvina Naira Safitri',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Dyo putra mulya',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'lisya nur abika',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Nazarudin Zidan',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Muhammad Ari Mahesa',
                'jurusan_prodi_kelas' => 'TE/TOLI/4A',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Ajeng Nur Azizah',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Kiki Amaliasari Ahmad',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Qessar Philoice Raja Pandia',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'id_department' => '4',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            // Kepala Department Perlengkapan
            [
                'nama_lengkap' => 'Ibrahim',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '6',
                'nama_jabatan' => 'Kepala Department',
                'status_seleksi' => 'lulus',
            ],
            // Staff Perlengkapan
            [
                'nama_lengkap' => 'Muhammad Rafli Az-Zikri',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Mochamad Aqil Maulana rais',
                'jurusan_prodi_kelas' => 'TM/TM/4E',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Dimas Surya Saputra',
                'jurusan_prodi_kelas' => 'TE/TT/4D',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Hanif Ramdhan',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Azhar Al Bana',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Leonardus Alvin Natanael Dappa',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Rizqi Cahya Ramadhan',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Haamiim Muhammad Al Fath',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Iqbal Jati Djatiran',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '6',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            // Kepala Department Polytechnic Robotics
            [
                'nama_lengkap' => 'Arif Fahmi Abdillah',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'id_department' => '7',
                'nama_jabatan' => 'Kepala Department',
                'status_seleksi' => 'lulus',
            ],
            // Staff Polytechnic Robotics
            [
                'nama_lengkap' => 'Muhammad Mufid',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Ali Fari Fakhrozi',
                'jurusan_prodi_kelas' => 'TE/EC/4A',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Jeremy Matthew Sejati',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Hasna Khaira Pramudianto',
                'jurusan_prodi_kelas' => 'AK/AKT/4B',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Pandu Muhamad Fazri',
                'jurusan_prodi_kelas' => 'TE/IKI/4B',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Illbe Jihadin Wilen',
                'jurusan_prodi_kelas' => 'TE/TOLI/4A',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
            [
                'nama_lengkap' => 'Dhika Adiansyah',
                'jurusan_prodi_kelas' => 'TM/ME/4D',
                'id_department' => '7',
                'nama_jabatan' => 'Staff',
                'status_seleksi' => 'lulus',
            ],
        ];

        foreach ($data as $recruitment) {
            OpenRecruitment::create([
                'id_tahun' => '1',
                'jenis_oprec' => 'pengurus',
                'nama_lengkap' => $recruitment['nama_lengkap'],
                'jurusan_prodi_kelas' => $recruitment['jurusan_prodi_kelas'],
                'id_department' => $recruitment['id_department'],
                'nama_jabatan' => $recruitment['nama_jabatan'],
                'status_seleksi' => $recruitment['status_seleksi'],
            ]);
        }
    }
}
