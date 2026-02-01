<?php

use App\Livewire\Galeri;
use App\Livewire\Welcome;
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
use App\Livewire\Akademik\ProgramPembelajaran;
use App\Livewire\Akademik\Pertemuan;
use App\Livewire\Akademik\PresensiPertemuan;

Route::get('/', Welcome::class);

Route::get('/cek-sertifikat', CekSertifikat::class)->middleware('guest');
Route::get('/kontak', KontakKami::class);
Route::get('/galeri', Galeri::class);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::post('/summernote/file/upload', [UploadController::class, 'uploadImageSummernote']);
    Route::post('/summernote/file/delete', [UploadController::class, 'deleteImageSummernote']);
});

Route::group(['middleware' => ['auth', 'role:pengurus']], function () {
    Route::get('tahun-kepengurusan', TahunKepengurusan::class)->name('tahun-kepengurusan');
    Route::get('department', Department::class)->name('department');
    Route::get('anggota', Anggota::class)->name('anggota');
    Route::get('profil-organisasi', ProfilOrganisasi::class)->name('profil-organisasi');
    Route::get('open-recruitment', OpenRecruitment::class)->name('open-recruitment');
    Route::get('control-user', ControlUser::class)->name('control-user');
    
    // Akademik Routes
    Route::get('program-pembelajaran', ProgramPembelajaran::class)->name('program-pembelajaran');
    Route::get('pertemuan', Pertemuan::class)->name('pertemuan');
    Route::get('projects', \App\Livewire\Akademik\Project::class)->name('projects');
    Route::get('presensi-kehadiran', PresensiPertemuan::class)->name('presensi-kehadiran');
    
    // Keuangan Routes
    Route::get('anggaran', \App\Livewire\Keuangan\Anggaran::class)->name('anggaran');
    Route::get('transaksi-keuangan', \App\Livewire\Keuangan\Transaksi::class)->name('transaksi-keuangan');
    Route::get('iuran-kas', \App\Livewire\Keuangan\IuranKas::class)->name('iuran-kas');
    Route::get('laporan-keuangan', \App\Livewire\Keuangan\Laporan::class)->name('laporan-keuangan');
    
    // Perlengkapan Routes
    Route::get('kategori-barang', \App\Livewire\Perlengkapan\KategoriBarang::class)->name('kategori-barang');
    Route::get('barang', \App\Livewire\Perlengkapan\Barang::class)->name('barang');
    Route::get('peminjaman-barang', \App\Livewire\Perlengkapan\PeminjamanBarang::class)->name('peminjaman-barang');
    Route::get('pengadaan-barang', \App\Livewire\Perlengkapan\PengadaanBarang::class)->name('pengadaan-barang');
    
    // LPJ Export
    Route::get('lpj/export-pdf', [\App\Http\Controllers\LpjExportController::class, 'exportPdf'])->name('lpj.pdf');
    Route::get('lpj/export-excel', [\App\Http\Controllers\LpjExportController::class, 'exportExcel'])->name('lpj.excel');
});

Route::group(['middleware' => ['auth', 'role:user']], function () {});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
