<div>
    <style>
        .scrollbar-hide {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
    </style>
    <div class="tw-flex-grow tw-container tw-max-w-7xl tw-mx-auto tw-px-4 lg:tw-px-0">
        {{-- Hero Section --}}
        <div class="tw-text-center tw-mb-16">
            <h1 class="tw-text-4xl md:tw-text-5xl tw-font-bold tw-text-white tw-mb-4">
                Tentang
                <span class="tw-text-cyan-300">Psychorobotic</span>
            </h1>
            <p class="tw-text-gray-400 tw-text-lg tw-max-w-3xl tw-mx-auto">Organisasi Kemahasiswaan yang berfokus pada pengembangan teknologi robotika dan kecerdasan buatan</p>
        </div>

        {{-- Profil Organisasi --}}
        <div class="tw-mb-20">
            {{-- Logo & Info Dasar - Centered --}}
            <div class="tw-text-center tw-mb-12">
                <div class="tw-flex tw-justify-center tw-mb-6">
                    <img src="{{ asset("icons/logo-psychorobotic.png") }}" class="tw-w-40 tw-h-40 tw-rounded-full tw-border-4 tw-border-cyan-500/30 tw-shadow-lg tw-shadow-cyan-500/20" alt="Logo Psychorobotic" />
                </div>
                <h2 class="tw-text-3xl tw-font-bold tw-text-cyan-300 tw-mb-2">
                    @if ($profilOrganisasi && isset($profilOrganisasi->nama_organisasi))
                        {{ $profilOrganisasi->nama_organisasi }}
                    @else
                        KSM Psychorobotic
                    @endif
                </h2>
                <p class="tw-text-gray-400 tw-mb-6 tw-max-w-2xl tw-mx-auto">
                    @if ($profilOrganisasi && isset($profilOrganisasi->deskripsi_singkat))
                        {{ $profilOrganisasi->deskripsi_singkat }}
                    @else
                        Kelompok Studi Mahasiswa Robotika - Politeknik Negeri Jakarta
                    @endif
                </p>

                <div class="tw-flex tw-justify-center tw-gap-8 tw-mt-8">
                    <div class="tw-text-center">
                        <div class="tw-text-4xl tw-font-bold tw-text-cyan-300 tw-mb-1">{{ $totalAnggota }}+</div>
                        <div class="tw-text-gray-400 tw-text-sm">Anggota Aktif</div>
                    </div>
                    <div class="tw-text-center">
                        <div class="tw-text-4xl tw-font-bold tw-text-purple-300 tw-mb-1">{{ count($departmentsWithMembers ?? []) }}</div>
                        <div class="tw-text-gray-400 tw-text-sm">Divisi</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Visi & Misi --}}
        <div class="tw-mb-20">
            <div class="tw-text-center tw-mb-12">
                <h3 class="tw-text-3xl tw-font-bold tw-text-cyan-300 tw-mb-4">Visi & Misi</h3>
                <p class="tw-text-gray-400 tw-text-sm tw-max-w-2xl tw-mx-auto">Arah dan tujuan organisasi dalam mengembangkan talenta robotika</p>
            </div>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-8">
                {{-- Visi Card --}}
                <div class="tw-bg-gradient-to-br tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border tw-border-cyan-500/30 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-shadow-lg hover:tw-shadow-cyan-500/20">
                    <div class="tw-flex tw-items-center tw-mb-6">
                        <div class="tw-w-16 tw-h-16 tw-bg-gradient-to-br tw-from-cyan-400 tw-to-blue-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4">
                            <i class="fas fa-eye tw-text-white tw-text-2xl"></i>
                        </div>
                        <h4 class="tw-text-2xl tw-font-bold tw-text-cyan-300">Visi</h4>
                    </div>
                    <p class="tw-text-gray-300 tw-text-base tw-leading-relaxed tw-text-justify">
                        @if ($profilOrganisasi && isset($profilOrganisasi->visi) && $profilOrganisasi->visi)
                            {{ $profilOrganisasi->visi }}
                        @else
                            Menjadi organisasi kemahasiswaan terdepan dalam pengembangan teknologi robotika dan kecerdasan buatan yang menghasilkan inovator-inovator muda berkualitas.
                        @endif
                    </p>
                </div>

                {{-- Misi Card --}}
                <div class="tw-bg-gradient-to-br tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border tw-border-purple-500/30 tw-transition-all tw-duration-300 hover:tw-border-purple-400 hover:tw-shadow-lg hover:tw-shadow-purple-500/20">
                    <div class="tw-flex tw-items-center tw-mb-6">
                        <div class="tw-w-16 tw-h-16 tw-bg-gradient-to-br tw-from-purple-400 tw-to-pink-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4">
                            <i class="fas fa-bullseye tw-text-white tw-text-2xl"></i>
                        </div>
                        <h4 class="tw-text-2xl tw-font-bold tw-text-purple-300">Misi</h4>
                    </div>
                    <div class="tw-text-gray-300 tw-text-base tw-leading-relaxed tw-text-justify">
                        @if ($profilOrganisasi && isset($profilOrganisasi->misi) && $profilOrganisasi->misi)
                            {!! $profilOrganisasi->misi !!}
                        @else
                            <ul class="tw-list-disc tw-list-inside tw-space-y-2">
                                <li>Menyelenggarakan pembelajaran terstruktur di bidang robotika</li>
                                <li>Memfasilitasi pengembangan project teknologi inovatif</li>
                                <li>Membangun komunitas pembelajar yang kolaboratif</li>
                                <li>Mempersiapkan anggota untuk kompetisi tingkat nasional</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Struktur Organisasi --}}
        <div class="tw-mb-20">
            <div class="tw-text-center tw-mb-12">
                <h3 class="tw-text-3xl tw-font-bold tw-text-cyan-300 tw-mb-4">Struktur Organisasi</h3>
                <p class="tw-text-gray-400 tw-text-xl tw-max-w-2xl tw-mx-auto">
                    Periode
                    @if ($activeTahun && isset($activeTahun->nama_tahun))
                        {{ $activeTahun->nama_tahun }}
                    @else
                        2024/2025
                    @endif
                </p>
            </div>

            {{-- Departments with Members --}}
            @if (count($departmentsWithMembers) > 0)
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6">
                    @foreach ($departmentsWithMembers as $index => $deptData)
                        @php
                            $dept = $deptData["department"];
                            $members = $deptData["members"];
                            $colors = [
                                ["gradient" => "tw-from-cyan-400 tw-to-blue-500", "border" => "tw-border-cyan-500/30", "text" => "tw-text-cyan-300", "bg" => "tw-bg-cyan-500/10"],
                                ["gradient" => "tw-from-purple-400 tw-to-pink-500", "border" => "tw-border-purple-500/30", "text" => "tw-text-purple-300", "bg" => "tw-bg-purple-500/10"],
                                ["gradient" => "tw-from-green-400 tw-to-emerald-500", "border" => "tw-border-green-500/30", "text" => "tw-text-green-300", "bg" => "tw-bg-green-500/10"],
                                ["gradient" => "tw-from-yellow-400 tw-to-orange-500", "border" => "tw-border-yellow-500/30", "text" => "tw-text-yellow-300", "bg" => "tw-bg-yellow-500/10"],
                                ["gradient" => "tw-from-red-400 tw-to-rose-500", "border" => "tw-border-red-500/30", "text" => "tw-text-red-300", "bg" => "tw-bg-red-500/10"],
                                ["gradient" => "tw-from-blue-400 tw-to-indigo-500", "border" => "tw-border-blue-500/30", "text" => "tw-text-blue-300", "bg" => "tw-bg-blue-500/10"],
                            ];
                            $color = $colors[$index % count($colors)];
                        @endphp

                        <div class="tw-bg-gradient-to-br tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-border {{ $color["border"] }} tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-lg tw-overflow-hidden">
                            {{-- Department Header --}}
                            <div class="tw-p-6 tw-pb-4">
                                <div class="tw-flex tw-items-center tw-mb-4">
                                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br {{ $color["gradient"] }} tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4 tw-shrink-0">
                                        <i class="fas {{ $dept->ikon ?? "fa-users" }} tw-text-white tw-text-2xl"></i>
                                    </div>
                                    <div class="tw-flex-1">
                                        <h4 class="tw-text-xl tw-font-bold {{ $color["text"] }} tw-leading-tight">{{ $dept->nama_department }}</h4>
                                        @if ($dept->kategori)
                                            <p class="tw-text-gray-400 tw-text-xs tw-mt-1">{{ $dept->kategori }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if ($dept->deskripsi)
                                    <div>
                                        <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed tw-mb-2">{{ Str::limit(strip_tags($dept->deskripsi), 80) }}</p>
                                        @if (strlen(strip_tags($dept->deskripsi)) > 80)
                                            <button onclick="document.getElementById('modal-dept-{{ $dept->id }}').classList.remove('tw-hidden')" class="tw-text-xs tw-text-cyan-400 hover:tw-text-cyan-300 tw-transition-colors tw-flex tw-items-center tw-gap-1">
                                                <span>Baca Selengkapnya</span>
                                                <i class="fas fa-arrow-right tw-text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            {{-- Members List --}}
                            @if (count($members) > 0)
                                <div class="tw-border-t tw-border-gray-700/50 {{ $color["bg"] }} tw-p-4">
                                    <p class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-mb-3 tw-uppercase tw-tracking-wide">Anggota ({{ count($members) }})</p>
                                    <div class="tw-space-y-2 tw-max-h-60 tw-overflow-y-auto scrollbar-hide">
                                        @foreach ($members as $member)
                                            <div class="tw-flex tw-items-start tw-gap-3 tw-p-2 tw-rounded-lg hover:tw-bg-white/5 tw-transition-colors">
                                                @if ($member->foto)
                                                    <img src="{{ asset("storage/" . $member->foto) }}" class="tw-w-10 tw-h-10 tw-rounded-full tw-object-cover tw-border-2 {{ $color["border"] }}" alt="{{ $member->nama_lengkap }}" />
                                                @else
                                                    <div class="tw-w-10 tw-h-10 tw-rounded-full tw-bg-gradient-to-br {{ $color["gradient"] }} tw-flex tw-items-center tw-justify-center tw-shrink-0">
                                                        <i class="fas fa-user tw-text-white tw-text-sm"></i>
                                                    </div>
                                                @endif
                                                <div class="tw-flex-1 tw-min-w-0">
                                                    <p class="tw-text-white tw-text-sm tw-font-semibold tw-truncate">{{ $member->nama_lengkap }}</p>
                                                    <p class="tw-text-gray-400 tw-text-xs tw-truncate">{{ $member->nama_jabatan }}</p>
                                                    @if ($member->jurusan_prodi_kelas)
                                                        <p class="tw-text-gray-500 tw-text-xs tw-mt-0.5">{{ $member->jurusan_prodi_kelas }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="tw-border-t tw-border-gray-700/50 {{ $color["bg"] }} tw-p-4">
                                    <p class="tw-text-gray-500 tw-text-sm tw-text-center tw-italic">Belum ada anggota</p>
                                </div>
                            @endif
                        </div>

                        {{-- Modal for Full Description --}}
                        @if ($dept->deskripsi && strlen(strip_tags($dept->deskripsi)) > 80)
                            <div id="modal-dept-{{ $dept->id }}" class="tw-hidden tw-fixed tw-inset-0 tw-bg-black/80 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4" onclick="if(event.target === this) this.classList.add('tw-hidden')">
                                <div class="tw-bg-gradient-to-br tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border {{ $color["border"] }} tw-max-w-2xl tw-w-full tw-max-h-[80vh] tw-overflow-y-auto scrollbar-hide" onclick="event.stopPropagation()">
                                    <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                                        <div class="tw-flex tw-items-center">
                                            <div class="tw-w-12 tw-h-12 tw-bg-gradient-to-br {{ $color["gradient"] }} tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4">
                                                <i class="fas {{ $dept->ikon ?? "fa-users" }} tw-text-white tw-text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="tw-text-2xl tw-font-bold {{ $color["text"] }}">{{ $dept->nama_department }}</h4>
                                                @if ($dept->kategori)
                                                    <p class="tw-text-gray-400 tw-text-sm">{{ $dept->kategori }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <button onclick="document.getElementById('modal-dept-{{ $dept->id }}').classList.add('tw-hidden')" class="tw-text-gray-400 hover:tw-text-white tw-transition-colors">
                                            <i class="fas fa-times tw-text-2xl"></i>
                                        </button>
                                    </div>
                                    <div class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">
                                        {!! nl2br(e($dept->deskripsi)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="tw-text-center tw-py-12">
                    <i class="fas fa-sitemap tw-text-6xl tw-text-gray-600 tw-mb-4"></i>
                    <p class="tw-text-gray-400">Data struktur organisasi akan segera ditampilkan</p>
                </div>
            @endif
        </div>
    </div>
</div>
