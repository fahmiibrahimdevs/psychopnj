<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Presensi Pertemuan</h1>
            @if ($selectedPertemuan)
                @if ($this->can("presensi.update"))
                    <!-- Tombol Update -->
                    <button @click="collectAndSubmit()" wire:loading.attr="disabled" class="btn btn-primary tw-ml-auto">
                        <i class="fas fa-save tw-mr-2"></i>
                        <span wire:loading.remove>Update Presensi</span>
                        <span wire:loading>
                            <i class="fas fa-spinner fa-spin tw-mr-2"></i>
                            Saving...
                        </span>
                    </button>
                @endif
            @endif
        </div>

        <div class="section-body">
            <div class="tw-grid tw-grid-cols-1 tw-gap-x-0 lg:tw-grid-cols-6 lg:tw-gap-x-4">
                <div class="tw-col-span-2">
                    <div class="card">
                        <div class="card-body tw-px-6">
                            <h5 class="tw-text-lg font-bagus tw-font-bold tw-mb-4">Statistik Presensi</h5>

                            @if ($selectedPertemuan)
                                <!-- Pie Chart -->
                                <div class="tw-flex tw-justify-center tw-mb-6">
                                    <canvas id="presensiChart"></canvas>
                                </div>

                                <!-- Statistik Detail -->
                                <div class="tw-space-y-3 font-bagus tw-font-normal tw-text-sm tw-tracking-normal">
                                    <div class="tw-flex tw-justify-between tw-items-center tw-border-b tw-pb-2">
                                        <span class="tw-font-semibold">Total Anggota:</span>
                                        <span class="tw-text-lg tw-font-bold">{{ $statistik["total"] }}</span>
                                    </div>
                                    <div class="tw-flex tw-justify-between tw-items-center">
                                        <div class="tw-flex tw-items-center">
                                            <div class="tw-w-4 tw-h-4 tw-rounded-full tw-bg-green-500 tw-mr-2"></div>
                                            <span>Hadir</span>
                                        </div>
                                        <span class="tw-font-semibold">{{ $statistik["hadir"] }}</span>
                                    </div>
                                    <div class="tw-flex tw-justify-between tw-items-center">
                                        <div class="tw-flex tw-items-center">
                                            <div class="tw-w-4 tw-h-4 tw-rounded-full tw-bg-orange-500 tw-mr-2"></div>
                                            <span>Izin</span>
                                        </div>
                                        <span class="tw-font-semibold">{{ $statistik["izin"] }}</span>
                                    </div>
                                    <div class="tw-flex tw-justify-between tw-items-center">
                                        <div class="tw-flex tw-items-center">
                                            <div class="tw-w-4 tw-h-4 tw-rounded-full tw-bg-blue-500 tw-mr-2"></div>
                                            <span>Sakit</span>
                                        </div>
                                        <span class="tw-font-semibold">{{ $statistik["sakit"] }}</span>
                                    </div>
                                    <div class="tw-flex tw-justify-between tw-items-center">
                                        <div class="tw-flex tw-items-center">
                                            <div class="tw-w-4 tw-h-4 tw-rounded-full tw-bg-red-500 tw-mr-2"></div>
                                            <span>Alfa</span>
                                        </div>
                                        <span class="tw-font-semibold">{{ $statistik["alfa"] }}</span>
                                    </div>
                                    <div class="tw-flex tw-justify-between tw-items-center">
                                        <div class="tw-flex tw-items-center">
                                            <div class="tw-w-4 tw-h-4 tw-rounded-full tw-bg-gray-300 tw-border tw-border-gray-400 tw-mr-2"></div>
                                            <span>Tanpa Keterangan</span>
                                        </div>
                                        <span class="tw-font-semibold">{{ $statistik["tanpa_keterangan"] }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="tw-text-center tw-text-gray-500 tw-py-8">
                                    <i class="fas fa-chart-pie tw-text-4xl tw-mb-3"></i>
                                    <p>Pilih pertemuan untuk melihat statistik</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tw-col-span-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="tw-mb-6 tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-4 tw-px-6">
                                <div wire:ignore>
                                    <label for="selectedPertemuan" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-2">Pilih Pertemuan</label>
                                    <select wire:model="selectedPertemuan" id="selectedPertemuan" class="form-control select2 tw-w-full">
                                        <option value="">-- Pilih Pertemuan --</option>
                                        @foreach ($pertemuans as $programName => $pertemuanGroup)
                                            <optgroup label="{{ $programName }}">
                                                @foreach ($pertemuanGroup as $pertemuan)
                                                    <option value="{{ $pertemuan["id"] }}">{{ $pertemuan["pertemuan_ke"] }}. {{ $pertemuan["judul_pertemuan"] }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="searchTerm" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-2">Cari Anggota</label>
                                    <div class="tw-relative">
                                        <input type="text" wire:model.live.debounce.300ms="searchTerm" id="searchTerm" placeholder="Cari nama anggota..." class="form-control tw-pl-10" />
                                        <i class="fas fa-search tw-absolute tw-left-3 tw-top-3 tw-text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            @if ($selectedPertemuan)
                                <!-- Tabs Navigation -->
                                <div class="tw-border-b tw-border-gray-200 tw-mb-4 tw-px-6">
                                    <nav class="tw--mb-px tw-flex tw-space-x-4" aria-label="Tabs">
                                        <button wire:click="switchTab('pengurus')" type="button" class="tw-whitespace-nowrap tw-py-4 tw-px-1 tw-border-b-2 tw-font-medium tw-text-sm {{ $activeTab === "pengurus" ? "tw-border-blue-500 tw-text-blue-600" : "tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300" }}">
                                            <i class="fas fa-user-tie tw-mr-2"></i>
                                            Pengurus
                                            @if (isset($anggotaData["pengurus"]))
                                                @php
                                                    $pengurusCount = 0;
                                                    foreach ($anggotaData["pengurus"] as $dept) {
                                                        $pengurusCount += count($dept);
                                                    }
                                                @endphp

                                                <span class="tw-ml-2 tw-py-0.5 tw-px-2 tw-rounded-full tw-text-xs tw-font-semibold {{ $activeTab === "pengurus" ? "tw-bg-blue-100 tw-text-blue-800" : "tw-bg-gray-100 tw-text-gray-600" }}">
                                                    {{ $pengurusCount }}
                                                </span>
                                            @endif
                                        </button>
                                        <button wire:click="switchTab('anggota')" type="button" class="tw-whitespace-nowrap tw-py-4 tw-px-1 tw-border-b-2 tw-font-medium tw-text-sm {{ $activeTab === "anggota" ? "tw-border-blue-500 tw-text-blue-600" : "tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300" }}">
                                            <i class="fas fa-users tw-mr-2"></i>
                                            Anggota
                                            @if (isset($anggotaData["anggota"]))
                                                <span class="tw-ml-2 tw-py-0.5 tw-px-2 tw-rounded-full tw-text-xs tw-font-semibold {{ $activeTab === "anggota" ? "tw-bg-blue-100 tw-text-blue-800" : "tw-bg-gray-100 tw-text-gray-600" }}">
                                                    {{ count($anggotaData["anggota"]) }}
                                                </span>
                                            @endif
                                        </button>
                                    </nav>
                                </div>

                                <!-- Tabel Presensi -->
                                <div class="table-responsive">
                                    <table class="tw-table-auto tw-w-full">
                                        <thead class="tw-sticky tw-top-0">
                                            <tr>
                                                <th class="tw-whitespace-nowrap tw-text-center" width="5%">NO</th>
                                                <th class="tw-whitespace-nowrap tw-text-left">NAMA LENGKAP</th>
                                                <th class="tw-whitespace-nowrap tw-text-center" width="15%">KELAS</th>
                                                <th class="tw-whitespace-nowrap tw-text-center" width="10%">HADIR</th>
                                                <th class="tw-whitespace-nowrap tw-text-center" width="10%">IZIN</th>
                                                <th class="tw-whitespace-nowrap tw-text-center" width="10%">SAKIT</th>
                                                <th class="tw-whitespace-nowrap tw-text-center" width="10%">ALFA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                            @endphp

                                            @foreach ($anggotaData as $statusAnggota => $anggotaGroup)
                                                @if ($statusAnggota === $activeTab)
                                                    @if ($statusAnggota == "pengurus")
                                                        {{-- Pengurus: Group by Department --}}
                                                        @foreach ($anggotaGroup as $departmentName => $departmentMembers)
                                                            <!-- Header Department -->
                                                            <tr>
                                                                <td colspan="7" class="tw-px-6 tw-py-3 tw-font-semibold font-bagus tw-text-sm tw-text-blue-700 tw-bg-blue-50">
                                                                    <i class="fas fa-sitemap tw-mr-2"></i>
                                                                    {{ strtoupper($departmentName ?: "TANPA DEPARTMENT") }}
                                                                </td>
                                                            </tr>

                                                            @foreach ($departmentMembers as $anggota)
                                                                @php
                                                                    $anggotaObj = (object) $anggota;
                                                                    // Check tempPresensiData first (unsaved changes), then presensiData (saved)
                                                                    $temp = $tempPresensiData[$anggotaObj->id] ?? null;
                                                                    $presensi = $presensiData[$anggotaObj->id] ?? null;

                                                                    // Use temp status if exists, otherwise use saved status
                                                                    if ($temp) {
                                                                        $status = is_array($temp) ? $temp["status"] ?? null : $temp;
                                                                        // Empty string means cleared
                                                                        if ($status === "") {
                                                                            $status = null;
                                                                        }
                                                                    } else {
                                                                        $status = is_array($presensi) ? $presensi["status"] ?? null : $presensi;
                                                                    }

                                                                    $rowClass = "";
                                                                    if ($status == "hadir") {
                                                                        $rowClass = "tw-bg-green-100";
                                                                    } elseif ($status == "izin") {
                                                                        $rowClass = "tw-bg-orange-100";
                                                                    } elseif ($status == "sakit") {
                                                                        $rowClass = "tw-bg-blue-100";
                                                                    } elseif ($status == "alfa") {
                                                                        $rowClass = "tw-bg-red-100";
                                                                    }

                                                                    // Get first letter for avatar
                                                                    $initials = strtoupper(substr($anggotaObj->nama_lengkap, 0, 1));
                                                                @endphp

                                                                <tr
                                                                    wire:key="anggota-{{ $selectedPertemuan }}-{{ $anggotaObj->id }}"
                                                                    x-data="{ 
                                                                    selectedStatus: '{{ $status }}',
                                                                    init() {
                                                                        this.$watch('selectedStatus', value => {
                                                                            @this.set('tempPresensiData.{{ $anggotaObj->id }}', { status: value || '' }, false);
                                                                        });
                                                                    }
                                                                }"
                                                                    data-anggota-id="{{ $anggotaObj->id }}"
                                                                    :class="{
                                                                    'tw-bg-green-100': selectedStatus === 'hadir',
                                                                    'tw-bg-orange-100': selectedStatus === 'izin',
                                                                    'tw-bg-blue-100': selectedStatus === 'sakit',
                                                                    'tw-bg-red-100': selectedStatus === 'alfa'
                                                                }"
                                                                >
                                                                    <td class="tw-text-center">{{ $counter++ }}</td>
                                                                    <td class="">
                                                                        <div class="tw-flex tw-items-center tw-gap-3">
                                                                            <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-blue-500 tw-flex tw-items-center tw-justify-center tw-text-white tw-font-bold">
                                                                                {{ $initials }}
                                                                            </div>
                                                                            <div>
                                                                                <div class="tw-uppercase font-bagus tw-text-sm tw-font-normal">{{ $anggotaObj->nama_lengkap }}</div>
                                                                                @if (is_array($presensi) && isset($presensi["waktu"]))
                                                                                    <div class="tw-text-xs tw-text-gray-600 tw-mt-1 font-bagus tw-font-normal">
                                                                                        {{ \Carbon\Carbon::parse($presensi["waktu"])->format("d/m/Y H:i") }}
                                                                                        <span class="tw-mx-1">•</span>
                                                                                        {{ ucfirst($presensi["metode"]) }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="tw-text-center">{{ $anggotaObj->jurusan_prodi_kelas ?? "-" }}</td>

                                                                    @if ($this->can("presensi.action"))
                                                                        <td class="tw-text-center">
                                                                            <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="hadir" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                            <div class="tw-mt-1" x-show="selectedStatus === 'hadir'" x-cloak>
                                                                                <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                            </div>
                                                                        </td>
                                                                        <td class="tw-text-center">
                                                                            <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="izin" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                            <div class="tw-mt-1" x-show="selectedStatus === 'izin'" x-cloak>
                                                                                <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                            </div>
                                                                        </td>
                                                                        <td class="tw-text-center">
                                                                            <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="sakit" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                            <div class="tw-mt-1" x-show="selectedStatus === 'sakit'" x-cloak>
                                                                                <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                            </div>
                                                                        </td>
                                                                        <td class="tw-text-center">
                                                                            <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="alfa" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                            <div class="tw-mt-1" x-show="selectedStatus === 'alfa'" x-cloak>
                                                                                <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                            </div>
                                                                        </td>
                                                                    @else
                                                                        <td class="tw-text-center">
                                                                            @if ($status === "hadir")
                                                                                <i class="fas fa-check tw-text-green-600 tw-text-xl"></i>
                                                                            @else
                                                                                <span class="tw-text-gray-300">-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="tw-text-center">
                                                                            @if ($status === "izin")
                                                                                <i class="fas fa-check tw-text-orange-600 tw-text-xl"></i>
                                                                            @else
                                                                                <span class="tw-text-gray-300">-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="tw-text-center">
                                                                            @if ($status === "sakit")
                                                                                <i class="fas fa-check tw-text-blue-600 tw-text-xl"></i>
                                                                            @else
                                                                                <span class="tw-text-gray-300">-</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="tw-text-center">
                                                                            @if ($status === "alfa")
                                                                                <i class="fas fa-check tw-text-red-600 tw-text-xl"></i>
                                                                            @else
                                                                                <span class="tw-text-gray-300">-</span>
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    @else
                                                        {{-- Anggota: Direct list --}}
                                                        @foreach ($anggotaGroup as $anggota)
                                                            @php
                                                                $anggotaObj = (object) $anggota;
                                                                // Check tempPresensiData first (unsaved changes), then presensiData (saved)
                                                                $temp = $tempPresensiData[$anggotaObj->id] ?? null;
                                                                $presensi = $presensiData[$anggotaObj->id] ?? null;

                                                                // Use temp status if exists, otherwise use saved status
                                                                if ($temp) {
                                                                    $status = is_array($temp) ? $temp["status"] ?? null : $temp;
                                                                    // Empty string means cleared
                                                                    if ($status === "") {
                                                                        $status = null;
                                                                    }
                                                                } else {
                                                                    $status = is_array($presensi) ? $presensi["status"] ?? null : $presensi;
                                                                }

                                                                $rowClass = "";
                                                                if ($status == "hadir") {
                                                                    $rowClass = "tw-bg-green-100";
                                                                } elseif ($status == "izin") {
                                                                    $rowClass = "tw-bg-orange-100";
                                                                } elseif ($status == "sakit") {
                                                                    $rowClass = "tw-bg-blue-100";
                                                                } elseif ($status == "alfa") {
                                                                    $rowClass = "tw-bg-red-100";
                                                                }

                                                                // Get first letter for avatar
                                                                $initials = strtoupper(substr($anggotaObj->nama_lengkap, 0, 1));
                                                            @endphp

                                                            <tr
                                                                wire:key="anggota-{{ $selectedPertemuan }}-{{ $anggotaObj->id }}"
                                                                x-data="{ 
                                                        selectedStatus: '{{ $status }}',
                                                        init() {
                                                            this.$watch('selectedStatus', value => {
                                                                @this.set('tempPresensiData.{{ $anggotaObj->id }}', { status: value || '' }, false);
                                                            });
                                                        }
                                                    }"
                                                                data-anggota-id="{{ $anggotaObj->id }}"
                                                                :class="{
                                                        'tw-bg-green-100': selectedStatus === 'hadir',
                                                        'tw-bg-orange-100': selectedStatus === 'izin',
                                                        'tw-bg-blue-100': selectedStatus === 'sakit',
                                                        'tw-bg-red-100': selectedStatus === 'alfa'
                                                    }"
                                                            >
                                                                <td class="tw-text-center">{{ $counter++ }}</td>
                                                                <td class="">
                                                                    <div class="tw-flex tw-items-center tw-gap-3">
                                                                        <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-blue-500 tw-flex tw-items-center tw-justify-center tw-text-white tw-font-bold">
                                                                            {{ $initials }}
                                                                        </div>
                                                                        <div>
                                                                            <div class="tw-uppercase font-bagus tw-text-sm tw-font-normal">{{ $anggotaObj->nama_lengkap }}</div>
                                                                            @if (is_array($presensi) && isset($presensi["waktu"]))
                                                                                <div class="tw-text-xs tw-text-gray-600 tw-mt-1 font-bagus tw-font-normal">
                                                                                    {{ \Carbon\Carbon::parse($presensi["waktu"])->format("d/m/Y H:i") }}
                                                                                    <span class="tw-mx-1">•</span>
                                                                                    {{ ucfirst($presensi["metode"]) }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="tw-text-center">{{ $anggotaObj->jurusan_prodi_kelas ?? "-" }}</td>

                                                                @if ($this->can("presensi.action"))
                                                                    <td class="tw-text-center">
                                                                        <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="hadir" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                        <div class="tw-mt-1" x-show="selectedStatus === 'hadir'" x-cloak>
                                                                            <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                        </div>
                                                                    </td>
                                                                    <td class="tw-text-center">
                                                                        <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="izin" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                        <div class="tw-mt-1" x-show="selectedStatus === 'izin'" x-cloak>
                                                                            <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                        </div>
                                                                    </td>
                                                                    <td class="tw-text-center">
                                                                        <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="sakit" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                        <div class="tw-mt-1" x-show="selectedStatus === 'sakit'" x-cloak>
                                                                            <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                        </div>
                                                                    </td>
                                                                    <td class="tw-text-center">
                                                                        <input type="radio" name="presensi_{{ $anggotaObj->id }}" x-model="selectedStatus" value="alfa" class="tw-w-5 tw-h-5 tw-cursor-pointer" />
                                                                        <div class="tw-mt-1" x-show="selectedStatus === 'alfa'" x-cloak>
                                                                            <button type="button" @click.prevent="selectedStatus = ''" class="tw-text-xs tw-text-red-500 hover:tw-text-red-700 tw-underline">clear</button>
                                                                        </div>
                                                                    </td>
                                                                @else
                                                                    <td class="tw-text-center">
                                                                        @if ($status === "hadir")
                                                                            <i class="fas fa-check tw-text-green-600 tw-text-xl"></i>
                                                                        @else
                                                                            <span class="tw-text-gray-300">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="tw-text-center">
                                                                        @if ($status === "izin")
                                                                            <i class="fas fa-check tw-text-orange-600 tw-text-xl"></i>
                                                                        @else
                                                                            <span class="tw-text-gray-300">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="tw-text-center">
                                                                        @if ($status === "sakit")
                                                                            <i class="fas fa-check tw-text-blue-600 tw-text-xl"></i>
                                                                        @else
                                                                            <span class="tw-text-gray-300">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="tw-text-center">
                                                                        @if ($status === "alfa")
                                                                            <i class="fas fa-check tw-text-red-600 tw-text-xl"></i>
                                                                        @else
                                                                            <span class="tw-text-gray-300">-</span>
                                                                        @endif
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="tw-text-center tw-py-12">
                                    <i class="fas fa-hand-pointer tw-text-6xl tw-text-gray-300 tw-mb-4"></i>
                                    <p class="tw-text-gray-500 tw-text-lg">Silakan pilih pertemuan terlebih dahulu</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push("general-css")
    <link href="{{ asset("assets/midragon/select2/select2.min.css") }}" rel="stylesheet" />
@endpush

@push("js-libraries")
    <script src="{{ asset("/assets/midragon/select2/select2.full.min.js") }}"></script>
@endpush

@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        $(document).ready(function() {
            // Init Select2
            $('#selectedPertemuan').select2();

            // Handle Change
            $('#selectedPertemuan').on('change', function(e) {
                var data = $(this).val();
                @this.set('selectedPertemuan', data);
            });
        });

        function collectAndSubmit() {
            // Data sudah otomatis tersinkron via Alpine.js $watch
            // Langsung panggil updatePresensi
            @this.call('updatePresensi');
        }

        let presensiChart = null;

        function updateChart() {
            const ctx = document.getElementById('presensiChart');
            if (!ctx) return;

            const hadir = @this.get('statistik.hadir') || 0;
            const izin = @this.get('statistik.izin') || 0;
            const sakit = @this.get('statistik.sakit') || 0;
            const alfa = @this.get('statistik.alfa') || 0;
            const tanpaKeterangan = @this.get('statistik.tanpa_keterangan') || 0;

            // Destroy existing chart if exists
            if (presensiChart) {
                presensiChart.destroy();
            }

            // Register the plugin
            Chart.register(ChartDataLabels);

            // Create new chart
            presensiChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Hadir', 'Izin', 'Sakit', 'Alfa', 'Tanpa Keterangan'],
                    datasets: [
                        {
                            data: [hadir, izin, sakit, alfa, tanpaKeterangan],
                            backgroundColor: [
                                '#22c55e', // green-500
                                '#f97316', // orange-500
                                '#3b82f6', // blue-500
                                '#ef4444', // red-500
                                '#e5e7eb', // gray-200
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 10,
                            hoverBorderWidth: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true,
                            boxPadding: 6,
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                },
                            },
                        },
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 14
                            },
                            formatter: (value, context) => {
                                if (value === 0) return '';
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(0) : 0;
                                return percentage + '%';
                            }
                        }
                    },
                    layout: {
                        padding: 10
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    },
                },
            });
        }

        // Initialize chart when document is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateChart);
        } else {
            updateChart();
        }

        // Update chart on Livewire component update
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ component }) => {
                setTimeout(() => updateChart(), 100);
            });
        });
    </script>
@endpush
