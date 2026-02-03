<div>
    @push("scripts")
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('closeModal', (modalId) => {
                    $('#' + modalId).modal('hide');
                });
            });
        </script>
    @endpush

    <section class="section custom-section">
        <div class="section-header">
            <h1>Barang</h1>
            <div class="section-header-breadcrumb">
                <div class="d-flex align-items-center">
                    <div>
                        <button wire:click="downloadTemplate" class="btn btn-info btn-icon icon-left">
                            <i class="fas fa-file-download"></i>
                            Template
                        </button>
                        <button class="btn btn-warning btn-icon icon-left" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-file-upload"></i>
                            Import
                        </button>
                        <button wire:click="downloadPdf" class="btn btn-danger btn-icon icon-left">
                            <i class="fas fa-file-pdf"></i>
                            PDF
                        </button>
                        <button wire:click="downloadExcel" class="btn btn-success btn-icon icon-left">
                            <i class="fas fa-file-excel"></i>
                            Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Tabel Barang</h3>
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

                    <!-- Filter Section -->
                    <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-4 tw-float-right tw-mr-4">
                        <select wire:model.live="filterKategori" class="tw-bg-white tw-border tw-border-gray-300 tw-text-gray-900 tw-text-sm tw-rounded-lg focus:tw-ring-blue-500 focus:tw-border-blue-500 tw-px-3 tw-py-2">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filterJenis" class="tw-bg-white tw-border tw-border-gray-300 tw-text-gray-900 tw-text-sm tw-rounded-lg focus:tw-ring-blue-500 focus:tw-border-blue-500 tw-px-3 tw-py-2">
                            <option value="">Semua Jenis</option>
                            <option value="inventaris">Inventaris</option>
                            <option value="habis_pakai">Habis Pakai</option>
                        </select>

                        <select wire:model.live="filterKondisi" class="tw-bg-white tw-border tw-border-gray-300 tw-text-gray-900 tw-text-sm tw-rounded-lg focus:tw-ring-blue-500 focus:tw-border-blue-500 tw-px-3 tw-py-2">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>

                    <!-- Table Layout Grouped by Jenis and Kategori -->
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="1%" class="text-center tw-whitespace-nowrap"></th>
                                    <th width="5%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Nama Barang</th>
                                    <th class="tw-whitespace-nowrap tw-text-center">Lokasi</th>
                                    <th class="tw-whitespace-nowrap tw-text-center">Kondisi Barang</th>
                                    <th width="10%" class="text-center tw-whitespace-nowrap">Tersedia</th>
                                    <th class="tw-whitespace-nowrap">Dibuat Oleh</th>
                                    <th width="15%" class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Group pertama berdasarkan jenis, kemudian kategori
                                    $groupedByJenis = $data->groupBy("jenis");
                                    $no = 1;
                                @endphp

                                @forelse ($groupedByJenis as $jenis => $itemsByJenis)
                                    <!-- Group Header: Jenis -->
                                    <tr class="tw-bg-white">
                                        <td colspan="10" class="tw-font-semibold tw-text-gray-800 tw-tracking-wider">
                                            @if ($jenis == "inventaris")
                                                Inventaris / Tools
                                            @else
                                                Bahan Habis Pakai
                                            @endif
                                        </td>
                                    </tr>

                                    @php
                                        // Group items berdasarkan kategori dalam jenis ini
                                        $groupedByKategori = $itemsByJenis->groupBy(function ($item) {
                                            if (! $item->relationLoaded("kategori")) {
                                                $item->load("kategori");
                                            }
                                            return $item->kategori && is_object($item->kategori)
                                                ? $item->kategori->nama
                                                : "Tanpa Kategori";
                                        });
                                    @endphp

                                    @foreach ($groupedByKategori as $kategori => $items)
                                        <!-- Sub Header: Kategori -->
                                        <tr class="tw-bg-white">
                                            <td colspan="10" class="tw-text-gray-700 tw-pl-8 tw-tracking-wider">
                                                {{ $kategori }}
                                            </td>
                                        </tr>

                                        <!-- Items -->
                                        @foreach ($items as $row)
                                            @php
                                                $tersedia = $row->stok_tersedia;
                                            @endphp

                                            <tr class="text-center">
                                                <td></td>
                                                <td>{{ $no++ }}</td>
                                                <td class="text-left">
                                                    <div class="tw-flex tw-items-start tw-gap-3">
                                                        @if ($row->foto)
                                                            <img src="{{ asset("storage/" . $row->foto) }}" alt="{{ $row->nama }}" class="tw-w-12 tw-h-12 tw-object-cover tw-rounded tw-border tw-border-gray-200" />
                                                        @else
                                                            <div class="tw-w-12 tw-h-12 tw-bg-gray-100 tw-rounded tw-flex tw-items-center tw-justify-center tw-border tw-border-gray-200">
                                                                <i class="fas fa-box tw-text-gray-400"></i>
                                                            </div>
                                                        @endif
                                                        <div class="tw-flex-1">
                                                            <div class="font-bagus tw-font-normal tw-tracking-wide tw-text-sm">{{ $row->nama }}</div>
                                                            <div class="font-bagus tw-font-normal tw-mt-1 tw-text-sm tw-text-gray-600">Jumlah: {{ $row->jumlah }} {{ $row->satuan }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $row->lokasi }}</td>
                                                <td>
                                                    @if ($row->kondisi == "baik")
                                                        <span class="tw-inline-flex tw-items-center tw-px-4 tw-py-1 tw-rounded-full tw-font-medium tw-bg-green-100 tw-text-green-800">Baik</span>
                                                    @elseif ($row->kondisi == "rusak_ringan")
                                                        <span class="tw-inline-flex tw-items-center tw-px-4 tw-py-1 tw-rounded-full tw-font-medium tw-bg-yellow-100 tw-text-yellow-800">Rusak Ringan</span>
                                                    @else
                                                        <span class="tw-inline-flex tw-items-center tw-px-4 tw-py-1 tw-rounded-full tw-font-medium tw-bg-red-100 tw-text-red-800">Rusak Berat</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="tw-font-bold {{ $tersedia > 0 ? "tw-text-green-600" : "tw-text-red-600" }}">
                                                        {{ $tersedia }}
                                                    </span>
                                                </td>
                                                <td class="text-left tw-text-sm tw-whitespace-nowrap">
                                                    <span class="tw-text-gray-600">{{ $row->user->name ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data barang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5 px-3">
                        {{ $data->links() }}
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
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Barang" : "Tambah Barang" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        @if ($isEditing && $kode)
                            <div class="form-group">
                                <label>Kode Barang</label>
                                <input type="text" class="form-control" value="{{ $kode }}" disabled />
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama">
                                        Nama Barang
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="nama" id="nama" class="form-control" placeholder="Nama barang" />
                                    @error("nama")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kategori_barang_id">Kategori</label>
                                    <select wire:model="kategori_barang_id" id="kategori_barang_id" class="form-control">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error("kategori_barang_id")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jumlah">
                                        Jumlah
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" wire:model="jumlah" id="jumlah" class="form-control" min="0" />
                                    @error("jumlah")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="satuan">
                                        Satuan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="satuan" id="satuan" class="form-control" placeholder="pcs, unit, set, dll" />
                                    @error("satuan")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="jenis">
                                        Jenis
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model="jenis" id="jenis" class="form-control">
                                        <option value="inventaris">Inventaris</option>
                                        <option value="habis_pakai">Habis Pakai</option>
                                    </select>
                                    @error("jenis")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kondisi">
                                        Kondisi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model="kondisi" id="kondisi" class="form-control">
                                        <option value="baik">Baik</option>
                                        <option value="rusak_ringan">Rusak Ringan</option>
                                        <option value="rusak_berat">Rusak Berat</option>
                                    </select>
                                    @error("kondisi")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lokasi">Lokasi Penyimpanan</label>
                                    <input type="text" wire:model="lokasi" id="lokasi" class="form-control" placeholder="Contoh: Rak A, Lemari 1" />
                                    @error("lokasi")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto">Foto Barang</label>
                            <div class="tw-flex tw-items-start tw-gap-4">
                                @if ($fotoPreview)
                                    <div class="tw-relative">
                                        <img src="{{ asset("storage/" . $fotoPreview) }}" alt="Preview" class="tw-w-24 tw-h-24 tw-object-cover tw-rounded tw-border" />
                                        <button type="button" wire:click="removeFoto" class="tw-absolute tw-top-0 tw-right-0 tw-bg-red-500 tw-text-white tw-rounded-full tw-w-5 tw-h-5 tw-flex tw-items-center tw-justify-center tw-text-xs" style="transform: translate(50%, -50%)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @elseif ($foto)
                                    <div class="tw-relative">
                                        <img src="{{ $foto->temporaryUrl() }}" alt="Preview" class="tw-w-24 tw-h-24 tw-object-cover tw-rounded tw-border" />
                                        <button type="button" wire:click="$set('foto', null)" class="tw-absolute tw-top-0 tw-right-0 tw-bg-red-500 tw-text-white tw-rounded-full tw-w-5 tw-h-5 tw-flex tw-items-center tw-justify-center tw-text-xs" style="transform: translate(50%, -50%)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
                                <div class="tw-flex-1">
                                    <input type="file" wire:model="foto" id="foto" class="form-control" accept="image/*" />
                                    <small class="text-muted">Format: JPG, PNG. Maks 5MB. Akan di-compress otomatis.</small>
                                    @error("foto")
                                        <span class="text-danger tw-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea wire:model="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan tentang barang..."></textarea>
                            @error("keterangan")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Tutup</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">
                            <span wire:loading.remove>Simpan</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" wire:ignore.self id="importModal" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Format File:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Download template terlebih dahulu</li>
                            <li>Isi data sesuai format (Excel/CSV)</li>
                            <li>Kolom: Nama_Barang, Kategori, Jenis, Jumlah, Satuan, Kondisi, Lokasi, Keterangan</li>
                            <li><strong>Kategori baru akan otomatis dibuat</strong></li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="importFile">
                            Pilih File Excel/CSV
                            <span class="text-danger">*</span>
                        </label>
                        <input type="file" wire:model="importFile" id="importFile" class="form-control" accept=".xlsx,.xls,.csv" />
                        @error("importFile")
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <small class="text-muted">Format: XLSX, XLS, atau CSV (Max 2MB)</small>
                    </div>

                    <div wire:loading wire:target="importFile" class="text-center">
                        <i class="fas fa-spinner fa-spin"></i>
                        Memproses file...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="importExcel" wire:loading.attr="disabled" wire:target="importExcel" class="btn btn-primary">
                        <span wire:loading.remove wire:target="importExcel">
                            <i class="fas fa-upload"></i>
                            Import
                        </span>
                        <span wire:loading wire:target="importExcel">
                            <i class="fas fa-spinner fa-spin"></i>
                            Importing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
