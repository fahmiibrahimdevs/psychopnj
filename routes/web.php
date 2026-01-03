<?php

use App\Livewire\Galeri;
use App\Livewire\Welcome;
use App\Livewire\KontakKami;
use App\Livewire\CekSertifikat;
use App\Livewire\Organisasi\Divisi;
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
    Route::get('divisi', Divisi::class)->name('divisi');
    Route::get('anggota', Anggota::class)->name('anggota');
    Route::get('profil-organisasi', ProfilOrganisasi::class)->name('profil-organisasi');
    Route::get('open-recruitment', OpenRecruitment::class)->name('open-recruitment');
    Route::get('control-user', ControlUser::class)->name('control-user');
    
    // Akademik Routes
    Route::get('program-pembelajaran', ProgramPembelajaran::class)->name('program-pembelajaran');
    Route::get('pertemuan', Pertemuan::class)->name('pertemuan');
    Route::get('presensi-kehadiran', PresensiPertemuan::class)->name('presensi-kehadiran');
});

Route::group(['middleware' => ['auth', 'role:user']], function () {});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
