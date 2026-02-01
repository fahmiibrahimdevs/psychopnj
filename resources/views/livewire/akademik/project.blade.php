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
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 xl:tw-grid-cols-3 tw-gap-4 tw-px-4 lg:tw-px-0">
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

                        $anggotaIds = $row->id_anggota ? explode(",", $row->id_anggota) : [];
                        $anggotaCount = count($anggotaIds);
                        $leaderIds = $row->id_leader ? explode(",", $row->id_leader) : [];
                        $leaderCount = count($leaderIds);
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
                                <i class="fas fa-user-tie tw-mr-1"></i>
                                {{ $leaderCount }} Leader
                            </span>
                            <span>
                                <i class="fas fa-users tw-mr-1"></i>
                                {{ $anggotaCount }} Anggota
                            </span>
                        </div>

                        <div class="tw-flex tw-items-center tw-justify-end tw-pt-3 tw-border-t tw-border-gray-100">
                            <div class="tw-flex tw-gap-1">
                                <button wire:click.prevent="view({{ $row->id }})" class="btn btn-info" data-toggle="modal" data-target="#viewModal">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body tw-py-16">
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center">
                                <div class="tw-text-gray-400 tw-mb-4">
                                    <i class="fas fa-folder-open tw-text-6xl"></i>
                                </div>
                                <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-2">Tidak Ada Data</h3>
                                <p class="tw-text-gray-500 tw-text-center">Belum ada project/kegiatan yang tersedia saat ini.</p>
                            </div>
                        </div>
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
        <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
            <i class="far fa-plus"></i>
        </button>
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
                            <label for="id_leader">Leader (Pengurus)</label>
                            <select wire:model="id_leader" id="id_leader" class="form-control select2" multiple>
                                @foreach ($pengurus as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_lengkap }} ({{ $p->nama_jabatan }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih satu atau lebih leader untuk project/kegiatan ini</small>
                            @error("id_leader")
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

                        <div class="form-group">
                            <label for="id_anggota">Anggota Project/Kegiatan</label>
                            <select wire:model="id_anggota" id="id_anggota" class="form-control select2" multiple>
                                @foreach ($anggotas as $a)
                                    <option value="{{ $a->id }}">{{ $a->nama_lengkap }} ({{ ucfirst($a->status_anggota) }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih anggota yang tergabung dalam project/kegiatan ini</small>
                            @error("id_anggota")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Detail Project/Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($viewData)
                        <div class="tw-mb-4">
                            <h4 class="tw-text-lg tw-font-semibold tw-text-gray-800">{{ $viewData["project"]->nama_project }}</h4>
                            @php
                                $statusStyles = [
                                    "draft" => "tw-bg-gray-100 tw-text-gray-700",
                                    "berjalan" => "tw-bg-green-100 tw-text-green-700",
                                    "selesai" => "tw-bg-blue-100 tw-text-blue-700",
                                    "ditunda" => "tw-bg-yellow-100 tw-text-yellow-700",
                                ];
                                $statusStyle = $statusStyles[$viewData["project"]->status] ?? "tw-bg-gray-100 tw-text-gray-700";
                            @endphp

                            <span class="tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-medium {{ $statusStyle }}">{{ ucfirst($viewData["project"]->status) }}</span>
                        </div>

                        <div class="tw-mb-4">
                            <label class="tw-text-sm font-bagus tw-font-semibold tw-tracking-normal">Deskripsi</label>
                            <p class="tw-tracking-normal font-bagus tw-font-normal tw-text-sm">{{ $viewData["project"]->deskripsi }}</p>
                        </div>

                        <div class="row tw-mb-4">
                            <div class="col-6">
                                <label class="tw-text-sm font-bagus tw-font-semibold tw-tracking-normal">Tanggal Mulai</label>
                                <p class="tw-text-gray-700 tw-text-sm">
                                    {{ $viewData["project"]->tanggal_mulai ? \Carbon\Carbon::parse($viewData["project"]->tanggal_mulai)->format("d M Y") : "-" }}
                                </p>
                            </div>
                            <div class="col-6">
                                <label class="tw-text-sm font-bagus tw-font-semibold tw-tracking-normal">Tanggal Selesai</label>
                                <p class="tw-text-gray-700 tw-text-sm">
                                    {{ $viewData["project"]->tanggal_selesai ? \Carbon\Carbon::parse($viewData["project"]->tanggal_selesai)->format("d M Y") : "-" }}
                                </p>
                            </div>
                        </div>

                        <div class="tw-mb-4">
                            <label class="tw-text-sm font-bagus tw-font-semibold tw-tracking-normal">Leader ({{ count($viewData["leaders"]) }})</label>
                            <div class="tw-mt-1">
                                @forelse ($viewData["leaders"] as $leader)
                                    <span class="tw-bg-blue-100 tw-text-blue-700 tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-medium tw-mr-1 tw-mb-1 tw-inline-block">{{ $leader->nama_lengkap }}</span>
                                @empty
                                    <span class="tw-text-gray-400 tw-text-sm">-</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="tw-mb-2">
                            <label class="tw-text-sm font-bagus tw-font-semibold tw-tracking-normal">Anggota ({{ count($viewData["anggotas"]) }})</label>
                            <div class="tw-mt-1">
                                @forelse ($viewData["anggotas"] as $anggota)
                                    <span class="tw-bg-emerald-100 tw-text-emerald-700 tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-medium tw-mr-1 tw-mb-1 tw-inline-block">{{ $anggota->nama_lengkap }}</span>
                                @empty
                                    <span class="tw-text-gray-400 tw-text-sm">-</span>
                                @endforelse
                            </div>
                        </div>

                        @if ($viewData["project"]->link_gdrive)
                            <div class="tw-my-4">
                                <label class="tw-text-sm font-bagus tw-font-semibold tw-tracking-normal">Link Google Drive</label>
                                <div class="tw-mt-1">
                                    <a href="{{ $viewData["project"]->link_gdrive }}" target="_blank" class="tw-text-blue-600 tw-text-sm hover:tw-underline">
                                        <i class="fab fa-google-drive tw-mr-1"></i>
                                        Buka Google Drive
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="tw-text-center tw-py-4">
                            <i class="fas fa-spinner fa-spin tw-text-2xl tw-text-gray-400"></i>
                        </div>
                    @endif
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
