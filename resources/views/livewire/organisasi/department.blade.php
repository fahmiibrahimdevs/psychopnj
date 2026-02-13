<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Department</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Daftar Department</h3>
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

            <!-- Card List View -->
            <div class="tw-space-y-6 tw-px-4 lg:tw-px-0">
                @forelse ($data->groupBy("nama_tahun") as $tahunGroup => $divisiList)
                    <!-- Tahun Header -->
                    <div class="tw-mb-4">
                        <div class="tw-bg-white tw-rounded-2xl tw-p-6 tw-shadow-md tw-shadow-slate-300">
                            <div class="tw-flex tw-items-center">
                                <div class="tw-bg-gradient-to-br tw-from-cyan-500 tw-to-blue-600 tw-rounded-lg tw-p-3 tw-mr-4">
                                    <i class="fas fa-crown tw-text-white tw-text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="tw-text-xl tw-font-bold tw-text-cyan-300">{{ $tahunGroup }}</h5>
                                    <p class="tw-text-sm tw-font-semibold tw-text-gray-600 tw-mt-1">Pimpinan Tertinggi Organisasi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Divisi Cards Grid -->
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-5 tw-mb-6">
                        @foreach ($divisiList as $row)
                            @php
                                $colors = [
                                    ["from" => "tw-from-yellow-400", "to" => "tw-to-orange-500", "text" => "tw-text-yellow-300"],
                                    ["from" => "tw-from-green-400", "to" => "tw-to-emerald-500", "text" => "tw-text-green-300"],
                                    ["from" => "tw-from-purple-400", "to" => "tw-to-pink-500", "text" => "tw-text-purple-300"],
                                    ["from" => "tw-from-blue-400", "to" => "tw-to-indigo-500", "text" => "tw-text-blue-300"],
                                    ["from" => "tw-from-red-400", "to" => "tw-to-rose-500", "text" => "tw-text-red-300"],
                                    ["from" => "tw-from-teal-400", "to" => "tw-to-cyan-500", "text" => "tw-text-teal-300"],
                                ];
                                $colorSet = $colors[$loop->index % 6];
                            @endphp

                            <div class="tw-bg-white tw-rounded-xl tw-p-5 tw-shadow-md tw-shadow-gray-300 tw-transition-all tw-duration-300">
                                <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
                                    <div class="tw-flex tw-items-center">
                                        <div class="tw-bg-gradient-to-br {{ $colorSet["from"] }} {{ $colorSet["to"] }} tw-rounded-lg tw-p-2.5 tw-mr-3">
                                            <i class="{{ $row->ikon }} tw-text-white tw-text-lg"></i>
                                        </div>
                                        <h5 class="tw-text-lg tw-font-semibold font-bagus {{ $colorSet["text"] }}">{{ $row->nama_department }}</h5>
                                    </div>
                                    @if ($row->status == "aktif")
                                        <i class="fas fa-check-circle tw-text-green-400 tw-text-lg"></i>
                                    @endif
                                </div>
                                <p class="tw-text-gray-700 tw-text-xs tw-mb-2 tw-font-semibold font-bagus">{{ $row->kategori }}</p>
                                <p class="tw-text-gray-700 tw-text-sm tw-leading-relaxed tw-mb-3 font-bagus tw-font-normal tw-tracking-normal">{{ Str::limit($row->deskripsi, 150) }}</p>

                                <div class="tw-flex tw-items-center tw-justify-between tw-pt-3 tw-border-t tw-border-gray-100">
                                    <span class="tw-text-xs tw-text-gray-500">
                                        <i class="fas fa-sort-numeric-up tw-mr-1"></i>
                                        Urutan: {{ $row->urutan }}
                                    </span>
                                    <div class="tw-flex tw-gap-2">
                                        @if($this->can("department.edit"))
                                            <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary tw-px-3 tw-py-1.5 tw-rounded-lg tw-transition-colors tw-text-sm" data-toggle="modal" data-target="#formDataModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif

                                        @if($this->can("department.delete"))
                                            <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger tw-px-3 tw-py-1.5 tw-rounded-lg tw-transition-colors tw-text-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body tw-py-16">
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                <div class="tw-text-gray-400 tw-mb-4">
                                    <i class="fas fa-inbox tw-text-6xl"></i>
                                </div>
                                <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-2">Tidak Ada Data</h3>
                                <p class="tw-text-gray-500 tw-text-center">Belum ada department yang tersedia saat ini.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="card tw-mt-6">
                <div class="card-body">
                    <div class="px-3">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
        @if($this->can("department.create"))
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
                        <div class="form-group">
                            <label for="id_tahun">Tahun Kepengurusan</label>
                            <select wire:model="id_tahun" id="id_tahun" class="form-control select2">
                                <option value="" disabled>-- Opsi Pilihan --</option>
                                @foreach ($tahuns as $tahun)
                                    <option value="{{ $tahun->id }}">{{ $tahun->nama_tahun }}</option>
                                @endforeach
                            </select>
                            @error("id_tahun")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nama_department">Nama Department</label>
                                    <input type="text" wire:model="nama_department" id="nama_department" class="form-control" />
                                    @error("nama_department")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <input type="text" wire:model="kategori" id="kategori" class="form-control" />
                                    @error("kategori")
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ikon">Ikon</label>
                                    <input type="text" wire:model="ikon" id="ikon" class="form-control" />
                                    @error("ikon")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="urutan">Urutan</label>
                                    <input type="number" wire:model="urutan" id="urutan" class="form-control" />
                                    @error("urutan")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select wire:model="status" id="status" class="form-control select2">
                                <option value="" disabled>-- Opsi Pilihan --</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                            @error("status")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="max_members">Max Members</label>
                            <input type="number" wire:model="max_members" id="max_members" class="form-control" placeholder="Optional" />
                            @error("max_members")
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
