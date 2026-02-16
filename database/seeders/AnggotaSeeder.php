<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anggotaData = [
            [
                'nama_lengkap' => 'Abdel Raindy Andrian Zakaria',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'abdel.raindy.andrian.zakaria.te25@stu.pnj.ac.id',
                'no_hp' => '082113321479',
            ],
            [
                'nama_lengkap' => 'Abdullah Luthfi Al Rasyid',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'abdullah.luthfi.al.rasyid.te25@stu.pnj.ac.id',
                'no_hp' => '081276278832',
            ],
            [
                'nama_lengkap' => 'Abinaya Bisma Wiratama',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'abhinayabisma3012@gmail.com',
                'no_hp' => '089516774053',
            ],
            [
                'nama_lengkap' => 'Abrisam Rasyid',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'abrisam.rasyid.te25@stu.pnj.ac.id',
                'no_hp' => '085122958471',
            ],
            [
                'nama_lengkap' => 'Achmad Syaechu',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'achmad.syaechu.te25@stu.pnj.ac.id',
                'no_hp' => '081586624207',
            ],
            [
                'nama_lengkap' => 'Adam Rizky Rahmadan',
                'jurusan_prodi_kelas' => 'TIK/TI/2B',
                'email' => 'adam.rizky.rahmadan.tik25@stu.pnj.ac.id',
                'no_hp' => '0895360846102',
            ],
            [
                'nama_lengkap' => 'Adira Prawira Wijaya',
                'jurusan_prodi_kelas' => 'TE/TOLI/2B',
                'email' => 'adira.prawira.wijaya.te25@stu.pnj.ac.id',
                'no_hp' => '087763072507',
            ],
            [
                'nama_lengkap' => 'Adrian Hosea',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'adrian.hosea.te25@stu.pnj.ac.id',
                'no_hp' => '085695039312',
            ],
            [
                'nama_lengkap' => 'Ahmad Haidar Alkhoiri',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'ahmad.haidar.alkhoiri.te25@stu.pnj.ac.id',
                'no_hp' => '089675971651',
            ],
            [
                'nama_lengkap' => 'Ahmad Zaki Ghani',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'email' => 'ahmad.zaki.ghani.te24@stu.pnj.ac.id',
                'no_hp' => '089503671594',
            ],
            [
                'nama_lengkap' => 'Ahmad Zidan',
                'jurusan_prodi_kelas' => 'TM/TRM/2A',
                'email' => 'salmidandidinda@gmail.com',
                'no_hp' => '0881012223639',
            ],
            [
                'nama_lengkap' => 'ailsa talitha nur maryam',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'ailsa.maryam28@gmail.com',
                'no_hp' => '085719720469',
            ],
            [
                'nama_lengkap' => 'Alfian Randy',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'alfian.randy.te25@stu.pnj.ac.id',
                'no_hp' => '089508350068',
            ],
            [
                'nama_lengkap' => 'Alfiana Cyndie Rahmadhani',
                'jurusan_prodi_kelas' => 'TIK/TI/2A',
                'email' => 'alfiana.cyndie.rahmadhani.tik25@stu.pnj.ac.id',
                'no_hp' => '081398177609',
            ],
            [
                'nama_lengkap' => 'Alif Ammar Fathurrahman',
                'jurusan_prodi_kelas' => 'TE/TL/2B',
                'email' => 'alif.ammar.fathurrahman.te25@stu.pnj.ac.id',
                'no_hp' => '087824012406',
            ],
            [
                'nama_lengkap' => 'Alif Rizaldy Azra',
                'jurusan_prodi_kelas' => 'TAK/BKT/2A',
                'email' => 'alif.rizaldy.azra.ak25@stu.pnj.ac.id',
                'no_hp' => '085695748343',
            ],
            [
                'nama_lengkap' => 'Amili Putri',
                'jurusan_prodi_kelas' => 'TAK/BKT/2A',
                'email' => 'amili.putri.ak25@stu.pnj.ac.id',
                'no_hp' => '085649840274',
            ],
            [
                'nama_lengkap' => 'Amir Abdillah',
                'jurusan_prodi_kelas' => 'TE/TOLI/2B',
                'email' => 'amir.abdillah.te25@stu.pnj.ac.id',
                'no_hp' => '087788344748',
            ],
            [
                'nama_lengkap' => 'Ananta Adya Putra Nugroho',
                'jurusan_prodi_kelas' => 'TAK/BKT/2B',
                'email' => 'ananta.adya.putra.nugroho.ak25@stu.pnj.ac.id',
                'no_hp' => '085693432152',
            ],
            [
                'nama_lengkap' => 'Anindya Putri',
                'jurusan_prodi_kelas' => 'TIK/TI/2A',
                'email' => 'anindya.putri.tik25@stu.pnj.ac.id',
                'no_hp' => '081324661670',
            ],
            [
                'nama_lengkap' => 'Aqilla Daffaryan Satria Putra',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'aqilladapa12@gmail.com',
                'no_hp' => '087761606456',
            ],
            [
                'nama_lengkap' => 'Arsya Fatwa Ardina',
                'jurusan_prodi_kelas' => 'TAK/BKT/2A',
                'email' => 'arsya.fatwa.ardina.ak25@stu.pnj.ac.id',
                'no_hp' => '083152930884',
            ],
            [
                'nama_lengkap' => 'Audia Rizky Kusuma',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'audia.rizky.kusuma.te25@stu.pnj.ac.id',
                'no_hp' => '085810003427',
            ],
            [
                'nama_lengkap' => 'Azga Syahraz Arditha',
                'jurusan_prodi_kelas' => 'TE/BM/2A',
                'email' => 'azga.syahraz.arditha.te25@stu.pnj.ac.id',
                'no_hp' => '085198680234',
            ],
            [
                'nama_lengkap' => 'Azka Maulidia',
                'jurusan_prodi_kelas' => 'TAK/BKT/2B',
                'email' => 'azka.maulidia.ak25@stu.pnj.ac.id',
                'no_hp' => '085814511292',
            ],
            [
                'nama_lengkap' => 'Bintang putra adryan',
                'jurusan_prodi_kelas' => 'TIK/TI/2A',
                'email' => 'bintang.putra.adryan.tik25@stu.pnj.ac.id',
                'no_hp' => '081384538695',
            ],
            [
                'nama_lengkap' => 'BUDI LUTVI HAKIM',
                'jurusan_prodi_kelas' => 'TE/TOLI/2B',
                'email' => 'budi.lutvi.hakim.te25@stu.pnj.ac.id',
                'no_hp' => '081290722307',
            ],
            [
                'nama_lengkap' => 'Chalid Nazwa El Azmi',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'chalid.nazwa.el.azmi.te25@stu.pnj.ac.id',
                'no_hp' => '083180130495',
            ],
            [
                'nama_lengkap' => 'Danendra Althaf',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'danendra.althaf.te25@stu.pnj.ac.id',
                'no_hp' => '081295675033',
            ],
            [
                'nama_lengkap' => 'Dante Gavriello Stevano',
                'jurusan_prodi_kelas' => 'TE/IKI/6B',
                'email' => 'dante.gavriello.stevano.te23@stu.pnj.ac.id',
                'no_hp' => '08988366008',
            ],
            [
                'nama_lengkap' => 'Davino Adinata Nugroho',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'davino.adinata.nugroho.te25@stu.pnj.ac.id',
                'no_hp' => '087836324797',
            ],
            [
                'nama_lengkap' => 'Dhani Wira Saputra',
                'jurusan_prodi_kelas' => 'TIK/TI/2B',
                'email' => 'dhani.wira.saputra.tik25@stu.pnj.ac.id',
                'no_hp' => '089525244866',
            ],
            [
                'nama_lengkap' => 'Dhia Eka Putri',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'dhia.eka.putri.te25@stu.pnj.ac.id',
                'no_hp' => '085142021607',
            ],
            [
                'nama_lengkap' => 'Dwi Nur Aprilia',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'dwi.nur.aprilia.te25@stu.pnj.ac.id',
                'no_hp' => '089516185872',
            ],
            [
                'nama_lengkap' => 'FABIOLA YUNANSIO PUTRI HARTANA',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'fabiolayphh@gmail.com',
                'no_hp' => '0812899796541',
            ],
            [
                'nama_lengkap' => 'Fachri Al-Shaleh',
                'jurusan_prodi_kelas' => 'TM/TM/2E',
                'email' => 'fachri.alshaleh.tm25@stu.pnj.ac.id',
                'no_hp' => '083874965168',
            ],
            [
                'nama_lengkap' => 'Fadillah Ar-Rosuli',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'email' => 'fadillah.ar.rosuli.te24@stu.pnj.ac.id',
                'no_hp' => '088808357735',
            ],
            [
                'nama_lengkap' => 'Fadli Muhammad At Thariq',
                'jurusan_prodi_kelas' => 'TM/ME/2D',
                'email' => 'fadli.muhammad.at.thariq.tm25@stu.pnj.ac.id',
                'no_hp' => '085691666207',
            ],
            [
                'nama_lengkap' => 'Fadli Rifqi Aziz',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'fadli.rifqi.aziz.te25@stu.pnj.ac.id',
                'no_hp' => '082325177971',
            ],
            [
                'nama_lengkap' => 'Fahmi Firdaus',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'email' => 'fahmi.firdaus.te24@stu.pnj.ac.id',
                'no_hp' => '085771501298',
            ],
            [
                'nama_lengkap' => 'Faira Izzati Nasution',
                'jurusan_prodi_kelas' => 'TM/TRKE/2A',
                'email' => 'fairaizztnastn1277@gmail.com',
                'no_hp' => '08974556745',
            ],
            [
                'nama_lengkap' => 'Faisal Man Ali Daniyal',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'faisal.man.ali.daniyal.te25@stu.pnj.ac.id',
                'no_hp' => '088298287453',
            ],
            [
                'nama_lengkap' => 'Fakhri Ramadhan',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'fakhri.ramadhan.te25@stu.pnj.ac.id',
                'no_hp' => '085758450426',
            ],
            [
                'nama_lengkap' => 'Farid Dhaffa Musyyafa',
                'jurusan_prodi_kelas' => 'TE/TL/2C',
                'email' => 'farid.dhaffa.musyyafa.te25@stu.pnj.ac.id',
                'no_hp' => '085781098351',
            ],
            [
                'nama_lengkap' => 'Fathir Azam Zahran',
                'jurusan_prodi_kelas' => 'TM/TM/2A',
                'email' => 'fathir.azam.zahran.tm25@stu.pnj.ac.id',
                'no_hp' => '085198653324',
            ],
            [
                'nama_lengkap' => 'Fauzi ramadhan',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'fauzi.ramadhan.te25@stu.pnj.ac.id',
                'no_hp' => '081398799729',
            ],
            [
                'nama_lengkap' => 'Fawwaz Faishal Alwan',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'fawwaz.faishal.alwan.te25@stu.pnj.ac.id',
                'no_hp' => '08558576272',
            ],
            [
                'nama_lengkap' => 'Felisha Putri Ardiani',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'felisha.putri.ardiani.te25@stu.pnj.ac.id',
                'no_hp' => '081573014959',
            ],
            [
                'nama_lengkap' => 'Ferdiansyah Dwi Apriliano',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'ferdiansyah.dwi.apriliano.te25@stu.pnj.ac.id',
                'no_hp' => '085892632824',
            ],
            [
                'nama_lengkap' => 'Ferdinan Muhamad Rofik',
                'jurusan_prodi_kelas' => 'TE/TOLI/2B',
                'email' => 'ferdinan.muhamad.rofik.te25@stu.pnj.ac.id',
                'no_hp' => '0895617748598',
            ],
            [
                'nama_lengkap' => 'Ghaisan Adiend Fathan Al Adli',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'ghaisan.adiend.fathan.al.adli.te25@stu.pnj.ac.id',
                'no_hp' => '088213623917',
            ],
            [
                'nama_lengkap' => 'Hadzoti Fawwaz Azifa',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'email' => 'hadzoti.fawwaz.azifa.te24@stu.pnj.ac.id',
                'no_hp' => '0895396200981',
            ],
            [
                'nama_lengkap' => 'Haikal Ahmad Ardiansyah',
                'jurusan_prodi_kelas' => 'TE/BM/2B',
                'email' => 'haikal.ahmad.ardiansyah.te25@stu.pnj.ac.id',
                'no_hp' => '081314374828',
            ],
            [
                'nama_lengkap' => 'Hapriyan alamsyha',
                'jurusan_prodi_kelas' => 'TE/IKI/6A',
                'email' => 'hapriyan.alamsyah.te23@stu.pnj.ac.id',
                'no_hp' => '085773280801',
            ],
            [
                'nama_lengkap' => 'Hasbi Ro\'uf Rabbani',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'hasbi.ro.uf.rabbani.te25@stu.pnj.ac.id',
                'no_hp' => '085770838987',
            ],
            [
                'nama_lengkap' => 'Huldi',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'email' => 'huldi.te24@stu.pnj.ac.id',
                'no_hp' => '087878601904',
            ],
            [
                'nama_lengkap' => 'I Gusti Agung Arya Wirapati RS',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'i.gusti.agung.arya.wirapati.rs.te25@stu.pnj.ac.id',
                'no_hp' => '08561391918',
            ],
            [
                'nama_lengkap' => 'Ibni Aqhibil Apta Putera',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'email' => 'ibni.aqhibil.apta.putera.te24@stu.pnj.ac.id',
                'no_hp' => '081381002078',
            ],
            [
                'nama_lengkap' => 'Ihsanullintang Sanjoyo',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'ihsanullintang.sanjoyo.te25@stu.pnj.ac.id',
                'no_hp' => '083899436099',
            ],
            [
                'nama_lengkap' => 'Imam Adli Achsani Nugrohk',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'imam.adli.achsani.nugroho.te25@stu.pnj.ac.id',
                'no_hp' => '085175049525',
            ],
            [
                'nama_lengkap' => 'Jasim muhammad rafif',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'jasim.muhammad.rafif.te25@stu.pnj.ac.id',
                'no_hp' => '082298021847',
            ],
            [
                'nama_lengkap' => 'Jihad Al Fath',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'jihadsch1@gmail.com',
                'no_hp' => '089662719963',
            ],
            [
                'nama_lengkap' => 'Jihad Masyahadad',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'jihad.masyahadad.te25@stu.pnj.ac.id',
                'no_hp' => '081364899841',
            ],
            [
                'nama_lengkap' => 'Juan Carlos Gea',
                'jurusan_prodi_kelas' => 'TIK/TMJ/2C',
                'email' => 'juan.carlos.gea.tik25@stu.pnj.ac.id',
                'no_hp' => '081410180810',
            ],
            [
                'nama_lengkap' => 'Julian Fadel Yasin',
                'jurusan_prodi_kelas' => 'TE/IKI/6B',
                'email' => 'julian.fadel.yasin.te23@stu.pnj.ac.id',
                'no_hp' => '081285263414',
            ],
            [
                'nama_lengkap' => 'Kayla Margareth M N',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'kayla.margareth.merdekawati.nababan.te25@stu.pnj.ac.id',
                'no_hp' => '085892064645',
            ],
            [
                'nama_lengkap' => 'Kessa Putra Anggalaksana',
                'jurusan_prodi_kelas' => 'TE/IKI/6A',
                'email' => 'kessa.putra.anggalaksana.te23@stu.pnj.ac.id',
                'no_hp' => '081290141773',
            ],
            [
                'nama_lengkap' => 'khairul ikhsan',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'khairul.ikhsan.te25@stu.pnj.ac.id',
                'no_hp' => '088975794149',
            ],
            [
                'nama_lengkap' => 'Khalid Adyatma',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'khalid.adyatma.te25@stu.pnj.ac.id',
                'no_hp' => '085714612817',
            ],
            [
                'nama_lengkap' => 'Khanaya Audina Permadi',
                'jurusan_prodi_kelas' => 'TAK/BKT/2A',
                'email' => 'khanaya.audina.permadi.ak25@stu.pnj.ac.id',
                'no_hp' => '081283568668',
            ],
            [
                'nama_lengkap' => 'Kreshna Wibowo',
                'jurusan_prodi_kelas' => 'TE/TT/4B',
                'email' => 'kreshna.wibowo.te24@stu.pnj.ac.id',
                'no_hp' => '082122850682',
            ],
            [
                'nama_lengkap' => 'Lavanya Azka Fadilah',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'lavanya.azka.fadilah.te25@stu.pnj.ac.id',
                'no_hp' => '085288039826',
            ],
            [
                'nama_lengkap' => 'Lidia Tri Ratnasari',
                'jurusan_prodi_kelas' => 'TAK/BKT/2B',
                'email' => 'lidia.tri.ratnasari.ak25@stu.pnj.ac.id',
                'no_hp' => '0895365153872',
            ],
            [
                'nama_lengkap' => 'Marta Fransiska Eufrasia',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'marta.fransiska.eufrasia.te25@stu.pnj.ac.id',
                'no_hp' => '0895340382437',
            ],
            [
                'nama_lengkap' => 'Masagus Ihsan Farizqy',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'masagus.ihsan.farizqy.te25@stu.pnj.ac.id',
                'no_hp' => '08111924638',
            ],
            [
                'nama_lengkap' => 'Maulana Rafly Abdullah',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'maulana.rafly.abdullah.te25@stu.pnj.ac.id',
                'no_hp' => '085150585256',
            ],
            [
                'nama_lengkap' => 'Maulana Zharif Arkan',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'maulana.zharif.arkan.te25@stu.pnj.ac.id',
                'no_hp' => '081210417123',
            ],
            [
                'nama_lengkap' => 'Mohamad Malik Fadillah Arwin',
                'jurusan_prodi_kelas' => 'TIK/TI/2B',
                'email' => 'mohamad.malik.fadillah.arwin.tik25@stu.pnj.ac.id',
                'no_hp' => '089512264970',
            ],
            [
                'nama_lengkap' => 'Muflih Nabil Aziz',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'muflih.nabil.aziz.te25@stu.pnj.ac.id',
                'no_hp' => '082226122621',
            ],
            [
                'nama_lengkap' => 'Muhamad Al Farisi',
                'jurusan_prodi_kelas' => 'TE/BM/2A',
                'email' => 'muhamad.al.farisi.te25@stu.pnj.ac.id',
                'no_hp' => '082133356934',
            ],
            [
                'nama_lengkap' => 'Muhamad Rizhan Faturahman',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'muhamad.rizhan.faturahman.te25@stu.pnj.ac.id',
                'no_hp' => '085776504819',
            ],
            [
                'nama_lengkap' => 'Muhamad Rizki Sabri',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'muhamad.rizki.sabri.te25@stu.pnj.ac.id',
                'no_hp' => '085888691962',
            ],
            [
                'nama_lengkap' => 'Muhamad safarul fajar',
                'jurusan_prodi_kelas' => 'TM/TM/2E',
                'email' => 'muhamad.safarul.fajar.tm25@stu.pnj.ac.id',
                'no_hp' => '089643035914',
            ],
            [
                'nama_lengkap' => 'Muhammad Abdillah Syaiban',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'abdillahsyaiban@gmail.com',
                'no_hp' => '081545873593',
            ],
            [
                'nama_lengkap' => 'Muhammad Abyan Kaafiy',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'muhammad.abyan.kaafiy.te25@stu.pnj.ac.id',
                'no_hp' => '08561619293',
            ],
            [
                'nama_lengkap' => 'Muhammad Addien Fikrul Akbar',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'muhammad.addien.fikrul.akbar.te25@stu.pnj.ac.id',
                'no_hp' => '085545132710',
            ],
            [
                'nama_lengkap' => 'Muhammad Adnan Hafiz',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'adnan.hafiz217@gmail.com',
                'no_hp' => '087885802757',
            ],
            [
                'nama_lengkap' => 'Muhammad Alfatih Sulaeman',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'muhammad.alfatih.sulaeman.te25@stu.pnj.ac.id',
                'no_hp' => '087792130683',
            ],
            [
                'nama_lengkap' => 'Muhammad Dzaki Hisyam',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'muhammad.dzaki.hisyam.te25@stu.pnj.ac.id',
                'no_hp' => '0895326118443',
            ],
            [
                'nama_lengkap' => 'Muhammad Fadlan Kamil',
                'jurusan_prodi_kelas' => 'TE/BM/2A',
                'email' => 'muhammad.fadlan.kamil.te25@stu.pnj.ac.id',
                'no_hp' => '081211902966',
            ],
            [
                'nama_lengkap' => 'Muhammad Fahrul Rizal Rahman',
                'jurusan_prodi_kelas' => 'TE/IKI/6A',
                'email' => 'muhammad.fahrul.rizal.rahman.te23@stu.pnj.ac.id',
                'no_hp' => '088801248606',
            ],
            [
                'nama_lengkap' => 'Muhammad Faiq Satia Prasaja',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'muhammad.faiq.satia.prasaja.te25@stu.pnj.ac.id',
                'no_hp' => '085894661380',
            ],
            [
                'nama_lengkap' => 'Muhammad Faiz An Naafi',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'muhammad.faiz.an.naafi.te25@stu.pnj.ac.id',
                'no_hp' => '082162647180',
            ],
            [
                'nama_lengkap' => 'Muhammad Fakhri Rossi',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'muhammad.fakhri.rossi.te25@stu.pnj.ac.id',
                'no_hp' => '081391445470',
            ],
            [
                'nama_lengkap' => 'Muhammad Fakhriansyah',
                'jurusan_prodi_kelas' => 'TE/IKI/6B',
                'email' => 'muhammad.fakhriansyah.te23@stu.pnj.ac.id',
                'no_hp' => '081212520223',
            ],
            [
                'nama_lengkap' => 'Muhammad Fakih Nur Muzakki',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'muhammad.fakih.nur.muzakki.te25@stu.pnj.ac.id',
                'no_hp' => '081282050427',
            ],
            [
                'nama_lengkap' => 'MUHAMMAD FARHAN ABDULLAH',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'muhammad.farhan.abdullah.te25@stu.pnj.ac.id',
                'no_hp' => '085649033609',
            ],
            [
                'nama_lengkap' => 'Muhammad Farrel Baddar Pamuji',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'muhammad.farrel.baddar.pamuji.te25@stu.pnj.ac.id',
                'no_hp' => '081387032739',
            ],
            [
                'nama_lengkap' => 'Muhammad Firas Alhamra',
                'jurusan_prodi_kelas' => 'TM/ME/2D',
                'email' => 'alhamrafiras18@gmail.com',
                'no_hp' => '085782265008',
            ],
            [
                'nama_lengkap' => 'Muhammad Hafizh',
                'jurusan_prodi_kelas' => 'TIK/TMJ/2A',
                'email' => 'muhammad.hafizh.tik25@stu.pnj.ac.id',
                'no_hp' => '089529015149',
            ],
            [
                'nama_lengkap' => 'Muhammad Hilmy Aulya Rahman',
                'jurusan_prodi_kelas' => 'TE/IKI/6A',
                'email' => 'muhammad.hilmy.aulya.rahman.te23@stu.pnj.ac.id',
                'no_hp' => '085693708137',
            ],
            [
                'nama_lengkap' => 'Muhammad Ibnu Aqil',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'muhammad.ibnu.aqil.te25@stu.pnj.ac.id',
                'no_hp' => '081287113690',
            ],
            [
                'nama_lengkap' => 'Muhammad Ichban Ramdhani',
                'jurusan_prodi_kelas' => 'TM/TM/2F',
                'email' => 'muhammad.ichban.ramdhani.tm25@stu.pnj.ac.id',
                'no_hp' => '082124867828',
            ],
            [
                'nama_lengkap' => 'Muhammad Ihsan',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'email' => 'muhammad.ihsan.te24@stu.pnj.ac.id',
                'no_hp' => '085692110774',
            ],
            [
                'nama_lengkap' => 'Muhammad Iqbal',
                'jurusan_prodi_kelas' => 'TM/TM/2D',
                'email' => 'iqbalmuhammad283732@gmail.com',
                'no_hp' => '081573222072',
            ],
            [
                'nama_lengkap' => 'Muhammad Irshad Al Faqih',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'muhammad.irshad.al.faqih.te25@stu.pnj.ac.id',
                'no_hp' => '081318477235',
            ],
            [
                'nama_lengkap' => 'Muhammad Lutfi Firmansyah',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'muhammad.lutfi.firmansyah.te25@stu.pnj.ac.id',
                'no_hp' => '085604088709',
            ],
            [
                'nama_lengkap' => 'Muhammad Luthfi',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'muhammad.luthfi.te25@stu.pnj.ac.id',
                'no_hp' => '088808992133',
            ],
            [
                'nama_lengkap' => 'Muhammad Nabil',
                'jurusan_prodi_kelas' => 'TAK/BKT/2A',
                'email' => 'arkansimdigei1@gmail.com',
                'no_hp' => '089510130247',
            ],
            [
                'nama_lengkap' => 'Muhammad Nabil Habibie',
                'jurusan_prodi_kelas' => 'TE/BM/2A',
                'email' => 'muhammad.nabil.habibie.te25@stu.pnj.ac.id',
                'no_hp' => '081385896025',
            ],
            [
                'nama_lengkap' => 'MUHAMMAD NANDANA NATHAN',
                'jurusan_prodi_kelas' => 'TM/TM/2E',
                'email' => 'muhammad.nandana.nathan.tm25@stu.pnj.ac.id',
                'no_hp' => '087890599921',
            ],
            [
                'nama_lengkap' => 'Muhammad Naufal Halim',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'muhammad.naufal.halim.te25@stu.pnj.ac.id',
                'no_hp' => '08997445420',
            ],
            [
                'nama_lengkap' => 'Muhammad Raditya',
                'jurusan_prodi_kelas' => 'TM/TRM/2A',
                'email' => 'muhamad.raditya.tm25@stu.pnj.ac.id',
                'no_hp' => '089668756791',
            ],
            [
                'nama_lengkap' => 'Muhammad Rafi Firmansyah',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'email' => 'muhammad.rafi.firmansyah.te24@stu.pnj.ac.id',
                'no_hp' => '08567314315',
            ],
            [
                'nama_lengkap' => 'Muhammad Raihan Fadhilah',
                'jurusan_prodi_kelas' => 'TE/BM/2B',
                'email' => 'muhammad.raihan.fadhilah.te25@stu.pnj.ac.id',
                'no_hp' => '087821050337',
            ],
            [
                'nama_lengkap' => 'Muhammad Rashya Erlangga',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'muhammad.rashya.erlangga.te25@stu.pnj.ac.id',
                'no_hp' => '085813920228',
            ],
            [
                'nama_lengkap' => 'Muhammad Tahadj Priyoutomo',
                'jurusan_prodi_kelas' => 'TE/TT/4B',
                'email' => 'muhammad.tahadj.priyoutomo.te24@stu.pnj.ac.id',
                'no_hp' => '085156564645',
            ],
            [
                'nama_lengkap' => 'MUHMAMMAD RIZKI RAMADHANI',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'rizkiramadhani7718@gmail.com',
                'no_hp' => '081915235774',
            ],
            [
                'nama_lengkap' => 'Musthafa Hadji',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'musthafa.hadji.te25@stu.pnj.ac.id',
                'no_hp' => '085210508257',
            ],
            [
                'nama_lengkap' => 'Mutiara Bunga Rianti',
                'jurusan_prodi_kelas' => 'TM/TRKE/2A',
                'email' => 'mutiara.bunga.rianti.tm25@stu.pnj.ac.id',
                'no_hp' => '089509602403',
            ],
            [
                'nama_lengkap' => 'Najwa Sharfina Muswar',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'najwa.sharfina.muswar.te25@stu.pnj.ac.id',
                'no_hp' => '082125514047',
            ],
            [
                'nama_lengkap' => 'Naufal Hakim Salik',
                'jurusan_prodi_kelas' => 'TE/BM/2A',
                'email' => 'naufal.hakim.salik.te25@stu.pnj.ac.id',
                'no_hp' => '081389563312',
            ],
            [
                'nama_lengkap' => 'Nayla Fitriani',
                'jurusan_prodi_kelas' => 'TE/EC/4B',
                'email' => 'nayla.fitriani.te24@stu.pnj.ac.id',
                'no_hp' => '087883559021',
            ],
            [
                'nama_lengkap' => 'Nazwaini',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'email' => 'nazwaini.te24@stu.pnj.ac.id',
                'no_hp' => '085814928958',
            ],
            [
                'nama_lengkap' => 'NISRINA TALITA KARIB',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'nisrina.talita.karib.te25@stu.pnj.ac.id',
                'no_hp' => '082123067963',
            ],
            [
                'nama_lengkap' => 'Pasha Pratama',
                'jurusan_prodi_kelas' => 'TE/TT/2B',
                'email' => 'pasha.pratama.te25@stu.pnj.ac.id',
                'no_hp' => '087747834023',
            ],
            [
                'nama_lengkap' => 'Putra Nur Pratama',
                'jurusan_prodi_kelas' => 'TM/TMJ/2B',
                'email' => 'putra.nur.pratama.tik25@stu.pnj.ac.id',
                'no_hp' => '085283778120',
            ],
            [
                'nama_lengkap' => 'Rafly Muhammad Zaky',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'rafly.muhammad.zaky.te25@stu.pnj.ac.id',
                'no_hp' => '085795217866',
            ],
            [
                'nama_lengkap' => 'Raka Arkana Fiery',
                'jurusan_prodi_kelas' => 'TE/TT/4B',
                'email' => 'raka.arkana.fiery.te24@stu.pnj.ac.id',
                'no_hp' => '08159409229',
            ],
            [
                'nama_lengkap' => 'Ramzhaniy Ardhan Narendra',
                'jurusan_prodi_kelas' => 'TE/TL/2B',
                'email' => 'ramzhaniy.ardhan.narendra.te25@stu.pnj.ac.id',
                'no_hp' => '081316840674',
            ],
            [
                'nama_lengkap' => 'Rangga Putra Ramadhan',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'rangga.putra.ramadhan.te25@stu.pnj.ac.id',
                'no_hp' => '08973743562',
            ],
            [
                'nama_lengkap' => 'Rara Areta Bathari Isa',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'rara.areta.bathari.isa.te25@stu.pnj.ac.id',
                'no_hp' => '081318562007',
            ],
            [
                'nama_lengkap' => 'Rasya Tri Aditya',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'rasya.tri.aditya.te25@stu.pnj.ac.id',
                'no_hp' => '085782506218',
            ],
            [
                'nama_lengkap' => 'Ratumas Sucihaawwaa',
                'jurusan_prodi_kelas' => 'TE/BM/2A',
                'email' => 'rciawaratucici@gmail.com',
                'no_hp' => '085811083607',
            ],
            [
                'nama_lengkap' => 'Rayhan Fachreza',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'rayhan.fachreza.te25@stu.pnj.ac.id',
                'no_hp' => '085693697590',
            ],
            [
                'nama_lengkap' => 'Rico Agustian Baringbing',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'ricobaringbing187@gmail.com',
                'no_hp' => '085311629544',
            ],
            [
                'nama_lengkap' => 'Ridhwan Hafidzun Alim',
                'jurusan_prodi_kelas' => 'TE/IKI/4A',
                'email' => 'ridhwan.hafidzun.alim.te24@stu.pnj.ac.id',
                'no_hp' => '081281222450',
            ],
            [
                'nama_lengkap' => 'Riski Febriyan',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'riski.febriyan.te25@stu.pnj.ac.id',
                'no_hp' => '087734290198',
            ],
            [
                'nama_lengkap' => 'Ruh Setojati',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'email' => 'ruh.setojati.te24@stu.pnj.ac.id',
                'no_hp' => '087812282069',
            ],
            [
                'nama_lengkap' => 'Ryan',
                'jurusan_prodi_kelas' => 'TE/IKI/2A',
                'email' => 'ryan.te25@stu.pnj.ac.id',
                'no_hp' => '087763541091',
            ],
            [
                'nama_lengkap' => 'Sabrina Vinca Az-Zahra',
                'jurusan_prodi_kelas' => 'TE/IKI/2B',
                'email' => 'sabrina.vinca.azzahra.te25@stu.pnj.ac.id',
                'no_hp' => '08975914646',
            ],
            [
                'nama_lengkap' => 'Safana Fuadah Azzahra',
                'jurusan_prodi_kelas' => 'TE/EC/2D',
                'email' => 'safana.fuadah.azzahra.te25@stu.pnj.ac.id',
                'no_hp' => '085716272585',
            ],
            [
                'nama_lengkap' => 'Shafura Kasyful Haq',
                'jurusan_prodi_kelas' => 'TE/EC/4D',
                'email' => 'shafura.kasyful.haq.te24@stu.pnj.ac.id',
                'no_hp' => '081314398606',
            ],
            [
                'nama_lengkap' => 'Syifa Kalilah',
                'jurusan_prodi_kelas' => 'AN/ABT/4C',
                'email' => 'syifa.kalilah.an24@stu.pnj.ac.id',
                'no_hp' => '081283404020',
            ],
            [
                'nama_lengkap' => 'Syifa Nur Jauzah',
                'jurusan_prodi_kelas' => 'TIK/TI/2A',
                'email' => 'syifanurjauzah@gmail.com',
                'no_hp' => '089514456843',
            ],
            [
                'nama_lengkap' => 'Teuku Naufal Abyan',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'teuku.naufal.abyan.te25@stu.pnj.ac.id',
                'no_hp' => '081297616225',
            ],
            [
                'nama_lengkap' => 'Thaufik Jonatan Bagaskara',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'thaufik.jonatan.bagaskara.te25@stu.pnj.ac.id',
                'no_hp' => '085161361606',
            ],
            [
                'nama_lengkap' => 'Umar Fadzli Santoso',
                'jurusan_prodi_kelas' => 'TE/TOLI/2A',
                'email' => 'umar.fadzli.santoso.te25@stu.pnj.ac.id',
                'no_hp' => '082125712676',
            ],
            [
                'nama_lengkap' => 'William Abraham ButarButar',
                'jurusan_prodi_kelas' => 'TE/EC/2B',
                'email' => 'william.abraham.butarbutar.te25@stu.pnj.ac.id',
                'no_hp' => '085697869179',
            ],
            [
                'nama_lengkap' => 'Wulan Tiara Kasih',
                'jurusan_prodi_kelas' => 'TE/EC/2A',
                'email' => 'wulan.tiara.kasih.te25@stu.pnj.ac.id',
                'no_hp' => '085156877161',
            ],
            [
                'nama_lengkap' => 'Yazid Yusuf',
                'jurusan_prodi_kelas' => 'TIK/TI/2A',
                'email' => 'yazid.yusuf.tik25@stu.pnj.ac.id',
                'no_hp' => '085161661805',
            ],
            [
                'nama_lengkap' => 'Yuan Rizqy Pratama',
                'jurusan_prodi_kelas' => 'TE/EC/2C',
                'email' => 'yuan.rizqy.pratama.te25@stu.pnj.ac.id',
                'no_hp' => '083808058849',
            ],
            [
                'nama_lengkap' => 'Zahra Aulia Nila Pramesti',
                'jurusan_prodi_kelas' => 'TM/TRM/2A',
                'email' => 'zahra.aulia.nila.pramesti.tm25@stu.pnj.ac.id',
                'no_hp' => '0895411938783',
            ],
        ];

        foreach ($anggotaData as $data) {
            // Create user account
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make('1'),
                'active' => '1',
            ]);

            // Assign anggota role
            $user->assignRole('anggota');

            // Insert into anggota table
            $anggotaId = DB::table('anggota')->insertGetId([
                'id_user' => $user->id,
                'id_tahun' => 1,
                'id_department' => 0,
                'nama_jabatan' => 'anggota',
                'nama_lengkap' => $data['nama_lengkap'],
                'jurusan_prodi_kelas' => $data['jurusan_prodi_kelas'],
                'nim' => null,
                'ttl' => null,
                'alamat' => null,
                'email' => $data['email'],
                'no_hp' => $data['no_hp'],
                'status_anggota' => 'anggota',
                'status_aktif' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Link dengan open_recruitment jika ada yang match (by email dan status lulus)
            DB::table('open_recruitment')
                ->where('email', $data['email'])
                ->where('status_seleksi', 'lulus')
                ->update([
                    'id_user' => $user->id,
                    'id_anggota' => $anggotaId,
                ]);

            // Update id_open_recruitment di anggota jika ada
            $openRecruitmentId = DB::table('open_recruitment')
                ->where('email', $data['email'])
                ->where('status_seleksi', 'lulus')
                ->value('id');

            if ($openRecruitmentId) {
                DB::table('anggota')
                    ->where('id', $anggotaId)
                    ->update(['id_open_recruitment' => $openRecruitmentId]);
            }
        }
    }
}
