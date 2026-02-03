<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Pengadaan Barang</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Tabel Pengadaan Barang</h3>
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

                        <div class="tw-flex tw-items-center tw-gap-2">
                            <select wire:model.live="filterStatus" class="form-control form-control-sm">
                                <option value="">Semua Status</option>
                                <option value="diusulkan">Diusulkan</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Cari nama barang..." class="form-control" />
                    </div>

                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="5%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Detail Barang</th>
                                    <th class="tw-whitespace-nowrap text-right">Total</th>
                                    <th class="tw-whitespace-nowrap">Pengusul</th>
                                    <th class="tw-whitespace-nowrap text-center">Status</th>
                                    <th class="tw-whitespace-nowrap">Dibuat Oleh</th>
                                    <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $currentCategory = null;
                                    $groupTotal = 0;
                                    $groupedData = [];

                                    // Group data by category
                                    foreach ($data as $item) {
                                        $categoryKey = "lainnya";
                                        $categoryLabel = "Lainnya";

                                        if ($item->department_id) {
                                            $categoryKey = "dept_" . $item->department_id;
                                            $categoryLabel = "Dept. " . $item->department->nama_department;
                                        } elseif ($item->project_id) {
                                            $categoryKey = "project_" . $item->project_id;
                                            $categoryLabel = "Project/Kegiatan: " . $item->project->nama_project;
                                        }

                                        if (! isset($groupedData[$categoryKey])) {
                                            $groupedData[$categoryKey] = [
                                                "label" => $categoryLabel,
                                                "items" => [],
                                                "total" => 0,
                                            ];
                                        }

                                        $groupedData[$categoryKey]["items"][] = $item;
                                        $groupedData[$categoryKey]["total"] += $item->total;
                                    }
                                @endphp

                                @forelse ($groupedData as $categoryKey => $group)
                                    {{-- Category Header --}}
                                    <tr>
                                        <td colspan="7" class="tw-bg-gray-50 tw-font-semibold tw-tracking-wider tw-text-left tw-px-4 tw-py-2 tw-text-gray-700">
                                            {{ $group["label"] }}
                                        </td>
                                    </tr>

                                    {{-- Items in this category --}}
                                    @foreach ($group["items"] as $row)
                                        <tr class="text-center">
                                            <td>{{ $loop->parent->index * count($group["items"]) + $loop->iteration }}</td>
                                            <td class="text-left">
                                                <div class="tw-font-normal tw-text-gray-800 tw-tracking-normal">
                                                    {{ $row->nama_barang }}
                                                    @if ($row->link_pembelian)
                                                        <a href="{{ $row->link_pembelian }}" target="_blank" class="tw-ml-1 text-info" title="Lihat Link">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="tw-text-sm tw-text-gray-500 tw-mt-1">{{ $row->jumlah }} x Rp {{ number_format($row->harga, 0, ",", ".") }}</div>
                                            </td>
                                            <td class="text-right">Rp {{ number_format($row->total, 0, ",", ".") }}</td>
                                            <td class="text-left">{{ $row->pengusul->nama_lengkap ?? "-" }}</td>
                                            <td>
                                                @if ($row->status === "diusulkan")
                                                    <span class="badge tw-bg-yellow-100 tw-text-yellow-800">Diusulkan</span>
                                                @elseif ($row->status === "disetujui")
                                                    <span class="badge tw-bg-green-100 tw-text-green-800">Disetujui</span>
                                                @elseif ($row->status === "ditolak")
                                                    <span class="badge tw-bg-red-100 tw-text-red-800">Ditolak</span>
                                                @elseif ($row->status === "selesai")
                                                    <span class="badge tw-bg-blue-100 tw-text-blue-800">Selesai</span>
                                                @endif
                                            </td>
                                            <td class="text-left tw-text-sm tw-whitespace-nowrap">
                                                <span class="tw-text-gray-600">{{ $row->user->name ?? '-' }}</span>
                                            </td>
                                            <td class="tw-whitespace-nowrap">
                                                @if ($row->status === "diusulkan")
                                                    <button wire:click.prevent="approveConfirm({{ $row->id }})" class="btn btn-success" title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button wire:click.prevent="rejectConfirm({{ $row->id }})" class="btn btn-warning" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @elseif ($row->status === "disetujui")
                                                    <button wire:click.prevent="markAsSelesai({{ $row->id }})" class="btn btn-info" title="Tandai Selesai">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </button>
                                                    <button wire:click.prevent="rollbackConfirm({{ $row->id }})" class="btn btn-warning" title="Batalkan Persetujuan">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @endif
                                                <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    {{-- Total Row for this category --}}
                                    <tr class="tw-bg-gray-100 tw-font-semibold">
                                        <td colspan="2" class="text-right tw-px-4 tw-py-2">Total {{ $group["label"] }}:</td>
                                        <td class="text-right tw-px-4 tw-py-2">Rp {{ number_format($group["total"], 0, ",", ".") }}</td>
                                        <td colspan="4"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pengadaan barang</td>
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

    {{-- Modal Form --}}
    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-max-w-lg sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-border-0 tw-shadow-lg tw-rounded-lg">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Pengadaan" : "Tambah Pengadaan Barang" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-p-6">
                        <div class="form-group">
                            <label class="d-block mb-3">Kategori Anggaran</label>
                            <div class="tw-grid tw-grid-cols-3 tw-gap-3">
                                <label class="tw-cursor-pointer tw-mb-0">
                                    <input type="radio" wire:model.live="kategori_anggaran" value="lainnya" class="tw-hidden tw-peer" />
                                    <div class="tw-text-center tw-px-3 tw-py-2 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-text-gray-600 peer-checked:tw-bg-blue-500 peer-checked:tw-text-white peer-checked:tw-border-blue-600 tw-transition-all">Lainnya</div>
                                </label>
                                <label class="tw-cursor-pointer tw-mb-0">
                                    <input type="radio" wire:model.live="kategori_anggaran" value="dept" class="tw-hidden tw-peer" />
                                    <div class="tw-text-center tw-px-3 tw-py-2 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-text-gray-600 peer-checked:tw-bg-blue-500 peer-checked:tw-text-white peer-checked:tw-border-blue-600 tw-transition-all">Divisi</div>
                                </label>
                                <label class="tw-cursor-pointer tw-mb-0">
                                    <input type="radio" wire:model.live="kategori_anggaran" value="project" class="tw-hidden tw-peer" />
                                    <div class="tw-text-center tw-px-3 tw-py-2 tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-text-gray-600 peer-checked:tw-bg-blue-500 peer-checked:tw-text-white peer-checked:tw-border-blue-600 tw-transition-all">Proyek</div>
                                </label>
                            </div>
                        </div>

                        @if ($kategori_anggaran === "dept")
                            <div class="form-group tw-animate-fade-in-down">
                                <label for="department_id">
                                    Pilih Department
                                    <span class="text-danger">*</span>
                                </label>
                                <select wire:model="department_id" id="department_id" class="form-control">
                                    <option value="">-- Pilih Department --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                                    @endforeach
                                </select>
                                @error("department_id")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        @if ($kategori_anggaran === "project")
                            <div class="form-group tw-animate-fade-in-down">
                                <label for="project_id">
                                    Pilih Project
                                    <span class="text-danger">*</span>
                                </label>
                                <select wire:model="project_id" id="project_id" class="form-control">
                                    <option value="">-- Pilih Project --</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->nama_project }}</option>
                                    @endforeach
                                </select>
                                @error("project_id")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group big-input">
                            <label for="nama_barang">
                                Nama Barang
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" wire:model="nama_barang" id="nama_barang" class="form-control" placeholder="Contoh: Lampu LED, Kertas A4" />
                            @error("nama_barang")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                            <div class="form-group">
                                <label for="jumlah">
                                    Jumlah
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" wire:model.live="jumlah" id="jumlah" class="form-control" min="1" />
                                @error("jumlah")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="harga">
                                    Harga Satuan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" wire:model.lazy="harga" id="harga" class="form-control" min="0" />
                                </div>
                                @error("harga")
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="total">Total Estimasi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" wire:model.live="total" id="total" class="form-control" min="0" />
                            </div>
                            @error("total")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="link_pembelian">
                                Link Pembelian
                                <span class="text-muted tw-font-normal">(Opsional)</span>
                            </label>
                            <input type="url" wire:model="link_pembelian" id="link_pembelian" class="form-control" placeholder="https://..." />
                            @error("link_pembelian")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer tw-bg-gray-50 tw-px-6 tw-py-4 tw-rounded-b-lg">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300 tw-border-0 tw-mr-2" data-dismiss="modal">Batal</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-600 tw-border-0 hover:tw-bg-blue-700">
                            <span wire:loading.remove>Simpan</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push("scripts")
    <script>
        window.addEventListener('swal:confirmApprove', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('approve');
                }
            });
        });

        window.addEventListener('swal:confirmReject', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
                input: 'textarea',
                inputLabel: 'Alasan Penolakan (opsional)',
                inputPlaceholder: 'Tulis alasan penolakan...',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.set('catatan', result.value || '');
                    @this.call('reject');
                }
            });
        });

        window.addEventListener('swal:confirmRollback', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('rollback');
                }
            });
        });
    </script>
@endpush
