<?php

use App\Livewire\Galeri;
use App\Livewire\Divisi;
use App\Livewire\Welcome;
use App\Livewire\TentangKami;
use App\Livewire\KontakKami;
use App\Livewire\CekSertifikat;
use App\Livewire\Organisasi\Department;
use App\Livewire\Organisasi\Anggota;
use App\Livewire\Dashboard\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Organisasi\ControlUser;
use App\Livewire\Organisasi\OpenRecruitment;
use App\Livewire\Organisasi\ProfilOrganisasi;
use App\Livewire\Organisasi\TahunKepengurusan;
use App\Livewire\Akademik\ProgramKegiatan;
use App\Livewire\Akademik\Pertemuan;
use App\Livewire\Akademik\PresensiPertemuan;

Route::get('/', Welcome::class);

Route::get('/tentang', TentangKami::class);
Route::get('/divisi', Divisi::class);
Route::get('/cek-sertifikat', CekSertifikat::class)->middleware('guest');
Route::get('/kontak', KontakKami::class);
Route::get('/galeri', Galeri::class);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');

    Route::post('/summernote/file/upload', [UploadController::class, 'uploadImageSummernote']);
    Route::post('/summernote/file/delete', [UploadController::class, 'deleteImageSummernote']);
});

Route::group(['middleware' => ['auth', 'role:super_admin|chairman|admin_pengajaran|admin_keuangan|admin_inventaris|admin_sekretaris|admin_project|admin_media']], function () {
    Route::get('tahun-kepengurusan', TahunKepengurusan::class)->name('tahun-kepengurusan')->middleware('can:tahun_kepengurusan.view');
    Route::get('department', Department::class)->name('department')->middleware('can:department.view');
    Route::get('anggota', Anggota::class)->name('anggota')->middleware('can:anggota.view');
    Route::get('profil-organisasi', ProfilOrganisasi::class)->name('profil-organisasi')->middleware('can:profil_organisasi.view');
    Route::get('open-recruitment', OpenRecruitment::class)->name('open-recruitment')->middleware('can:open_recruitment.view');
    Route::get('control-user', ControlUser::class)->name('control-user')->middleware('can:control_user.view');
    Route::get('permission-matrix', \App\Livewire\Organisasi\PermissionMatrix::class)->name('permission-matrix')->middleware('can:permission_matrix.view');
    
    // Akademik Routes
    Route::get('program-kegiatan', ProgramKegiatan::class)->name('program-kegiatan')->middleware('can:program_kegiatan.view');
    Route::get('pertemuan', Pertemuan::class)->name('pertemuan')->middleware('can:pertemuan.view');
    Route::get('part/{partId}/soal', \App\Livewire\Akademik\BankSoal\SoalPertemuan::class)->name('part.soal')->middleware('can:pertemuan.bank_soal');
    Route::get('projects', \App\Livewire\Akademik\Project::class)->name('projects')->middleware('can:project.view');
    Route::get('project/{projectId}/teams', \App\Livewire\Akademik\ProjectTeams::class)->name('project.teams')->middleware('can:project_team.view');
    Route::get('presensi-kehadiran', PresensiPertemuan::class)->name('presensi-kehadiran')->middleware('can:presensi.view');
    Route::get('statistik-kehadiran', \App\Livewire\Akademik\StatistikKehadiran::class)->name('statistik-kehadiran')->middleware('can:statistik_kehadiran.view');
    Route::get('status-anggota-ujian', \App\Livewire\Akademik\StatusAnggotaUjian::class)->name('status-anggota-ujian')->middleware('can:ujian.view');
    Route::get('hasil-ujian-pertemuan', \App\Livewire\Akademik\HasilUjianPertemuan::class)->name('hasil-ujian-pertemuan')->middleware('can:ujian.view');
    Route::get('hasil-ujian-pertemuan/koreksi/{id_part}/{id_anggota}', \App\Livewire\Akademik\HasilUjian\Koreksi::class)->name('koreksi-hasil-ujian')->middleware('can:ujian.koreksi');
    
    // Keuangan Routes
    Route::get('anggaran', \App\Livewire\Keuangan\Anggaran::class)->name('anggaran')->middleware('can:anggaran.view');
    Route::get('jenis-anggaran', \App\Livewire\Keuangan\JenisAnggaran::class)->name('jenis-anggaran')->middleware('can:jenis_anggaran.view');
    Route::get('transaksi-keuangan', \App\Livewire\Keuangan\Transaksi::class)->name('transaksi-keuangan')->middleware('can:transaksi.view');
    Route::get('iuran-kas', \App\Livewire\Keuangan\IuranKas::class)->name('iuran-kas')->middleware('can:iuran_kas.view');
    Route::get('laporan-keuangan', \App\Livewire\Keuangan\Laporan::class)->name('laporan-keuangan')->middleware('can:laporan_keuangan.view');
    
    // Perlengkapan Routes
    Route::get('kategori-barang', \App\Livewire\Perlengkapan\KategoriBarang::class)->name('kategori-barang')->middleware('can:kategori_barang.view');
    Route::get('barang', \App\Livewire\Perlengkapan\Barang::class)->name('barang')->middleware('can:barang.view');
    Route::get('peminjaman-barang', \App\Livewire\Perlengkapan\PeminjamanBarang::class)->name('peminjaman-barang')->middleware('can:peminjaman_barang.view');
    Route::get('pengadaan-barang', \App\Livewire\Perlengkapan\PengadaanBarang::class)->name('pengadaan-barang')->middleware('can:pengadaan_barang.view');

    // Sekretaris Routes
    Route::get('surat-administrasi', \App\Livewire\Sekretaris\Surat::class)->name('surat-administrasi')->middleware('can:surat.view');
    
    // LPJ Export
    Route::get('lpj/export-pdf', [\App\Http\Controllers\LpjExportController::class, 'exportPdf'])->name('lpj.pdf');
    Route::get('lpj/export-excel', [\App\Http\Controllers\LpjExportController::class, 'exportExcel'])->name('lpj.excel');

    // Security
    Route::get('door-lock-history', \App\Livewire\DoorLockHistory::class)->name('door-lock-history')->middleware('can:door_lock.view');
});


Route::group(['middleware' => ['auth', 'role:anggota'], 'prefix' => 'anggota'], function () {
    Route::get('daftar-pertemuan', \App\Livewire\Anggota\DaftarPertemuan::class)->name('daftar-pertemuan');
    Route::get('konfirmasi/{partId}', \App\Livewire\Anggota\KerjakanSoal\Konfirmasi::class)->name('konfirmasi-soal');
    Route::get('mengerjakan/{partId}', \App\Livewire\Anggota\KerjakanSoal\Mengerjakan::class)->name('mengerjakan-soal');
    Route::get('hasil-soal', \App\Livewire\Anggota\HasilSoal::class)->name('hasil-soal');
    Route::get('riwayat-presensi', \App\Livewire\Anggota\RiwayatPresensi::class)->name('riwayat-presensi');
    Route::get('iuran-kas-saya', \App\Livewire\Anggota\IuranKasSaya::class)->name('iuran-kas-saya');
    Route::get('lihat-project', \App\Livewire\Anggota\LihatProject::class)->name('lihat-project');
    Route::get('statistik-kehadiran', \App\Livewire\Dashboard\AnggotaStatistikKehadiran::class)->name('anggota.statistik-kehadiran');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
