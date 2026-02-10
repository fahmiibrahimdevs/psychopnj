<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Riwayat Presensi</h1>
        </div>

        <div class="section-body">
            {{-- Statistik Cards --}}
            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-4 tw-mb-4">
                <div class="card tw-mb-0 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="card-body tw-text-center tw-py-4">
                        <div class="tw-w-12 tw-h-12 tw-bg-blue-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-2">
                            <i class="fas fa-list-ol tw-text-blue-600 tw-text-lg"></i>
                        </div>
                        <h5 class="tw-text-2xl tw-font-bold tw-text-blue-600 tw-mb-1">{{ $statistik["total"] }}</h5>
                        <p class="tw-text-xs tw-text-gray-600 tw-mb-0 tw-font-semibold">Total</p>
                    </div>
                </div>
                <div class="card tw-mb-0 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="card-body tw-text-center tw-py-4">
                        <div class="tw-w-12 tw-h-12 tw-bg-green-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-2">
                            <i class="fas fa-check-circle tw-text-green-600 tw-text-lg"></i>
                        </div>
                        <h5 class="tw-text-2xl tw-font-bold tw-text-green-600 tw-mb-1">{{ $statistik["hadir"] }}</h5>
                        <p class="tw-text-xs tw-text-gray-600 tw-mb-0 tw-font-semibold">Hadir</p>
                    </div>
                </div>
                <div class="card tw-mb-0 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="card-body tw-text-center tw-py-4">
                        <div class="tw-w-12 tw-h-12 tw-bg-yellow-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-2">
                            <i class="fas fa-hand-paper tw-text-yellow-600 tw-text-lg"></i>
                        </div>
                        <h5 class="tw-text-2xl tw-font-bold tw-text-yellow-600 tw-mb-1">{{ $statistik["izin"] }}</h5>
                        <p class="tw-text-xs tw-text-gray-600 tw-mb-0 tw-font-semibold">Izin</p>
                    </div>
                </div>
                <div class="card tw-mb-0 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="card-body tw-text-center tw-py-4">
                        <div class="tw-w-12 tw-h-12 tw-bg-orange-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-2">
                            <i class="fas fa-notes-medical tw-text-orange-600 tw-text-lg"></i>
                        </div>
                        <h5 class="tw-text-2xl tw-font-bold tw-text-orange-600 tw-mb-1">{{ $statistik["sakit"] }}</h5>
                        <p class="tw-text-xs tw-text-gray-600 tw-mb-0 tw-font-semibold">Sakit</p>
                    </div>
                </div>
                <div class="card tw-mb-0 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="card-body tw-text-center tw-py-4">
                        <div class="tw-w-12 tw-h-12 tw-bg-red-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-2">
                            <i class="fas fa-times-circle tw-text-red-600 tw-text-lg"></i>
                        </div>
                        <h5 class="tw-text-2xl tw-font-bold tw-text-red-600 tw-mb-1">{{ $statistik["alfa"] }}</h5>
                        <p class="tw-text-xs tw-text-gray-600 tw-mb-0 tw-font-semibold">Alfa</p>
                    </div>
                </div>
                <div class="card tw-mb-0 tw-rounded-xl tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="card-body tw-text-center tw-py-4">
                        <div class="tw-w-12 tw-h-12 tw-bg-purple-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-2">
                            <i class="fas fa-chart-pie tw-text-purple-600 tw-text-lg"></i>
                        </div>
                        <h5 class="tw-text-2xl tw-font-bold tw-text-purple-600 tw-mb-1">{{ $statistik["persentase"] }}%</h5>
                        <p class="tw-text-xs tw-text-gray-600 tw-mb-0 tw-font-semibold">Kehadiran</p>
                    </div>
                </div>
            </div>

            <div class="card tw-rounded-xl">
                <div class="card-body tw-p-0">
                    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
                        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800 tw-mb-0">Riwayat Kehadiran</h3>
                    </div>

                    <div class="tw-overflow-x-auto">
                        @forelse ($presensiList as $index => $presensi)
                            <div class="tw-flex tw-flex-col md:tw-flex-row tw-items-start md:tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition tw-gap-3">
                                <div class="tw-flex tw-items-start tw-gap-4 tw-w-full md:tw-w-auto">
                                    <div class="tw-flex-shrink-0 tw-w-12 tw-h-12 tw-bg-gradient-to-br tw-from-blue-500 tw-to-blue-600 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-text-white tw-font-bold tw-shadow-md">
                                        {{ $presensi->pertemuan_ke }}
                                    </div>
                                    <div class="tw-flex-1 tw-min-w-0">
                                        <h4 class="tw-font-semibold tw-text-gray-800 tw-mb-1 tw-text-sm md:tw-text-base">{{ $presensi->judul_pertemuan }}</h4>
                                        <div class="tw-flex tw-flex-wrap tw-gap-2 tw-items-center">
                                            <span class="tw-inline-flex tw-items-center tw-text-xs tw-bg-blue-50 tw-text-blue-700 tw-px-2 tw-py-1 tw-rounded-md tw-font-medium">
                                                <i class="fas fa-book tw-mr-1"></i>
                                                {{ $presensi->nama_program }}
                                            </span>
                                            <span class="tw-inline-flex tw-items-center tw-text-xs tw-text-gray-600">
                                                <i class="fas fa-calendar tw-mr-1"></i>
                                                {{ $presensi->tanggal ? \Carbon\Carbon::parse($presensi->tanggal)->format("d M Y") : "-" }}
                                            </span>
                                            @if ($presensi->waktu)
                                                <span class="tw-inline-flex tw-items-center tw-text-xs tw-text-gray-600">
                                                    <i class="fas fa-clock tw-mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($presensi->waktu)->format("H:i") }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tw-flex-shrink-0 tw-w-full md:tw-w-auto">
                                    @switch($presensi->status)
                                        @case("hadir")
                                            <span class="tw-inline-flex tw-items-center tw-bg-green-100 tw-text-green-700 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-text-sm tw-w-full md:tw-w-auto tw-justify-center">
                                                <i class="fas fa-check-circle tw-mr-2"></i>
                                                Hadir
                                            </span>

                                            @break
                                        @case("izin")
                                            <span class="tw-inline-flex tw-items-center tw-bg-yellow-100 tw-text-yellow-700 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-text-sm tw-w-full md:tw-w-auto tw-justify-center">
                                                <i class="fas fa-hand-paper tw-mr-2"></i>
                                                Izin
                                            </span>

                                            @break
                                        @case("sakit")
                                            <span class="tw-inline-flex tw-items-center tw-bg-orange-100 tw-text-orange-700 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-text-sm tw-w-full md:tw-w-auto tw-justify-center">
                                                <i class="fas fa-notes-medical tw-mr-2"></i>
                                                Sakit
                                            </span>

                                            @break
                                        @case("alfa")
                                            <span class="tw-inline-flex tw-items-center tw-bg-red-100 tw-text-red-700 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-text-sm tw-w-full md:tw-w-auto tw-justify-center">
                                                <i class="fas fa-times-circle tw-mr-2"></i>
                                                Alfa
                                            </span>

                                            @break
                                        @default
                                            @if (\Carbon\Carbon::parse($presensi->tanggal)->isPast())
                                                <span class="tw-inline-flex tw-items-center tw-bg-gray-100 tw-text-gray-500 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-text-sm tw-w-full md:tw-w-auto tw-justify-center">
                                                    <i class="fas fa-question-circle tw-mr-2"></i>
                                                    Belum Hadir
                                                </span>
                                            @else
                                                <span class="tw-inline-flex tw-items-center tw-bg-blue-50 tw-text-blue-500 tw-px-4 tw-py-2 tw-rounded-lg tw-font-semibold tw-text-sm tw-w-full md:tw-w-auto tw-justify-center">
                                                    <i class="fas fa-hourglass-start tw-mr-2"></i>
                                                    Belum Mulai
                                                </span>
                                            @endif
                                    @endswitch
                                </div>
                            </div>
                        @empty
                            <div class="tw-text-center tw-py-12 tw-px-4">
                                <div class="tw-w-20 tw-h-20 tw-bg-gray-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-4">
                                    <i class="fas fa-inbox tw-text-4xl tw-text-gray-300"></i>
                                </div>
                                <h4 class="tw-text-lg tw-font-semibold tw-text-gray-700 tw-mb-2">Belum Ada Riwayat Presensi</h4>
                                <p class="tw-text-gray-500 tw-text-sm">Riwayat presensi Anda akan muncul di sini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
