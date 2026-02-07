<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>{{ $project->nama_project }} - Kelompok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route("projects") }}">Projects</a></div>
                <div class="breadcrumb-item active">Kelompok</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="tw-flex tw-ml-6 tw-my-5">
                    <h3 class="tw-tracking-wider tw-text-[#34395e] tw-text-base tw-font-semibold tw-mt-1">Daftar Kelompok</h3>
                    <button wire:click="openTeamModal" class="btn btn-primary ml-auto mr-4" data-toggle="modal" data-target="#teamModal">
                        <i class="fas fa-plus"></i>
                        Tambah Kelompok
                    </button>
                </div>
            </div>
            <div class="-tw-mt-2">
                @if ($teams->isEmpty())
                    <div class="tw-text-center tw-py-16">
                        <i class="fas fa-inbox tw-text-gray-300 tw-text-6xl tw-mb-4"></i>
                        <p class="tw-text-gray-500 tw-text-lg">Belum ada kelompok</p>
                        <p class="tw-text-gray-400 tw-text-sm">Klik tombol "Tambah Kelompok" untuk menambah kelompok baru</p>
                    </div>
                @else
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">
                        @foreach ($teams as $team)
                            @php
                                $colors = [
                                    ["from" => "tw-from-cyan-400", "to" => "tw-to-blue-500"],
                                    ["from" => "tw-from-green-400", "to" => "tw-to-emerald-500"],
                                    ["from" => "tw-from-purple-400", "to" => "tw-to-pink-500"],
                                    ["from" => "tw-from-orange-400", "to" => "tw-to-red-500"],
                                ];
                                $colorSet = $colors[$loop->index % 4];
                                $leader = collect($team->members)
                                    ->where("role", "leader")
                                    ->first();
                                $members = collect($team->members)->where("role", "anggota");
                            @endphp

                            <div class="tw-bg-white tw-rounded-xl tw-p-4 tw-shadow-md tw-shadow-gray-300 tw-transition-all tw-duration-300 tw-flex tw-flex-col">
                                <div class="tw-flex tw-items-start tw-justify-between tw-mb-3">
                                    <div class="tw-flex tw-items-center">
                                        <div class="tw-bg-gradient-to-br {{ $colorSet["from"] }} {{ $colorSet["to"] }} tw-rounded-lg tw-p-2.5 tw-mr-3">
                                            <i class="fas fa-users tw-text-white tw-text-lg"></i>
                                        </div>
                                        <div>
                                            <h5 class="tw-text-base tw-font-semibold tw-text-gray-800 tw-leading-tight">{{ Str::limit($team->nama_kelompok, 25) }}</h5>
                                        </div>
                                    </div>
                                </div>

                                @if ($team->deskripsi)
                                    <p class="font-bagus tw-tracking-normal tw-text-sm tw-font-normal tw-leading-relaxed tw-mb-3 tw-flex-grow">{{ Str::limit($team->deskripsi, 80) }}</p>
                                @else
                                    <p class="font-bagus tw-tracking-normal tw-text-sm tw-font-normal tw-leading-relaxed tw-mb-3 tw-flex-grow tw-text-gray-400">-</p>
                                @endif

                                <div class="tw-flex tw-flex-wrap tw-gap-3 tw-text-xs tw-text-gray-500 tw-mb-3">
                                    @if ($leader)
                                        <span>
                                            <i class="fas fa-user-tie tw-mr-1"></i>
                                            {{ $leader->nama_lengkap }}
                                        </span>
                                    @endif

                                    @if ($members->isNotEmpty())
                                        <span>
                                            <i class="fas fa-users tw-mr-1"></i>
                                            {{ $members->count() }} Anggota
                                        </span>
                                    @endif
                                </div>

                                <div class="tw-flex tw-items-center tw-justify-end tw-pt-3 tw-border-t tw-border-gray-100">
                                    <div class="tw-flex tw-gap-1">
                                        <button wire:click="editTeam({{ $team->id }})" class="btn btn-primary" data-toggle="modal" data-target="#teamModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteTeamConfirm({{ $team->id }})" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Modal Kelompok -->
    <div class="modal fade" wire:ignore.self id="teamModal" aria-labelledby="teamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamModalLabel">{{ $isEditingTeam ? "Edit" : "Tambah" }} Kelompok</h5>
                    <button type="button" wire:click="closeTeamModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_kelompok">
                                Nama Kelompok
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" wire:model="nama_kelompok" class="form-control" id="nama_kelompok" placeholder="Contoh: Kelompok 1, Tim Alpha, dll" />
                            @error("nama_kelompok")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi_kelompok">Deskripsi</label>
                            <textarea wire:model="deskripsi_kelompok" class="form-control" id="deskripsi_kelompok" rows="3" placeholder="Deskripsi kelompok (opsional)"></textarea>
                            @error("deskripsi_kelompok")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_leader">
                                Leader
                                <span class="text-danger">*</span>
                            </label>
                            <select wire:model="id_leader" class="form-control select2" id="id_leader">
                                <option value="">-- Pilih Leader --</option>
                                @foreach ($anggotas as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error("id_leader")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="id_anggota">Anggota</label>
                            <select wire:model="id_anggota" class="form-control select2" id="id_anggota" multiple>
                                @foreach ($anggotas as $anggota)
                                    <option value="{{ $anggota->id }}">{{ $anggota->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error("id_anggota")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <small class="form-text text-muted">Leader tidak perlu dipilih lagi sebagai anggota</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" wire:click="closeTeamModal" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="saveTeam">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                    </div>
                </form>
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
        function initSelect2(selectedLeader = null, selectedAnggotas = null) {
            // Destroy existing Select2 instances
            if ($('#id_leader').hasClass('select2-hidden-accessible')) {
                $('#id_leader').select2('destroy');
            }
            if ($('#id_anggota').hasClass('select2-hidden-accessible')) {
                $('#id_anggota').select2('destroy');
            }

            // Initialize Select2
            $('#id_leader').select2({
                placeholder: '-- Pilih Leader --',
                allowClear: true,
                dropdownParent: $('#teamModal')
            });

            $('#id_anggota').select2({
                placeholder: '-- Pilih Anggota --',
                allowClear: true,
                dropdownParent: $('#teamModal')
            });

            // Set values if provided
            if (selectedLeader) {
                $('#id_leader').val(selectedLeader).trigger('change.select2');
            }
            if (selectedAnggotas && selectedAnggotas.length > 0) {
                $('#id_anggota').val(selectedAnggotas).trigger('change.select2');
            }

            // Event handlers - unbind first to prevent duplicates
            $('#id_leader').off('change').on('change', function(e) {
                @this.set('id_leader', $(this).val());
            });

            $('#id_anggota').off('change').on('change', function(e) {
                @this.set('id_anggota', $(this).val());
            });
        }

        $(document).ready(function() {
            // Initialize on modal shown
            $('#teamModal').on('shown.bs.modal', function() {
                setTimeout(function() {
                    var leader = @this.get('id_leader') || null;
                    var anggotas = @this.get('id_anggota') || [];
                    initSelect2(leader, anggotas);
                }, 100);
            });

            // Destroy on modal hidden
            $('#teamModal').on('hidden.bs.modal', function() {
                if ($('#id_leader').hasClass('select2-hidden-accessible')) {
                    $('#id_leader').select2('destroy');
                }
                if ($('#id_anggota').hasClass('select2-hidden-accessible')) {
                    $('#id_anggota').select2('destroy');
                }
            });
        });

        window.addEventListener('initSelect2', event => {
            setTimeout(function() {
                var leader = @this.get('id_leader') || null;
                var anggotas = @this.get('id_anggota') || [];
                initSelect2(leader, anggotas);
            }, 150);
        });

        // SweetAlert confirmation handler
        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                title: event.detail.message || 'Are you sure?',
                text: event.detail.text || "You won't be able to revert this!",
                icon: event.detail.type || 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteTeam');
                }
            });
        });
    </script>
@endpush
