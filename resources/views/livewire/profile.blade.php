<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Profile</h1>
        </div>

        <div class="section-body">
            <div class="tw-px-4 lg:tw-px-0">
                <h2 class="section-title">Pengaturan Profile</h2>
                <p class="section-lead">Perbarui informasi profile, biodata, dan password Anda.</p>
            </div>

            <div class="row tw-mt-4">
                <!-- Update Profile Information -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <h3>Informasi Akun</h3>
                        <form wire:submit.prevent="updateProfile">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        Nama Lengkap
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="name" class="form-control @error("name") is-invalid @enderror" placeholder="Masukkan nama lengkap" />
                                    @error("name")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" wire:model="email" class="form-control @error("email") is-invalid @enderror" placeholder="Masukkan email" />
                                    @error("email")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Info:</strong>
                                    Perubahan email akan mempengaruhi login Anda.
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="updateProfile">
                                    <span wire:loading.remove wire:target="updateProfile">
                                        <i class="fas fa-save"></i>
                                        Simpan Perubahan
                                    </span>
                                    <span wire:loading wire:target="updateProfile">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <h3>Ubah Password</h3>
                        <form wire:submit.prevent="updatePassword">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        Password Saat Ini
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" wire:model="current_password" class="form-control @error("current_password") is-invalid @enderror" placeholder="Masukkan password saat ini" />
                                    @error("current_password")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Password Baru
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" wire:model="password" class="form-control @error("password") is-invalid @enderror" placeholder="Masukkan password baru (min. 8 karakter)" />
                                    @error("password")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>
                                        Konfirmasi Password Baru
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" wire:model="password_confirmation" class="form-control" placeholder="Konfirmasi password baru" />
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Perhatian:</strong>
                                    Pastikan password baru Anda kuat dan mudah diingat.
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="updatePassword">
                                    <span wire:loading.remove wire:target="updatePassword">
                                        <i class="fas fa-key"></i>
                                        Update Password
                                    </span>
                                    <span wire:loading wire:target="updatePassword">
                                        <i class="fas fa-spinner fa-spin"></i>
                                        Memperbarui...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if ($anggota)
                <!-- Update Biodata Anggota -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <h3>Biodata Anggota</h3>
                            <form wire:submit.prevent="updateBiodata">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>
                                                            Nama Lengkap
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" wire:model="nama_lengkap" class="form-control @error("nama_lengkap") is-invalid @enderror" placeholder="Masukkan nama lengkap" />
                                                        @error("nama_lengkap")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nama Jabatan</label>
                                                        <input type="text" wire:model="nama_jabatan" class="form-control" placeholder="Contoh: Anggota, Ketua, dll" readonly />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>
                                                            Jurusan/Prodi/Kelas
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" wire:model="jurusan_prodi_kelas" class="form-control @error("jurusan_prodi_kelas") is-invalid @enderror" placeholder="Contoh: TI/S1/3A" />
                                                        @error("jurusan_prodi_kelas")
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>NIM</label>
                                                        <input type="text" wire:model="nim" class="form-control" placeholder="Masukkan NIM" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tempat, Tanggal Lahir</label>
                                                        <input type="text" wire:model="ttl" class="form-control" placeholder="Contoh: Jakarta, 01 Januari 2000" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>No. HP/WhatsApp</label>
                                                        <input type="text" wire:model="no_hp" class="form-control" placeholder="Contoh: 081234567890" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Alamat Lengkap</label>
                                                <textarea wire:model="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Foto Profil</label>
                                                <div class="text-center mb-3">
                                                    @if ($foto)
                                                        <img src="{{ $foto->temporaryUrl() }}" class="img-thumbnail" style="max-height: 200px; width: auto" />
                                                    @elseif ($foto_preview)
                                                        <img src="{{ $foto_preview }}" class="img-thumbnail" style="max-height: 200px; width: auto" />
                                                    @else
                                                        <div class="bg-light p-5 rounded">
                                                            <i class="fas fa-user fa-5x text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <input type="file" wire:model="foto" class="form-control @error("foto") is-invalid @enderror" accept="image/*" />
                                                @error("foto")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror

                                                <small class="form-text text-muted">
                                                    <i class="fas fa-info-circle"></i>
                                                    Format: JPG, PNG. Maksimal 2MB
                                                </small>
                                                <div wire:loading wire:target="foto" class="mt-2">
                                                    <small class="text-primary">
                                                        <i class="fas fa-spinner fa-spin"></i>
                                                        Memuat foto...
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="updateBiodata,foto">
                                        <span wire:loading.remove wire:target="updateBiodata">
                                            <i class="fas fa-save"></i>
                                            Simpan Biodata
                                        </span>
                                        <span wire:loading wire:target="updateBiodata">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            Menyimpan...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <h3>Informasi Lengkap</h3>
                        <div class="card-body tw-px-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Data Akun</h6>
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td class="font-weight-bold" style="width: 180px">Nama</td>
                                            <td>: {{ Auth::user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Email</td>
                                            <td>: {{ Auth::user()->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Role</td>
                                            <td>
                                                :
                                                @foreach (Auth::user()->roles as $role)
                                                    <span class="badge badge-primary">{{ ucwords(str_replace("_", " ", $role->name)) }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Terdaftar Sejak</td>
                                            <td>: {{ Auth::user()->created_at->format("d M Y") }}</td>
                                        </tr>
                                    </table>
                                </div>

                                @if ($anggota)
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-3">Data Anggota</h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="font-weight-bold" style="width: 180px">Tahun Kepengurusan</td>
                                                <td>: {{ $anggota->tahunKepengurusan->nama_tahun ?? "-" }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Department</td>
                                                <td>: {{ $anggota->department->nama_department ?? "Anggota Non-Pengurus" }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Status Anggota</td>
                                                <td>
                                                    :
                                                    <span class="badge badge-{{ $anggota->status_anggota == "pengurus" ? "success" : "info" }}">
                                                        {{ ucfirst($anggota->status_anggota) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Status Aktif</td>
                                                <td>
                                                    :
                                                    <span class="badge badge-{{ $anggota->status_aktif == "aktif" ? "success" : "danger" }}">
                                                        {{ ucfirst($anggota->status_aktif) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push("scripts")
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal:modal', (event) => {
                swal({
                    title: event.message,
                    text: event.text,
                    icon: event.type,
                    button: 'OK',
                });
            });
        });
    </script>
@endpush
