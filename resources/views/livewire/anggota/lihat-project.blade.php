<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Project / Kegiatan</h1>
        </div>

        <div class="section-body">
            {{-- Filter --}}
            <div class="card">
                <h3>Daftar Project/Kegiatan</h3>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="search-column">
                                <p>Search:</p>
                                <input type="search" wire:model.live.debounce.500ms="searchTerm" id="search-data" placeholder="Cari nama project..." class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-0">
                                <label class="d-block font-weight-bold text-muted mb-1" style="font-size: 0.875rem">Filter Status</label>
                                <select wire:model.live="filterStatus" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="berjalan">Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditunda">Ditunda</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail View Modal --}}
            @if ($viewData)
                <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5)" wire:click.self="closeView">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary text-white">
                                <div>
                                    <h4 class="modal-title mb-2">{{ $viewData->nama_project }}</h4>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-light mr-2">{{ $viewData->nama_tahun }}</span>

                                        @switch($viewData->status)
                                            @case("draft")
                                                <span class="badge badge-secondary">Draft</span>

                                                @break
                                            @case("berjalan")
                                                <span class="badge badge-success">Berjalan</span>

                                                @break
                                            @case("selesai")
                                                <span class="badge badge-primary">Selesai</span>

                                                @break
                                            @case("ditunda")
                                                <span class="badge badge-warning">Ditunda</span>

                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                <button type="button" class="close text-white" wire:click="closeView">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small d-block mb-1">Tanggal Mulai</label>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-start mr-2 text-primary"></i>
                                                <span>{{ $viewData->tanggal_mulai ? \Carbon\Carbon::parse($viewData->tanggal_mulai)->translatedFormat("d M Y") : "-" }}</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="font-weight-bold text-muted small d-block mb-1">Tanggal Selesai</label>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-check mr-2 text-primary"></i>
                                                <span>{{ $viewData->tanggal_selesai ? \Carbon\Carbon::parse($viewData->tanggal_selesai)->translatedFormat("d M Y") : "-" }}</span>
                                            </div>
                                        </div>
                                        @if ($viewData->link_gdrive)
                                            <div class="mb-3">
                                                <label class="font-weight-bold text-muted small d-block mb-1">Google Drive</label>
                                                <a href="{{ $viewData->link_gdrive }}" target="_blank" class="text-primary font-weight-bold">
                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                    Buka Link
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold text-muted small d-block mb-2">Deskripsi</label>
                                        <div class="bg-light p-3 rounded">
                                            {!! $viewData->deskripsi ?? '<em class="text-muted">Tidak ada deskripsi</em>' !!}
                                        </div>
                                    </div>
                                </div>

                                {{-- Teams --}}
                                @if (! empty($viewData->teams) && count($viewData->teams) > 0)
                                    <hr />
                                    <h5 class="font-weight-bold mb-3">
                                        <i class="fas fa-users mr-2 text-primary"></i>
                                        Tim & Anggota
                                    </h5>
                                    <div class="row">
                                        @foreach ($viewData->teams as $team)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-primary h-100">
                                                    <div class="card-header bg-primary text-white py-2">
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-layer-group mr-1"></i>
                                                            {{ $team->nama_kelompok ?? "Team " . $loop->iteration }}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <ul class="list-group list-group-flush">
                                                            @forelse ($team->members as $member)
                                                                <li class="list-group-item py-2">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <div class="font-weight-bold">{{ $member->nama_lengkap }}</div>
                                                                            <small class="text-muted">{{ $member->jurusan_prodi_kelas }}</small>
                                                                        </div>
                                                                        @if ($member->role === "leader")
                                                                            <span class="badge badge-warning">
                                                                                <i class="fas fa-star"></i>
                                                                                Leader
                                                                            </span>
                                                                        @else
                                                                            <span class="badge badge-info">Anggota</span>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @empty
                                                                <li class="list-group-item text-center text-muted py-3">
                                                                    <i class="fas fa-user-slash mb-2 d-block" style="font-size: 2rem"></i>
                                                                    <small>Belum ada anggota</small>
                                                                </li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closeView">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Project Grid --}}
            <div class="row">
                @forelse ($projects as $project)
                    @php
                        $statusColors = [
                            "draft" => "secondary",
                            "berjalan" => "success",
                            "selesai" => "primary",
                            "ditunda" => "warning",
                        ];
                        $statusBadge = $statusColors[$project->status] ?? "secondary";

                        $gradients = [
                            "bg-gradient-primary",
                            "bg-gradient-success",
                            "bg-gradient-info",
                            "bg-gradient-warning",
                        ];
                        $gradientClass = $gradients[$loop->index % 4];
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card card-hover h-100 shadow-sm" style="cursor: pointer" wire:click="viewProject({{ $project->id }})">
                            <div class="card-header {{ $gradientClass }} text-white d-flex justify-content-between align-items-start py-3">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="bg-white rounded p-2 mr-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center">
                                        <i class="fas fa-project-diagram text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">{{ Str::limit($project->nama_project, 30) }}</h6>
                                    </div>
                                </div>
                                <span class="badge badge-{{ $statusBadge }} ml-2">{{ ucfirst($project->status) }}</span>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3" style="font-size: 0.9rem; min-height: 60px">{{ Str::limit($project->deskripsi, 100) }}</p>

                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    @if ($project->tanggal_mulai)
                                        <span>
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ \Carbon\Carbon::parse($project->tanggal_mulai)->translatedFormat("d M Y") }}
                                        </span>
                                    @endif

                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $project->nama_tahun }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0 text-right">
                                <button class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-inbox text-muted mb-3" style="font-size: 4rem; opacity: 0.3"></i>
                                <h5 class="font-weight-bold text-muted mb-2">Tidak Ada Data</h5>
                                <p class="text-muted">Belum ada project/kegiatan yang tersedia saat ini.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
