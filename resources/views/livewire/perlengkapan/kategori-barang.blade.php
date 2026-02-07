<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Kategori Barang</h1>
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
                <h3>Tabel Kategori Barang</h3>
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
                                    <th class="tw-whitespace-nowrap">Nama Kategori</th>
                                    <th class="tw-whitespace-nowrap text-center">Jumlah Barang</th>
                                    <th class="tw-whitespace-nowrap">Dibuat Oleh</th>
                                    <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                        <td class="text-left">{{ $row->nama_kategori }}</td>
                                        <td>{{ $row->jumlah_barang }} barang</td>
                                        <td class="text-left tw-text-sm tw-whitespace-nowrap">
                                            <span class="tw-text-gray-600">{{ $row->user->name ?? "-" }}</span>
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
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data kategori barang</td>
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
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Kategori" : "Tambah Kategori" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <div class="form-group">
                            <label for="nama">
                                Nama Kategori
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" wire:model="nama" id="nama" class="form-control" placeholder="Contoh: Elektronik, Tools, ATK" />
                            @error("nama")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Tutup</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" wire:ignore.self id="importModal" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="importModalLabel">Import Kategori Barang dari Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="importExcel">
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <div class="alert alert-info">
                            <strong>Petunjuk:</strong>
                            <ol class="tw-mb-0 tw-pl-4">
                                <li>Download template Excel terlebih dahulu</li>
                                <li>
                                    Isi data kategori pada kolom
                                    <strong>Nama Kategori</strong>
                                </li>
                                <li>
                                    Kolom
                                    <strong>ID</strong>
                                    biarkan kosong (akan otomatis)
                                </li>
                                <li>Kategori yang sudah ada akan dilewati</li>
                                <li>Upload file yang sudah diisi</li>
                            </ol>
                        </div>
                        <div class="form-group">
                            <label for="importFile">
                                File Excel (.xlsx, .xls, .csv)
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" wire:model="importFile" id="importFile" class="form-control" accept=".xlsx,.xls,.csv" />
                            @error("importFile")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:loading wire:target="importFile" class="alert alert-warning">Memproses file...</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Tutup</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="importExcel" class="btn btn-primary tw-bg-blue-500">
                            <span wire:loading.remove wire:target="importExcel">Import</span>
                            <span wire:loading wire:target="importExcel">Importing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('closeModal', (modalId) => {
            $('#' + modalId).modal('hide');
        });
    </script>
@endscript
