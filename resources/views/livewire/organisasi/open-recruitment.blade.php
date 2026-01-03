<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Open Recruitment</h1>
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
                        <h3>Tabel Open Recruitment - Pengurus</h3>
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
                                            <th class="tw-whitespace-nowrap">Divisi Dilamar</th>
                                            <th class="tw-whitespace-nowrap">Jabatan Dilamar</th>
                                            <th class="tw-whitespace-nowrap">Status Seleksi</th>
                                            <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dataPengurus as $row)
                                            <tr class="text-center">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td class="text-left">
                                                    <div class="tw-flex tw-items-center">
                                                        <div class="tw-w-10 tw-h-10 tw-mr-3">
                                                            <img src="{{ asset("assets/stisla/img/avatar/avatar-1.png") }}" alt="avatar" class="tw-rounded-full" />
                                                        </div>
                                                        <div>
                                                            <p>{{ $row->nama_lengkap }}</p>
                                                            <p class="tw-text-gray-500">{{ $row->kelas }}, {{ $row->jurusan }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-left tw-capitalize">{{ $row->nama_divisi }}</td>
                                                <td class="text-left tw-capitalize">{{ $row->nama_jabatan }}</td>
                                                <td class="text-left">
                                                    @if ($row->status_seleksi === "pending")
                                                        <span class="tw-bg-yellow-100 tw-text-yellow-600 tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full">
                                                            <i class="far fa-clock"></i>
                                                            Pending
                                                        </span>
                                                    @elseif ($row->status_seleksi === "lulus")
                                                        <span class="tw-bg-green-100 tw-text-green-600 tw-tracking-wider tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full">
                                                            <i class="far fa-badge-check"></i>
                                                            Lulus
                                                        </span>
                                                    @else
                                                        <span class="tw-bg-red-100 tw-text-red-600 tw-tracking-wider tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full">
                                                            <i class="far fa-times-circle"></i>
                                                            Gagal
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="tw-whitespace-nowrap">
                                                    <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewDataModal" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if ($row->status_seleksi !== "lulus")
                                                        <button wire:click.prevent="updateStatus({{ $row->id }}, 'lulus')" class="btn btn-success" title="Tandai Lulus">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif

                                                    @if ($row->status_seleksi !== "gagal")
                                                        <button wire:click.prevent="updateStatus({{ $row->id }}, 'gagal')" class="btn btn-danger" title="Tandai Gagal">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif

                                                    <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-secondary" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No data available in the table</td>
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
                        <h3>Tabel Open Recruitment - Anggota</h3>
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
                                            <th class="tw-whitespace-nowrap">Status Seleksi</th>
                                            <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($dataAnggota as $row)
                                            <tr class="text-center">
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td class="text-left">
                                                    <div class="tw-flex tw-items-center">
                                                        <div class="tw-w-10 tw-h-10 tw-mr-3">
                                                            <img src="{{ asset("assets/stisla/img/avatar/avatar-1.png") }}" alt="avatar" class="tw-rounded-full" />
                                                        </div>
                                                        <div>
                                                            <p>{{ $row->nama_lengkap }}</p>
                                                            <p class="tw-text-gray-500">{{ $row->kelas }}, {{ $row->jurusan }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-left">
                                                    @if ($row->status_seleksi === "pending")
                                                        <span class="tw-bg-yellow-100 tw-text-yellow-600 tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full">
                                                            <i class="far fa-clock"></i>
                                                            Pending
                                                        </span>
                                                    @elseif ($row->status_seleksi === "lulus")
                                                        <span class="tw-bg-green-100 tw-text-green-600 tw-tracking-wider tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full">
                                                            <i class="far fa-badge-check"></i>
                                                            Lulus
                                                        </span>
                                                    @else
                                                        <span class="tw-bg-red-100 tw-text-red-600 tw-tracking-wider tw-font-semibold tw-px-3 tw-py-1 tw-rounded-full">
                                                            <i class="far fa-times-circle"></i>
                                                            Gagal
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="tw-whitespace-nowrap">
                                                    <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewDataModal" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if ($row->status_seleksi !== "lulus")
                                                        <button wire:click.prevent="updateStatus({{ $row->id }}, 'lulus')" class="btn btn-success" title="Tandai Lulus">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif

                                                    @if ($row->status_seleksi !== "gagal")
                                                        <button wire:click.prevent="updateStatus({{ $row->id }}, 'gagal')" class="btn btn-danger" title="Tandai Gagal">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif

                                                    <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-secondary" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No data available in the table</td>
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
                        <div class="form-group">
                            <label for="jenis_oprec">Jenis Oprec</label>
                            <select wire:model.live="jenis_oprec" id="jenis_oprec" class="form-control select2">
                                <option value="" disabled>-- Opsi Pilihan --</option>
                                <option value="pengurus">Pengurus</option>
                                <option value="anggota">Anggota</option>
                            </select>
                            @error("jenis_oprec")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" wire:model="nama_lengkap" id="nama_lengkap" class="form-control" />
                                    @error("nama_lengkap")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
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
                        @if ($jenis_oprec == "pengurus")
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="id_divisi">Divisi Dilamar</label>
                                        <select wire:model="id_divisi" id="id_divisi" class="form-control select2">
                                            <option value="" disabled>-- Opsi Pilihan --</option>
                                            @foreach ($divisis as $divisi)
                                                <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                            @endforeach
                                        </select>
                                        @error("id_divisi")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="nama_jabatan">Jabatan Dilamar</label>
                                        <select wire:model="nama_jabatan" id="nama_jabatan" class="form-control select2">
                                            <option value="" disabled>-- Opsi Pilihan --</option>
                                            <option value="ketua">Ketua</option>
                                            <option value="wakil ketua">Wakil Ketua</option>
                                            <option value="kadiv">Kepala Divisi</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                        @error("nama_jabatan")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="motivasi">Motivasi</label>
                                    <textarea wire:model="motivasi" id="motivasi" class="form-control" style="height: 100px !important"></textarea>
                                    @error("motivasi")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="pengalaman">Pengalaman</label>
                                    <textarea wire:model="pengalaman" id="pengalaman" class="form-control" style="height: 100px !important"></textarea>
                                    @error("pengalaman")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_seleksi">Status Seleksi</label>
                            <select wire:model="status_seleksi" id="status_seleksi" class="form-control select2">
                                <option value="" disabled>-- Opsi Pilihan --</option>
                                <option value="pending">Pending</option>
                                <option value="lulus">Lulus</option>
                                <option value="gagal">Gagal</option>
                            </select>
                            @error("status_seleksi")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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

    <div class="modal fade" wire:ignore.self id="viewDataModal" aria-labelledby="viewDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-lg tw-border-0 tw-shadow-lg">
                <!-- Header -->
                <div class="modal-header tw-bg-white tw-border-b tw-border-gray-200">
                    <h5 class="modal-title tw-font-semibold tw-text-xl tw-text-gray-800" id="viewDataModalLabel">Detail Pendaftar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body tw-p-6">
                    @if ($viewData)
                        <!-- Profile Section -->
                        <div class="tw-flex tw-items-start tw-gap-6 tw-pb-6 tw-border-b tw-border-gray-200 tw-mb-6">
                            <div class="tw-flex-shrink-0">
                                <img src="{{ asset("assets/stisla/img/avatar/avatar-1.png") }}" alt="avatar" class="tw-w-24 tw-h-24 tw-rounded-full tw-border-2 tw-border-gray-200" />
                            </div>
                            <div class="tw-flex-1">
                                <h3 class="tw-text-2xl tw-font-semibold tw-text-gray-900 tw-mb-2">{{ $viewData->nama_lengkap ?? "" }}</h3>
                                <div class="tw-flex tw-flex-wrap tw-gap-3 tw-mb-3">
                                    <span class="tw-text-sm tw-text-gray-600">
                                        <i class="fas fa-chalkboard tw-mr-1"></i>
                                        {{ $viewData->kelas ?? "-" }}
                                    </span>
                                    <span class="tw-text-sm tw-text-gray-400">•</span>
                                    <span class="tw-text-sm tw-text-gray-600">
                                        <i class="fas fa-university tw-mr-1"></i>
                                        {{ $viewData->jurusan ?? "-" }}
                                    </span>
                                </div>
                                <div>
                                    @if ($viewData->status_seleksi === "pending")
                                        <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-md tw-text-sm tw-font-medium tw-bg-yellow-50 tw-text-yellow-700 tw-border tw-border-yellow-200">
                                            <i class="fas fa-clock tw-mr-2"></i>
                                            Menunggu Seleksi
                                        </span>
                                    @elseif ($viewData->status_seleksi === "lulus")
                                        <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-md tw-text-sm tw-font-medium tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-200">
                                            <i class="fas fa-check-circle tw-mr-2"></i>
                                            Lulus Seleksi
                                        </span>
                                    @else
                                        <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-md tw-text-sm tw-font-medium tw-bg-red-50 tw-text-red-700 tw-border tw-border-red-200">
                                            <i class="fas fa-times-circle tw-mr-2"></i>
                                            Tidak Lulus
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Information Grid -->
                        <div class="row tw-mb-6">
                            <!-- Data Akademik -->
                            <div class="col-lg-6 tw-mb-4">
                                <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-h-full">
                                    <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-uppercase tw-tracking-wide tw-mb-4">Data Akademik</h6>
                                    <div class="tw-space-y-3">
                                        <div class="tw-flex tw-justify-between tw-items-center">
                                            <span class="tw-text-sm tw-text-gray-600">Kelas</span>
                                            <span class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $viewData->kelas ?? "-" }}</span>
                                        </div>
                                        <div class="tw-h-px tw-bg-gray-200"></div>
                                        <div class="tw-flex tw-justify-between tw-items-center">
                                            <span class="tw-text-sm tw-text-gray-600">Jurusan</span>
                                            <span class="tw-text-sm tw-font-medium tw-text-gray-900 tw-text-right tw-max-w-xs">{{ $viewData->jurusan ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pendaftaran -->
                            <div class="col-lg-6 tw-mb-4">
                                <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-h-full">
                                    <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-uppercase tw-tracking-wide tw-mb-4">Data Pendaftaran</h6>
                                    <div class="tw-space-y-3">
                                        <div class="tw-flex tw-justify-between tw-items-center">
                                            <span class="tw-text-sm tw-text-gray-600">Jenis Oprec</span>
                                            <span class="tw-text-sm tw-font-medium tw-text-gray-900 tw-capitalize">{{ $viewData->jenis_oprec ?? "-" }}</span>
                                        </div>
                                        <div class="tw-h-px tw-bg-gray-200"></div>
                                        <div class="tw-flex tw-justify-between tw-items-center">
                                            <span class="tw-text-sm tw-text-gray-600">Tahun</span>
                                            <span class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $viewData->nama_tahun ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($viewData->jenis_oprec === "pengurus")
                            <!-- Posisi yang Dilamar -->
                            <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-mb-6">
                                <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-uppercase tw-tracking-wide tw-mb-4">Posisi yang Dilamar</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
                                            <span class="tw-text-sm tw-text-gray-600">Divisi</span>
                                            <span class="tw-text-sm tw-font-medium tw-text-gray-900">{{ $viewData->nama_divisi ?? "-" }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tw-flex tw-justify-between tw-items-center">
                                            <span class="tw-text-sm tw-text-gray-600">Jabatan</span>
                                            <span class="tw-text-sm tw-font-medium tw-text-gray-900 tw-capitalize">{{ $viewData->nama_jabatan ?? "-" }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($viewData->motivasi || $viewData->pengalaman)
                            <div class="row">
                                @if ($viewData->motivasi)
                                    <div class="col-lg-6 tw-mb-4">
                                        <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-h-full">
                                            <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-uppercase tw-tracking-wide tw-mb-3">Motivasi</h6>
                                            <p class="tw-text-sm tw-text-gray-700 tw-leading-relaxed">{{ $viewData->motivasi }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($viewData->pengalaman)
                                    <div class="col-lg-6 tw-mb-4">
                                        <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-h-full">
                                            <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-uppercase tw-tracking-wide tw-mb-3">Pengalaman</h6>
                                            <p class="tw-text-sm tw-text-gray-700 tw-leading-relaxed">{{ $viewData->pengalaman }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>

                <div class="modal-footer tw-bg-gray-50 tw-border-t tw-border-gray-200">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    <link href="{{ asset("assets/midragon/select2/select2.min.css") }}" rel="stylesheet" />
@endpush

@push("js-libraries")
    <script src="{{ asset("/assets/midragon/select2/select2.full.min.js") }}"></script>
@endpush

@push("scripts")
    <script>
        window.addEventListener('initSelect2', event => {
            $(document).ready(function() {
                $('.select2').select2();

                $('.select2').on('change', function(e) {
                    var id = $(this).attr('id');
                    var data = $(this).select2("val");
                    @this.set(id, data);
                });
            });
        })
    </script>
@endpush
