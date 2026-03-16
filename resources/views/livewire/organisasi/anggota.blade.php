<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Anggota</h1>
        </div>

        <div class="section-body">
            <ul class="nav nav-pills tw-my-3" id="myTab3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "pengurus" ? "active" : "" }}" wire:click.prevent="switchTab('pengurus')" id="pengurus-tab3" data-toggle="tab" href="#pengurus" role="tab" aria-controls="pengurus" aria-selected="{{ $activeTab === "pengurus" ? "true" : "false" }}">Pengurus ({{ $countPengurus }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "anggota" ? "active" : "" }}" wire:click.prevent="switchTab('anggota')" id="anggota-tab3" data-toggle="tab" href="#anggota" role="tab" aria-controls="anggota" aria-selected="{{ $activeTab === "anggota" ? "true" : "false" }}">Anggota ({{ $countAnggota }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "statistik" ? "active" : "" }}" wire:click.prevent="switchTab('statistik')" id="statistik-tab3" data-toggle="tab" href="#statistik" role="tab" aria-controls="statistik" aria-selected="{{ $activeTab === "statistik" ? "true" : "false" }}">Statistik Kelas</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent2">
                <div class="tab-pane fade {{ $activeTab === "statistik" ? "show active" : "" }}" id="statistik" role="tabpanel" aria-labelledby="statistik-tab3">
                    <div class="card">
                        <h3><i class="fas fa-chart-pie"></i> Statistik Kelas Berdasarkan Jurusan/Prodi/Kelas</h3>
                        <div class="card-body tw-px-4 lg:tw-px-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-center mb-4 font-bagus tw-text-sm">Pengurus ({{ $countPengurus }})</h5>
                                    @if($statistikPengurus->count() > 0)
                                        <div class="row">
                                            <div class="col-md-6 mb-3 d-flex justify-content-center" wire:ignore>
                                                <div style="width: 100%; max-width: 300px;">
                                                    <canvas id="chartPengurus" data-chart='@json($statistikPengurus)'></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="tw-space-y-2" style="max-height: 400px; overflow-y: auto;">
                                                    @foreach($statistikPengurus as $index => $stat)
                                                        <div class="tw-flex tw-justify-between tw-items-center tw-text-sm tw-border-b tw-pb-1">
                                                            <div class="tw-flex tw-items-center" style="flex: 1; min-width: 0;">
                                                                <div class="tw-w-3 tw-h-3 tw-rounded-full tw-mr-2 tw-flex-shrink-0" style="background-color: {{ ['#3b82f6', '#22c55e', '#f97316', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f59e0b', '#06b6d4', '#84cc16', '#f43f5e', '#a855f7'][$index % 12] }}"></div>
                                                                <span class="tw-truncate" title="{{ $stat['jurusan_prodi_kelas'] }}">{{ $stat['jurusan_prodi_kelas'] }}</span>
                                                            </div>
                                                            <span class="tw-font-semibold tw-ml-2 tw-whitespace-nowrap">{{ $stat['total'] }} ({{ $stat['persentase'] }}%)</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">Belum ada data</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-center mb-3">Anggota ({{ $countAnggota }})</h5>
                                    @if($statistikAnggota->count() > 0)
                                        <div class="row">
                                            <div class="col-md-6 mb-3 d-flex justify-content-center" wire:ignore>
                                                <div style="width: 100%; max-width: 300px;">
                                                    <canvas id="chartAnggota" data-chart='@json($statistikAnggota)'></canvas>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="tw-space-y-2" style="max-height: 400px; overflow-y: auto;">
                                                    @foreach($statistikAnggota as $index => $stat)
                                                        <div class="tw-flex tw-justify-between tw-items-center tw-text-sm tw-border-b tw-pb-1">
                                                            <div class="tw-flex tw-items-center" style="flex: 1; min-width: 0;">
                                                                <div class="tw-w-3 tw-h-3 tw-rounded-full tw-mr-2 tw-flex-shrink-0" style="background-color: {{ ['#3b82f6', '#22c55e', '#f97316', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f59e0b', '#06b6d4', '#84cc16', '#f43f5e', '#a855f7'][$index % 12] }}"></div>
                                                                <span class="tw-truncate" title="{{ $stat['jurusan_prodi_kelas'] }}">{{ $stat['jurusan_prodi_kelas'] }}</span>
                                                            </div>
                                                            <span class="tw-font-semibold tw-ml-2 tw-whitespace-nowrap">{{ $stat['total'] }} ({{ $stat['persentase'] }}%)</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">Belum ada data</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        @php
                                            $counter = 1;
                                        @endphp

                                        @forelse ($dataPengurus as $departmentName => $anggotaList)
                                            <tr>
                                                <td class="tw-font-semibold tw-tracking-wider tw-bg-gray-100" colspan="6">Department: {{ $departmentName ?: "Tidak Ada Department" }}</td>
                                            </tr>

                                            @foreach ($anggotaList as $row)
                                                <tr class="text-center">
                                                    <td>{{ $counter++ }}</td>
                                                    <td class="text-left">
                                                        <div class="tw-flex tw-items-center">
                                                            <img src="{{ $row->foto ? storageUrl($row->foto) : asset('assets/stisla/img/avatar/avatar-1.png') }}" alt="{{ $row->nama_lengkap }}" class="tw-w-10 tw-h-10 tw-rounded-full tw-object-cover" />
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
                                                                <p class="tw-text-gray-400 tw-whitespace-nowrap">{{ $row->jurusan_prodi_kelas }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-left tw-whitespace-nowrap">{{ $row->nama_department ?: "-" }}</td>
                                                    <td class="text-left tw-whitespace-nowrap tw-capitalize">{{ $row->nama_jabatan }}</td>
                                                    <td class="text-left tw-capitalize">{{ $row->status_aktif }}</td>
                                                        <td class="tw-whitespace-nowrap">
                                                            @if($this->can('anggota.view_detail'))
                                                            <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewDataModal">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @endif
                                                            @if($this->can('anggota.edit'))
                                                            <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            @endif
                                                            @if($this->can('anggota.delete'))
                                                            <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            @endif
                                                        </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No data available in the table</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($dataPengurus->flatten()->count() < $countPengurus)
                                <div class="text-center mt-3">
                                    <button wire:click="loadMorePengurus" class="btn btn-primary">
                                        <i class="fas fa-chevron-down"></i>
                                        Load More
                                    </button>
                                </div>
                            @endif
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
                                        @php
                                            $counter = 1;
                                        @endphp

                                        @forelse ($dataAnggota as $jurusanProdiKelas => $anggotaList)
                                            <tr>
                                                <td class="tw-font-semibold tw-tracking-wider tw-bg-gray-100" colspan="6">Jurusan/Prodi/Kelas: {{ $jurusanProdiKelas ?: "Tidak Ada Data" }} ({{ $anggotaList->count() }})</td>
                                            </tr>

                                            @foreach ($anggotaList as $row)
                                                <tr class="text-center">
                                                    <td>{{ $counter++ }}</td>
                                                    <td class="text-left">
                                                        <div class="tw-flex tw-items-center">
                                                            <img src="{{ $row->foto ? storageUrl($row->foto) : asset('assets/stisla/img/avatar/avatar-1.png') }}" alt="{{ $row->nama_lengkap }}" class="tw-w-10 tw-h-10 tw-rounded-full tw-object-cover" />
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
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-left tw-whitespace-nowrap">{{ $row->nama_department ?: "-" }}</td>
                                                    <td class="text-left tw-whitespace-nowrap tw-capitalize">{{ $row->nama_jabatan }}</td>
                                                    <td class="text-left tw-capitalize">{{ $row->status_aktif }}</td>
                                                        <td class="tw-whitespace-nowrap">
                                                            @if($this->can('anggota.view_detail'))
                                                            <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewDataModal">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            @endif
                                                            @if($this->can('anggota.edit'))
                                                            <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            @endif
                                                            @if($this->can('anggota.delete'))
                                                            <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            @endif
                                                        </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No data available in the table</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($dataAnggota->flatten()->count() < $countAnggota)
                                <div class="text-center mt-3">
                                    <button wire:click="loadMoreAnggota" class="btn btn-primary">
                                        <i class="fas fa-chevron-down"></i>
                                        Load More
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($this->can('anggota.create'))
        <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
            <i class="far fa-plus"></i>
        </button>
        @endif
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

                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap</label>
                                    <input type="text" wire:model="nama_lengkap" id="nama_lengkap" class="form-control" />
                                    @error("nama_lengkap")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jurusan_prodi_kelas">Jurusan/Prodi/Kelas</label>
                                    <input type="text" wire:model="jurusan_prodi_kelas" id="jurusan_prodi_kelas" class="form-control" placeholder="Contoh: TE/EC/4D" />
                                    <small class="form-text text-muted">Format: Jurusan/Prodi/Kelas (Contoh: TE/EC/4D)</small>
                                    @error("jurusan_prodi_kelas")
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
                                            <label for="ttl">Tempat, Tanggal Lahir</label>
                                            <input type="text" wire:model="ttl" id="ttl" class="form-control" placeholder="Contoh: Jakarta, 01 Januari 2000" />
                                            @error("ttl")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea wire:model="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..."></textarea>
                                    @error("alamat")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" wire:model="email" id="email" class="form-control" />
                                            @error("email")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="no_hp">No. Hp/Wa</label>
                                            <input type="text" wire:model="no_hp" id="no_hp" class="form-control" placeholder="Contoh: 08123456789" />
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
                            </div>

                            <!-- Tab Pembuatan Akun -->
                            <div class="tab-pane fade" id="akun" role="tabpanel">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select wire:model="role" id="role" class="form-control" {{ $status_anggota == 'anggota' ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Role --</option>
                                        @foreach($roles as $r)
                                            <option value="{{ $r }}">{{ $r }}</option>
                                        @endforeach
                                    </select>
                                    @error("role")
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
                                
                                <hr class="my-4">
                                
                                <!-- RFID Card Registration -->
                                <div class="form-group">
                                    <label for="rfid_card">Kartu RFID</label>
                                    <div class="input-group">
                                        <input type="text" wire:model="rfid_card" id="rfid_card" class="form-control" placeholder="Scan kartu RFID..." readonly />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mqttConfigModal">
                                                <i class="fas fa-wifi"></i> Connect MQTT
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Klik tombol "Connect MQTT" untuk mendaftarkan kartu RFID</small>
                                    @error("rfid_card")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
    
    <!-- MQTT Configuration Modal -->
    <div class="modal fade" id="mqttConfigModal" tabindex="-1" role="dialog" aria-labelledby="mqttConfigModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mqttConfigModalLabel">
                        <i class="fas fa-network-wired"></i> Konfigurasi MQTT untuk Registrasi RFID
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mqtt-connection-form">
                        <div class="form-group">
                            <label for="mqtt_hostname">Hostname / IP Broker</label>
                            <input type="text" id="mqtt_hostname" class="form-control" placeholder="psychoroboticpnj.tech" value="psychoroboticpnj.tech">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mqtt_protocol">Protokol</label>
                                    <select id="mqtt_protocol" class="form-control">
                                        <option value="wss">WSS (SSL/TLS)</option>
                                        <option value="ws">WS (Non-SSL)</option>
                                    </select>
                                    <small class="text-muted">Gunakan WSS jika website HTTPS</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mqtt_port">Port</label>
                                    <input type="number" id="mqtt_port" class="form-control" placeholder="443" value="443">
                                    <small class="text-muted">WSS: 443 | WS: 80</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mqtt_username">Username</label>
                                    <input type="text" id="mqtt_username" class="form-control" placeholder="username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mqtt_password">Password</label>
                                    <input type="password" id="mqtt_password" class="form-control" placeholder="password">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="mqtt_save_credentials">
                                <i class="fas fa-save"></i> Save Credential
                            </button>
                            <button type="button" class="btn btn-success" id="mqtt_connect_btn">
                                <i class="fas fa-plug"></i> Connect
                            </button>
                        </div>
                    </div>
                    
                    <div id="mqtt-connected-status" style="display: none;">
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong>Connected!</strong> MQTT Broker terhubung
                        </div>
                        <div class="form-group">
                            <label for="rfid_card_input">RFID Card UID</label>
                            <input type="text" id="rfid_card_input" class="form-control bg-light" readonly placeholder="Menunggu scan kartu...">
                            <small class="text-muted">Tempelkan kartu RFID pada reader untuk mendaftar</small>
                        </div>
                        <button type="button" class="btn btn-danger btn-block" id="mqtt_disconnect_btn">
                            <i class="fas fa-times"></i> Disconnect
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Data Modal -->
    <div class="modal fade" wire:ignore.self id="viewDataModal" aria-labelledby="viewDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content tw-border-0 tw-shadow-2xl tw-rounded-3xl tw-overflow-hidden">
                <!-- Close Button -->
                <div class="tw-absolute tw-top-4 tw-right-4 tw-z-10">
                    <button type="button" class="tw-bg-white tw-bg-opacity-90 tw-backdrop-blur-sm tw-rounded-full tw-w-10 tw-h-10 tw-flex tw-items-center tw-justify-center tw-shadow-md hover:tw-shadow-lg tw-transition-all hover:tw-scale-110" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times tw-text-gray-700"></i>
                    </button>
                </div>

                @if ($viewData)
                    <!-- Header Section -->
                    <div class="tw-bg-gradient-to-br tw-from-indigo-600 tw-to-indigo-700 tw-px-8 tw-pt-10 tw-pb-8">
                        <!-- Organization -->
                        <div class="tw-text-center tw-mb-6">
                            <h2 class="tw-text-white tw-font-bold tw-text-xl tw-tracking-widest tw-mb-1">PSYCHOROBOTIC</h2>
                            <div class="tw-inline-block tw-bg-white tw-bg-opacity-20 tw-backdrop-blur-sm tw-px-4 tw-py-1 tw-rounded-full">
                                <p class="tw-text-white tw-text-xs tw-font-medium tw-tracking-wide">MEMBER CARD</p>
                            </div>
                        </div>

                        <!-- Photo & Name -->
                        <div class="tw-text-center">
                            <div class="tw-inline-block tw-relative tw-mb-5">
                                <img src="{{ $viewData->foto ? storageUrl($viewData->foto) : asset('assets/stisla/img/avatar/avatar-1.png') }}" 
                                     alt="{{ $viewData->nama_lengkap }}" 
                                     class="tw-w-32 tw-h-32 tw-rounded-full tw-object-cover tw-border-4 tw-border-white tw-shadow-xl" />
                                @if ($viewData->status_aktif == "aktif")
                                    <div class="tw-absolute tw-bottom-0 tw-right-0 tw-bg-green-500 tw-w-9 tw-h-9 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-border-4 tw-border-white tw-shadow-lg">
                                        <i class="fas fa-check tw-text-white tw-text-sm"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <h3 class="tw-text-white tw-text-2xl tw-font-bold tw-mb-2">{{ $viewData->nama_lengkap }}</h3>
                            <p class="tw-text-white tw-text-sm tw-font-semibold tw-mb-1">{{ $viewData->nama_jabatan }}</p>
                            @if($viewData->nama_department)
                                <p class="tw-text-indigo-200 tw-text-sm">{{ $viewData->nama_department }}</p>
                            @endif
                            
                            <div class="tw-flex tw-justify-center tw-gap-2 tw-mt-4">
                                <span class="tw-bg-white tw-bg-opacity-20 tw-backdrop-blur-sm tw-px-4 tw-py-1.5 tw-rounded-full tw-text-white tw-text-xs tw-font-semibold tw-capitalize">
                                    {{ $viewData->status_anggota }}
                                </span>
                                <span class="tw-bg-white tw-bg-opacity-20 tw-backdrop-blur-sm tw-px-4 tw-py-1.5 tw-rounded-full tw-text-white tw-text-xs tw-font-semibold">
                                    {{ $viewData->nama_tahun }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="tw-bg-white tw-p-6">
                        <!-- Info Cards -->
                        <div class="tw-space-y-3">
                            <!-- Jurusan -->
                            <div class="tw-border tw-border-gray-200 tw-rounded-xl tw-p-4">
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <div class="tw-w-10 tw-h-10 tw-bg-indigo-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                                        <i class="fas fa-graduation-cap tw-text-indigo-600"></i>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-xs tw-text-gray-500 tw-font-medium tw-mb-1">Jurusan/Prodi/Kelas</p>
                                        <p class="tw-text-sm tw-font-semibold tw-text-gray-900 tw-leading-tight">{{ $viewData->jurusan_prodi_kelas }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- NIM & Phone Row -->
                            <div class="tw-grid tw-grid-cols-2 tw-gap-3">
                                <div class="tw-border tw-border-gray-200 tw-rounded-xl tw-p-4">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                                        <div class="tw-w-8 tw-h-8 tw-bg-gray-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-id-card tw-text-gray-600 tw-text-xs"></i>
                                        </div>
                                        <p class="tw-text-xs tw-text-gray-500 tw-font-medium">NIM</p>
                                    </div>
                                    <p class="tw-text-sm tw-font-semibold tw-text-gray-900">{{ $viewData->nim ?: "-" }}</p>
                                </div>

                                <div class="tw-border tw-border-gray-200 tw-rounded-xl tw-p-4">
                                    <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                                        <div class="tw-w-8 tw-h-8 tw-bg-gray-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                            <i class="fas fa-phone tw-text-gray-600 tw-text-xs"></i>
                                        </div>
                                        <p class="tw-text-xs tw-text-gray-500 tw-font-medium">Phone</p>
                                    </div>
                                    <p class="tw-text-sm tw-font-semibold tw-text-gray-900 tw-truncate">{{ $viewData->no_hp ?: "-" }}</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="tw-border tw-border-gray-200 tw-rounded-xl tw-p-4">
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <div class="tw-w-10 tw-h-10 tw-bg-gray-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                                        <i class="fas fa-envelope tw-text-gray-600"></i>
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <p class="tw-text-xs tw-text-gray-500 tw-font-medium tw-mb-1">Email</p>
                                        <p class="tw-text-sm tw-font-semibold tw-text-gray-900 tw-break-all tw-leading-tight">{{ $viewData->email ?: "-" }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            @if($viewData->ttl || $viewData->alamat)
                                <details class="tw-group tw-border tw-border-gray-200 tw-rounded-xl tw-overflow-hidden">
                                    <summary class="tw-cursor-pointer tw-px-4 tw-py-3 tw-bg-gray-50 hover:tw-bg-gray-100 tw-transition-colors tw-flex tw-items-center tw-justify-between">
                                        <span class="tw-text-sm tw-font-semibold tw-text-gray-700">
                                            <i class="fas fa-info-circle tw-mr-2 tw-text-gray-400"></i>Informasi Lainnya
                                        </span>
                                        <i class="fas fa-chevron-down tw-text-xs tw-text-gray-400 group-open:tw-rotate-180 tw-transition-transform"></i>
                                    </summary>
                                    <div class="tw-p-4 tw-space-y-3 tw-border-t tw-border-gray-200">
                                        @if($viewData->ttl)
                                            <div>
                                                <p class="tw-text-xs tw-text-gray-500 tw-font-medium tw-mb-1">Tempat, Tanggal Lahir</p>
                                                <p class="tw-text-sm tw-font-semibold tw-text-gray-900">{{ $viewData->ttl }}</p>
                                            </div>
                                        @endif
                                        @if($viewData->alamat)
                                            <div>
                                                <p class="tw-text-xs tw-text-gray-500 tw-font-medium tw-mb-1">Alamat</p>
                                                <p class="tw-text-sm tw-font-semibold tw-text-gray-900 tw-leading-relaxed">{{ $viewData->alamat }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </details>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="tw-bg-white tw-px-6 tw-pb-6">
                        <button type="button" class="tw-w-full tw-bg-gray-900 hover:tw-bg-gray-800 tw-text-white tw-font-semibold tw-py-3 tw-rounded-xl tw-transition-all hover:tw-shadow-lg" data-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push("general-css")
    
@endpush

@push('js-libraries')
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.2/mqttws31.min.js" type="text/javascript"></script>
@endpush

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    
    {{-- MQTT Script for RFID Registration --}}
    <script>
        let mqttClient = null;
        const MQTT_TOPIC_REGISTER_REQUEST = 'psychorobotic/rfid/register/request';
        const MQTT_TOPIC_REGISTER_RESPONSE = 'psychorobotic/rfid/register/response';
        
        // Migrate old credentials to new nginx proxy settings
        (function() {
            const oldPort = localStorage.getItem('mqtt_port');
            const oldHost = localStorage.getItem('mqtt_hostname');
            if (oldPort === '9443' || oldPort === '9001' || (oldHost && oldHost.match(/^\d+\.\d+\.\d+\.\d+$/))) {
                localStorage.setItem('mqtt_hostname', 'psychoroboticpnj.tech');
                localStorage.setItem('mqtt_port', '443');
                localStorage.setItem('mqtt_protocol', 'wss');
                console.log('MQTT credentials migrated to use nginx proxy (psychoroboticpnj.tech:443)');
            }
        })();
        
        // Load saved credentials
        function loadMQTTCredentials() {
            return {
                hostname: localStorage.getItem('mqtt_hostname') || 'psychoroboticpnj.tech',
                port: parseInt(localStorage.getItem('mqtt_port')) || 443,
                protocol: localStorage.getItem('mqtt_protocol') || 'wss',
                username: localStorage.getItem('mqtt_username') || '',
                password: localStorage.getItem('mqtt_password') || ''
            };
        }
        
        // Save credentials
        document.getElementById('mqtt_save_credentials')?.addEventListener('click', function() {
            localStorage.setItem('mqtt_hostname', document.getElementById('mqtt_hostname').value);
            localStorage.setItem('mqtt_port', document.getElementById('mqtt_port').value);
            localStorage.setItem('mqtt_protocol', document.getElementById('mqtt_protocol').value);
            localStorage.setItem('mqtt_username', document.getElementById('mqtt_username').value);
            localStorage.setItem('mqtt_password', document.getElementById('mqtt_password').value);
            
            alert('Kredensial MQTT berhasil disimpan!');
        });
        
        // Auto-switch port when protocol changes
        document.getElementById('mqtt_protocol')?.addEventListener('change', function() {
            const portInput = document.getElementById('mqtt_port');
            if (this.value === 'wss' && portInput.value === '80') {
                portInput.value = '443';
            } else if (this.value === 'ws' && portInput.value === '443') {
                portInput.value = '80';
            }
        });
        
        // Auto-load credentials on modal show
        $('#mqttConfigModal').on('show.bs.modal', function () {
            const creds = loadMQTTCredentials();
            document.getElementById('mqtt_hostname').value = creds.hostname;
            document.getElementById('mqtt_port').value = creds.port;
            document.getElementById('mqtt_protocol').value = creds.protocol;
            document.getElementById('mqtt_username').value = creds.username;
            document.getElementById('mqtt_password').value = creds.password;
            
            // Check if already connected
            if (mqttClient && mqttClient.isConnected()) {
                document.getElementById('mqtt-connection-form').style.display = 'none';
                document.getElementById('mqtt-connected-status').style.display = 'block';
                
                // Send register request lagi untuk activate ESP8266 register mode
                const message = new Paho.MQTT.Message(JSON.stringify({
                    action: 'start_register',
                    timestamp: Date.now()
                }));
                message.destinationName = MQTT_TOPIC_REGISTER_REQUEST;
                mqttClient.send(message);
                
                // Clear previous RFID value
                document.getElementById('rfid_card_input').value = '';
            } else {
                document.getElementById('mqtt-connection-form').style.display = 'block';
                document.getElementById('mqtt-connected-status').style.display = 'none';
            }
        });
        
        // Connect MQTT
        document.getElementById('mqtt_connect_btn')?.addEventListener('click', function() {
            const hostname = document.getElementById('mqtt_hostname').value;
            const port = parseInt(document.getElementById('mqtt_port').value);
            const protocol = document.getElementById('mqtt_protocol').value;
            const username = document.getElementById('mqtt_username').value;
            const password = document.getElementById('mqtt_password').value;
            
            if (!hostname || !port) {
                alert('Hostname dan Port harus diisi!');
                return;
            }
            
            // Clean hostname - remove http:// or https:// or ws:// or wss:// if exists
            let cleanHostname = hostname.replace(/^(https?|wss?):\/\//, '').replace(/\/$/, '');
            
            const clientId = 'web_rfid_register_' + Math.random().toString(16).substr(2, 8);
            mqttClient = new Paho.MQTT.Client(cleanHostname, port, '/mqtt', clientId);
            
            mqttClient.onConnectionLost = function(responseObject) {
                console.warn('MQTT Connection Lost:', responseObject.errorMessage);
                if (responseObject.errorCode !== 0) {
                    document.getElementById('mqtt-connection-form').style.display = 'block';
                    document.getElementById('mqtt-connected-status').style.display = 'none';
                    // Show notification to user
                    if (responseObject.errorCode !== 0) {
                        console.error('MQTT disconnected unexpectedly:', responseObject.errorMessage);
                    }
                }
            };
            
            mqttClient.onMessageArrived = function(message) {
                if (message.destinationName === MQTT_TOPIC_REGISTER_RESPONSE) {
                    try {
                        const data = JSON.parse(message.payloadString);
                        if (data.rfid_uid) {
                            document.getElementById('rfid_card_input').value = data.rfid_uid;
                            @this.set('rfid_card', data.rfid_uid);
                            
                            // Auto close modal after 1 second
                            setTimeout(() => {
                                $('#mqttConfigModal').modal('hide');
                            }, 1000);
                        }
                    } catch (e) {
                        console.error('Error parsing MQTT message:', e);
                    }
                }
            };
            
            const connectOptions = {
                useSSL: protocol === 'wss',
                timeout: 10,
                keepAliveInterval: 30,
                onSuccess: function() {
                    mqttClient.subscribe(MQTT_TOPIC_REGISTER_RESPONSE);
                    
                    // Send register request
                    const message = new Paho.MQTT.Message(JSON.stringify({
                        action: 'start_register',
                        timestamp: Date.now()
                    }));
                    message.destinationName = MQTT_TOPIC_REGISTER_REQUEST;
                    mqttClient.send(message);
                    
                    // Show connected status
                    document.getElementById('mqtt-connection-form').style.display = 'none';
                    document.getElementById('mqtt-connected-status').style.display = 'block';
                },
                onFailure: function(error) {
                    console.error('MQTT Connection Failed:', error);
                    let errorMsg = 'Gagal terhubung ke MQTT Broker.\n\n';
                    if (error.errorCode === 7) {
                        errorMsg += 'Socket Error - Kemungkinan penyebab:\n';
                        errorMsg += '• Pastikan hostname: psychoroboticpnj.tech\n';
                        errorMsg += '• Pastikan port: 443 (untuk WSS)\n';
                        errorMsg += '• Pastikan protokol: WSS (SSL/TLS)\n';
                    } else {
                        errorMsg += error.errorMessage;
                    }
                    alert(errorMsg);
                }
            };
            
            if (username) {
                connectOptions.userName = username;
                connectOptions.password = password;
            }
            
            mqttClient.connect(connectOptions);
        });
        
        // Disconnect MQTT
        document.getElementById('mqtt_disconnect_btn')?.addEventListener('click', function() {
            if (mqttClient && mqttClient.isConnected()) {
                mqttClient.disconnect();
            }
            document.getElementById('mqtt-connection-form').style.display = 'block';
            document.getElementById('mqtt-connected-status').style.display = 'none';
            document.getElementById('rfid_card_input').value = '';
        });
        
        // Clean up on modal close - JANGAN disconnect MQTT
        $('#mqttConfigModal').on('hidden.bs.modal', function () {
            // MQTT tetap connected biar bisa register lagi
            // Hanya reset tampilan form
            document.getElementById('rfid_card_input').value = '';
        });
        
        // Disconnect MQTT hanya saat user manual close atau pindah halaman
        window.addEventListener('beforeunload', function() {
            if (mqttClient && mqttClient.isConnected()) {
                mqttClient.disconnect();
            }
        });
        
        // Keep tab at "Pembuatan Akun" when modal opens if rfid_card being registered
        $('#formDataModal').on('show.bs.modal', function (e) {
            // Check if we're in RFID registration flow
            const activeElement = document.activeElement;
            if (activeElement && activeElement.closest('#akun')) {
                // Stay on Pembuatan Akun tab
                setTimeout(() => {
                    $('#akun-tab').tab('show');
                }, 50);
            }
        });
        
        // Ensure tab stays on Pembuatan Akun when Connect MQTT button is clicked
        $(document).on('click', '[data-target="#mqttConfigModal"]', function() {
            // Mark that we're in RFID flow
            sessionStorage.setItem('rfid_registration_active', 'true');
        });
        
        $('#mqttConfigModal').on('hidden.bs.modal', function () {
            // After MQTT modal closes, keep Pembuatan Akun tab active if in registration flow
            if (sessionStorage.getItem('rfid_registration_active') === 'true') {
                setTimeout(() => {
                    $('#akun-tab').tab('show');
                }, 100);
                sessionStorage.removeItem('rfid_registration_active');
            }
        });
    </script>
    
    <script>
        let chartPengurusInstance = null;
        let chartAnggotaInstance = null;

        function initChartsAnggota() {
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                setTimeout(initChartsAnggota, 100);
                return;
            }

            // Chart colors
            const colors = [
                '#3b82f6', '#22c55e', '#f97316', '#ef4444', 
                '#8b5cf6', '#ec4899', '#14b8a6', '#f59e0b',
                '#06b6d4', '#84cc16', '#f43f5e', '#a855f7'
            ];

            // Register plugin
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }

            // Destroy existing charts
            if (chartPengurusInstance) {
                chartPengurusInstance.destroy();
                chartPengurusInstance = null;
            }
            if (chartAnggotaInstance) {
                chartAnggotaInstance.destroy();
                chartAnggotaInstance = null;
            }

            // Chart Pengurus
            const ctxPengurus = document.getElementById('chartPengurus');
            if (ctxPengurus && ctxPengurus.dataset.chart) {
                try {
                    const dataPengurus = JSON.parse(ctxPengurus.dataset.chart);
                    
                    chartPengurusInstance = new Chart(ctxPengurus, {
                        type: 'pie',
                        data: {
                            labels: dataPengurus.map(item => item.jurusan_prodi_kelas),
                            datasets: [{
                                data: dataPengurus.map(item => item.total),
                                backgroundColor: colors,
                                borderColor: '#ffffff',
                                borderWidth: 2,
                                hoverOffset: 10,
                                hoverBorderWidth: 3,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const percentage = dataPengurus[context.dataIndex].persentase;
                                            return label + ': ' + value + ' (' + percentage + '%)';
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 11
                                    },
                                    formatter: (value, context) => {
                                        if (value === 0) return '';
                                        const percentage = dataPengurus[context.dataIndex].persentase;
                                        return percentage >= 5 ? percentage + '%' : '';
                                    }
                                }
                            }
                        }
                    });
                } catch (e) {
                    // Error creating Pengurus chart
                }
            }

            // Chart Anggota
            const ctxAnggota = document.getElementById('chartAnggota');
            if (ctxAnggota && ctxAnggota.dataset.chart) {
                try {
                    const dataAnggota = JSON.parse(ctxAnggota.dataset.chart);
                    
                    chartAnggotaInstance = new Chart(ctxAnggota, {
                        type: 'pie',
                        data: {
                            labels: dataAnggota.map(item => item.jurusan_prodi_kelas),
                            datasets: [{
                                data: dataAnggota.map(item => item.total),
                                backgroundColor: colors,
                                borderColor: '#ffffff',
                                borderWidth: 2,
                                hoverOffset: 10,
                                hoverBorderWidth: 3,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const percentage = dataAnggota[context.dataIndex].persentase;
                                            return label + ': ' + value + ' (' + percentage + '%)';
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold',
                                        size: 11
                                    },
                                    formatter: (value, context) => {
                                        if (value === 0) return '';
                                        const percentage = dataAnggota[context.dataIndex].persentase;
                                        return percentage >= 5 ? percentage + '%' : '';
                                    }
                                }
                            }
                        }
                    });
                } catch (e) {
                    // Error creating Anggota chart
                }
            }
        }

        // Multiple initialization attempts
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChartsAnggota);
        } else {
            initChartsAnggota();
        }

        // Livewire hooks
        document.addEventListener('livewire:navigated', initChartsAnggota);
        
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('commit', ({component, commit, respond, succeed, fail}) => {
                succeed(({snapshot, effect}) => {
                    queueMicrotask(() => {
                        if (component.name === 'organisasi.anggota') {
                            setTimeout(initChartsAnggota, 300);
                        }
                    });
                });
            });
        }
    </script>
    @endpush
</div>
