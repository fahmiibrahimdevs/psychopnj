<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Iuran Kas</h1>
            <div class="section-header-breadcrumb">
                <div class="d-flex align-items-center">
                    @if ($this->can("iuran_kas.export"))
                        <div>
                            <button wire:click="downloadPdf" class="btn btn-danger btn-icon icon-left" wire:loading.attr="disabled" wire:target="downloadPdf">
                                <span wire:loading.remove wire:target="downloadPdf">
                                    <i class="fas fa-file-pdf"></i>
                                    Export PDF
                                </span>
                                <span wire:loading wire:target="downloadPdf">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                    Mengekspor...
                                </span>
                            </button>
                            <button wire:click="downloadExcel" class="btn btn-success btn-icon icon-left" wire:loading.attr="disabled" wire:target="downloadExcel">
                                <span wire:loading.remove wire:target="downloadExcel">
                                    <i class="fas fa-file-excel"></i>
                                    Export Excel
                                </span>
                                <span wire:loading wire:target="downloadExcel">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                    Mengekspor...
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="section-body">
            <ul class="nav nav-pills tw-mt-7 tw-mb-2 tw-px-6 lg:tw-px-0" id="iuranKasTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "pengurus" ? "active" : "" }}" wire:click.prevent="switchTab('pengurus')" id="iuran-kas-pengurus-tab" data-toggle="tab" href="#iuran-kas-pengurus" role="tab" aria-controls="iuran-kas-pengurus" aria-selected="{{ $activeTab === "pengurus" ? "true" : "false" }}">Pengurus ({{ $countPengurus }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "anggota" ? "active" : "" }}" wire:click.prevent="switchTab('anggota')" id="iuran-kas-anggota-tab" data-toggle="tab" href="#iuran-kas-anggota" role="tab" aria-controls="iuran-kas-anggota" aria-selected="{{ $activeTab === "anggota" ? "true" : "false" }}">Anggota ({{ $countAnggota }})</a>
                </li>
            </ul>

            <div class="tab-content" id="iuranKasTabContent">
                <div class="tab-pane fade {{ $activeTab === "pengurus" ? "show active" : "" }}" id="iuran-kas-pengurus" role="tabpanel" aria-labelledby="iuran-kas-pengurus-tab">
                    <div class="card">
                        <h3>Tabel Pengurus</h3>
                        <div class="card-body">
                            <div class="search-column">
                                <p>Search:</p>
                                <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-pengurus" placeholder="Cari nama anggota..." class="form-control" />
                            </div>

                            <div class="table-responsive">
                                <table class="tw-table-auto tw-w-full">
                                    <thead class="tw-sticky tw-top-0">
                                        <tr class="tw-text-gray-700">
                                            <th rowspan="2" class="text-center tw-whitespace-nowrap" width="5%">No</th>
                                            <th rowspan="2" class="text-left tw-whitespace-nowrap" style="min-width: 250px">Nama Lengkap</th>

                                            @if (count($periodeList) > 0)
                                                <th colspan="{{ count($periodeList) }}" class="text-center tw-whitespace-nowrap">Pertemuan</th>
                                            @else
                                                <th class="text-center tw-whitespace-nowrap">Periode</th>
                                            @endif
                                            <th rowspan="2" class="text-center tw-whitespace-nowrap" width="10%">Total</th>
                                        </tr>
                                        <tr class="tw-text-gray-700">
                                            @forelse ($periodeList as $periode)
                                                <th class="text-center tw-whitespace-nowrap tw-align-top">
                                                    <div class="dropdown d-inline-block">
                                                        <a class="tw-font-bold tw-text-gray-700 tw-no-underline hover:tw-text-blue-500 tw-cursor-pointer dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            {{ $periode }}
                                                        </a>
                                                        <br />
                                                        <span class="tw-text-xs tw-text-gray-500 tw-font-normal">Rp {{ number_format($periodeNominals[$periode] ?? 5000, 0, ",", ".") }}</span>
                                                        <div class="dropdown-menu shadow" style="z-index: 99999">
                                                            @if ($this->can("iuran_kas.edit"))
                                                                <a class="dropdown-item has-icon" href="#" wire:click.prevent="openRenameModal('{{ $periode }}')">
                                                                    <i class="fas fa-edit text-primary"></i>
                                                                    Edit Nama
                                                                </a>
                                                            @endif

                                                            @if ($this->can("iuran_kas.delete"))
                                                                <a class="dropdown-item has-icon text-danger" href="#" wire:click.prevent="confirmDeletePeriode('{{ $periode }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                    Hapus
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </th>
                                            @empty
                                                <th class="text-center tw-text-gray-400 tw-italic tw-font-normal">- Belum ada -</th>
                                            @endforelse
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counterPengurus = 1;
                                        @endphp

                                        @forelse ($pengurusGrouped as $departmentName => $rows)
                                            <tr>
                                                <td colspan="{{ count($periodeList) + 3 }}" class="tw-font-semibold tw-tracking-wider tw-bg-gray-100">Department: {{ $departmentName ?: "Tidak Ada Department" }}</td>
                                            </tr>

                                            @foreach ($rows as $row)
                                                <tr class="text-center">
                                                    <td>{{ $counterPengurus++ }}</td>
                                                    <td class="text-left">
                                                        <div>{{ $row["nama"] }}</div>
                                                        <small class="text-muted">NIM: {{ $row["nim"] ?: "-" }}</small>
                                                    </td>

                                                    @foreach ($periodeList as $periode)
                                                        @php
                                                            $payment = $row["payments"][$periode] ?? null;
                                                            $cellKey = $row["id"] . "-" . $periode;
                                                            $isNewlyChecked = in_array($cellKey, $newlyChecked);
                                                            $isNewlyUnchecked = in_array($cellKey, $newlyUnchecked);

                                                            $bgClass = $isNewlyChecked ? "tw-bg-green-100 dark:tw-bg-green-100" : ($isNewlyUnchecked ? "tw-bg-red-100 dark:tw-bg-red-100" : "");
                                                            $bgStyle = $isNewlyChecked ? "background-color: #d4edda !important;" : ($isNewlyUnchecked ? "background-color: #f8d7da !important;" : "");
                                                        @endphp

                                                        <td class="p-1 {{ $bgClass }}" style="{{ $bgStyle }}">
                                                            @if ($this->can("iuran_kas.approve"))
                                                                <div class="d-flex flex-column align-items-center">
                                                                    <div class="custom-control custom-checkbox mb-1" style="min-height: 1.5rem" wire:key="checkbox-pengurus-{{ $row["id"] }}-{{ $periode }}-{{ $payment ? "lunas" : "kosong" }}">
                                                                        <input type="checkbox" class="custom-control-input" id="cb-pengurus-{{ $row["id"] }}-{{ $loop->index }}" wire:click.prevent="toggleStatus({{ $row["id"] }}, '{{ $periode }}', '{{ $row["nama"] }}')" {{ $payment && $payment["status"] === "lunas" ? "checked" : "" }} />
                                                                        <label class="custom-control-label" for="cb-pengurus-{{ $row["id"] }}-{{ $loop->index }}"></label>
                                                                    </div>

                                                                    <div wire:loading wire:target="toggleStatus({{ $row["id"] }}, '{{ $periode }}', '{{ $row["nama"] }}')" class="spinner-border spinner-border-sm text-primary spinner-sm" style="width: 14px; height: 14px; border-width: 0.15em" role="status">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>

                                                                    <div wire:loading.remove wire:target="toggleStatus({{ $row["id"] }}, '{{ $periode }}', '{{ $row["nama"] }}')">
                                                                        @if ($payment && $payment["status"] === "lunas")
                                                                            <small class="text-success font-weight-bold cursor-pointer hover:text-primary tw-text-xs tw-cursor-pointer" wire:click="openEditDateModal({{ $payment["id"] }})" title="Klik untuk ubah tanggal">
                                                                                {{ \Carbon\Carbon::parse($payment["tanggal_bayar"])->locale("id")->translatedFormat("d M") }}
                                                                            </small>
                                                                        @else
                                                                            <div style="height: 15px"></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach

                                                    <td class="font-weight-bold text-success">
                                                        <div class="d-flex justify-content-between">
                                                            <span>Rp</span>
                                                            <span>{{ number_format($row["total_bayar"], 0, ",", ".") }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($periodeList) + 3 }}" class="text-center text-muted">Tidak ada pengurus aktif</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === "anggota" ? "show active" : "" }}" id="iuran-kas-anggota" role="tabpanel" aria-labelledby="iuran-kas-anggota-tab">
                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-8 tw-gap-x-0 lg:tw-gap-x-4">
                        <div class="tw-col-span-2">
                            <div class="card">
                                <h3>Filter Jurusan</h3>
                                <div class="card-body tw-px-6">
                                    <div class="form-group mb-0" wire:ignore>
                                        <label for="jurusan-filter-select">Jurusan / Prodi / Kelas</label>
                                        <select id="jurusan-filter-select" class="form-control">
                                            <option value="">Semua Jurusan ({{ $jurusanFilterOptions->sum("total") }})</option>
                                            @foreach ($jurusanFilterOptions as $option)
                                                <option value="{{ $option["value"] }}" {{ $selectedJurusan === $option["value"] ? "selected" : "" }}>{{ $option["label"] }} ({{ $option["total"] }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tw-col-span-6">
                            <div class="card">
                                <h3>Tabel Anggota</h3>
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
                                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-anggota" placeholder="Cari nama anggota..." class="form-control" />
                                    </div>

                                    <div class="table-responsive">
                                        <table class="tw-table-auto tw-w-full">
                                            <thead class="tw-sticky tw-top-0">
                                                <tr class="tw-text-gray-700">
                                                    <th rowspan="2" class="text-center tw-whitespace-nowrap" width="5%">No</th>
                                                    <th rowspan="2" class="text-left tw-whitespace-nowrap" style="min-width: 250px">Nama Lengkap</th>

                                                    @if (count($periodeList) > 0)
                                                        <th colspan="{{ count($periodeList) }}" class="text-center tw-whitespace-nowrap">Pertemuan</th>
                                                    @else
                                                        <th class="text-center tw-whitespace-nowrap">Periode</th>
                                                    @endif
                                                    <th rowspan="2" class="text-center tw-whitespace-nowrap" width="10%">Total</th>
                                                </tr>
                                                <tr class="tw-text-gray-700">
                                                    @forelse ($periodeList as $periode)
                                                        <th class="text-center tw-whitespace-nowrap tw-align-top">
                                                            <div class="dropdown d-inline-block">
                                                                <a class="tw-font-bold tw-text-gray-700 tw-no-underline hover:tw-text-blue-500 tw-cursor-pointer dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    {{ $periode }}
                                                                </a>
                                                                <br />
                                                                <span class="tw-text-xs tw-text-gray-500 tw-font-normal">Rp {{ number_format($periodeNominals[$periode] ?? 5000, 0, ",", ".") }}</span>
                                                                <div class="dropdown-menu shadow" style="z-index: 99999">
                                                                    @if ($this->can("iuran_kas.edit"))
                                                                        <a class="dropdown-item has-icon" href="#" wire:click.prevent="openRenameModal('{{ $periode }}')">
                                                                            <i class="fas fa-edit text-primary"></i>
                                                                            Edit Nama
                                                                        </a>
                                                                    @endif

                                                                    @if ($this->can("iuran_kas.delete"))
                                                                        <a class="dropdown-item has-icon text-danger" href="#" wire:click.prevent="confirmDeletePeriode('{{ $periode }}')">
                                                                            <i class="fas fa-trash"></i>
                                                                            Hapus
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </th>
                                                    @empty
                                                        <th class="text-center tw-text-gray-400 tw-italic tw-font-normal">- Belum ada -</th>
                                                    @endforelse
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $counterAnggota = $anggotaPaginator ? $anggotaPaginator->firstItem() ?? 1 : 1;
                                                @endphp

                                                @forelse ($anggotaGrouped as $kelasName => $rows)
                                                    <tr>
                                                        <td colspan="{{ count($periodeList) + 3 }}" class="tw-font-semibold tw-tracking-wider tw-bg-gray-100">Jurusan/Prodi/Kelas: {{ $kelasName ?: "Tidak Ada Data" }} ({{ count($rows) }})</td>
                                                    </tr>

                                                    @foreach ($rows as $row)
                                                        <tr class="text-center">
                                                            <td>{{ $counterAnggota++ }}</td>
                                                            <td class="text-left">
                                                                <div>{{ $row["nama"] }}</div>
                                                                <small class="text-muted">NIM: {{ $row["nim"] ?: "-" }}</small>
                                                            </td>

                                                            @foreach ($periodeList as $periode)
                                                                @php
                                                                    $payment = $row["payments"][$periode] ?? null;
                                                                    $cellKey = $row["id"] . "-" . $periode;
                                                                    $isNewlyChecked = in_array($cellKey, $newlyChecked);
                                                                    $isNewlyUnchecked = in_array($cellKey, $newlyUnchecked);

                                                                    $bgClass = $isNewlyChecked ? "tw-bg-green-100 dark:tw-bg-green-100" : ($isNewlyUnchecked ? "tw-bg-red-100 dark:tw-bg-red-100" : "");
                                                                    $bgStyle = $isNewlyChecked ? "background-color: #d4edda !important;" : ($isNewlyUnchecked ? "background-color: #f8d7da !important;" : "");
                                                                @endphp

                                                                <td class="p-1 {{ $bgClass }}" style="{{ $bgStyle }}">
                                                                    @if ($this->can("iuran_kas.approve"))
                                                                        <div class="d-flex flex-column align-items-center">
                                                                            <div class="custom-control custom-checkbox mb-1" style="min-height: 1.5rem" wire:key="checkbox-anggota-{{ $row["id"] }}-{{ $periode }}-{{ $payment ? "lunas" : "kosong" }}">
                                                                                <input type="checkbox" class="custom-control-input" id="cb-ang-{{ $row["id"] }}-{{ $loop->index }}" wire:click.prevent="toggleStatus({{ $row["id"] }}, '{{ $periode }}', '{{ $row["nama"] }}')" {{ $payment && $payment["status"] === "lunas" ? "checked" : "" }} />
                                                                                <label class="custom-control-label" for="cb-ang-{{ $row["id"] }}-{{ $loop->index }}"></label>
                                                                            </div>

                                                                            <div wire:loading wire:target="toggleStatus({{ $row["id"] }}, '{{ $periode }}', '{{ $row["nama"] }}')" class="spinner-border spinner-border-sm text-primary spinner-sm" style="width: 14px; height: 14px; border-width: 0.15em" role="status">
                                                                                <span class="sr-only">Loading...</span>
                                                                            </div>

                                                                            <div wire:loading.remove wire:target="toggleStatus({{ $row["id"] }}, '{{ $periode }}', '{{ $row["nama"] }}')">
                                                                                @if ($payment && $payment["status"] === "lunas")
                                                                                    <small class="text-success font-weight-bold hover:text-primary tw-text-xs tw-cursor-pointer" wire:click="openEditDateModal({{ $payment["id"] }})" title="Klik untuk ubah tanggal">
                                                                                        {{ \Carbon\Carbon::parse($payment["tanggal_bayar"])->locale("id")->translatedFormat("d M") }}
                                                                                    </small>
                                                                                @else
                                                                                    <div style="height: 15px"></div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                            @endforeach

                                                            <td class="font-weight-bold text-success tw-whitespace-nowrap">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>Rp</span>
                                                                    <span>{{ number_format($row["total_bayar"], 0, ",", ".") }}</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @empty
                                                    <tr>
                                                        <td colspan="{{ count($periodeList) + 3 }}" class="text-center text-muted">Tidak ada anggota biasa aktif</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    @if ($anggotaPaginator)
                                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-start md:tw-items-center tw-gap-3 tw-mt-4">
                                            <small class="text-muted">Menampilkan {{ $anggotaPaginator->firstItem() ?? 0 }} - {{ $anggotaPaginator->lastItem() ?? 0 }} dari {{ $anggotaPaginator->total() }} anggota aktif</small>
                                            <div>
                                                {{ $anggotaPaginator->links() }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-bg-gray-50 tw-rounded tw-mt-3 tw-py-2 tw-px-3">
                <div class="d-flex justify-content-between tw-font-bold tw-text-green-700 tw-whitespace-nowrap">
                    <span>TOTAL KESELURUHAN</span>
                    <span>Rp {{ number_format($summary["total_keseluruhan"], 0, ",", ".") }}</span>
                </div>
            </div>
        </div>

        @if ($this->can("iuran_kas.create"))
            <!-- Floating Action Button for Generate -->
            <button wire:click="openGenerateModal" class="btn-modal" data-toggle="tooltip" title="Generate Periode Baru">
                <i class="far fa-plus"></i>
            </button>
        @endif
    </section>

    <!-- Modals (Generate & Rename) -->
    <div wire:ignore.self class="modal fade" id="generateModal" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="generateModalLabel">Generate Periode Baru</h5>
                    <button type="button" wire:click="closeGenerateModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-6">
                    <div class="form-group">
                        <label for="newPeriode">Nama Periode</label>
                        <input type="text" wire:model="newPeriode" id="newPeriode" class="form-control" placeholder="Contoh: 1-2" />
                        <small class="text-muted">Contoh: "1-2" atau "3-4" (Angka pertemuan)</small>
                        @error("newPeriode")
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="newNominal">Nominal per Orang (Rp)</label>
                        <input type="number" wire:model="newNominal" id="newNominal" class="form-control" min="0" />
                        @error("newNominal")
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeGenerateModal" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" wire:click="generatePeriode" class="btn btn-primary">Generate</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="renameModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="renameModalLabel">Edit Nama Periode</h5>
                    <button type="button" wire:click="closeRenameModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-6">
                    <div class="form-group">
                        <label for="renamePeriodeValue">Nama Periode</label>
                        <input type="text" wire:model="renamePeriodeValue" id="renamePeriodeValue" class="form-control" />
                        @error("renamePeriodeValue")
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="renameNominalValue">Nominal per Orang (Rp)</label>
                        <input type="number" wire:model="renameNominalValue" id="renameNominalValue" class="form-control" min="0" />
                        <small class="text-muted">Perhatian: Mengubah nominal ini akan memperbarui tagihan seluruh anggota yang sudah membayar di periode ini.</small>
                        @error("renameNominalValue")
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeRenameModal" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="renamePeriode" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Date Modal -->
    <div wire:ignore.self class="modal fade" id="editDateModal" tabindex="-1" role="dialog" aria-labelledby="editDateModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="editDateModalLabel">Edit Tanggal Bayar</h5>
                    <button type="button" wire:click="closeEditDateModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-6">
                    <div class="form-group">
                        <label for="editTanggalValue">Tanggal Pembayaran</label>
                        <input type="date" wire:model="editTanggalValue" id="editTanggalValue" class="form-control" />
                        @error("editTanggalValue")
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeEditDateModal" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="updateDate" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for modal events are in app.blade.php or handle here if needed, but previously we added them -->
    <!-- Daily History Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h4>Riwayat Pemasukan Harian</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-auto">
                    <thead>
                        <tr>
                            <th class="text-center" width="8%">No</th>
                            <th>Tanggal</th>
                            <th class="text-center">Jumlah Transaksi</th>
                            <th class="text-right">Total Masuk</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyHistory as $index => $history)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->date)->locale("id")->translatedFormat("l, d F Y") }}</td>
                                <td class="text-center">{{ $history->count }}</td>
                                <td class="font-weight-bold text-success">
                                    <div class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($history->total, 0, ",", ".") }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info" wire:click="openHistoryDetail('{{ $history->date }}')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data pemasukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- History Detail Modal -->
    <div wire:ignore.self class="modal fade" id="historyDetailModal" tabindex="-1" role="dialog" aria-labelledby="historyDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="historyDetailModalLabel">Detail Pemasukan: {{ $detailDate ? \Carbon\Carbon::parse($detailDate)->locale("id")->translatedFormat("d F Y") : "-" }}</h5>
                    <button type="button" wire:click="closeHistoryDetail" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-4 lg:tw-px-0">
                    <div class="table-responsive tw-table-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Nama Anggota</th>
                                    <th class="tw-whitespace-nowrap">Pertemuan</th>
                                    <th class="tw-whitespace-nowrap">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detailMembers as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->anggota->nama_lengkap ?? "-" }}</td>
                                        <td class="tw-whitespace-nowrap tw-text-center">{{ $item->periode }}</td>
                                        <td class="tw-whitespace-nowrap">
                                            <div class="d-flex justify-content-between">
                                                <span>Rp</span>
                                                <span>{{ number_format($item->nominal, 0, ",", ".") }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeHistoryDetail" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
            function initJurusanFilterSelect2() {
                const $select = $('#jurusan-filter-select');
                if (!$select.length || typeof $select.select2 !== 'function') {
                    return;
                }

                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }

                $select.select2({
                    width: '100%'
                });

                $select.on('change', function() {
                    const selectedValue = $(this).val() || '';
                    @this.set('selectedJurusan', selectedValue);
                });
            }

            document.addEventListener('livewire:initialized', () => {
                initJurusanFilterSelect2();
            });

            window.addEventListener('open-modal', (event) => {
                $('#' + event.detail[0].id).modal('show');
            });
            window.addEventListener('close-modal', (event) => {
                $('#' + event.detail[0].id).modal('hide');
            });
            window.addEventListener('swal:confirmPayment', (event) => {
                Swal.fire({
                    title: event.detail[0].message,
                    text: event.detail[0].text,
                    icon: event.detail[0].type,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, batalkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('destroyPayment');
                    } else {
                        // Re-render livewire component to reset the checkbox UI logically
                        Livewire.dispatch('$refresh');
                    }
                });
            });
        </script>
    @endpush
</div>
