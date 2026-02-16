<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Pertemuan</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="tw-flex">
                    <h3 class="tw-tracking-wider tw-text-[#34395e] tw-ml-6 tw-mt-6 tw-mb-5 lg:tw-mb-1 tw-text-base tw-font-semibold">Daftar Pertemuan</h3>
                    <div class="form-group tw-ml-auto tw-mr-4 tw-mt-4">
                        <select wire:model.live="filterProgram" class="form-control">
                            <option value="">-- Pilih Program --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->nama_program }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body -tw-mt-4 -tw-mb-4">
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
                </div>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-x-4 tw-px-4 lg:tw-px-0">
                @forelse ($data as $row)
                    <div class="card tw-rounded-xl">
                        <div class="program-card-img-container">
                            @if ($row->thumbnail)
                                <img src="{{ Storage::url($row->thumbnail) }}" wire:key="{{ rand() }}" class="card-img-top program-card-img tw-rounded-t-xl" alt="{{ $row->nama_program }}" />
                            @else
                                <div class="program-card-placeholder tw-rounded-t-xl">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            @endif
                            <div style="position: absolute; top: 12px; right: 12px">
                                <span class="badge badge-info" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; box-shadow: 0 2px 8px rgba(6, 182, 212, 0.4)">Pertemuan {{ $row->pertemuan_ke }}</span>
                            </div>
                        </div>
                        <div class="card-body tw-py-3">
                            <div class="tw-px-4">
                                <p class="font-bagus tw-font-semibold">{{ $row->judul_pertemuan }}</p>
                                <p class="font-bagus tw-text-xs tw-mt-2 tw-font-semibold tw-text-gray-500">Dilaksanakan: {{ \Carbon\Carbon::parse($row->tanggal)->format("d F Y") }}</p>
                                <div x-data="{ expanded: false }" class="font-bagus tw-text-sm tw-tracking-normal tw-mt-4">
                                    <p x-show="!expanded">{{ Str::limit($row->deskripsi, 80) }}</p>
                                    <p x-show="expanded">{{ $row->deskripsi }}</p>
                                    @if (strlen($row->deskripsi) > 80)
                                        <button @click="expanded = !expanded" class="tw-text-blue-500 hover:tw-text-blue-700 tw-text-xs tw-mt-1 tw-font-semibold">
                                            <span x-show="!expanded">Show More</span>
                                            <span x-show="expanded">Show Less</span>
                                        </button>
                                    @endif
                                </div>
                                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-justify-center tw-mt-4 font-bagus tw-text-sm tw-tracking-normal">
                                    <div class="tw-col-span-2">
                                        <p>
                                            <i class="fas fa-book tw-mr-1"></i>
                                            {{ $row->nama_program }}
                                        </p>
                                    </div>
                                    <div>
                                        <p>
                                            <i class="fas fa-clock tw-mr-1"></i>
                                            Minggu ke-{{ $row->minggu_ke }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center program-card-footer tw-px-3">
                                <p class="font-bagus tw-text-sm tw-font-semibold">Pemateri: {{ $row->nama_pemateri }}</p>
                                <div class="d-flex program-btn-container">
                                    @if ($this->can("pertemuan.bank_soal"))
                                        @if ($row->has_bank_soal)
                                            <a href="{{ route("pertemuan.soal", ["pertemuanId" => $row->id]) }}" class="btn btn-warning text-white" title="Kelola Bank Soal">
                                                <i class="fas fa-folders"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if ($this->can("pertemuan.gallery"))
                                        <button wire:click.prevent="openGalleryModal({{ $row->id }})" class="btn program-btn-view" data-toggle="modal" data-target="#galleryModal" title="Manage Gallery">
                                            <i class="fas fa-images"></i>
                                        </button>
                                    @endif

                                    @if ($this->can("pertemuan.edit"))
                                        <button wire:click.prevent="edit({{ $row->id }})" class="btn program-btn-edit" data-toggle="modal" data-target="#formDataModal">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    @endif

                                    @if ($this->can("pertemuan.delete"))
                                        <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn program-btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="tw-col-span-1 lg:tw-col-span-3 tw-w-full tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-16 tw-px-4">
                        <div class="tw-text-gray-400 tw-mb-4">
                            <i class="fas fa-inbox tw-text-6xl"></i>
                        </div>
                        <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-2">Tidak Ada Data</h3>
                        <p class="tw-text-gray-500 tw-text-center">Belum ada pertemuan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="px-3">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
        @if ($this->can("pertemuan.create"))
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
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-pills tw-mb-3" id="pertemuanTabs" role="tablist" wire:ignore>
                            <li class="nav-item">
                                <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
                                    <i class="fas fa-info-circle"></i>
                                    Data Pertemuan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="files-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">
                                    <i class="fas fa-image"></i>
                                    Thumbnail
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="parts-tab" data-toggle="tab" href="#parts" role="tab" aria-controls="parts" aria-selected="false">
                                    <i class="fas fa-list-ol"></i>
                                    Kelola Part
                                </a>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="pertemuanTabsContent">
                            <!-- Tab 1: Data Pertemuan -->
                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab" wire:ignore.self>
                                <div class="pt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_program">Program Kegiatan</label>
                                                <select wire:model="id_program" id="id_program" class="form-control select2">
                                                    <option value="" disabled>-- Pilih Opsi --</option>
                                                    @foreach ($programs as $program)
                                                        <option value="{{ $program->id }}">{{ $program->nama_program }}</option>
                                                    @endforeach
                                                </select>
                                                @error("id_program")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama_pemateri">Nama Pemateri</label>
                                                <input type="text" wire:model="nama_pemateri" id="nama_pemateri" class="form-control" />
                                                @error("nama_pemateri")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pertemuan_ke">Pertemuan Ke</label>
                                                <input type="number" wire:model="pertemuan_ke" id="pertemuan_ke" class="form-control" />
                                                @error("pertemuan_ke")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="judul_pertemuan">Judul Pertemuan</label>
                                                <input type="text" wire:model="judul_pertemuan" id="judul_pertemuan" class="form-control" />
                                                @error("judul_pertemuan")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea wire:model="deskripsi" id="deskripsi" class="form-control" style="height: 100px !important"></textarea>
                                        @error("deskripsi")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" wire:model="tanggal" id="tanggal" class="form-control" />
                                                @error("tanggal")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="minggu_ke">Minggu Ke</label>
                                                <input type="number" wire:model="minggu_ke" id="minggu_ke" class="form-control" />
                                                @error("minggu_ke")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select wire:model="status" id="status" class="form-control select2">
                                                    <option value="" disabled>-- Pilih Opsi --</option>
                                                    <option value="visible">Visible</option>
                                                    <option value="hidden">Hidden</option>
                                                </select>
                                                @error("status")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="d-block mb-2">
                                            Jenis Presensi Kehadiran
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="tw-flex tw-gap-4">
                                            <label class="tw-flex tw-items-center tw-cursor-pointer">
                                                <input type="checkbox" wire:model="jenis_presensi" value="pengurus" class="tw-mr-2" />
                                                <span>Pengurus</span>
                                            </label>
                                            <label class="tw-flex tw-items-center tw-cursor-pointer">
                                                <input type="checkbox" wire:model="jenis_presensi" value="anggota" class="tw-mr-2" />
                                                <span>Anggota</span>
                                            </label>
                                        </div>
                                        @error("jenis_presensi")
                                            <span class="text-danger d-block mt-1">{{ $message }}</span>
                                        @enderror

                                        <small class="text-muted">Pilih siapa yang bisa melakukan presensi di pertemuan ini</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 2: Thumbnail -->
                            <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab" wire:ignore.self>
                                <div class="pt-3">
                                    <div class="form-group">
                                        <label for="thumbnail">Thumbnail</label>
                                        <input type="file" wire:model="thumbnail" id="thumbnail" class="form-control" accept="image/*" />
                                        @error("thumbnail")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        @if ($thumbnail)
                                            <div class="mt-2">
                                                <img src="{{ $thumbnail->temporaryUrl() }}" style="max-width: 200px" />
                                            </div>
                                        @elseif ($isEditing && $oldThumbnail)
                                            <div class="mt-2">
                                                <img src="{{ Storage::url($oldThumbnail) }}" style="max-width: 200px" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 3: Kelola Part -->
                            <div class="tab-pane fade" id="parts" role="tabpanel" aria-labelledby="parts-tab" wire:ignore.self>
                                <div class="pt-3">
                                    @if (! $isEditing)
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            Simpan pertemuan terlebih dahulu sebelum menambah part.
                                        </div>
                                    @else
                                        <!-- Tombol Tambah Part Baru -->
                                        <div class="mb-3">
                                            <button type="button" wire:click="cancelEditPart" class="btn btn-primary" data-toggle="modal" data-target="#partFormModal">
                                                <i class="fas fa-plus-circle"></i>
                                                Tambah Part Baru
                                            </button>
                                        </div>

                                        <!-- List Parts -->
                                        <div class="tw-space-y-3">
                                            @forelse ($parts as $index => $part)
                                                <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-shadow-sm hover:tw-shadow-md tw-transition-shadow tw-duration-200 tw-mb-3 tw-overflow-hidden" wire:key="part-{{ $part["id"] }}">
                                                    <!-- Header Part -->
                                                    <div class="tw-bg-gradient-to-r tw-from-gray-50 tw-to-gray-100 tw-px-4 tw-py-3 tw-border-b tw-border-gray-200">
                                                        <div class="tw-flex tw-justify-between tw-items-start">
                                                            <div class="tw-flex-grow">
                                                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2">
                                                                    <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-bold tw-bg-blue-500 tw-text-white">Part {{ $part["urutan"] }}</span>
                                                                    <h6 class="tw-mb-0 tw-font-semibold tw-text-gray-800 tw-text-base">
                                                                        {{ $part["nama_part"] }}
                                                                    </h6>
                                                                </div>
                                                                @if ($part["deskripsi"])
                                                                    <p class="tw-text-gray-600 tw-text-sm tw-mb-0 tw-leading-relaxed">
                                                                        <i class="fas fa-info-circle tw-text-gray-400 tw-mr-1"></i>
                                                                        {{ $part["deskripsi"] }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="tw-flex tw-gap-1 tw-ml-3 tw-flex-shrink-0">
                                                                <a href="{{ route("part.soal", ["partId" => $part["id"]]) }}" class="tw-inline-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-lg tw-bg-yellow-500 hover:tw-bg-yellow-600 tw-text-white tw-transition-colors tw-duration-200" title="Kelola Bank Soal">
                                                                    <i class="fas fa-folders tw-text-sm"></i>
                                                                </a>
                                                                <button type="button" wire:click="editPart({{ $part["id"] }})" class="tw-inline-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-lg tw-bg-cyan-500 hover:tw-bg-cyan-600 tw-text-white tw-transition-colors tw-duration-200" data-toggle="modal" data-target="#partFormModal" title="Edit Part">
                                                                    <i class="fas fa-edit tw-text-sm"></i>
                                                                </button>
                                                                <button type="button" wire:click="deletePartConfirm({{ $part["id"] }})" class="tw-inline-flex tw-items-center tw-justify-center tw-w-9 tw-h-9 tw-rounded-lg tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-transition-colors tw-duration-200" title="Hapus Part">
                                                                    <i class="fas fa-trash tw-text-sm"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Files Section -->
                                                    <div class="tw-px-4 tw-py-3">
                                                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
                                                            <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-0">
                                                                <i class="fas fa-paperclip tw-text-gray-400 tw-mr-1"></i>
                                                                Files ({{ count($part["files"] ?? []) }})
                                                            </h6>
                                                        </div>

                                                        @if (! empty($part["files"]))
                                                            <div class="tw-space-y-2 tw-mb-3">
                                                                @foreach ($part["files"] as $file)
                                                                    <div class="tw-flex tw-items-center tw-justify-between tw-p-2.5 tw-bg-gray-50 hover:tw-bg-gray-100 tw-rounded-lg tw-border tw-border-gray-200 tw-transition-colors tw-duration-150">
                                                                        <div class="tw-flex tw-items-center tw-gap-2 tw-flex-1 tw-min-w-0">
                                                                            <div class="tw-flex-shrink-0 tw-w-8 tw-h-8 tw-bg-blue-100 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                                                                <i class="fas fa-file tw-text-blue-600 tw-text-sm"></i>
                                                                            </div>
                                                                            <div class="tw-flex-1 tw-min-w-0">
                                                                                <p class="tw-text-sm tw-font-medium tw-text-gray-800 tw-truncate">
                                                                                    {{ $file["original_name"] }}
                                                                                </p>
                                                                                <p class="tw-text-xs tw-text-gray-500">{{ number_format($file["ukuran_file"] / 1024, 1) }} KB</p>
                                                                            </div>
                                                                        </div>
                                                                        <button type="button" wire:click="deletePartFileConfirm({{ $file["id"] }})" class="tw-flex-shrink-0 tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-transition-colors tw-duration-200">
                                                                            <i class="fas fa-trash tw-text-xs"></i>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="tw-text-center tw-py-4 tw-mb-3">
                                                                <i class="fas fa-folder-open tw-text-3xl tw-text-gray-300 tw-mb-2"></i>
                                                                <p class="tw-text-sm tw-text-gray-500">Belum ada file</p>
                                                            </div>
                                                        @endif

                                                        <!-- Upload Files -->
                                                        <div class="tw-border-t tw-border-gray-200 tw-pt-3">
                                                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                                                                <i class="fas fa-upload tw-mr-1"></i>
                                                                Upload File Baru
                                                            </label>
                                                            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-gap-2">
                                                                <input type="file" wire:model="partFilesToUpload" class="tw-flex-1 tw-text-sm tw-text-gray-600 file:tw-mr-4 file:tw-py-2 file:tw-px-4 file:tw-rounded-lg file:tw-border-0 file:tw-text-sm file:tw-font-semibold file:tw-bg-blue-50 file:tw-text-blue-700 hover:file:tw-bg-blue-100 file:tw-cursor-pointer tw-cursor-pointer" multiple accept=".ppt,.pptx,.pdf,.zip,.jpg,.jpeg,.png" />
                                                                @if (! empty($partFilesToUpload))
                                                                    <button type="button" wire:click="uploadPartFiles({{ $part["id"] }})" class="tw-flex-shrink-0 tw-px-4 tw-py-2 tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-text-sm tw-font-medium tw-rounded-lg tw-transition-colors tw-duration-200 tw-flex tw-items-center tw-justify-center tw-gap-2 tw-whitespace-nowrap">
                                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                                        Upload ({{ count($partFilesToUpload) }})
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="tw-text-center tw-py-12">
                                                    <div class="tw-inline-flex tw-items-center tw-justify-center tw-w-16 tw-h-16 tw-bg-gray-100 tw-rounded-full tw-mb-4">
                                                        <i class="fas fa-inbox tw-text-3xl tw-text-gray-400"></i>
                                                    </div>
                                                    <h6 class="tw-text-base tw-font-semibold tw-text-gray-700 tw-mb-1">Belum Ada Part</h6>
                                                    <p class="tw-text-sm tw-text-gray-500">Klik tombol "Tambah Part Baru" untuk membuat part pertama</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">
                            <span wire:loading.remove>Save Data</span>
                            <span wire:loading>Uploading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Part Form Modal -->
    <div class="modal fade" wire:ignore.self id="partFormModal" tabindex="-1" aria-labelledby="partFormModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partFormModalLabel">
                        <i class="fas fa-{{ $isEditingPart ? "edit" : "plus-circle" }}"></i>
                        {{ $isEditingPart ? "Edit Part" : "Tambah Part Baru" }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_part">
                            Nama Part
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" wire:model="nama_part" id="nama_part" class="form-control" placeholder="Contoh: Hukum Ohm" />
                        @error("nama_part")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_part">Deskripsi</label>
                        <textarea wire:model="deskripsi_part" id="deskripsi_part" class="form-control" rows="2" placeholder="Deskripsi singkat tentang part ini"></textarea>
                    </div>

                    <!-- Bank Soal Configuration (Collapsible) -->
                    <div class="card bg-light mb-3">
                        <div class="card-header py-2" style="cursor: pointer" data-toggle="collapse" data-target="#bankSoalConfig">
                            <h6 class="mb-0">
                                <i class="fas fa-clipboard-list"></i>
                                Konfigurasi Bank Soal (Opsional)
                                <i class="fas fa-chevron-down float-right"></i>
                            </h6>
                        </div>
                        <div class="collapse" id="bankSoalConfig">
                            <div class="card-body">
                                <small class="text-muted d-block mb-3">Isi jika ingin langsung membuat bank soal untuk part ini</small>

                                <!-- Pilihan Ganda -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pilihan Ganda</label>
                                            <input type="number" wire:model="jml_pg" class="form-control form-control-sm" placeholder="Contoh: 10" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bobot PG (%)</label>
                                            <input type="number" wire:model="bobot_pg" class="form-control form-control-sm" placeholder="Contoh: 30" min="0" max="100" />
                                        </div>
                                    </div>
                                </div>

                                <!-- PG Kompleks -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>PG Kompleks</label>
                                            <input type="number" wire:model="jml_kompleks" class="form-control form-control-sm" placeholder="Contoh: 5" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bobot PG Kompleks (%)</label>
                                            <input type="number" wire:model="bobot_kompleks" class="form-control form-control-sm" placeholder="Contoh: 20" min="0" max="100" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Menjodohkan -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Menjodohkan</label>
                                            <input type="number" wire:model="jml_jodohkan" class="form-control form-control-sm" placeholder="Contoh: 5" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bobot Jodohkan (%)</label>
                                            <input type="number" wire:model="bobot_jodohkan" class="form-control form-control-sm" placeholder="Contoh: 15" min="0" max="100" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Isian -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Isian</label>
                                            <input type="number" wire:model="jml_isian" class="form-control form-control-sm" placeholder="Contoh: 5" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bobot Isian (%)</label>
                                            <input type="number" wire:model="bobot_isian" class="form-control form-control-sm" placeholder="Contoh: 20" min="0" max="100" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Esai -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Esai</label>
                                            <input type="number" wire:model="jml_esai" class="form-control form-control-sm" placeholder="Contoh: 3" min="0" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bobot Esai (%)</label>
                                            <input type="number" wire:model="bobot_esai" class="form-control form-control-sm" placeholder="Contoh: 15" min="0" max="100" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Jumlah Opsi -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jumlah Opsi Pilihan Ganda</label>
                                            <select wire:model="opsi" class="form-control form-control-sm">
                                                <option value="3">3 Opsi (A, B, C)</option>
                                                <option value="4">4 Opsi (A, B, C, D)</option>
                                                <option value="5">5 Opsi (A, B, C, D, E)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="cancelEditPart" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    @if ($isEditingPart)
                        <button type="button" wire:click="updatePart" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Part
                        </button>
                    @else
                        <button type="button" wire:click="addPart" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah Part
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" wire:ignore.self id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">
                        <i class="fas fa-images tw-mr-2"></i>
                        Manage Gallery
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Upload Area -->
                    <div class="tw-mb-6">
                        <label class="tw-block tw-text-sm tw-font-semibold tw-mb-2">Upload Images / Videos</label>
                        <div x-data="{ 
                            isDragging: false,
                            handleDrop(e) {
                                this.isDragging = false;
                                const files = Array.from(e.dataTransfer.files);
                                @this.upload('galleryFiles', files);
                            }
                        }" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop" :class="{ 'tw-border-blue-500 tw-bg-blue-50': isDragging }" class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-8 tw-text-center tw-transition-all">
                            <input type="file" wire:model="galleryFiles" id="galleryFiles" multiple accept="image/*,video/*" class="tw-hidden" />

                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                <i class="fas fa-cloud-upload-alt tw-text-5xl tw-text-gray-400 tw-mb-3"></i>
                                <p class="tw-text-gray-600 tw-mb-2">
                                    <label for="galleryFiles" class="tw-text-blue-500 hover:tw-text-blue-700 tw-cursor-pointer tw-font-semibold">Click to upload</label>
                                    or drag and drop
                                </p>
                                <p class="tw-text-sm tw-text-gray-500">Images (JPG, PNG) or Videos (MP4, MOV, AVI) - Max 20MB per file</p>
                            </div>

                            @if (! empty($galleryFiles))
                                <div class="tw-mt-4">
                                    <p class="tw-text-sm tw-text-green-600 tw-font-semibold">{{ count($galleryFiles) }} file(s) selected</p>
                                    <button type="button" wire:click="uploadGalleryFiles" wire:loading.attr="disabled" class="btn btn-primary tw-mt-2">
                                        <span wire:loading.remove wire:target="uploadGalleryFiles">
                                            <i class="fas fa-upload tw-mr-1"></i>
                                            Upload Now
                                        </span>
                                        <span wire:loading wire:target="uploadGalleryFiles">
                                            <i class="fas fa-spinner fa-spin tw-mr-1"></i>
                                            Uploading...
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="tw-my-6" />

                    <!-- Gallery Grid -->
                    <div class="tw-mb-4">
                        <h6 class="tw-font-semibold tw-mb-3">Gallery Items</h6>
                        <div class="tw-grid tw-grid-cols-3 tw-gap-4" id="galleryGrid">
                            @forelse ($galleryItems as $item)
                                <div class="tw-relative tw-group" wire:key="gallery-{{ $item["id"] }}">
                                    <div class="tw-aspect-square tw-rounded-lg tw-overflow-hidden tw-bg-gray-100 tw-cursor-pointer" onclick="openLightbox('{{ Storage::url($item["file_path"]) }}', '{{ $item["tipe"] }}')">
                                        @if ($item["tipe"] === "image")
                                            <img src="{{ Storage::url($item["file_path"]) }}" class="tw-w-full tw-h-full tw-object-cover hover:tw-scale-110 tw-transition-transform tw-duration-300" alt="Gallery Image" />
                                        @else
                                            <div class="tw-relative tw-w-full tw-h-full tw-bg-gray-900">
                                                <video src="{{ Storage::url($item["file_path"]) }}" class="tw-w-full tw-h-full tw-object-cover"></video>
                                                <div class="tw-absolute tw-inset-0 tw-flex tw-items-center tw-justify-center">
                                                    <i class="fas fa-play-circle tw-text-white tw-text-5xl tw-opacity-80"></i>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" wire:click="deleteGalleryConfirm({{ $item["id"] }})" class="tw-absolute tw-top-2 tw-right-2 tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-rounded-full tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-opacity-0 group-hover:tw-opacity-100 tw-transition-opacity">
                                        <i class="fas fa-trash tw-text-sm"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="tw-col-span-3 tw-text-center tw-py-12">
                                    <i class="fas fa-images tw-text-5xl tw-text-gray-300 tw-mb-3"></i>
                                    <p class="tw-text-gray-500">No gallery items yet. Upload some images or videos!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Load More Button -->
                        @if ($hasMoreGallery && count($galleryItems) > 0)
                            <div class="tw-text-center tw-mt-6">
                                <button type="button" wire:click="loadGalleryItems" class="btn btn-outline-primary">
                                    <span wire:loading.remove wire:target="loadGalleryItems">
                                        <i class="fas fa-chevron-down tw-mr-1"></i>
                                        Load More
                                    </span>
                                    <span wire:loading wire:target="loadGalleryItems">
                                        <i class="fas fa-spinner fa-spin tw-mr-1"></i>
                                        Loading...
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="tw-hidden tw-fixed tw-inset-0 tw-bg-black tw-bg-opacity-90 tw-z-[9999] tw-flex tw-items-center tw-justify-center" onclick="closeLightbox()">
        <button onclick="closeLightbox()" class="tw-absolute tw-top-4 tw-right-4 tw-text-white tw-text-4xl tw-z-[10000] hover:tw-text-gray-300 tw-transition-colors">
            <i class="fas fa-times"></i>
        </button>

        <!-- Previous Button -->
        <button onclick="event.stopPropagation(); navigateLightbox(-1)" class="tw-absolute tw-left-4 tw-top-1/2 -tw-translate-y-1/2 tw-text-white tw-text-5xl tw-z-[10000] hover:tw-text-gray-300 tw-transition-colors tw-bg-black tw-bg-opacity-50 tw-rounded-full tw-w-16 tw-h-16 tw-flex tw-items-center tw-justify-center" id="lightboxPrev">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- Next Button -->
        <button onclick="event.stopPropagation(); navigateLightbox(1)" class="tw-absolute tw-right-4 tw-top-1/2 -tw-translate-y-1/2 tw-text-white tw-text-5xl tw-z-[10000] hover:tw-text-gray-300 tw-transition-colors tw-bg-black tw-bg-opacity-50 tw-rounded-full tw-w-16 tw-h-16 tw-flex tw-items-center tw-justify-center" id="lightboxNext">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div id="lightboxContent" class="tw-max-w-5xl tw-max-h-[90vh] tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center tw-p-4" onclick="event.stopPropagation()">
            <!-- Content will be injected here -->
        </div>

        <!-- Image Counter -->
        <div class="tw-absolute tw-bottom-4 tw-left-1/2 -tw-translate-x-1/2 tw-text-white tw-text-lg tw-z-[10000] tw-bg-black tw-bg-opacity-50 tw-px-4 tw-py-2 tw-rounded-full" id="lightboxCounter">
            <span id="currentImage">1</span>
            /
            <span id="totalImages">1</span>
        </div>
    </div>
</div>

@push("general-css")
    <link href="{{ asset("assets/midragon/select2/select2.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/midragon/css/card-style.css") }}" rel="stylesheet" />
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

        window.addEventListener('swal:confirmFile', (event) => {
            Swal.fire({
                title: event.detail[0].message,
                html: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('deleteFile');
                }
            });
        });

        window.addEventListener('swal:confirmPertemuan', (event) => {
            Swal.fire({
                title: event.detail[0].message,
                html: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete');
                }
            });
        });

        window.addEventListener('swal:confirmGallery', (event) => {
            Swal.fire({
                title: event.detail[0].message,
                html: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteGalleryItem');
                }
            });
        });

        // Lightbox functions
        let galleryImages = [];
        let currentLightboxIndex = 0;

        function openLightbox(url, type, index = 0) {
            // Collect all gallery items
            galleryImages = [];
            const galleryItems = document.querySelectorAll('#galleryGrid > div');
            galleryItems.forEach((item, idx) => {
                const img = item.querySelector('img, video');
                if (img) {
                    const src = img.src;
                    const isVideo = item.querySelector('video') !== null;
                    galleryImages.push({ url: src, type: isVideo ? 'video' : 'image' });
                }
            });

            // Find the index of the clicked image
            currentLightboxIndex = galleryImages.findIndex(img => img.url === url);
            if (currentLightboxIndex === -1) currentLightboxIndex = 0;

            showLightboxImage(currentLightboxIndex);

            const modal = document.getElementById('lightboxModal');
            modal.classList.remove('tw-hidden');

            updateNavigationButtons();
        }

        function showLightboxImage(index) {
            const content = document.getElementById('lightboxContent');
            const item = galleryImages[index];

            if (item.type === 'image') {
                content.innerHTML = `<img src="${item.url}" class="tw-max-w-full tw-max-h-full tw-object-contain" />`;
            } else {
                content.innerHTML = `
                    <video controls class="tw-max-w-full tw-max-h-full" autoplay>
                        <source src="${item.url}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
            }

            // Update counter
            document.getElementById('currentImage').textContent = index + 1;
            document.getElementById('totalImages').textContent = galleryImages.length;
        }

        function navigateLightbox(direction) {
            currentLightboxIndex += direction;

            // Loop around
            if (currentLightboxIndex < 0) {
                currentLightboxIndex = galleryImages.length - 1;
            } else if (currentLightboxIndex >= galleryImages.length) {
                currentLightboxIndex = 0;
            }

            showLightboxImage(currentLightboxIndex);
            updateNavigationButtons();
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('lightboxPrev');
            const nextBtn = document.getElementById('lightboxNext');

            // Show/hide buttons based on gallery length
            if (galleryImages.length <= 1) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            }
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            const content = document.getElementById('lightboxContent');
            content.innerHTML = '';
            modal.classList.add('tw-hidden');
            galleryImages = [];
            currentLightboxIndex = 0;
        }

        // Close lightbox on ESC key, navigate with arrow keys
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('lightboxModal');
            if (!modal.classList.contains('tw-hidden')) {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    navigateLightbox(-1);
                } else if (e.key === 'ArrowRight') {
                    navigateLightbox(1);
                }
            }
        });

        // SweetAlert Delete Confirmations
        window.addEventListener('swal:confirmPart', event => {
            Swal.fire({
                title: '<strong>Hapus Part?</strong>',
                html: `<div style="text-align: left;">
                    <p class="mb-2"><i class="fas fa-exclamation-triangle text-warning"></i> Tindakan ini akan menghapus:</p>
                    <ul style="text-align: left;">
                        <li>Part dan semua konfigurasinya</li>
                        <li>Bank soal dan semua soalnya</li>
                        <li>File-file yang terlampir</li>
                    </ul>
                    <p class="mt-2 text-danger"><strong>Data tidak dapat dikembalikan!</strong></p>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                customClass: {
                    popup: 'animated fadeIn faster',
                    confirmButton: 'btn btn-danger btn-lg mr-2',
                    cancelButton: 'btn btn-secondary btn-lg'
                },
                buttonsStyling: false,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deletePart');
                }
            });
        });

        window.addEventListener('swal:confirmPartFile', event => {
            const fileName = event.detail[0]?.text || event.detail.text || 'file ini';
            Swal.fire({
                title: '<strong>Hapus File?</strong>',
                html: `<div style="text-align: center;">
                    <p class="mb-3"><i class="fas fa-file-pdf fa-3x text-danger mb-2"></i></p>
                    <p class="mb-2">File: <strong>${fileName.replace('Menghapus file: ', '')}</strong></p>
                    <p class="text-muted">File akan dihapus permanen dari server</p>
                </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Hapus',
                cancelButtonText: '<i class="fas fa-times"></i> Batal',
                customClass: {
                    popup: 'animated fadeIn faster',
                    confirmButton: 'btn btn-danger mr-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deletePartFile');
                }
            });
        });

        // Success notifications for Part operations
        window.addEventListener('swal:partAdded', event => {
            $('#partFormModal').modal('hide');
            // Fix scroll issue when modal is closed
            setTimeout(() => {
                if ($('#formDataModal').hasClass('show')) {
                    $('body').addClass('modal-open').css('overflow', 'hidden');
                }
            }, 100);
            Swal.fire({
                title: '<strong>Berhasil!</strong>',
                html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>Part berhasil ditambahkan',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'animated fadeIn faster'
                }
            });
        });

        window.addEventListener('swal:partUpdated', event => {
            $('#partFormModal').modal('hide');
            // Fix scroll issue when modal is closed
            setTimeout(() => {
                if ($('#formDataModal').hasClass('show')) {
                    $('body').addClass('modal-open').css('overflow', 'hidden');
                }
            }, 100);
            Swal.fire({
                title: '<strong>Berhasil!</strong>',
                html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>Part berhasil diupdate',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'animated fadeIn faster'
                }
            });
        });

        window.addEventListener('swal:partDeleted', event => {
            Swal.fire({
                title: '<strong>Terhapus!</strong>',
                html: '<i class="fas fa-trash-alt fa-3x text-success mb-3"></i><br>Part berhasil dihapus',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'animated fadeIn faster'
                }
            });
        });

        // Fix scroll issue when part modal is closed manually
        $('#partFormModal').on('hidden.bs.modal', function () {
            if ($('#formDataModal').hasClass('show')) {
                $('body').addClass('modal-open').css('overflow', 'hidden');
            }
        });

        // Success notification for file deletion
        window.addEventListener('swal:fileDeleted', event => {
            Swal.fire({
                title: '<strong>Terhapus!</strong>',
                html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>File berhasil dihapus',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'animated fadeIn faster'
                }
            });
        });
    </script>
@endpush
