<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Peminjaman Barang</h1>
        </div>

        <div class="section-body tw-mt-4 lg:-tw-mt-2">
            {{-- Tabs --}}
            <ul class="nav nav-pills tw-mb-4 tw-px-4 lg:tw-px-0">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "dipinjam" ? "active" : "" }}" href="#" wire:click.prevent="setTab('dipinjam')">Sedang Dipinjam</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "riwayat" ? "active" : "" }}" href="#" wire:click.prevent="setTab('riwayat')">Riwayat</a>
                </li>
            </ul>
            <div class="card">
                <h3>Tabel Peminjaman Barang</h3>
                <div class="card-body">
                    <div class="tw-flex tw-flex-wrap tw-gap-3 tw-mb-4">
                        <div class="show-entries">
                            <p class="show-entries-show">Show</p>
                            <select wire:model.live="lengthData" id="length-data">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <p class="show-entries-entries">Entries</p>
                        </div>
                    </div>

                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Cari nama peminjam, keperluan..." class="form-control" />
                    </div>

                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="5%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Peminjam</th>
                                    <th width="30%" class="tw-whitespace-nowrap">Barang</th>
                                    {{-- <th class="tw-whitespace-nowrap text-center">Tgl Pinjam</th> --}}
                                    @if ($activeTab === "riwayat")
                                        <th class="tw-whitespace-nowrap text-center">Tgl Kembali</th>
                                    @endif

                                    <th class="tw-whitespace-nowrap">Keperluan</th>
                                    <th class="tw-whitespace-nowrap">Pencatat</th>
                                    <th class="tw-whitespace-nowrap">Dibuat Oleh</th>
                                    <th width="15%" class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentDate = null;
                                @endphp

                                @forelse ($data as $row)
                                    @if ($currentDate !== $row->tanggal_pinjam->format("Y-m-d"))
                                        @php
                                            $currentDate = $row->tanggal_pinjam->format("Y-m-d");
                                        @endphp

                                        <tr class="tw-bg-gray-50">
                                            <th colspan="{{ $activeTab === "riwayat" ? 8 : 7 }}" class="text-left tw-px-4 tw-py-2.5 tw-text-gray-700">
                                                {{ $row->tanggal_pinjam->format("d F Y") }}
                                            </th>
                                        </tr>
                                    @endif

                                    <tr class="text-center">
                                        <td>{{ $loop->index + 1 + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                        <td class="text-left">
                                            <div class="tw-flex tw-items-center">
                                                <img alt="image" src="{{ asset("/assets/stisla/img/avatar/avatar-1.png") }}" class="tw-rounded-full tw-w-10 tw-h-10 tw-mr-3 tw-object-cover" />
                                                <div class="font-bagus">
                                                    <div class="tw-font-normal tw-tracking-normal tw-text-sm">{{ $row->nama_peminjam }}</div>
                                                    @if ($row->kontak_peminjam)
                                                        <small class="text-muted tw-text-xs">{{ $row->kontak_peminjam }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-left">
                                            @foreach ($row->details as $detail)
                                                <span class="badge font-bagus tw-text-xs tw-tracking-wide tw-font-normal tw-mr-1 tw-mb-1">- {{ $detail->barang->nama }} ({{ $detail->jumlah }} {{ $detail->barang->satuan }})</span>
                                            @endforeach
                                        </td>
                                        {{-- <td>{{ $row->tanggal_pinjam->format("d/m/Y") }}</td> --}}
                                        @if ($activeTab === "riwayat")
                                            <td>{{ $row->tanggal_kembali ? $row->tanggal_kembali->format("d/m/Y") : "-" }}</td>
                                        @endif

                                        <td class="text-left">{{ $row->keperluan }}</td>
                                        <td class="text-left">{{ $row->pencatat->nama_lengkap ?? "-" }}</td>
                                        <td class="text-left tw-text-sm tw-whitespace-nowrap">
                                            <span class="tw-text-gray-600">{{ $row->user->name ?? "-" }}</span>
                                        </td>
                                        <td class="tw-whitespace-nowrap">
                                            @if ($this->can("peminjaman_barang.view_detail"))
                                                <button wire:click.prevent="showDetailModal({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#detailModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @endif

                                            @if ($row->status === "dipinjam" && $this->can("peminjaman_barang.return"))
                                                <button wire:click.prevent="kembalikan({{ $row->id }})" class="btn btn-primary" title="Kembalikan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif

                                            @if ($this->can("peminjaman_barang.delete"))
                                                <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $activeTab === "riwayat" ? 8 : 7 }}" class="text-center">
                                            @if ($activeTab === "dipinjam")
                                                Tidak ada barang yang sedang dipinjam
                                            @else
                                                Belum ada riwayat peminjaman
                                            @endif
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
        @if ($this->can("peminjaman_barang.create"))
            <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
                <i class="far fa-plus"></i>
            </button>
        @endif
    </section>

    {{-- Modal Form Peminjaman --}}
    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">Catat Peminjaman Baru</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama_peminjam">
                                        Nama Peminjam
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="nama_peminjam" id="nama_peminjam" class="form-control" placeholder="Nama lengkap peminjam" />
                                    @error("nama_peminjam")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kontak_peminjam">Kontak (HP/WA)</label>
                                    <input type="text" wire:model="kontak_peminjam" id="kontak_peminjam" class="form-control" placeholder="08xxxxxxxxxx" />
                                    @error("kontak_peminjam")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_pinjam">
                                        Tanggal Pinjam
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" wire:model="tanggal_pinjam" id="tanggal_pinjam" class="form-control" />
                                    @error("tanggal_pinjam")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keperluan">
                                        Keperluan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="keperluan" id="keperluan" class="form-control" placeholder="Untuk kegiatan apa?" />
                                    @error("keperluan")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <fieldset class="border rounded p-3 mb-3">
                            <legend class="w-auto px-2 mb-0" style="font-size: 14px; font-weight: 600">
                                Barang yang Dipinjam
                                <span class="text-danger">*</span>
                            </legend>

                            @error("selectedBarangs")
                                <span class="text-danger tw-block tw-mb-2">{{ $message }}</span>
                            @enderror

                            <table class="table table-bordered tw-mb-3">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th width="20%">Jumlah</th>
                                        <th width="15%">Tersedia</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedBarangs as $index => $item)
                                        <tr>
                                            <td>
                                                <select wire:model="selectedBarangs.{{ $index }}.barang_id" class="form-control form-control-sm">
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach ($barangList as $barang)
                                                        <option value="{{ $barang["id"] }}" {{ $barang["stok_tersedia"] <= 0 ? "disabled" : "" }}>[{{ $barang["kode"] }}] {{ $barang["nama"] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" wire:model="selectedBarangs.{{ $index }}.jumlah" class="form-control form-control-sm" min="1" />
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $selectedBarang = collect($barangList)->firstWhere("id", $item["barang_id"]);
                                                @endphp

                                                @if ($selectedBarang)
                                                    <span class="badge {{ $selectedBarang["stok_tersedia"] > 0 ? "badge-success" : "badge-danger" }}">{{ $selectedBarang["stok_tersedia"] }} {{ $selectedBarang["satuan"] }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (count($selectedBarangs) > 1)
                                                    <button type="button" wire:click="removeBarangRow({{ $index }})" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <button type="button" wire:click="addBarangRow" class="btn btn-secondary btn-sm">
                                <i class="fas fa-plus"></i>
                                Tambah Barang
                            </button>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Tutup</button>
                        <button type="submit" wire:click.prevent="store()" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">
                            <span wire:loading.remove>Simpan Peminjaman</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Detail --}}
    <div class="modal fade" wire:ignore.self id="detailModal" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="detailModalLabel">Detail Peminjaman</h5>
                    <button type="button" wire:click="closeDetailModal()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-p-0">
                    @if ($detailData)
                        <div class="tw-bg-gray-50 tw-p-6 tw-border-b tw-border-gray-100">
                            <div class="tw-flex tw-justify-between tw-items-start">
                                <div class="tw-flex tw-items-center">
                                    <img alt="image" src="{{ asset("/assets/stisla/img/avatar/avatar-1.png") }}" class="tw-rounded-full tw-w-16 tw-h-16 tw-object-cover tw-mr-4 tw-shadow-sm" />
                                    <div>
                                        <h4 class="tw-font-bold tw-text-gray-800 tw-mb-1" style="font-size: 1.1rem">{{ $detailData->nama_peminjam }}</h4>
                                        <div class="tw-flex tw-items-center tw-text-gray-500 tw-text-sm">
                                            <i class="far fa-id-card tw-mr-2"></i>
                                            {{ $detailData->kontak_peminjam ?? "Tidak ada kontak" }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($detailData->status === "dipinjam")
                                        <span class="tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-bold tw-bg-yellow-100 tw-text-yellow-700">SEDANG DIPINJAM</span>
                                    @else
                                        <span class="tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-bold tw-bg-green-100 tw-text-green-700">SUDAH DIKEMBALIKAN</span>
                                    @endif
                                    <div class="tw-mt-2 tw-text-xs tw-text-gray-400">Pencatat: {{ $detailData->pencatat->nama_lengkap ?? "-" }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="tw-p-6">
                            <div class="tw-grid tw-grid-cols-2 tw-gap-y-4 tw-gap-x-8 tw-mb-8">
                                <div>
                                    <div class="tw-text-xs tw-text-gray-400 tw-font-bold tw-uppercase tw-mb-1">Tanggal Pinjam</div>
                                    <div class="tw-text-gray-800 tw-font-medium">
                                        <i class="far fa-calendar-alt tw-mr-2 tw-text-blue-500"></i>
                                        {{ $detailData->tanggal_pinjam->format("d F Y") }}
                                    </div>
                                </div>
                                <div>
                                    <div class="tw-text-xs tw-text-gray-400 tw-font-bold tw-uppercase tw-mb-1">Tanggal Kembali</div>
                                    <div class="tw-text-gray-800 tw-font-medium">
                                        <i class="far fa-calendar-check tw-mr-2 tw-text-green-500"></i>
                                        {{ $detailData->tanggal_kembali ? $detailData->tanggal_kembali->format("d F Y") : "-" }}
                                    </div>
                                </div>
                                <div class="tw-col-span-2 tw-mt-4">
                                    <div class="tw-text-xs tw-text-gray-400 tw-font-bold tw-uppercase tw-mb-1">Keperluan</div>
                                    <div class="tw-text-gray-800 tw-font-medium tw-leading-relaxed">
                                        {{ $detailData->keperluan }}
                                    </div>
                                </div>
                            </div>

                            <div class="tw-mb-2">
                                <div class="tw-text-xs tw-text-gray-400 tw-font-bold tw-uppercase tw-mb-3">Barang yang Dipinjam</div>
                                <div class="tw-bg-white tw-border tw-rounded-lg tw-overflow-hidden">
                                    <table class="tw-w-full tw-table-auto">
                                        <thead class="tw-bg-gray-50 tw-border-b">
                                            <tr>
                                                <th class="tw-px-4 tw-py-2 tw-text-xs tw-font-semibold tw-text-gray-500 tw-text-left">KODE</th>
                                                <th class="tw-px-4 tw-py-2 tw-text-xs tw-font-semibold tw-text-gray-500 tw-text-left">NAMA BARANG</th>
                                                <th class="tw-px-4 tw-py-2 tw-text-xs tw-font-semibold tw-text-gray-500 tw-text-right">JUMLAH</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tw-divide-y tw-divide-gray-100">
                                            @foreach ($detailData->details as $item)
                                                <tr class="tw-group hover:tw-bg-blue-50 tw-transition-colors">
                                                    <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-blue-600">{{ $item->barang->kode }}</td>
                                                    <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-gray-700 tw-font-medium">{{ $item->barang->nama }}</td>
                                                    <td class="tw-px-4 tw-py-3 tw-text-sm tw-text-gray-700 tw-text-right">
                                                        <span class="tw-bg-gray-100 tw-px-2 tw-py-1 tw-rounded tw-text-xs tw-font-bold">{{ $item->jumlah }} {{ $item->barang->satuan }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if ($detailData->catatan)
                                <div class="tw-mt-4 tw-p-3 tw-bg-yellow-50 tw-text-yellow-800 tw-rounded-md tw-text-sm tw-border tw-border-yellow-100">
                                    <i class="fas fa-sticky-note tw-mr-2"></i>
                                    <strong>Catatan:</strong>
                                    {{ $detailData->catatan }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeDetailModal()" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("scripts")
    <script>
        window.addEventListener('swal:confirmKembali', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Sudah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('prosesKembalikan');
                }
            });
        });
    </script>
@endpush
