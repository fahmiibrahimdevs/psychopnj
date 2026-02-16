<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Statistik Kehadiran Saya</h1>
        </div>

        <div class="section-body">
            @if ($anggota)
                <!-- Card Profile Anggota -->
                <div class="card mb-4">
                    <div class="card-body tw-px-4 lg:tw-px-6">
                        <div class="tw-flex tw-items-center tw-gap-4">
                            <div class="tw-relative">
                                @if ($anggota->foto)
                                    <img src="{{ asset("storage/" . $anggota->foto) }}" class="tw-w-20 tw-h-20 tw-rounded-full tw-object-cover" alt="Avatar" />
                                @else
                                    <div class="tw-w-20 tw-h-20 tw-rounded-full tw-bg-gray-200 tw-flex tw-items-center tw-justify-center">
                                        <i class="fas fa-user tw-text-gray-400 tw-text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="tw-flex-1">
                                <h4 class="tw-text-lg tw-font-bold tw-text-gray-800 tw-mb-1">{{ $anggota->nama_lengkap }}</h4>
                                <p class="tw-text-sm tw-text-gray-600 tw-mb-0">
                                    @if ($anggota->status_anggota === "pengurus")
                                        Pengurus - {{ $anggota->department->nama_department ?? "-" }}
                                    @else
                                        Anggota - {{ $anggota->jurusan_prodi_kelas ?? "-" }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Statistik Kehadiran -->
                <div class="card">
                    <h3>
                        <i class="fas fa-chart-bar"></i>
                        Ringkasan Kehadiran
                    </h3>
                    <div class="card-body tw-px-4 lg:tw-px-6">
                        <!-- Progress Bar -->
                        <div class="tw-mb-6">
                            <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
                                <span class="tw-text-sm tw-font-semibold tw-text-gray-700">Persentase Kehadiran</span>
                                <span class="tw-text-2xl tw-font-bold {{ $statistics["percentage"] >= 75 ? "tw-text-green-600" : ($statistics["percentage"] >= 50 ? "tw-text-yellow-600" : "tw-text-red-600") }}">{{ $statistics["percentage"] }}%</span>
                            </div>
                            <div class="progress tw-h-4 tw-mb-2">
                                <div class="progress-bar {{ $statistics["percentage"] >= 75 ? "bg-success" : ($statistics["percentage"] >= 50 ? "bg-warning" : "bg-danger") }}" role="progressbar" style="width: {{ $statistics["percentage"] }}%" aria-valuenow="{{ $statistics["percentage"] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="tw-text-xs tw-text-gray-500 tw-text-center">{{ $statistics["hadir"] }} dari {{ $statistics["total_wajib"] }} pertemuan wajib</p>
                        </div>

                        <!-- Statistik Grid -->
                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-4 tw-gap-4 tw-mb-6">
                            <div class="tw-bg-green-50 tw-p-4 tw-rounded-lg tw-border tw-border-green-200">
                                <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                    <i class="fas fa-check-circle tw-text-green-600 tw-text-xl"></i>
                                    <span class="tw-text-2xl tw-font-bold tw-text-green-600">{{ $statistics["hadir"] }}</span>
                                </div>
                                <div class="tw-text-xs tw-text-gray-600 tw-font-semibold">Hadir</div>
                            </div>

                            <div class="tw-bg-blue-50 tw-p-4 tw-rounded-lg tw-border tw-border-blue-200">
                                <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                    <i class="fas fa-file-alt tw-text-blue-600 tw-text-xl"></i>
                                    <span class="tw-text-2xl tw-font-bold tw-text-blue-600">{{ $statistics["izin"] }}</span>
                                </div>
                                <div class="tw-text-xs tw-text-gray-600 tw-font-semibold">Izin</div>
                            </div>

                            <div class="tw-bg-yellow-50 tw-p-4 tw-rounded-lg tw-border tw-border-yellow-200">
                                <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                    <i class="fas fa-thermometer-half tw-text-yellow-600 tw-text-xl"></i>
                                    <span class="tw-text-2xl tw-font-bold tw-text-yellow-600">{{ $statistics["sakit"] }}</span>
                                </div>
                                <div class="tw-text-xs tw-text-gray-600 tw-font-semibold">Sakit</div>
                            </div>

                            <div class="tw-bg-red-50 tw-p-4 tw-rounded-lg tw-border tw-border-red-200">
                                <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                                    <i class="fas fa-times-circle tw-text-red-600 tw-text-xl"></i>
                                    <span class="tw-text-2xl tw-font-bold tw-text-red-600">{{ $statistics["alfa"] }}</span>
                                </div>
                                <div class="tw-text-xs tw-text-gray-600 tw-font-semibold">Alfa/Tanpa Ket</div>
                            </div>
                        </div>

                        <!-- Riwayat Kehadiran -->
                        <div class="tw-mt-6">
                            <h5 class="tw-text-base tw-font-bold tw-text-gray-800 tw-mb-4">
                                <i class="fas fa-history"></i>
                                Riwayat Kehadiran Pertemuan
                            </h5>

                            @if (count($attendanceHistory) > 0)
                                <div class="tw-space-y-4">
                                    @foreach ($attendanceHistory as $programName => $meetings)
                                        <div class="tw-bg-gray-50 tw-rounded-lg tw-p-4 tw-border">
                                            <h6 class="tw-text-sm tw-font-bold tw-text-gray-700 tw-mb-3 tw-uppercase tw-tracking-wider tw-border-b tw-pb-2">
                                                <i class="fas fa-book"></i>
                                                {{ $programName }}
                                            </h6>
                                            <div class="tw-space-y-2">
                                                @foreach ($meetings as $meeting)
                                                    <div class="tw-flex tw-justify-between tw-items-center tw-bg-white tw-p-3 tw-rounded tw-border-l-4 {{ $meeting["status"] == "hadir" ? "tw-border-green-500" : ($meeting["status"] == "izin" ? "tw-border-blue-500" : ($meeting["status"] == "sakit" ? "tw-border-yellow-500" : "tw-border-red-500")) }}">
                                                        <div class="tw-flex-1">
                                                            <div class="tw-text-sm tw-font-semibold tw-text-gray-800">{{ $meeting["pertemuan"] }}</div>
                                                            <div class="tw-text-xs tw-text-gray-500 tw-mt-1">
                                                                <i class="far fa-calendar"></i>
                                                                {{ \Carbon\Carbon::parse($meeting["tanggal"])->format("d M Y") }}
                                                                @if ($meeting["waktu"])
                                                                    |
                                                                    <i class="far fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($meeting["waktu"])->format("H:i") }}
                                                                @endif

                                                                @if ($meeting["metode"])
                                                                    |
                                                                    <i class="fas fa-qrcode"></i>
                                                                    {{ ucfirst($meeting["metode"]) }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            @if ($meeting["status"] == "hadir")
                                                                <span class="badge badge-success tw-text-xs tw-px-3 tw-py-1">Hadir</span>
                                                            @elseif ($meeting["status"] == "izin")
                                                                <span class="badge badge-info tw-text-xs tw-px-3 tw-py-1">Izin</span>
                                                            @elseif ($meeting["status"] == "sakit")
                                                                <span class="badge badge-warning tw-text-xs tw-px-3 tw-py-1">Sakit</span>
                                                            @elseif ($meeting["status"] == "alfa")
                                                                <span class="badge badge-danger tw-text-xs tw-px-3 tw-py-1">Alfa</span>
                                                            @else
                                                                <span class="badge badge-light tw-text-gray-600 tw-text-xs tw-px-3 tw-py-1">Tanpa Ket</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="tw-text-center tw-py-8">
                                    <img src="{{ asset("assets/img/drawkit/drawkit-nature-man-colour.svg") }}" alt="Empty" class="tw-h-32 tw-mx-auto tw-mb-4 tw-opacity-50" />
                                    <h5 class="tw-text-gray-500">Belum ada riwayat pertemuan</h5>
                                    <p class="tw-text-sm tw-text-gray-400">Data kehadiran akan muncul setelah Anda mengikuti pertemuan</p>
                                </div>
                            @endif
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
