<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Anggota</h1>
        </div>

        <div class="section-body">
            <ul class="nav nav-pills" id="myTab3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "pengurus" ? "active" : "" }}" wire:click.prevent="switchTab('pengurus')" id="pengurus-tab3" data-toggle="tab" href="#pengurus" role="tab" aria-controls="pengurus" aria-selected="{{ $activeTab === "pengurus" ? "true" : "false" }}">Pengurus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "anggota" ? "active" : "" }}" wire:click.prevent="switchTab('anggota')" id="anggota-tab3" data-toggle="tab" href="#anggota" role="tab" aria-controls="anggota" aria-selected="{{ $activeTab === "anggota" ? "true" : "false" }}">Anggota</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent2">
                <div class="tab-pane fade {{ $activeTab === "pengurus" ? "show active" : "" }}" id="pengurus" role="tabpanel" aria-labelledby="pengurus-tab3">
                    <div class="card">
                        <h3>Tabel Pengurus</h3>
                        <div class="card-body">
                            <div class="show-entries">
                                <p class="show-entries-show">Show</p>
                                <select wire:model.live="lengthData" id="length-data">
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                </select>
                                <p class="show-entries-entries">Entries</p>
                            </div>
                            <div class="search-column">
                                <p>Search:</p>
                                <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Search here..." class="form-control" />
                            </div>
                            <div class="table-responsive">
                                <table class="tw-table-auto tw-w-full">
                                    <thead class="tw-sticky tw-top-0">
                                        <tr class="tw-text-gray-700">
                                            <th width="6%" class="text-center tw-whitespace-nowrap">No</th>
                                            <th class="tw-whitespace-nowrap">Nama Lengkap</th>
                                            <th class="tw-whitespace-nowrap">Department</th>
                                            <th class="tw-whitespace-nowrap">Jabatan</th>
                                            <th class="tw-whitespace-nowrap">Status</th>
                                            <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dataPengurus->groupBy("nama_tahun") as $result)
                                            <tr>
                                                <td class="tw-font-semibold tw-tracking-wider" colspan="10">Tahun Kepengurusan: {{ $result[0]->nama_tahun }}</td>
                                            </tr>

                                            @foreach ($result as $row)
                                                <tr class="text-center">
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td class="text-left">
                                                        <div class="tw-flex tw-items-center">
                                                            <img src="{{ asset($row->foto == "" ? "assets/stisla/img/avatar/avatar-1.png" : $row->foto) }}" alt="{{ $row->nama_lengkap }}" class="tw-w-10 tw-h-10 tw-rounded-full tw-object-cover" />
                                                            <div class="tw-ml-2">
                                                                <span>
                                                                    {{ $row->nama_lengkap }}
                                                                    @if ($row->status_aktif == "aktif")
                                                                        <i class="fas fa-check text-success"></i>
                                                                    @elseif ($row->status_aktif == "nonaktif")
                                                                        <i class="fas fa-times text-danger"></i>
                                                                    @elseif ($row->status_aktif == "diberhentikan")
                                                                        <i class="fas fa-ban text-danger"></i>
                                                                    @elseif ($row->status_aktif == "mengundurkan diri")
                                                                        <i class="fas fa-sign-out-alt text-warning"></i>
                                                                    @endif
                                                                </span>
                                                                <p class="tw-text-gray-400 tw-whitespace-nowrap">{{ $row->kelas }}, {{ $row->jurusan }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-left tw-whitespace-nowrap">{{ $row->nama_department ?: "-" }}</td>
                                                    <td class="text-left tw-whitespace-nowrap tw-capitalize">{{ $row->nama_jabatan }}</td>
                                                    <td class="text-left tw-capitalize">{{ $row->status_aktif }}</td>
                                                    <td class="tw-whitespace-nowrap">
                                                        <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewDataModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">No data available in the table</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-5 px-3">
                                {{ $dataPengurus->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{ $activeTab === "anggota" ? "show active" : "" }}" id="anggota" role="tabpanel" aria-labelledby="anggota-tab3">
                    <div class="card">
                        <h3>Tabel Anggota</h3>
                        <div class="card-body">
                            <div class="show-entries">
                                <p class="show-entries-show">Show</p>
                                <select wire:model.live="lengthData" id="length-data">
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="250">250</option>
                                    <option value="500">500</option>
                                </select>
                                <p class="show-entries-entries">Entries</p>
                            </div>
                            <div class="search-column">
                                <p>Search:</p>
                                <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Search here..." class="form-control" />
                            </div>
                            <div class="table-responsive">
                                <table class="tw-table-auto tw-w-full">
                                    <thead class="tw-sticky tw-top-0">
                                        <tr class="tw-text-gray-700">
                                            <th width="6%" class="text-center tw-whitespace-nowrap">No</th>
                                            <th class="tw-whitespace-nowrap">Nama Lengkap</th>
                                            <th class="tw-whitespace-nowrap">Department</th>
                                            <th class="tw-whitespace-nowrap">Jabatan</th>
                                            <th class="tw-whitespace-nowrap">Status</th>
                                            <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dataAnggota->groupBy("nama_tahun") as $result)
                                            <tr>
                                                <td class="tw-font-semibold tw-tracking-wider" colspan="10">Tahun Kepengurusan: {{ $result[0]->nama_tahun }}</td>
                                            </tr>

                                            @foreach ($result as $row)
                                                <tr class="text-center">
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td class="text-left">
                                                        <div class="tw-flex tw-items-center">
                                                            <img src="{{ asset($row->foto == "" ? "assets/stisla/img/avatar/avatar-1.png" : $row->foto) }}" alt="{{ $row->nama_lengkap }}" class="tw-w-10 tw-h-10 tw-rounded-full tw-object-cover" />
                                                            <div class="tw-ml-2">
                                                                <span>
                                                                    {{ $row->nama_lengkap }}
                                                                    @if ($row->status_aktif == "aktif")
                                                                        <i class="fas fa-check text-success"></i>
                                                                    @elseif ($row->status_aktif == "nonaktif")
                                                                        <i class="fas fa-times text-danger"></i>
                                                                    @elseif ($row->status_aktif == "diberhentikan")
                                                                        <i class="fas fa-ban text-danger"></i>
                                                                    @elseif ($row->status_aktif == "mengundurkan diri")
                                                                        <i class="fas fa-sign-out-alt text-warning"></i>
                                                                    @endif
                                                                </span>
                                                                <p class="tw-text-gray-400 tw-whitespace-nowrap">{{ $row->kelas }}, {{ $row->jurusan }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-left tw-whitespace-nowrap">{{ $row->nama_department ?: "-" }}</td>
                                                    <td class="text-left tw-whitespace-nowrap tw-capitalize">{{ $row->nama_jabatan }}</td>
                                                    <td class="text-left tw-capitalize">{{ $row->status_aktif }}</td>
                                                    <td class="tw-whitespace-nowrap">
                                                        <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewDataModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">No data available in the table</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-5 px-3">
                                {{ $dataAnggota->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
            <i class="far fa-plus"></i>
        </button>
    </section>

    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Data" : "Add Data" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <!-- Status Keanggotaan di Paling Atas -->
                        <div class="form-group">
                            <label for="status_anggota">Status Keanggotaan</label>
                            <select wire:model.live="status_anggota" id="status_anggota" class="form-control">
                                <option value="" disabled>-- Opsi Pilihan --</option>
                                <option value="pengurus">Pengurus</option>
                                <option value="anggota">Anggota</option>
                            </select>
                            @error("status_anggota")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nav Tabs -->
                        <ul class="nav nav-pills tw-mb-4" id="modalTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="biodata-tab" data-toggle="tab" href="#biodata" role="tab">
                                    <i class="fas fa-user tw-mr-1"></i>
                                    Biodata
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="akun-tab" data-toggle="tab" href="#akun" role="tab">
                                    <i class="fas fa-key tw-mr-1"></i>
                                    Pembuatan Akun
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Tab Biodata -->
                            <div class="tab-pane fade show active" id="biodata" role="tabpanel">
                                @if ($status_anggota == "pengurus")
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="id_department">Department</label>
                                                <select wire:model="id_department" id="id_department" class="form-control">
                                                    <option value="" disabled>-- Opsi Pilihan --</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">{{ $department->nama_department }}</option>
                                                    @endforeach
                                                </select>
                                                @error("id_department")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="nama_jabatan">Nama Jabatan</label>
                                                <select wire:model="nama_jabatan" id="nama_jabatan" class="form-control">
                                                    <option value="" disabled>-- Opsi Pilihan --</option>
                                                    <option value="Ketua">Ketua</option>
                                                    <option value="Wakil Ketua">Wakil Ketua</option>
                                                    <option value="Kadiv">Kadiv</option>
                                                    <option value="Staff">Staff</option>
                                                </select>
                                                @error("nama_jabatan")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="nama_lengkap">Nama Lengkap</label>
                                            <input type="text" wire:model="nama_lengkap" id="nama_lengkap" class="form-control" />
                                            @error("nama_lengkap")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="kelas">Kelas</label>
                                            <input type="text" wire:model="kelas" id="kelas" class="form-control" />
                                            @error("kelas")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jurusan">Jurusan</label>
                                    <select wire:model="jurusan" id="jurusan" class="form-control">
                                        <option value="" disabled>-- Opsi Pilihan --</option>
                                        <option value="Teknik Sipil">Teknik Sipil</option>
                                        <option value="Teknik Mesin">Teknik Mesin</option>
                                        <option value="Teknik Elektro">Teknik Elektro</option>
                                        <option value="Akuntansi">Akuntansi</option>
                                        <option value="Administrasi Niaga">Administrasi Niaga</option>
                                        <option value="Teknik Grafika Penerbitan">Teknik Grafika Penerbitan</option>
                                        <option value="Teknik Informatika dan Komputer">Teknik Informatika dan Komputer</option>
                                    </select>
                                    @error("jurusan")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="nim">NIM</label>
                                            <input type="text" wire:model="nim" id="nim" class="form-control" />
                                            @error("nim")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="no_hp">No. Hp/Wa</label>
                                            <input type="text" wire:model="no_hp" id="no_hp" class="form-control" />
                                            @error("no_hp")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status_aktif">Status Aktif</label>
                                    <select wire:model="status_aktif" id="status_aktif" class="form-control">
                                        <option value="" disabled>-- Opsi Pilihan --</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                        <option value="diberhentikan">Diberhentikan</option>
                                        <option value="mengundurkan diri">Mengundurkan Diri</option>
                                    </select>
                                    @error("status_aktif")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="foto">Foto</label>
                                    <input type="text" wire:model="foto" id="foto" class="form-control" />
                                    @error("foto")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="motivasi">Motivasi (Opsional)</label>
                                            <textarea wire:model="motivasi" id="motivasi" class="form-control" rows="4" placeholder="Masukkan motivasi..."></textarea>
                                            @error("motivasi")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="pengalaman">Pengalaman (Opsional)</label>
                                            <textarea wire:model="pengalaman" id="pengalaman" class="form-control" rows="4" placeholder="Masukkan pengalaman..."></textarea>
                                            @error("pengalaman")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Pembuatan Akun -->
                            <div class="tab-pane fade" id="akun" role="tabpanel">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" wire:model="email" id="email" class="form-control" />
                                    @error("email")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="password">Password {{ $isEditing ? "(Kosongkan jika tidak ingin mengubah)" : "" }}</label>
                                            <input type="password" wire:model="password" id="password" class="form-control" />
                                            @error("password")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Konfirmasi Password</label>
                                            <input type="password" wire:model="password_confirmation" id="password_confirmation" class="form-control" />
                                            @error("password_confirmation")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Data Modal -->
    <div class="modal fade" wire:ignore.self id="viewDataModal" aria-labelledby="viewDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="viewDataModalLabel">Detail Anggota</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-6">
                    @if ($viewData)
                        <div class="tw-flex tw-flex-col tw-items-center tw-mb-6">
                            <img src="{{ asset($viewData->foto == "" ? "assets/stisla/img/avatar/avatar-1.png" : $viewData->foto) }}" alt="{{ $viewData->nama_lengkap }}" class="tw-w-32 tw-h-32 tw-rounded-full tw-object-cover tw-border-4 tw-border-gray-200 tw-shadow-lg" />
                            <h3 class="tw-mt-4 tw-text-2xl tw-font-bold tw-text-gray-800">{{ $viewData->nama_lengkap }}</h3>
                            <p class="tw-text-gray-500 tw-text-sm">{{ $viewData->nama_jabatan }} - {{ $viewData->nama_department ?: "-" }}</p>
                            <div class="tw-mt-2">
                                @if ($viewData->status_aktif == "aktif")
                                    <span class="badge badge-success tw-px-3 tw-py-1">
                                        <i class="fas fa-check"></i>
                                        Aktif
                                    </span>
                                @elseif ($viewData->status_aktif == "nonaktif")
                                    <span class="badge badge-secondary tw-px-3 tw-py-1">
                                        <i class="fas fa-times"></i>
                                        Nonaktif
                                    </span>
                                @elseif ($viewData->status_aktif == "diberhentikan")
                                    <span class="badge badge-danger tw-px-3 tw-py-1">
                                        <i class="fas fa-ban"></i>
                                        Diberhentikan
                                    </span>
                                @else
                                    <span class="badge badge-warning tw-px-3 tw-py-1">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Mengundurkan Diri
                                    </span>
                                @endif
                                <span class="badge badge-info tw-px-3 tw-py-1 tw-ml-1 tw-capitalize">
                                    {{ $viewData->status_anggota }}
                                </span>
                            </div>
                        </div>

                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                            <!-- Informasi Pribadi -->
                            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                                <h6 class="tw-font-bold tw-text-gray-700 tw-mb-3 tw-border-b tw-pb-2">
                                    <i class="fas fa-user tw-mr-2"></i>
                                    Informasi Pribadi
                                </h6>
                                <div class="tw-space-y-2">
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Kelas:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->kelas }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Jurusan:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->jurusan }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">NIM:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->nim ?: "-" }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Kontak -->
                            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                                <h6 class="tw-font-bold tw-text-gray-700 tw-mb-3 tw-border-b tw-pb-2">
                                    <i class="fas fa-address-book tw-mr-2"></i>
                                    Informasi Kontak
                                </h6>
                                <div class="tw-space-y-2">
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Email:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->email ?: "-" }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">No. HP:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->no_hp ?: "-" }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Organisasi -->
                            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                                <h6 class="tw-font-bold tw-text-gray-700 tw-mb-3 tw-border-b tw-pb-2">
                                    <i class="fas fa-sitemap tw-mr-2"></i>
                                    Informasi Organisasi
                                </h6>
                                <div class="tw-space-y-2">
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Department:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->nama_department ?: "-" }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Jabatan:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->nama_jabatan }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Status:</span>
                                        <span class="tw-text-gray-800 tw-capitalize">{{ $viewData->status_anggota }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Kepengurusan -->
                            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                                <h6 class="tw-font-bold tw-text-gray-700 tw-mb-3 tw-border-b tw-pb-2">
                                    <i class="fas fa-calendar-alt tw-mr-2"></i>
                                    Periode Kepengurusan
                                </h6>
                                <div class="tw-space-y-2">
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Tahun:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->nama_tahun }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Periode:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->mulai }} - {{ $viewData->akhir }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Status:</span>
                                        <span class="tw-text-gray-800 tw-capitalize">{{ $viewData->status_aktif }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($viewData->motivasi || $viewData->pengalaman)
                            <div class="tw-mt-4">
                                <div class="row">
                                    @if ($viewData->motivasi)
                                        <div class="col-lg-6">
                                            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                                                <h6 class="tw-font-bold tw-text-gray-700 tw-mb-3 tw-border-b tw-pb-2">
                                                    <i class="fas fa-heart tw-mr-2 tw-text-red-500"></i>
                                                    Motivasi
                                                </h6>
                                                <p class="tw-text-gray-700 tw-text-sm tw-leading-relaxed">{{ $viewData->motivasi }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($viewData->pengalaman)
                                        <div class="col-lg-6">
                                            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
                                                <h6 class="tw-font-bold tw-text-gray-700 tw-mb-3 tw-border-b tw-pb-2">
                                                    <i class="fas fa-star tw-mr-2 tw-text-yellow-500"></i>
                                                    Pengalaman
                                                </h6>
                                                <p class="tw-text-gray-700 tw-text-sm tw-leading-relaxed">{{ $viewData->pengalaman }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($viewData->created_at)
                            <div class="tw-mt-4 tw-pt-4 tw-border-t tw-text-center">
                                <p class="tw-text-sm tw-text-gray-500">
                                    <i class="fas fa-clock tw-mr-1"></i>
                                    Terdaftar sejak: {{ \Carbon\Carbon::parse($viewData->created_at)->format("d F Y, H:i") }} WIB
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    
@endpush

@push("js-libraries")
    
@endpush

@push("scripts")
    
@endpush
