<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Program Kegiatan</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Program Kegiatan</h3>
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
                </div>
            </div>
            <!-- Card View -->

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-x-4 tw-px-4 lg:tw-px-0">
                @forelse ($data as $row)
                    <div class="card tw-rounded-xl">
                        <div class="program-card-img-container">
                            @if ($row->thumbnail)
                                <img src="{{ Storage::url($row->thumbnail) }}" class="card-img-top program-card-img tw-rounded-t-xl lg:tw-rounded-t-xl" alt="{{ $row->nama_program }}" />
                            @else
                                <div class="program-card-placeholder tw-rounded-xl lg:tw-rounded-t-xl">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            @endif
                            <div style="position: absolute; top: 12px; right: 12px">
                                <span class="badge badge-info" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; box-shadow: 0 2px 8px rgba(6, 182, 212, 0.4)">{{ $row->nama_tahun }}</span>
                            </div>
                        </div>
                        <div class="card-body tw-py-3">
                            <div class="tw-px-4">
                                <p class="font-bagus tw-font-semibold">{{ $row->nama_program }}</p>
                                <p class="font-bagus tw-text-xs tw-mt-2 tw-font-semibold tw-text-gray-500">Dibuat: {{ \Carbon\Carbon::parse($row->created_at)->format("d F Y") }}</p>
                                <div x-data="{ expanded: false }" class="font-bagus tw-text-sm tw-tracking-normal tw-mt-4">
                                    <p x-show="!expanded">{{ Str::limit($row->deskripsi, 70) }}</p>
                                    <p x-show="expanded">{{ $row->deskripsi }}</p>
                                    @if (strlen($row->deskripsi) > 70)
                                        <button @click="expanded = !expanded" class="tw-text-blue-500 hover:tw-text-blue-700 tw-text-xs tw-mt-1 tw-font-semibold">
                                            <span x-show="!expanded">Show More</span>
                                            <span x-show="expanded">Show Less</span>
                                        </button>
                                    @endif
                                </div>
                                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-justify-center tw-mt-4 font-bagus tw-text-sm tw-tracking-normal">
                                    <div>
                                        <p>
                                            <i class="fas fa-chalkboard-teacher tw-mr-1"></i>
                                            {{ $row->jumlah_pertemuan }}x Pertemuan
                                        </p>
                                    </div>
                                    <div>
                                        <p>
                                            <i class="fas fa-users tw-mr-1"></i>
                                            {{ $row->penyelenggara }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center program-card-footer tw-px-3">
                                <span class="badge program-badge-{{ $row->jenis_program }}">
                                    {{ ucfirst($row->jenis_program) }}
                                </span>
                                <div class="d-flex program-btn-container">
                                    @if ($this->can("program_pembelajaran.view"))
                                        <button wire:click.prevent="viewPertemuan({{ $row->id }})" class="btn program-btn-view" data-toggle="modal" data-target="#viewPertemuanModal">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif

                                    @if ($this->can("program_pembelajaran.edit"))
                                        <button wire:click.prevent="edit({{ $row->id }})" class="btn program-btn-edit" data-toggle="modal" data-target="#formDataModal">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    @endif

                                    @if ($this->can("program_pembelajaran.delete"))
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
                        <p class="tw-text-gray-500 tw-text-center">Belum ada program kegiatan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="px-3 py-0">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
        @if ($this->can("program_pembelajaran.create"))
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
                        <input type="hidden" wire:model="id_tahun" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jenis_program">Jenis Program</label>
                                    <select wire:model="jenis_program" id="jenis_program" class="form-control select2">
                                        <option value="" disabled>-- Pilih Opsi --</option>
                                        <option value="internal">Internal</option>
                                        <option value="eksternal">Eksternal</option>
                                    </select>
                                    @error("jenis_program")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_program">Nama Program</label>
                                    <input type="text" wire:model="nama_program" id="nama_program" class="form-control" />
                                    @error("nama_program")
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_pertemuan">Jumlah Pertemuan</label>
                                    <input type="number" wire:model="jumlah_pertemuan" id="jumlah_pertemuan" class="form-control" />
                                    @error("jumlah_pertemuan")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="penyelenggara">Penyelenggara</label>
                                    <input type="text" wire:model="penyelenggara" id="penyelenggara" class="form-control" />
                                    @error("penyelenggara")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
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
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Pertemuan Modal -->
    <div class="modal fade" wire:ignore.self id="viewPertemuanModal" aria-labelledby="viewPertemuanModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="viewPertemuanModalLabel">Daftar Pertemuan</h5>
                    <button type="button" wire:click="closeViewModal()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-6">
                    <div class="tw-space-y-3">
                        @forelse ($pertemuanList as $index => $pertemuan)
                            <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-p-4 hover:tw-shadow-md tw-transition-shadow">
                                <div class="tw-flex tw-items-start tw-justify-between tw-mb-2">
                                    <div class="tw-flex tw-items-center tw-gap-3">
                                        <div class="tw-bg-blue-100 tw-text-blue-700 tw-font-bold tw-rounded-full tw-w-10 tw-h-10 tw-flex tw-items-center tw-justify-center tw-text-sm">
                                            {{ $pertemuan->pertemuan_ke }}
                                        </div>
                                        <div>
                                            <h6 class="tw-font-semibold tw-text-gray-800 tw-mb-1">{{ $pertemuan->judul_pertemuan }}</h6>
                                            <p class="tw-text-sm tw-text-gray-600 tw-mb-0">
                                                <i class="fas fa-user tw-mr-1"></i>
                                                {{ $pertemuan->nama_pemateri }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="badge badge-{{ $pertemuan->status == "selesai" ? "success" : ($pertemuan->status == "berlangsung" ? "warning" : "secondary") }}">
                                        {{ ucfirst($pertemuan->status) }}
                                    </span>
                                </div>
                                <div class="tw-flex tw-items-center tw-text-sm tw-text-gray-500 tw-mt-2">
                                    <i class="far fa-calendar-alt tw-mr-2"></i>
                                    {{ \Carbon\Carbon::parse($pertemuan->tanggal)->format("d F Y") }}
                                    <span class="tw-mx-2">•</span>
                                    <i class="fas fa-clock tw-mr-1"></i>
                                    Minggu ke-{{ $pertemuan->minggu_ke }}
                                </div>
                            </div>
                        @empty
                            <div class="tw-text-center tw-py-8 tw-text-gray-500">
                                <i class="fas fa-inbox tw-text-4xl tw-mb-3 tw-text-gray-300"></i>
                                <p class="tw-mb-0">Belum ada pertemuan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeViewModal()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                </div>
            </div>
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
    </script>
@endpush
