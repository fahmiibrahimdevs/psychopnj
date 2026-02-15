<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Project/Kegiatan</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Daftar Project/Kegiatan</h3>
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

            <!-- Card Grid View -->
            <div class="-tw-mt-2 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 xl:tw-grid-cols-3 tw-gap-4 tw-px-4 lg:tw-px-0">
                @forelse ($data as $row)
                    @php
                        $colors = [
                            ["from" => "tw-from-cyan-400", "to" => "tw-to-blue-500", "text" => "tw-text-cyan-300"],
                            ["from" => "tw-from-green-400", "to" => "tw-to-emerald-500", "text" => "tw-text-green-300"],
                            ["from" => "tw-from-purple-400", "to" => "tw-to-pink-500", "text" => "tw-text-purple-300"],
                            ["from" => "tw-from-orange-400", "to" => "tw-to-red-500", "text" => "tw-text-orange-300"],
                        ];
                        $colorSet = $colors[$loop->index % 4];

                        $statusColors = [
                            "draft" => "tw-bg-gray-100 tw-text-gray-600",
                            "berjalan" => "tw-bg-green-100 tw-text-green-600",
                            "selesai" => "tw-bg-blue-100 tw-text-blue-600",
                            "ditunda" => "tw-bg-yellow-100 tw-text-yellow-600",
                        ];
                        $statusColor = $statusColors[$row->status] ?? "tw-bg-gray-100 tw-text-gray-600";
                    @endphp

                    <div class="tw-bg-white tw-rounded-xl tw-p-4 tw-shadow-md tw-shadow-gray-300 tw-transition-all tw-duration-300 tw-flex tw-flex-col">
                        <div class="tw-flex tw-items-start tw-justify-between tw-mb-3">
                            <div class="tw-flex tw-items-center">
                                <div class="tw-bg-gradient-to-br {{ $colorSet["from"] }} {{ $colorSet["to"] }} tw-rounded-lg tw-p-2.5 tw-mr-3">
                                    <i class="fas fa-project-diagram tw-text-white tw-text-lg"></i>
                                </div>
                                <div>
                                    <h5 class="tw-text-base tw-font-semibold tw-text-gray-800 tw-leading-tight">{{ Str::limit($row->nama_project, 25) }}</h5>
                                </div>
                            </div>
                            <span class="tw-px-2 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold {{ $statusColor }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </div>

                        <p class="font-bagus tw-tracking-normal tw-text-sm tw-font-normal tw-leading-relaxed tw-mb-3 tw-flex-grow">{{ Str::limit($row->deskripsi, 80) }}</p>

                        <div class="tw-flex tw-flex-wrap tw-gap-3 tw-text-xs tw-text-gray-500 tw-mb-3">
                            <span>
                                <i class="fas fa-layer-group tw-mr-1"></i>
                                {{ $row->teams_count }} Kelompok
                            </span>
                            <span>
                                <i class="fas fa-user-tie tw-mr-1"></i>
                                {{ $row->leaders_count }} Leader
                            </span>
                            <span>
                                <i class="fas fa-users tw-mr-1"></i>
                                {{ $row->members_count }} Anggota
                            </span>
                        </div>

                        <div class="tw-flex tw-items-center tw-justify-end tw-pt-3 tw-border-t tw-border-gray-100">
                            <div class="tw-flex tw-gap-1">
                                @if ($this->can("project.kelola_kelompok"))
                                    <a href="{{ route("project.teams", ["projectId" => $row->id]) }}" class="btn btn-success" title="Kelola Kelompok">
                                        <i class="fas fa-users"></i>
                                    </a>
                                @endif

                                @if ($this->can("project.view_team"))
                                    <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewModal">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endif

                                @if ($this->can("project.edit"))
                                    <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif

                                @if ($this->can("project.delete"))
                                    <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="tw-col-span-1 md:tw-col-span-2 lg:tw-col-span-3 xl:tw-col-span-3 tw-w-full tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-16 tw-px-4">
                        <div class="tw-text-gray-400 tw-mb-4">
                            <i class="fas fa-inbox tw-text-6xl"></i>
                        </div>
                        <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-2">Tidak Ada Data</h3>
                        <p class="tw-text-gray-500 tw-text-center">Belum ada project/kegiatan yang tersedia saat ini.</p>
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
        @if ($this->can("project.create"))
            <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
                <i class="far fa-plus"></i>
            </button>
        @endif
    </section>

    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Project/Kegiatan" : "Add Project/Kegiatan" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <div class="form-group">
                            <label for="nama_project">Nama Project/Kegiatan</label>
                            <input type="text" wire:model="nama_project" id="nama_project" class="form-control" placeholder="Contoh: Robot Line Follower" />
                            @error("nama_project")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea wire:model="deskripsi" id="deskripsi" class="form-control" style="height: 100px !important" placeholder="Jelaskan tentang project/kegiatan ini..."></textarea>
                            @error("deskripsi")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select wire:model="status" id="status" class="form-control select2-single">
                                <option value="" disabled>-- Pilih Status --</option>
                                <option value="draft">Draft</option>
                                <option value="berjalan">Berjalan</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditunda">Ditunda</option>
                            </select>
                            @error("status")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="alert alert-info tw-mb-4">
                            <i class="fas fa-info-circle tw-mr-2"></i>
                            <strong>Info:</strong>
                            Untuk menambahkan kelompok, leader, dan anggota, silakan kelola melalui halaman
                            <strong>Kelola Kelompok</strong>
                            setelah project dibuat.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" wire:model="tanggal_mulai" id="tanggal_mulai" class="form-control" />
                                    @error("tanggal_mulai")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai (Opsional)</label>
                                    <input type="date" wire:model="tanggal_selesai" id="tanggal_selesai" class="form-control" />
                                    @error("tanggal_selesai")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="link_gdrive">Link Google Drive (Opsional)</label>
                            <input type="url" wire:model="link_gdrive" id="link_gdrive" class="form-control" placeholder="https://drive.google.com/..." />
                            @error("link_gdrive")
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

    <!-- View Modal -->
    <div class="modal fade" wire:ignore.self id="viewModal" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-full sm:tw-max-w-2xl sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-xl tw-border-0 tw-shadow-2xl">
                <div class="modal-header tw-border-b-0 tw-pb-0 tw-pt-6 tw-px-6">
                    <h5 class="modal-title tw-text-xl tw-font-bold tw-text-gray-800" id="viewModalLabel">Detail Project/Kegiatan</h5>
                    <button type="button" class="close tw-opacity-50 hover:tw-opacity-100 tw-transition-opacity" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-px-6 tw-py-6">
                    @if ($viewData)
                        <!-- Project Header -->
                        <div class="tw-mb-8">
                            <h4 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-mb-2">{{ $viewData["project"]->nama_project }}</h4>
                            @php
                                $statusStyles = [
                                    "draft" => "tw-bg-gray-100 tw-text-gray-600 tw-border tw-border-gray-200",
                                    "berjalan" => "tw-bg-emerald-50 tw-text-emerald-600 tw-border tw-border-emerald-200",
                                    "selesai" => "tw-bg-blue-50 tw-text-blue-600 tw-border tw-border-blue-200",
                                    "ditunda" => "tw-bg-amber-50 tw-text-amber-600 tw-border tw-border-amber-200",
                                ];
                                $statusStyle = $statusStyles[$viewData["project"]->status] ?? "tw-bg-gray-100 tw-text-gray-600 tw-border tw-border-gray-200";
                            @endphp

                            <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-md tw-text-xs tw-font-medium {{ $statusStyle }}">
                                {{ ucfirst($viewData["project"]->status) }}
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="tw-mb-8">
                            <label class="tw-block tw-text-xs tw-font-bold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-2">Deskripsi</label>
                            <p class="tw-text-gray-700 tw-text-sm tw-leading-relaxed tw-whitespace-pre-line tw-tracking-normal">{{ $viewData["project"]->deskripsi ?: "-" }}</p>
                        </div>

                        <!-- Dates Grid -->
                        <div class="tw-grid tw-grid-cols-2 tw-gap-6 tw-mb-8">
                            <div>
                                <label class="tw-block tw-text-xs tw-font-bold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-1">Tanggal Mulai</label>
                                <p class="tw-text-gray-900 tw-text-sm tw-font-medium tw-tracking-normal">
                                    {{ $viewData["project"]->tanggal_mulai ? \Carbon\Carbon::parse($viewData["project"]->tanggal_mulai)->format("d M Y") : "-" }}
                                </p>
                            </div>
                            <div>
                                <label class="tw-block tw-text-xs tw-font-bold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-1">Tanggal Selesai</label>
                                <p class="tw-text-gray-900 tw-text-sm tw-font-medium tw-tracking-normal">
                                    {{ $viewData["project"]->tanggal_selesai ? \Carbon\Carbon::parse($viewData["project"]->tanggal_selesai)->format("d M Y") : "-" }}
                                </p>
                            </div>
                        </div>

                        <!-- Teams Table -->
                        <div class="tw-mb-8">
                            <div class="tw-text-xs tw-font-bold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-3">LIST KELOMPOK</div>

                            @if ($viewData["teams"] && count($viewData["teams"]) > 0)
                                <div class="tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-overflow-hidden">
                                    <table class="tw-w-full tw-table-auto">
                                        <thead class="tw-bg-gray-50 tw-border-b tw-border-gray-200">
                                            <tr>
                                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-bold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-text-left">NAMA KELOMPOK</th>
                                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-bold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-text-left">LEADER</th>
                                                <th class="tw-px-4 tw-py-3 tw-text-xs tw-font-bold tw-text-gray-500 tw-uppercase tw-tracking-wider tw-text-right">JUMLAH ANGGOTA</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tw-divide-y tw-divide-gray-100">
                                            @foreach ($viewData["teams"] as $team)
                                                @php
                                                    $leader = collect($team->members)
                                                        ->where("role", "leader")
                                                        ->first();
                                                    $anggotaCount = collect($team->members)
                                                        ->where("role", "anggota")
                                                        ->count();
                                                @endphp

                                                <tr class="tw-group hover:tw-bg-blue-50 tw-transition-colors">
                                                    <td class="tw-px-4 tw-py-3.5 tw-text-sm tw-text-gray-700 tw-font-medium">{{ $team->nama_kelompok }}</td>
                                                    <td class="tw-px-4 tw-py-3.5 tw-text-sm tw-text-gray-600">
                                                        {{ $leader ? $leader->nama_lengkap : "-" }}
                                                    </td>
                                                    <td class="tw-px-4 tw-py-3.5 tw-text-sm tw-text-gray-600 tw-text-right">
                                                        <span class="tw-bg-gray-100 tw-text-gray-600 tw-px-2.5 tw-py-1 tw-rounded-md tw-text-xs tw-font-medium">{{ $anggotaCount }} orang</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="tw-bg-gray-50 tw-border tw-border-dashed tw-border-gray-300 tw-rounded-lg tw-p-8 tw-text-center">
                                    <i class="fas fa-users tw-text-3xl tw-text-gray-300 tw-mb-3"></i>
                                    <p class="tw-text-sm tw-text-gray-500">Belum ada kelompok yang dibentuk</p>
                                </div>
                            @endif
                        </div>

                        <!-- GDrive Link -->
                        @if ($viewData["project"]->link_gdrive)
                            <div class="tw-mb-2">
                                <label class="tw-block tw-text-xs tw-font-bold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-2">Link Google Drive</label>
                                <a href="{{ $viewData["project"]->link_gdrive }}" target="_blank" rel="noopener noreferrer" class="tw-inline-flex tw-items-center tw-text-sm tw-text-blue-600 hover:tw-text-blue-700 hover:tw-underline tw-font-medium tw-transition-colors tw-tracking-normal">
                                    <i class="fab fa-google-drive tw-mr-2 tw-text-lg"></i>
                                    {{ Str::limit($viewData["project"]->link_gdrive, 50) }}
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-12">
                            <i class="fas fa-circle-notch fa-spin tw-text-3xl tw-text-blue-500 tw-mb-3"></i>
                            <span class="tw-text-sm tw-text-gray-500">Memuat data...</span>
                        </div>
                    @endif
                </div>
                <div class="modal-footer tw-border-t-0 tw-bg-gray-50 tw-rounded-b-xl tw-px-6 tw-py-4">
                    <button type="button" class="btn btn-secondary tw-bg-gray-200 tw-text-gray-700 tw-border-0 hover:tw-bg-gray-300 tw-font-medium tw-px-6" data-dismiss="modal">Close</button>
                </div>
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
        function initSelect2(selectedLeaders = null, selectedAnggotas = null, selectedStatus = null) {
            // Destroy existing Select2 instances first
            if ($('#id_leader').hasClass('select2-hidden-accessible')) {
                $('#id_leader').select2('destroy');
            }
            if ($('#id_anggota').hasClass('select2-hidden-accessible')) {
                $('#id_anggota').select2('destroy');
            }
            if ($('#status').hasClass('select2-hidden-accessible')) {
                $('#status').select2('destroy');
            }

            // Multi-select untuk leader dan anggota
            $('#id_leader').select2({
                placeholder: "Pilih...",
                allowClear: true
            });

            $('#id_anggota').select2({
                placeholder: "Pilih...",
                allowClear: true
            });

            // Single select untuk status
            $('#status').select2({
                placeholder: "Pilih...",
                allowClear: true
            });

            // Set values if provided
            if (selectedLeaders && selectedLeaders.length > 0) {
                $('#id_leader').val(selectedLeaders).trigger('change.select2');
            }
            if (selectedAnggotas && selectedAnggotas.length > 0) {
                $('#id_anggota').val(selectedAnggotas).trigger('change.select2');
            }
            if (selectedStatus) {
                $('#status').val(selectedStatus).trigger('change.select2');
            }

            // Event handlers - unbind first to prevent duplicates
            $('#id_leader').off('change').on('change', function(e) {
                @this.set('id_leader', $(this).val());
            });

            $('#id_anggota').off('change').on('change', function(e) {
                @this.set('id_anggota', $(this).val());
            });

            $('#status').off('change').on('change', function(e) {
                @this.set('status', $(this).val());
            });
        }

        $(document).ready(function() {
            initSelect2();
        });

        window.addEventListener('initSelect2', event => {
            setTimeout(function() {
                // Get current values from Livewire component
                var leaders = @this.get('id_leader') || [];
                var anggotas = @this.get('id_anggota') || [];
                var status = @this.get('status') || null;
                initSelect2(leaders, anggotas, status);
            }, 150);
        });
    </script>
@endpush
