<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Anggota</h1>
        </div>

        <div class="section-body">
            <ul class="nav nav-pills" id="myTab3" role="tablist">
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

                                        @forelse ($dataAnggota as $departmentName => $anggotaList)
                                            @if ($departmentName)
                                                <tr>
                                                    <td class="tw-font-semibold tw-tracking-wider tw-bg-gray-100" colspan="6">Department: {{ $departmentName ?: "Tidak Ada Department" }}</td>
                                                </tr>
                                            @endif

                                            @foreach ($anggotaList as $row)
                                                <tr class="text-center">
                                                    <td>{{ $counter++ }}</td>
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
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-32">Jurusan/Prodi/Kelas:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->jurusan_prodi_kelas }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-32">NIM:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->nim ?: "-" }}</span>
                                    </div>
                                    <div class="tw-flex">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-32">TTL:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->ttl ?: "-" }}</span>
                                    </div>
                                    <div class="tw-flex tw-items-start">
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-32">Alamat:</span>
                                        <span class="tw-text-gray-800">{{ $viewData->alamat ?: "-" }}</span>
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
                                        <span class="tw-font-semibold tw-text-gray-600 tw-w-24">Status:</span>
                                        <span class="tw-text-gray-800 tw-capitalize">{{ $viewData->status_aktif }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Created at column removed from query --}}
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        let chartPengurusInstance = null;
        let chartAnggotaInstance = null;

        function initChartsAnggota() {
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.log('Chart.js not loaded yet, retrying in 100ms...');
                setTimeout(initChartsAnggota, 100);
                return;
            }

            console.log('Chart.js loaded! Initializing charts...');

            // Chart colors
            const colors = [
                '#3b82f6', '#22c55e', '#f97316', '#ef4444', 
                '#8b5cf6', '#ec4899', '#14b8a6', '#f59e0b',
                '#06b6d4', '#84cc16', '#f43f5e', '#a855f7'
            ];

            // Register plugin
            if (typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
                console.log('ChartDataLabels registered');
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
                    console.log('Creating Pengurus chart with data:', dataPengurus);
                    
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
                    console.log('Pengurus chart created successfully!');
                } catch (e) {
                    console.error('Error creating Pengurus chart:', e);
                }
            } else {
                console.log('Canvas chartPengurus not found or no data!');
            }

            // Chart Anggota
            const ctxAnggota = document.getElementById('chartAnggota');
            if (ctxAnggota && ctxAnggota.dataset.chart) {
                try {
                    const dataAnggota = JSON.parse(ctxAnggota.dataset.chart);
                    console.log('Creating Anggota chart with data:', dataAnggota);
                    
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
                    console.log('Anggota chart created successfully!');
                } catch (e) {
                    console.error('Error creating Anggota chart:', e);
                }
            } else {
                console.log('Canvas chartAnggota not found or no data!');
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
</div>
