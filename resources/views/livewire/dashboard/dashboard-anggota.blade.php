<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Dashboard Anggota</h1>
        </div>

        <div class="section-body">
            @if ($anggota)
                <div class="card">
                    <h3>
                        <i class="fas fa-users"></i>
                        Daftar Kehadiran Semua Anggota
                    </h3>
                    <div class="card-body tw-px-4 lg:tw-px-6">
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

                        <!-- List Anggota -->
                        <div class="tw-space-y-4">
                            @forelse ($members as $index => $member)
                                <div class="tw-border tw-rounded-lg tw-p-4 hover:tw-shadow-md tw-transition-all tw-bg-white" x-data="{ expanded: false }">
                                    <div class="tw-flex tw-items-center tw-justify-between tw-cursor-pointer" @click="expanded = !expanded">
                                        <div class="tw-flex tw-items-center tw-gap-4 tw-flex-1">
                                            <span class="tw-font-bold tw-text-gray-400 tw-w-8 text-center">{{ $members->firstItem() + $index }}</span>

                                            <div class="tw-relative">
                                                @if ($member->foto)
                                                    <img src="{{ asset("storage/" . $member->foto) }}" class="tw-w-12 tw-h-12 tw-rounded-full tw-object-cover" alt="Avatar" />
                                                @else
                                                    <div class="tw-w-12 tw-h-12 tw-rounded-full tw-bg-gray-200 tw-flex tw-items-center tw-justify-center">
                                                        <i class="fas fa-user tw-text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="tw-flex-1">
                                                <h4 class="tw-text-base tw-font-bold tw-text-gray-800 tw-mb-0">{{ $member->nama_lengkap }}</h4>
                                                <p class="tw-text-xs tw-text-gray-500 tw-mb-1">{{ $member->jurusan_prodi_kelas ?? "-" }}</p>

                                                <!-- Progress Bar Mobile -->
                                                <div class="tw-block sm:tw-hidden tw-mt-2">
                                                    <div class="tw-flex tw-justify-between tw-text-xs tw-mb-1">
                                                        <span class="tw-font-semibold {{ $member->stats["percentage"] >= 75 ? "tw-text-green-600" : ($member->stats["percentage"] >= 50 ? "tw-text-yellow-600" : "tw-text-red-600") }}">{{ $member->stats["percentage"] }}% Kehadiran</span>
                                                        <small class="text-muted">{{ $member->stats["hadir"] }}/{{ $member->stats["total_wajib"] }} Pertemuan</small>
                                                    </div>
                                                    <div class="progress tw-h-1.5">
                                                        <div class="progress-bar {{ $member->stats["percentage"] >= 75 ? "bg-success" : ($member->stats["percentage"] >= 50 ? "bg-warning" : "bg-danger") }}" role="progressbar" style="width: {{ $member->stats["percentage"] }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progress Bar Desktop -->
                                        <div class="tw-hidden sm:tw-flex tw-items-center tw-gap-4 tw-w-1/3">
                                            <div class="tw-flex-1">
                                                <div class="tw-flex tw-justify-between tw-text-xs tw-mb-1">
                                                    <span class="tw-font-semibold">Kehadiran</span>
                                                    <span class="tw-font-bold">{{ $member->stats["percentage"] }}%</span>
                                                </div>
                                                <div class="progress tw-h-2">
                                                    <div class="progress-bar {{ $member->stats["percentage"] >= 75 ? "bg-success" : ($member->stats["percentage"] >= 50 ? "bg-warning" : "bg-danger") }}" role="progressbar" style="width: {{ $member->stats["percentage"] }}%"></div>
                                                </div>
                                                <div class="tw-text-xs tw-text-gray-500 tw-mt-1 tw-text-right">{{ $member->stats["hadir"] }}/{{ $member->stats["total_wajib"] }} Pertemuan</div>
                                            </div>
                                            <div class="tw-text-right">
                                                <i class="fas" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Details -->
                                    <div x-show="expanded" x-collapse class="tw-mt-4 tw-border-t tw-pt-4">
                                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-4 tw-gap-4">
                                            <div class="tw-bg-green-50 tw-p-3 tw-rounded-lg tw-text-center">
                                                <div class="tw-text-2xl tw-font-bold tw-text-green-600">{{ $member->stats["hadir"] }}</div>
                                                <div class="tw-text-xs tw-text-gray-600 tw-mt-1">Hadir</div>
                                            </div>
                                            <div class="tw-bg-blue-50 tw-p-3 tw-rounded-lg tw-text-center">
                                                <div class="tw-text-2xl tw-font-bold tw-text-blue-600">{{ $member->stats["izin"] }}</div>
                                                <div class="tw-text-xs tw-text-gray-600 tw-mt-1">Izin</div>
                                            </div>
                                            <div class="tw-bg-yellow-50 tw-p-3 tw-rounded-lg tw-text-center">
                                                <div class="tw-text-2xl tw-font-bold tw-text-yellow-600">{{ $member->stats["sakit"] }}</div>
                                                <div class="tw-text-xs tw-text-gray-600 tw-mt-1">Sakit</div>
                                            </div>
                                            <div class="tw-bg-red-50 tw-p-3 tw-rounded-lg tw-text-center">
                                                <div class="tw-text-2xl tw-font-bold tw-text-red-600">{{ $member->stats["alfa"] }}</div>
                                                <div class="tw-text-xs tw-text-gray-600 tw-mt-1">Alfa/Tanpa Ket</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="tw-text-center tw-py-8">
                                    <img src="{{ asset("assets/img/drawkit/drawkit-nature-man-colour.svg") }}" alt="Empty" class="tw-h-32 tw-mx-auto tw-mb-4 tw-opacity-50" />
                                    <h4 class="tw-text-gray-500">Belum ada data anggota</h4>
                                </div>
                            @endforelse

                            <div class="tw-mt-4">
                                {{ $members->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body tw-text-center tw-py-8">
                        <i class="fas fa-exclamation-triangle tw-text-yellow-500 tw-text-5xl tw-mb-4"></i>
                        <h4 class="tw-text-gray-700">Data anggota tidak ditemukan</h4>
                        <p class="tw-text-gray-500">Silakan hubungi administrator</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
