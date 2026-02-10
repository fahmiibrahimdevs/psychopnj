<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1 class="tw-text-lg">Project / Kegiatan</h1>
        </div>

        <div class="section-body">
            {{-- Filter --}}
            <div class="card">
                <div class="card-body px-4 py-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="text" wire:model.live.debounce.300ms="searchTerm" class="form-control" placeholder="Cari nama project..." />
                        </div>
                        <div class="col-lg-3">
                            <select wire:model.live="filterStatus" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="planning">Planning</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail View --}}
            @if ($viewData)
                <div class="card tw-border-l-4 tw-border-blue-500">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-folder-open mr-2 tw-text-blue-500"></i>
                            {{ $viewData->nama_project }}
                        </h4>
                        <div class="card-header-action">
                            <button wire:click="closeView" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i>
                                Tutup
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td class="tw-font-semibold" style="width: 150px">Tahun</td>
                                        <td>{{ $viewData->nama_tahun }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tw-font-semibold">Leader</td>
                                        <td>{{ $viewData->nama_leader ?? "-" }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tw-font-semibold">Status</td>
                                        <td>
                                            @switch($viewData->status)
                                                @case("planning")
                                                    <span class="badge badge-secondary">Planning</span>

                                                    @break
                                                @case("in_progress")
                                                    <span class="badge badge-primary">In Progress</span>

                                                    @break
                                                @case("completed")
                                                    <span class="badge badge-success">Completed</span>

                                                    @break
                                                @case("cancelled")
                                                    <span class="badge badge-danger">Cancelled</span>

                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="tw-font-semibold">Tanggal Mulai</td>
                                        <td>{{ $viewData->tanggal_mulai ? \Carbon\Carbon::parse($viewData->tanggal_mulai)->translatedFormat("d M Y") : "-" }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tw-font-semibold">Tanggal Selesai</td>
                                        <td>{{ $viewData->tanggal_selesai ? \Carbon\Carbon::parse($viewData->tanggal_selesai)->translatedFormat("d M Y") : "-" }}</td>
                                    </tr>
                                    @if ($viewData->link_gdrive)
                                        <tr>
                                            <td class="tw-font-semibold">Google Drive</td>
                                            <td>
                                                <a href="{{ $viewData->link_gdrive }}" target="_blank" class="tw-text-blue-600">
                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                    Buka Link
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <h6 class="tw-font-semibold tw-mb-2">Deskripsi</h6>
                                <div class="tw-bg-gray-50 tw-p-3 tw-rounded-lg">
                                    {!! $viewData->deskripsi ?? "<em>Tidak ada deskripsi</em>" !!}
                                </div>
                            </div>
                        </div>

                        {{-- Teams --}}
                        @if (! empty($viewData->teams) && count($viewData->teams) > 0)
                            <hr />
                            <h6 class="tw-font-semibold tw-mb-3">
                                <i class="fas fa-users mr-1"></i>
                                Tim
                            </h6>
                            <div class="row">
                                @foreach ($viewData->teams as $team)
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <div class="card tw-shadow-sm tw-border mb-0">
                                            <div class="card-header py-2">
                                                <h6 class="mb-0 tw-font-semibold">{{ $team->nama_team ?? "Team " . $loop->iteration }}</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <ul class="list-group list-group-flush">
                                                    @forelse ($team->members as $member)
                                                        <li class="list-group-item py-2">
                                                            <div class="tw-flex tw-items-center tw-justify-between">
                                                                <div>
                                                                    <strong>{{ $member->nama_lengkap }}</strong>
                                                                    <br />
                                                                    <small class="tw-text-gray-500">{{ $member->jurusan_prodi_kelas }}</small>
                                                                </div>
                                                                @if ($member->role)
                                                                    <span class="badge badge-light">{{ $member->role }}</span>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @empty
                                                        <li class="list-group-item text-muted text-center py-2">Belum ada anggota</li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Project Grid --}}
            <div class="row">
                @forelse ($projects as $project)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card tw-h-full tw-shadow-sm hover:tw-shadow-md tw-transition-all tw-cursor-pointer mb-0" wire:click="viewProject({{ $project->id }})">
                            @if ($project->thumbnail)
                                <img src="{{ asset("storage/" . $project->thumbnail) }}" class="card-img-top" style="height: 160px; object-fit: cover" alt="{{ $project->nama_project }}" />
                            @else
                                <div class="tw-bg-gradient-to-r tw-from-blue-400 tw-to-purple-500 tw-flex tw-items-center tw-justify-center" style="height: 160px">
                                    <i class="fas fa-project-diagram tw-text-white" style="font-size: 3rem"></i>
                                </div>
                            @endif
                            <div class="card-body py-3">
                                <h6 class="tw-font-bold tw-mb-1">{{ $project->nama_project }}</h6>
                                <p class="tw-text-gray-500 tw-text-sm tw-mb-2">Leader: {{ $project->nama_leader ?? "-" }}</p>
                                <div class="tw-flex tw-items-center tw-justify-between">
                                    @switch($project->status)
                                        @case("planning")
                                            <span class="badge badge-secondary">Planning</span>

                                            @break
                                        @case("in_progress")
                                            <span class="badge badge-primary">In Progress</span>

                                            @break
                                        @case("completed")
                                            <span class="badge badge-success">Completed</span>

                                            @break
                                        @case("cancelled")
                                            <span class="badge badge-danger">Cancelled</span>

                                            @break
                                    @endswitch
                                    @if ($project->tanggal_mulai)
                                        <small class="tw-text-gray-400">{{ \Carbon\Carbon::parse($project->tanggal_mulai)->translatedFormat("d M Y") }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-folder-open tw-text-gray-300" style="font-size: 3rem"></i>
                                <p class="tw-text-gray-400 tw-mt-3">Belum ada project / kegiatan</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            {{ $projects->links() }}
        </div>
    </section>
</div>
