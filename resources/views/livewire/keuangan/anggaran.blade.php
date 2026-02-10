<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Anggaran</h1>
        </div>

        <div class="section-body tw-mt-6">
            <!-- Summary Cards -->
            <!-- Summary Cards -->
            <div class="tw-flex tw-flex-nowrap lg:tw-grid lg:tw-grid-cols-3 tw-gap-4 tw-mb-4 tw-px-4 lg:tw-px-0 tw-overflow-x-auto tw-pb-2" style="-webkit-overflow-scrolling: touch; scrollbar-width: none">
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow-md tw-min-w-[85vw] sm:tw-min-w-[60vw] lg:tw-min-w-0 tw-flex-shrink-0">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Total Anggaran Pemasukan</p>
                            <h4 class="tw-text-xl tw-font-bold tw-text-green-600">Rp {{ number_format($totalPemasukan, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-arrow-down tw-text-green-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow-md tw-min-w-[85vw] sm:tw-min-w-[60vw] lg:tw-min-w-0 tw-flex-shrink-0">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Total Anggaran Pengeluaran</p>
                            <h4 class="tw-text-xl tw-font-bold tw-text-red-600">Rp {{ number_format($totalPengeluaran, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-arrow-up tw-text-red-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow-md tw-min-w-[85vw] sm:tw-min-w-[60vw] lg:tw-min-w-0 tw-flex-shrink-0">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Selisih Anggaran</p>
                            <h4 class="tw-text-xl tw-font-bold {{ $totalPemasukan - $totalPengeluaran >= 0 ? "tw-text-blue-600" : "tw-text-red-600" }}">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-balance-scale tw-text-blue-500 tw-text-2xl"></i>
                    </div>
                </div>
            </div>

            <style>
                .tw-overflow-x-auto::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <div class="card">
                <h3>Tabel Anggaran</h3>
                <div class="card-body">
                    <div class="show-entries">
                        <p class="show-entries-show">Filter</p>
                        <select wire:model.live="filterKategori" id="filter-kategori">
                            <option value="">Semua</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Cari anggaran..." class="form-control" />
                    </div>
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="5%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Kategori</th>
                                    <th class="tw-whitespace-nowrap">Jenis</th>
                                    <th class="tw-whitespace-nowrap">Deskripsi</th>
                                    <th class="tw-whitespace-nowrap">Dept/Project</th>
                                    <th class="tw-whitespace-nowrap text-right">Nominal</th>
                                    <th class="tw-whitespace-nowrap">Dibuat Oleh</th>
                                    <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td class="text-left">
                                            <span class="tw-px-2 tw-py-1 tw-rounded tw-text-xs tw-font-medium {{ $row->kategori === "pemasukan" ? "tw-bg-green-100 tw-text-green-700" : "tw-bg-red-100 tw-text-red-700" }}">
                                                {{ ucfirst($row->kategori) }}
                                            </span>
                                        </td>
                                        <td class="text-left">{{ $this->getJenisLabel($row->jenis) }}</td>
                                        <td class="text-left">{{ $row->nama }}</td>
                                        <td class="text-left">
                                            @if ($row->jenis === "Departemen" && $row->department)
                                                {{ $row->department->nama_department }}
                                            @elseif ($row->jenis === "Project" && $row->project)
                                                {{ $row->project->nama_project }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right tw-font-semibold {{ $row->kategori === "pemasukan" ? "tw-text-green-600" : "tw-text-red-600" }} tw-whitespace-nowrap">Rp {{ number_format($row->nominal, 0, ",", ".") }}</td>
                                        <td class="text-left tw-text-sm tw-whitespace-nowrap">
                                            <span class="tw-text-gray-600">{{ $row->user->name ?? "-" }}</span>
                                        </td>
                                        <td class="tw-whitespace-nowrap">
                                            <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data anggaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
            <i class="far fa-plus"></i>
        </button>
    </section>

    <!-- Modal Form -->
    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Anggaran" : "Tambah Anggaran" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select wire:model="kategori" id="kategori" class="form-control select2">
                                        <option value="" disabled>-- Pilih Kategori --</option>
                                        <option value="pemasukan">Pemasukan</option>
                                        <option value="pengeluaran">Pengeluaran</option>
                                    </select>
                                    @error("kategori")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="jenis">Jenis</label>
                                    <select wire:model="jenis" id="jenis" class="form-control select2">
                                        <option value="">-- Pilih Jenis --</option>
                                        @if ($kategori)
                                            @php
                                                $jenisOptions = $this->getJenisAnggaranByKategori($kategori);
                                            @endphp

                                            @foreach ($jenisOptions as $jenisOption)
                                                <option value="{{ $jenisOption }}" {{ $this->getJenisLabel($jenisOption) == $jenis ? "selected" : "" }}>{{ $this->getJenisLabel($jenisOption) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error("jenis")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if ($jenis === "Departemen")
                            <div class="form-group">
                                <label for="id_department">Departemen</label>
                                <select wire:model="id_department" id="id_department" class="form-control select2">
                                    <option value="" disabled>-- Pilih Departemen --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                                    @endforeach
                                </select>
                                @error("id_department")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        @if ($jenis === "Project")
                            <div class="form-group">
                                <label for="id_project">Project/Kegiatan</label>
                                <select wire:model="id_project" id="id_project" class="form-control select2">
                                    <option value="" disabled>-- Pilih Project/Kegiatan --</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->nama_project }}</option>
                                    @endforeach
                                </select>
                                @error("id_project")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="nama">Nama Item Anggaran</label>
                            <input type="text" wire:model="nama" id="nama" class="form-control" placeholder="Contoh: Dana awal dari kating" />
                            @error("nama")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nominal">Nominal (Rp)</label>
                            <input type="number" wire:model="nominal" id="nominal" class="form-control" placeholder="0" min="0" />
                            @error("nominal")
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
    <script src="{{ asset("assets/midragon/select2/select2.full.min.js") }}"></script>
@endpush

@push("scripts")
    <script>
        $('#formDataModal').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#formDataModal')
            });

            $('.select2').on('change', function(e) {
                var id = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(id, data);
            });
        });

        window.addEventListener('initSelect2', event => {
            $(document).ready(function() {
                $('.select2').select2({
                    dropdownParent: $('#formDataModal')
                });

                $('.select2').on('change', function(e) {
                    var id = $(this).attr('id');
                    var data = $(this).select2("val");
                    @this.set(id, data);
                });
            });
        })
    </script>
@endpush
