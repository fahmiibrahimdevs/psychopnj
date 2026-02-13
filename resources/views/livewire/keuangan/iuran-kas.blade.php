<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Iuran Kas</h1>
            <div class="section-header-breadcrumb">
                <div class="d-flex align-items-center">
                    @if ($this->can("iuran_kas.export"))
                        <div>
                            <button wire:click="downloadPdf" class="btn btn-danger btn-icon icon-left">
                                <i class="fas fa-file-pdf"></i>
                                Export PDF
                            </button>
                            <button wire:click="downloadExcel" class="btn btn-success btn-icon icon-left">
                                <i class="fas fa-file-excel"></i>
                                Export Excel
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Tabel Iuran Kas</h4>
                    <div class="card-header-form">
                        <input type="search" wire:model.live.debounce.300ms="searchTerm" id="search-data" placeholder="Cari nama anggota..." class="form-control tw-py-5" />
                    </div>
                </div>
                <div class="card-body">
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
                                <!-- Group: PENGURUS -->
                                <tr class="tw-bg-gray-100">
                                    <td colspan="{{ count($periodeList) + 3 }}" class="tw-font-bold tw-py-2 tw-px-3 tw-text-gray-700 tw-tracking-wider tw-text-sm">ROLE: PENGURUS</td>
                                </tr>
                                @forelse ($matrix["pengurus"] as $index => $row)
                                    <tr class="text-center">
                                        <td class="">{{ $index + 1 }}</td>
                                        <td class="text-left">{{ $row["nama"] }}</td>

                                        @foreach ($periodeList as $periode)
                                            @php
                                                $payment = $row["payments"][$periode] ?? null;
                                            @endphp

                                            <td class="p-1">
                                                @if ($this->can("iuran_kas.approve"))
                                                    <div class="d-flex flex-column align-items-center">
                                                        <div class="custom-control custom-checkbox mb-1" style="min-height: 1.5rem">
                                                            <input type="checkbox" class="custom-control-input" id="cb-{{ $row["id"] }}-{{ $loop->index }}" wire:click="toggleStatus({{ $row["id"] }}, '{{ $periode }}')" {{ $payment && $payment["status"] === "lunas" ? "checked" : "" }} />
                                                            <label class="custom-control-label" for="cb-{{ $row["id"] }}-{{ $loop->index }}"></label>
                                                        </div>

                                                        @if ($payment && $payment["status"] === "lunas")
                                                            <small class="text-success font-weight-bold cursor-pointer hover:text-primary tw-text-xs tw-cursor-pointer" wire:click="openEditDateModal({{ $payment["id"] }})" title="Klik untuk ubah tanggal">
                                                                {{ \Carbon\Carbon::parse($payment["tanggal_bayar"])->locale("id")->translatedFormat("d M") }}
                                                            </small>
                                                        @else
                                                            <div style="height: 15px"></div>
                                                        @endif
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
                                @empty
                                    <tr>
                                        <td colspan="{{ count($periodeList) + 3 }}" class="text-center text-muted tw-text-base">Tidak ada pengurus aktif</td>
                                    </tr>
                                @endforelse

                                <!-- Group: ANGGOTA -->
                                <tr class="tw-bg-gray-100">
                                    <td colspan="{{ count($periodeList) + 3 }}" class="tw-font-bold tw-py-2 tw-px-3 tw-text-gray-700 tw-tracking-wider tw-text-sm">ROLE: ANGGOTA</td>
                                </tr>
                                @forelse ($matrix["anggota"] as $index => $row)
                                    <tr class="text-center">
                                        <td class="">{{ $index + 1 }}</td>
                                        <td class="text-left">{{ $row["nama"] }}</td>

                                        @foreach ($periodeList as $periode)
                                            @php
                                                $payment = $row["payments"][$periode] ?? null;
                                            @endphp

                                            <td class="p-1">
                                                @if ($this->can("iuran_kas.approve"))
                                                    <div class="d-flex flex-column align-items-center">
                                                        <div class="custom-control custom-checkbox mb-1" style="min-height: 1.5rem">
                                                            <input type="checkbox" class="custom-control-input" id="cb-ang-{{ $row["id"] }}-{{ $loop->index }}" wire:click="toggleStatus({{ $row["id"] }}, '{{ $periode }}')" {{ $payment && $payment["status"] === "lunas" ? "checked" : "" }} />
                                                            <label class="custom-control-label" for="cb-ang-{{ $row["id"] }}-{{ $loop->index }}"></label>
                                                        </div>

                                                        @if ($payment && $payment["status"] === "lunas")
                                                            <small class="text-success font-weight-bold hover:text-primary tw-text-xs tw-cursor-pointer" wire:click="openEditDateModal({{ $payment["id"] }})" title="Klik untuk ubah tanggal">
                                                                {{ \Carbon\Carbon::parse($payment["tanggal_bayar"])->locale("id")->translatedFormat("d M") }}
                                                            </small>
                                                        @else
                                                            <div style="height: 15px"></div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach

                                        <td class="font-weight-bold text-success tw-whitespace-nowrap">
                                            <div class="d-flex justify-content-between">
                                                <span class="">Rp</span>
                                                <span>{{ number_format($row["total_bayar"], 0, ",", ".") }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($periodeList) + 3 }}" class="text-center text-muted">Tidak ada anggota biasa aktif</td>
                                    </tr>
                                @endforelse

                                <!-- Footer Total -->
                                <tr class="tw-bg-gray-50">
                                    <td colspan="{{ count($periodeList) + 2 }}" class="text-right font-weight-bold py-3 text-uppercase">TOTAL KESELURUHAN</td>
                                    <td class="font-weight-bold text-success h5 tw-whitespace-nowrap">
                                        <div class="d-flex justify-content-between">
                                            <span>Rp</span>
                                            <span>{{ number_format($summary["total_keseluruhan"], 0, ",", ".") }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                        <label for="nominalDefault">Nominal per Orang (Rp)</label>
                        <input type="number" wire:model="nominalDefault" id="nominalDefault" class="form-control" min="0" />
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
                        @forelse ($this->getDailyHistoryProperty() as $index => $history)
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

    @push("scripts")
        <script>
            window.addEventListener('open-modal', (event) => {
                $('#' + event.detail[0].id).modal('show');
            });
            window.addEventListener('close-modal', (event) => {
                $('#' + event.detail[0].id).modal('hide');
            });
        </script>
    @endpush
</div>
