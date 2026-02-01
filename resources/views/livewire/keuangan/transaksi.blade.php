<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Transaksi Keuangan</h1>
            <div class="section-header-breadcrumb">
                <div class="d-flex align-items-center">
                    <div>
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
            <!-- Summary Cards -->
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4 tw-mb-4 tw-px-4 lg:tw-px-0">
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Total Pemasukan</p>
                            <h4 class="tw-text-xl tw-font-bold tw-text-green-600">Rp {{ number_format($totalPemasukan, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-arrow-down tw-text-green-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Total Pengeluaran</p>
                            <h4 class="tw-text-xl tw-font-bold tw-text-red-600">Rp {{ number_format($totalPengeluaran, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-arrow-up tw-text-red-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Saldo Saat Ini</p>
                            <h4 class="tw-text-xl tw-font-bold {{ $saldoAkhir >= 0 ? "tw-text-blue-600" : "tw-text-red-600" }}">Rp {{ number_format($saldoAkhir, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-wallet tw-text-blue-500 tw-text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="card">
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
                    <div class="tw-flex tw-gap-2 tw-mb-4 tw-flex-wrap">
                        <select wire:model.live="filterJenis" class="form-control tw-w-auto">
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
                                    <th class="tw-whitespace-nowrap">Jenis</th>
                                    <th class="tw-whitespace-nowrap">Kategori</th>
                                    <th class="tw-whitespace-nowrap">Deskripsi</th>
                                    <th class="tw-whitespace-nowrap text-right">Pemasukan</th>
                                    <th class="tw-whitespace-nowrap text-right">Pengeluaran</th>
                                    <th class="tw-whitespace-nowrap text-right">Saldo</th>
                                    <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $row)
                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td class="text-left tw-whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tanggal)->format("d M Y") }}</td>
                                        <td class="text-left tw-whitespace-nowrap">
                                            <span class="tw-px-2 tw-py-1 tw-rounded tw-text-xs tw-font-medium {{ $row->jenis === "pemasukan" ? "tw-bg-green-100 tw-text-green-700" : "tw-bg-red-100 tw-text-red-700" }}">
                                                {{ ucfirst($row->jenis) }}
                                            </span>
                                        </td>
                                        <td class="text-left tw-whitespace-nowrap">{{ $this->getKategoriLabel($row->kategori) }}</td>
                                        <td class="text-left">
                                            {{ $row->deskripsi }}

                                            @if ($row->kategori === "dept" && $row->department)
                                                <br />
                                                <small class="tw-text-gray-500">{{ $row->department->nama_department }}</small>
                                            @elseif ($row->kategori === "project" && $row->project)
                                                <br />
                                                <small class="tw-text-gray-500">{{ $row->project->nama_project }}</small>
                                            @endif
                                        </td>
                                        <td class="text-right tw-text-green-600 tw-font-medium tw-whitespace-nowrap">
                                            {{ $row->jenis === "pemasukan" ? "Rp " . number_format($row->nominal, 0, ",", ".") : "-" }}
                                        </td>
                                        <td class="text-right tw-text-red-600 tw-font-medium tw-whitespace-nowrap">
                                            {{ $row->jenis === "pengeluaran" ? "Rp " . number_format($row->nominal, 0, ",", ".") : "-" }}
                                        </td>
                                        <td class="text-right tw-font-semibold tw-whitespace-nowrap {{ ($runningTotals[$row->id] ?? 0) >= 0 ? "tw-text-blue-600" : "tw-text-red-600" }}">Rp {{ number_format($runningTotals[$row->id] ?? 0, 0, ",", ".") }}</td>
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
                                        <td colspan="9" class="text-center">Tidak ada transaksi</td>
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
                                    <select wire:model.live="jenis" id="jenis" class="form-control">
                                        <option value="" disabled>-- Pilih Jenis --</option>
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
                            <select wire:model.live="kategori" id="kategori" class="form-control">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                @if ($jenis === "pemasukan")
                                    <option value="saldo_awal">Saldo Awal</option>
                                    <option value="iuran_kas">Iuran Kas</option>
                                    <option value="sponsor">Sponsor</option>
                                    <option value="lainnya">Lainnya</option>
                                @elseif ($jenis === "pengeluaran")
                                    <option value="dept">Departemen</option>
                                    <option value="project">Project/Kegiatan</option>
                                    <option value="lainnya">Lainnya</option>
                                @endif
                            </select>
                            @error("kategori")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($kategori === "dept")
                            <div class="form-group">
                                <label for="id_department">Departemen</label>
                                <select wire:model="id_department" id="id_department" class="form-control">
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

                        @if ($kategori === "project")
                            <div class="form-group">
                                <label for="id_project">Project/Kegiatan</label>
                                <select wire:model="id_project" id="id_project" class="form-control">
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
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
