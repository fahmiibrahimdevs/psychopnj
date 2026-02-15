<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Transaksi Keuangan</h1>
            <div class="section-header-breadcrumb">
                <div class="d-flex align-items-center">
                    <div>
                        @if ($this->can("transaksi.export"))
                            <button wire:click="downloadPdf" class="btn btn-danger btn-icon icon-left">
                                <i class="fas fa-file-pdf"></i>
                                Export PDF
                            </button>
                            <button wire:click="downloadExcel" class="btn btn-success btn-icon icon-left">
                                <i class="fas fa-file-excel"></i>
                                Export Excel
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body tw-mt-5 lg:tw-mt-0">
            <!-- Summary Cards -->
            <div class="tw-overflow-x-auto tw-pb-4 tw-px-4 lg:tw-px-0" style="-webkit-overflow-scrolling: touch; scrollbar-width: none">
                <div class="tw-grid tw-grid-flow-col tw-auto-cols-max tw-gap-4">
                    <!-- Saldo -->
                    <div class="card tw-bg-white tw-shadow-md tw-border tw-border-gray-200 tw-rounded-lg tw-min-w-[280px] tw-mb-0">
                        <div class="card-body tw-p-0">
                            <div class="tw-flex tw-items-center tw-px-5 tw-py-6 tw-space-x-4">
                                <div>
                                    <div class="tw-px-4 tw-py-3 tw-border tw-border-gray-100 tw-rounded-lg tw-bg-gray-50">
                                        <i class="fas fa-wallet tw-text-xl tw-text-blue-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="tw-text-gray-500 tw-text-sm tw-font-medium tw-uppercase tw-tracking-wider">Saldo Saat Ini</h3>
                                    <p class="tw-text-2xl tw-font-bold {{ $saldoAkhir >= 0 ? "tw-text-blue-600" : "tw-text-red-600" }}">Rp {{ number_format($saldoAkhir, 0, ",", ".") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pemasukan -->
                    <div class="card tw-bg-white tw-shadow-md tw-border tw-border-gray-200 tw-rounded-lg tw-min-w-[280px] tw-mb-0">
                        <div class="card-body tw-p-0">
                            <div class="tw-flex tw-items-center tw-px-5 tw-py-6 tw-space-x-4">
                                <div>
                                    <div class="tw-px-4 tw-py-3 tw-border tw-border-gray-100 tw-rounded-lg tw-bg-gray-50">
                                        <i class="fas fa-arrow-down tw-text-xl tw-text-green-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="tw-text-gray-500 tw-text-sm tw-font-medium tw-uppercase tw-tracking-wider">Total Pemasukan</h3>
                                    <p class="tw-text-2xl tw-font-bold tw-text-green-600">Rp {{ number_format($totalPemasukan, 0, ",", ".") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengeluaran -->
                    <div class="card tw-bg-white tw-shadow-md tw-border tw-border-gray-200 tw-rounded-lg tw-min-w-[280px] tw-mb-0">
                        <div class="card-body tw-p-0">
                            <div class="tw-flex tw-items-center tw-px-5 tw-py-6 tw-space-x-4">
                                <div>
                                    <div class="tw-px-4 tw-py-3 tw-border tw-border-gray-100 tw-rounded-lg tw-bg-gray-50">
                                        <i class="fas fa-arrow-up tw-text-xl tw-text-red-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="tw-text-gray-500 tw-text-sm tw-font-medium tw-uppercase tw-tracking-wider">Total Pengeluaran</h3>
                                    <p class="tw-text-2xl tw-font-bold tw-text-red-600">Rp {{ number_format($totalPengeluaran, 0, ",", ".") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .tw-overflow-x-auto::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <div class="card tw-mt-2">
                <h3>Buku Kas</h3>
                <div class="card-body">
                    <div class="show-entries">
                        <p class="show-entries-show">Show</p>
                        <select wire:model.live="lengthData" id="length-data">
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <p class="show-entries-entries">Entries</p>
                    </div>
                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Cari transaksi..." class="form-control" />
                    </div>

                    <!-- Filter -->
                    <div class="tw-flex tw-gap-2 tw-mb-4 tw-flex-wrap tw-mr-4">
                        <select wire:model.live="filterJenis" class="form-control tw-w-auto tw-ml-auto">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                        <select wire:model.live="filterKategori" class="form-control tw-w-auto">
                            <option value="">Semua Kategori</option>
                            <option value="saldo_awal">Saldo Awal</option>
                            <option value="iuran_kas">Iuran Kas</option>
                            <option value="sponsor">Sponsor</option>
                            <option value="dept">Departemen</option>
                            <option value="project">Project/Kegiatan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="5%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Tanggal</th>
                                    <th class="tw-whitespace-nowrap">Deskripsi</th>
                                    <th class="tw-whitespace-nowrap text-right">Pemasukan</th>
                                    <th class="tw-whitespace-nowrap text-right">Pengeluaran</th>
                                    <th class="tw-whitespace-nowrap text-right">Saldo</th>
                                    <th class="tw-whitespace-nowrap">Dibuat Oleh</th>
                                    <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentMonth = null;
                                    $monthlyPemasukan = 0;
                                    $monthlyPengeluaran = 0;
                                @endphp

                                @forelse ($data as $row)
                                    @php
                                        $rowMonth = \Carbon\Carbon::parse($row->tanggal)->translatedFormat("F Y");
                                    @endphp

                                    @if ($currentMonth !== $rowMonth)
                                        {{-- Close previous month summary (if not first iteration) --}}
                                        @if ($currentMonth !== null)
                                            <tr class="tw-bg-gray-100 tw-font-bold">
                                                <td colspan="3" class="text-right">Total {{ $currentMonth }}</td>
                                                <td class="text-right tw-text-green-600">Rp {{ number_format($monthlyPemasukan, 0, ",", ".") }}</td>
                                                <td class="text-right tw-text-red-600">Rp {{ number_format($monthlyPengeluaran, 0, ",", ".") }}</td>
                                                <td colspan="3"></td>
                                            </tr>
                                        @endif

                                        {{-- New Month Header --}}
                                        <tr class="tw-bg-gray-100">
                                            <td colspan="8" class="tw-font-semibold tw-tracking-wider tw-px-4 tw-py-2 tw-text-gray-700 tw-text-left">
                                                {{ $rowMonth }}
                                            </td>
                                        </tr>

                                        @php
                                            $currentMonth = $rowMonth;
                                            $monthlyPemasukan = 0;
                                            $monthlyPengeluaran = 0;
                                        @endphp
                                    @endif

                                    @php
                                        if ($row->jenis === "pemasukan") {
                                            $monthlyPemasukan += $row->nominal;
                                        } elseif ($row->jenis === "pengeluaran") {
                                            $monthlyPengeluaran += $row->nominal;
                                        }
                                    @endphp

                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td class="text-left tw-whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tanggal)->format("d M Y") }}</td>
                                        <td class="tw-text-left">
                                            @php
                                                $prefix = "";
                                                // Handle Departemen
                                                if (($row->kategori === "Departemen" || $row->kategori === "dept") && $row->nama_department) {
                                                    $prefix = "Dept. " . $row->nama_department . ":";
                                                }
                                                // Handle Project
                                                elseif (($row->kategori === "Project" || $row->kategori === "project") && $row->nama_project) {
                                                    $prefix = "Project " . $row->nama_project . ":";
                                                }
                                                // Handle Other Categories
                                                elseif (! in_array(strtolower($row->kategori), ["dept", "project", "departemen"])) {
                                                    $prefix = ucwords(str_replace("_", " ", $row->kategori)) . ":";
                                                }
                                            @endphp

                                            @if ($prefix)
                                                <div class="tw-text-gray-800 tw-mb-1">{{ $prefix }}</div>
                                            @endif

                                            <div class="tw-text-gray-600">{{ $row->deskripsi }}</div>
                                        </td>
                                        <td class="text-right tw-text-green-600 tw-font-medium tw-whitespace-nowrap">
                                            {{ $row->jenis === "pemasukan" ? "Rp " . number_format($row->nominal, 0, ",", ".") : "-" }}
                                        </td>
                                        <td class="text-right tw-text-red-600 tw-font-medium tw-whitespace-nowrap">
                                            {{ $row->jenis === "pengeluaran" ? "Rp " . number_format($row->nominal, 0, ",", ".") : "-" }}
                                        </td>
                                        <td class="text-right tw-font-semibold tw-whitespace-nowrap {{ ($runningTotals[$row->id] ?? 0) >= 0 ? "tw-text-blue-600" : "tw-text-red-600" }}">Rp {{ number_format($runningTotals[$row->id] ?? 0, 0, ",", ".") }}</td>
                                        <td class="text-left tw-text-sm tw-whitespace-nowrap">
                                            <span class="tw-text-gray-600">{{ $row->user_name ?? "-" }}</span>
                                        </td>
                                        <td class="tw-whitespace-nowrap">
                                            @if ($this->can("transaksi.edit"))
                                                <button wire:click="edit({{ $row->id }})" class="btn btn-warning btn-icon" data-toggle="modal" data-target="#formDataModal">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            @endif

                                            @if ($this->can("transaksi.delete"))
                                                <button wire:click="deleteConfirm({{ $row->id }})" class="btn btn-danger btn-icon">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Last Month Summary (for the very last group) --}}
                                    @if ($loop->last)
                                        <tr class="tw-bg-gray-100 tw-font-bold">
                                            <td colspan="3" class="text-right">Total {{ $currentMonth }}</td>
                                            <td class="text-right tw-text-green-600">Rp {{ number_format($monthlyPemasukan, 0, ",", ".") }}</td>
                                            <td class="text-right tw-text-red-600">Rp {{ number_format($monthlyPengeluaran, 0, ",", ".") }}</td>
                                            <td colspan="3"></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center font-italic text-muted py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-ghost fa-3x mb-3"></i>
                                                <span>Belum ada data transaksi</span>
                                            </div>
                                        </td>
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

        @if ($this->can("transaksi.create"))
            <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
                <i class="far fa-plus"></i>
            </button>
        @endif
    </section>

    <!-- Modal Form -->
    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Transaksi" : "Tambah Transaksi" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        {{-- Tabs Navigation --}}
                        <ul class="nav nav-pills tw-mb-3" id="transaksiTabs" role="tablist" wire:ignore>
                            <li class="nav-item">
                                <a class="nav-link active" id="data-tab" data-toggle="tab" href="#dataTransaksi" role="tab" aria-controls="data" aria-selected="true">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    Data Transaksi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="upload-tab" data-toggle="tab" href="#uploadBukti" role="tab" aria-controls="upload" aria-selected="false">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    Upload Bukti
                                </a>
                            </li>
                        </ul>

                        {{-- Tabs Content --}}
                        <div class="tab-content" id="transaksiTabsContent">
                            {{-- Tab 1: Data Transaksi --}}
                            <div class="tab-pane fade show active" id="dataTransaksi" role="tabpanel" aria-labelledby="data-tab" wire:ignore.self>
                                <div class="pt-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" wire:model="tanggal" id="tanggal" class="form-control" />
                                                @error("tanggal")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="jenis">Jenis Transaksi</label>
                                                <select wire:model="jenis" id="jenis" class="form-control select2">
                                                    <option value="">-- Pilih Jenis --</option>
                                                    <option value="pemasukan">Pemasukan</option>
                                                    <option value="pengeluaran">Pengeluaran</option>
                                                </select>
                                                @error("jenis")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="kategori">Kategori</label>
                                        <select wire:model="kategori" id="kategori" class="form-control select2">
                                            <option value="">-- Pilih Kategori --</option>
                                            @if ($jenis === "pemasukan")
                                                @foreach ($jenisAnggaranPemasukan as $item)
                                                    <option value="{{ $item->nama_jenis }}" {{ $item->nama_jenis == $kategori ? "selected" : "" }}>{{ $item->nama_jenis }}</option>
                                                @endforeach
                                            @elseif ($jenis === "pengeluaran")
                                                @foreach ($jenisAnggaranPengeluaran as $item)
                                                    <option value="{{ $item->nama_jenis }}" {{ $item->nama_jenis == $kategori ? "selected" : "" }}>{{ $item->nama_jenis }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error("kategori")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if ($kategori === "Departemen")
                                        <div class="form-group">
                                            <label for="id_department">Departemen</label>
                                            <select wire:model="id_department" id="id_department" class="form-control select2">
                                                <option value="">-- Pilih Departemen --</option>
                                                @foreach ($departments as $dept)
                                                    <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                                                @endforeach
                                            </select>
                                            @error("id_department")
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    @if ($kategori === "Project")
                                        <div class="form-group">
                                            <label for="id_project">Project/Kegiatan</label>
                                            <select wire:model="id_project" id="id_project" class="form-control select2">
                                                <option value="">-- Pilih Project/Kegiatan --</option>
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
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea wire:model="deskripsi" id="deskripsi" class="form-control" style="height: 80px !important" placeholder="Keterangan transaksi..."></textarea>
                                        @error("deskripsi")
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="nominal">Nominal (Rp)</label>
                                                <input type="number" wire:model="nominal" id="nominal" class="form-control" placeholder="0" min="0" />
                                                @error("nominal")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="bukti">Link Bukti (Opsional)</label>
                                                <input type="url" wire:model="bukti" id="bukti" class="form-control" placeholder="https://..." />
                                                @error("bukti")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tab 2: Upload Bukti --}}
                            <div class="tab-pane fade" id="uploadBukti" role="tabpanel" aria-labelledby="upload-tab" wire:ignore.self>
                                <div class="pt-3">
                                    <div class="form-group">
                                        <label>Upload Files (Nota, Reimburse, Foto)</label>
                                        <div x-data="{ 
                                            isDragging: false,
                                            uploadType: '',
                                            handleDrop(e, type) {
                                                this.isDragging = false;
                                                const files = Array.from(e.dataTransfer.files);
                                                if (type === 'nota') @this.upload('filesNota', files, () => {});
                                                else if (type === 'reimburse') @this.upload('filesReimburse', files, () => {});
                                                else if (type === 'foto') @this.upload('filesFoto', files, () => {});
                                            }
                                        }" class="tw-space-y-4">
                                            {{-- Nota Upload --}}
                                            <div @dragover.prevent="isDragging = true; uploadType = 'nota'" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event, 'nota')" :class="{ 'tw-border-blue-500 tw-bg-blue-50': isDragging && uploadType === 'nota' }" class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-6 tw-text-center tw-transition-all">
                                                <input type="file" wire:model="filesNota" id="filesNota" multiple accept=".pdf,.jpg,.jpeg,.png" class="tw-hidden" />
                                                <div class="tw-flex tw-flex-col tw-items-center">
                                                    <i class="fas fa-file-invoice tw-text-3xl tw-text-blue-500 tw-mb-2"></i>
                                                    <p class="tw-text-gray-600 tw-mb-1">
                                                        <label for="filesNota" class="tw-text-blue-500 hover:tw-text-blue-700 tw-cursor-pointer tw-font-semibold">Nota</label>
                                                        - Click to upload or drag and drop
                                                    </p>
                                                    <p class="tw-text-xs tw-text-gray-500">PDF, JPG, PNG - Max 10MB</p>
                                                </div>
                                                @if ($filesNota)
                                                    <div class="tw-text-sm tw-text-blue-600 tw-mt-2 tw-font-semibold">{{ count($filesNota) }} file(s) selected</div>
                                                @endif

                                                @foreach ($errors->get("filesNota.*") as $messages)
                                                    @foreach ($messages as $message)
                                                        <span class="text-danger tw-block tw-mt-2">{{ $message }}</span>
                                                    @endforeach
                                                @endforeach

                                                <div wire:loading wire:target="filesNota" class="tw-text-sm tw-text-blue-600 tw-mt-2">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    Uploading...
                                                </div>
                                            </div>

                                            {{-- Reimburse Upload --}}
                                            <div @dragover.prevent="isDragging = true; uploadType = 'reimburse'" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event, 'reimburse')" :class="{ 'tw-border-green-500 tw-bg-green-50': isDragging && uploadType === 'reimburse' }" class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-6 tw-text-center tw-transition-all">
                                                <input type="file" wire:model="filesReimburse" id="filesReimburse" multiple accept=".pdf,.jpg,.jpeg,.png" class="tw-hidden" />
                                                <div class="tw-flex tw-flex-col tw-items-center">
                                                    <i class="fas fa-receipt tw-text-3xl tw-text-green-500 tw-mb-2"></i>
                                                    <p class="tw-text-gray-600 tw-mb-1">
                                                        <label for="filesReimburse" class="tw-text-green-500 hover:tw-text-green-700 tw-cursor-pointer tw-font-semibold">Reimburse</label>
                                                        - Click to upload or drag and drop
                                                    </p>
                                                    <p class="tw-text-xs tw-text-gray-500">PDF, JPG, PNG - Max 10MB</p>
                                                </div>
                                                @if ($filesReimburse)
                                                    <div class="tw-text-sm tw-text-green-600 tw-mt-2 tw-font-semibold">{{ count($filesReimburse) }} file(s) selected</div>
                                                @endif

                                                @foreach ($errors->get("filesReimburse.*") as $messages)
                                                    @foreach ($messages as $message)
                                                        <span class="text-danger tw-block tw-mt-2">{{ $message }}</span>
                                                    @endforeach
                                                @endforeach

                                                <div wire:loading wire:target="filesReimburse" class="tw-text-sm tw-text-green-600 tw-mt-2">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    Uploading...
                                                </div>
                                            </div>

                                            {{-- Foto Upload --}}
                                            <div @dragover.prevent="isDragging = true; uploadType = 'foto'" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop($event, 'foto')" :class="{ 'tw-border-purple-500 tw-bg-purple-50': isDragging && uploadType === 'foto' }" class="tw-border-2 tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-6 tw-text-center tw-transition-all">
                                                <input type="file" wire:model="filesFoto" id="filesFoto" multiple accept=".jpg,.jpeg,.png,.mp4,.mov,.avi,.mkv" class="tw-hidden" />
                                                <div class="tw-flex tw-flex-col tw-items-center">
                                                    <i class="fas fa-images tw-text-3xl tw-text-purple-500 tw-mb-2"></i>
                                                    <p class="tw-text-gray-600 tw-mb-1">
                                                        <label for="filesFoto" class="tw-text-purple-500 hover:tw-text-purple-700 tw-cursor-pointer tw-font-semibold">Foto Barang</label>
                                                        - Click to upload or drag and drop
                                                    </p>
                                                    <p class="tw-text-xs tw-text-gray-500">JPG, PNG, Video - Max 50MB</p>
                                                </div>
                                                @if ($filesFoto)
                                                    <div class="tw-text-sm tw-text-purple-600 tw-mt-2 tw-font-semibold">{{ count($filesFoto) }} file(s) selected</div>
                                                @endif

                                                @foreach ($errors->get("filesFoto.*") as $messages)
                                                    @foreach ($messages as $message)
                                                        <span class="text-danger tw-block tw-mt-2">{{ $message }}</span>
                                                    @endforeach
                                                @endforeach

                                                <div wire:loading wire:target="filesFoto" class="tw-text-sm tw-text-purple-600 tw-mt-2">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    Uploading...
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="tw-my-4" />

                                    {{-- Existing Files Display --}}
                                    @if ($isEditing && ! empty($existingFiles))
                                        <div class="form-group">
                                            <strong>Uploaded Files:</strong>
                                            <ul class="list-group mt-2">
                                                @foreach ($existingFiles as $file)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            @if (str_contains($file["mime_type"], "pdf"))
                                                                <i class="fas fa-file-pdf tw-text-red-500"></i>
                                                            @elseif (str_contains($file["mime_type"], "image"))
                                                                <i class="fas fa-file-image tw-text-blue-500"></i>
                                                            @elseif (str_contains($file["mime_type"], "video"))
                                                                <i class="fas fa-file-video tw-text-purple-500"></i>
                                                            @else
                                                                <i class="fas fa-file tw-text-gray-500"></i>
                                                            @endif
                                                            {{ $file["original_name"] }}
                                                            <small class="text-muted">({{ number_format($file["file_size"] / 1024, 0) }} KB - {{ strtoupper($file["tipe"]) }})</small>
                                                        </div>
                                                        <div class="tw-flex tw-gap-1">
                                                            <button type="button" onclick="previewFile('{{ asset("storage/" . $file["file_path"]) }}', '{{ $file["mime_type"] }}')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#filePreviewModal">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button type="button" wire:click="deleteFile({{ $file["id"] }})" wire:confirm="Yakin hapus file ini?" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
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

    {{-- File Preview Modal --}}
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">
                        <i class="fas fa-eye tw-mr-2"></i>
                        File Preview
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-p-0" style="max-height: 70vh; overflow: auto">
                    <div id="filePreviewContent" class="tw-flex tw-items-center tw-justify-center tw-p-4">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
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
        // Init setelah modal dibuka
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
    <script>
        function previewFile(filePath, mimeType) {
            const content = document.getElementById('filePreviewContent');

            // Show loading
            content.innerHTML = `
                <div class="tw-text-center tw-text-gray-500 tw-p-8">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="tw-mt-2">Loading...</p>
                </div>
            `;

            // Render content based on mime type
            setTimeout(() => {
                if (mimeType.includes('image')) {
                    content.innerHTML = `
                        <img src="${filePath}"
                             class="tw-w-full tw-h-auto"
                             alt="Preview"
                             onerror="this.parentElement.innerHTML='<div class=\\'tw-text-center tw-p-8\\'><i class=\\'fas fa-exclamation-circle fa-3x tw-text-red-500\\'></i><p class=\\'tw-mt-2\\'>Gagal memuat gambar</p></div>'">
                    `;
                } else if (mimeType.includes('pdf')) {
                    content.innerHTML = `
                        <iframe src="${filePath}"
                                class="tw-w-full"
                                style="height: 65vh; border: none;"
                                onerror="this.parentElement.innerHTML='<div class=\\'tw-text-center tw-p-8\\'><i class=\\'fas fa-exclamation-circle fa-3x tw-text-red-500\\'></i><p class=\\'tw-mt-2\\'>Gagal memuat PDF</p></div>'">
                        </iframe>
                    `;
                } else if (mimeType.includes('video')) {
                    content.innerHTML = `
                        <video controls class="tw-w-full tw-h-auto" style="max-height: 65vh;">
                            <source src="${filePath}" type="${mimeType}">
                            Browser Anda tidak mendukung video tag.
                        </video>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="tw-text-center tw-p-8">
                            <i class="fas fa-file fa-3x tw-text-gray-400"></i>
                            <p class="tw-mt-2 tw-text-gray-600">Preview tidak tersedia untuk tipe file ini</p>
                            <a href="${filePath}" target="_blank" class="btn btn-primary tw-mt-4">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </div>
                    `;
                }
            }, 100);
        }
    </script>
@endpush
