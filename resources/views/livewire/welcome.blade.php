<div>
    <div class="tw-flex-grow tw-container tw-max-w-7xl tw-mx-auto">
        {{-- Hero Section --}}
        <div class="tw-grid tw-grid-cols-1 tw-px-4 tw-gap-10 lg:tw-grid-cols-3 lg:tw-px-0">
            <div class="tw-col-span-2">
                <p class="tw-text-lg tw-text-cyan-300">Halo, Kami</p>
                <div id="typing-effect" class="tw-text-2xl tw-h-16 lg:tw-h-8 tw-font-bold tw-mt-2 tw-leading-relaxed tw-tracking-wide"></div>
                <p class="tw-mt-10 tw-text-sm lg:tw-text-base tw-text-white tw-tracking-wide tw-leading-relaxed">
                    Psychorobotic adalah
                    <span class="tw-text-cyan-300 tw-font-semibold">organisasi kemahasiswaan</span>
                    di bawah naungan Jurusan Teknik Elektro PNJ yang berfokus pada bidang
                    <span class="tw-text-cyan-300 tw-font-semibold">Robotika.</span>
                    Meskipun berada di bawah Jurusan Teknik Elektro, kami terbuka untuk seluruh mahasiswa dari jurusan manapun.
                </p>
                <div class="tw-mt-10 tw-flex tw-gap-4 tw-flex-wrap">
                    <a href="{{ url("secure-access") }}" class="tw-bg-gradient-to-r tw-from-cyan-400 tw-to-blue-500 tw-px-6 tw-py-3 tw-rounded-full tw-text-white tw-font-semibold tw-text-sm tw-shadow-lg hover:tw-shadow-cyan-500/50 tw-transition-all tw-duration-300 hover:tw-scale-105">
                        <i class="fas fa-rocket tw-mr-2"></i>
                        Yuk Gabung Sekarang
                    </a>
                    <a href="{{ url("galeri") }}" class="tw-bg-transparent tw-border-2 tw-border-cyan-400 tw-px-6 tw-py-3 tw-rounded-full tw-text-cyan-300 tw-font-semibold tw-text-sm hover:tw-bg-cyan-400 hover:tw-text-black tw-transition-all tw-duration-300">
                        <i class="fas fa-images tw-mr-2"></i>
                        Lihat Galeri
                    </a>
                </div>
            </div>
            <div class="tw-hidden md:tw-hidden lg:tw-block">
                <div class="tw-ml-20">
                    <img class="tw-w-12/12 tw-rounded-full tw-shadow-2xl tw-shadow-cyan-500/30 tw-border-4 tw-border-cyan-400/20" src="{{ asset("psychorobotic/foto-1.jpg") }}" alt="Psychorobotic Team" />
                </div>
            </div>
        </div>

        {{-- Statistics Section --}}
        <div class="tw-mt-20 tw-px-4 lg:tw-px-0">
            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-4 tw-gap-6">
                {{-- Stat 1: Anggota Aktif --}}
                <div class="tw-bg-gradient-to-br tw-from-cyan-500/10 tw-to-blue-500/10 tw-rounded-2xl tw-p-6 tw-text-center tw-border tw-border-cyan-500/20 tw-backdrop-blur-sm tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-lg hover:tw-shadow-cyan-500/20">
                    <div class="tw-text-4xl tw-font-bold tw-text-cyan-300 tw-mb-2">{{ $totalAnggota }}+</div>
                    <div class="tw-text-gray-400 tw-text-sm">Anggota Aktif</div>
                </div>

                {{-- Stat 2: Project Selesai --}}
                <div class="tw-bg-gradient-to-br tw-from-purple-500/10 tw-to-pink-500/10 tw-rounded-2xl tw-p-6 tw-text-center tw-border tw-border-purple-500/20 tw-backdrop-blur-sm tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-lg hover:tw-shadow-purple-500/20">
                    <div class="tw-text-4xl tw-font-bold tw-text-purple-300 tw-mb-2">{{ $totalProjects }}+</div>
                    <div class="tw-text-gray-400 tw-text-sm">Project Selesai</div>
                </div>

                {{-- Stat 3: Workshop --}}
                <div class="tw-bg-gradient-to-br tw-from-green-500/10 tw-to-emerald-500/10 tw-rounded-2xl tw-p-6 tw-text-center tw-border tw-border-green-500/20 tw-backdrop-blur-sm tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-lg hover:tw-shadow-green-500/20">
                    <div class="tw-text-4xl tw-font-bold tw-text-green-300 tw-mb-2">{{ $totalWorkshops }}+</div>
                    <div class="tw-text-gray-400 tw-text-sm">Workshop</div>
                </div>

                {{-- Stat 4: Pertemuan --}}
                <div class="tw-bg-gradient-to-br tw-from-yellow-500/10 tw-to-orange-500/10 tw-rounded-2xl tw-p-6 tw-text-center tw-border tw-border-yellow-500/20 tw-backdrop-blur-sm tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-lg hover:tw-shadow-yellow-500/20">
                    <div class="tw-text-4xl tw-font-bold tw-text-yellow-300 tw-mb-2">{{ $totalPertemuan }}+</div>
                    <div class="tw-text-gray-400 tw-text-sm">Total Pertemuan</div>
                </div>
            </div>
        </div>

        {{-- Visi Misi Section --}}
        @if ($profilOrganisasi && ($profilOrganisasi->visi || $profilOrganisasi->misi))
            <div class="tw-mt-20 tw-px-4 lg:tw-px-0">
                <div class="tw-text-center tw-mb-12">
                    <h3 class="tw-text-3xl tw-font-bold tw-text-cyan-300 tw-mb-4">Visi & Misi</h3>
                    <p class="tw-text-gray-400 tw-text-sm tw-max-w-2xl tw-mx-auto">Arah dan tujuan Psychorobotic dalam mengembangkan talenta robotika di PNJ</p>
                </div>

                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-8">
                    @if ($profilOrganisasi->visi)
                        {{-- Visi Card --}}
                        <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border tw-border-cyan-500/30 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-shadow-lg hover:tw-shadow-cyan-500/20">
                            <div class="tw-flex tw-items-center tw-mb-6">
                                <div class="tw-w-12 tw-h-12 tw-bg-gradient-to-br tw-from-cyan-400 tw-to-blue-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4">
                                    <i class="fas fa-eye tw-text-white tw-text-xl"></i>
                                </div>
                                <h4 class="tw-text-2xl tw-font-bold tw-text-cyan-300">Visi</h4>
                            </div>
                            <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed tw-text-justify">{{ $profilOrganisasi->visi }}</p>
                        </div>
                    @endif

                    @if ($profilOrganisasi->misi)
                        {{-- Misi Card --}}
                        <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border tw-border-purple-500/30 tw-transition-all tw-duration-300 hover:tw-border-purple-400 hover:tw-shadow-lg hover:tw-shadow-purple-500/20">
                            <div class="tw-flex tw-items-center tw-mb-6">
                                <div class="tw-w-12 tw-h-12 tw-bg-gradient-to-br tw-from-purple-400 tw-to-pink-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4">
                                    <i class="fas fa-bullseye tw-text-white tw-text-xl"></i>
                                </div>
                                <h4 class="tw-text-2xl tw-font-bold tw-text-purple-300">Misi</h4>
                            </div>
                            <div class="tw-text-gray-300 tw-text-sm tw-leading-relaxed tw-text-justify">
                                {!! $profilOrganisasi->misi !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Why Join Us Section --}}
        <div class="tw-mt-20 tw-px-4 lg:tw-px-0">
            <div class="tw-text-center tw-mb-12">
                <h3 class="tw-text-3xl tw-font-bold tw-text-cyan-300 tw-mb-4">Kenapa Harus Bergabung?</h3>
                <p class="tw-text-gray-400 tw-text-sm tw-max-w-2xl tw-mx-auto">Psychorobotic bukan hanya organisasi biasa, kami adalah keluarga yang saling mendukung dalam belajar dan berkembang bersama</p>
            </div>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6">
                {{-- Reason 1: Pembelajaran Terstruktur --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-border tw-border-cyan-500/20 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-shadow-lg hover:tw-shadow-cyan-500/20">
                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br tw-from-cyan-400 tw-to-blue-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-book-reader tw-text-white tw-text-2xl"></i>
                    </div>
                    <h4 class="tw-text-xl tw-font-semibold tw-text-cyan-300 tw-mb-3">Pembelajaran Terstruktur</h4>
                    <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed">Kurikulum pembelajaran yang tersusun rapi dari basic hingga advanced dengan mentor berpengalaman</p>
                </div>

                {{-- Reason 2: Project Real-World --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-border tw-border-purple-500/20 tw-transition-all tw-duration-300 hover:tw-border-purple-400 hover:tw-shadow-lg hover:tw-shadow-purple-500/20">
                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br tw-from-purple-400 tw-to-pink-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-project-diagram tw-text-white tw-text-2xl"></i>
                    </div>
                    <h4 class="tw-text-xl tw-font-semibold tw-text-purple-300 tw-mb-3">Project Real-World</h4>
                    <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed">Kesempatan mengerjakan project nyata yang bisa dimasukkan ke portfolio dan CV kamu</p>
                </div>

                {{-- Reason 3: Networking --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-border tw-border-green-500/20 tw-transition-all tw-duration-300 hover:tw-border-green-400 hover:tw-shadow-lg hover:tw-shadow-green-500/20">
                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br tw-from-green-400 tw-to-emerald-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-users tw-text-white tw-text-2xl"></i>
                    </div>
                    <h4 class="tw-text-xl tw-font-semibold tw-text-green-300 tw-mb-3">Networking Luas</h4>
                    <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed">Bertemu dengan orang-orang yang punya passion sama dan membangun relasi untuk masa depan</p>
                </div>

                {{-- Reason 4: Kompetisi --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-border tw-border-yellow-500/20 tw-transition-all tw-duration-300 hover:tw-border-yellow-400 hover:tw-shadow-lg hover:tw-shadow-yellow-500/20">
                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br tw-from-yellow-400 tw-to-orange-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-trophy tw-text-white tw-text-2xl"></i>
                    </div>
                    <h4 class="tw-text-xl tw-font-semibold tw-text-yellow-300 tw-mb-3">Persiapan Kompetisi</h4>
                    <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed">Training intensif untuk berbagai kompetisi robotika dan teknologi tingkat nasional</p>
                </div>

                {{-- Reason 5: Fasilitas Lengkap --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-border tw-border-red-500/20 tw-transition-all tw-duration-300 hover:tw-border-red-400 hover:tw-shadow-lg hover:tw-shadow-red-500/20">
                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br tw-from-red-400 tw-to-rose-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-tools tw-text-white tw-text-2xl"></i>
                    </div>
                    <h4 class="tw-text-xl tw-font-semibold tw-text-red-300 tw-mb-3">Fasilitas Lengkap</h4>
                    <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed">Akses ke laboratorium, tools, komponen elektronik, dan platform digital modern</p>
                </div>

                {{-- Reason 6: Sertifikat --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-border tw-border-indigo-500/20 tw-transition-all tw-duration-300 hover:tw-border-indigo-400 hover:tw-shadow-lg hover:tw-shadow-indigo-500/20">
                    <div class="tw-w-14 tw-h-14 tw-bg-gradient-to-br tw-from-indigo-400 tw-to-blue-500 tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mb-4">
                        <i class="fas fa-certificate tw-text-white tw-text-2xl"></i>
                    </div>
                    <h4 class="tw-text-xl tw-font-semibold tw-text-indigo-300 tw-mb-3">Sertifikat Digital</h4>
                    <p class="tw-text-gray-400 tw-text-sm tw-leading-relaxed">Dapatkan sertifikat untuk setiap kegiatan dan pencapaian yang bisa diverifikasi online</p>
                </div>
            </div>
        </div>

        {{-- Divisi Robotika Section --}}
        <div class="tw-mt-20 tw-px-4 lg:tw-px-0">
            <div class="tw-text-center tw-mb-12">
                <h3 class="tw-text-3xl tw-font-bold tw-text-cyan-300 tw-mb-4">3 Divisi Utama Robotika</h3>
                <p class="tw-text-gray-400 tw-text-sm tw-max-w-2xl tw-mx-auto">Setiap divisi memiliki fokus pembelajaran dan teknologi yang berbeda dalam pengembangan robot</p>
            </div>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-8 tw-mb-16">
                @php
                    $divisi = [
                        [
                            "name" => "Mechanical",
                            "icon" => "fa-gears",
                            "gradient" => "tw-from-orange-500 tw-to-red-600",
                            "border" => "tw-border-orange-500/30",
                            "hover" => "hover:tw-border-orange-400",
                            "shadow" => "hover:tw-shadow-orange-500/20",
                            "description" => "Desain dan konstruksi struktur fisik robot menggunakan prinsip mekanika",
                            "techs" => ["SolidWorks", "AutoCAD", "Fusion 360", "3D Printing", "CAM", "Material Science"],
                        ],
                        [
                            "name" => "Electrical",
                            "icon" => "fa-bolt",
                            "gradient" => "tw-from-yellow-500 tw-to-orange-600",
                            "border" => "tw-border-yellow-500/30",
                            "hover" => "hover:tw-border-yellow-400",
                            "shadow" => "hover:tw-shadow-yellow-500/20",
                            "description" => "Sistem kelistrikan, elektronika, dan rangkaian kontrol pada robot",
                            "techs" => ["Arduino", "ESP32", "Sensor & Actuator", "PCB Design", "Power System", "Motor Control"],
                        ],
                        [
                            "name" => "Programming",
                            "icon" => "fa-code",
                            "gradient" => "tw-from-blue-500 tw-to-purple-600",
                            "border" => "tw-border-blue-500/30",
                            "hover" => "hover:tw-border-blue-400",
                            "shadow" => "hover:tw-shadow-blue-500/20",
                            "description" => "Pemrograman, kontrol sistem, dan kecerdasan buatan pada robot",
                            "techs" => ["Python", "C/C++", "ROS", "Computer Vision", "IoT", "Machine Learning"],
                        ],
                    ];
                @endphp

                @foreach ($divisi as $div)
                    <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border {{ $div["border"] }} tw-transition-all tw-duration-300 {{ $div["hover"] }} tw-shadow-lg {{ $div["shadow"] }} hover:tw-scale-105">
                        <div class="tw-w-20 tw-h-20 tw-mx-auto tw-mb-6 tw-bg-gradient-to-br {{ $div["gradient"] }} tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-shadow-lg">
                            <i class="fas {{ $div["icon"] }} tw-text-4xl tw-text-white"></i>
                        </div>
                        <h4 class="tw-text-2xl tw-font-bold tw-text-white tw-mb-3 tw-text-center">{{ $div["name"] }}</h4>
                        <p class="tw-text-gray-300 tw-text-sm tw-text-center tw-mb-6 tw-leading-relaxed">{{ $div["description"] }}</p>
                        <div class="tw-flex tw-flex-wrap tw-gap-2 tw-justify-center">
                            @foreach ($div["techs"] as $tech)
                                <span class="tw-px-3 tw-py-1.5 tw-bg-white/5 tw-backdrop-blur-sm tw-rounded-full tw-text-xs tw-text-gray-300 tw-border tw-border-white/10 hover:tw-bg-white/10 tw-transition-colors">
                                    {{ $tech }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tech Stack Grid --}}
            <div class="tw-text-center tw-mb-8">
                <h4 class="tw-text-3xl tw-font-semibold tw-text-cyan-300 tw-mb-2">Teknologi yang Dipelajari</h4>
                <p class="tw-text-gray-400 tw-text-sm">Tools dan platform yang digunakan dalam pembelajaran</p>
            </div>

            <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-6">
                {{-- Arduino --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-text-center tw-border tw-border-gray-700/50 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-scale-105">
                    <img src="{{ asset("icons/Arduino.png") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-3 tw-object-contain" alt="Arduino" />
                    <p class="tw-text-sm tw-text-gray-300">Arduino</p>
                </div>

                {{-- ESP32 --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-text-center tw-border tw-border-gray-700/50 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-scale-105">
                    <img src="{{ asset("icons/ESP32.png") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-3 tw-object-contain" alt="ESP32" />
                    <p class="tw-text-sm tw-text-gray-300">ESP32</p>
                </div>

                {{-- Python --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-text-center tw-border tw-border-gray-700/50 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-scale-105">
                    <img src="{{ asset("icons/Python.png") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-3 tw-object-contain" alt="Python" />
                    <p class="tw-text-sm tw-text-gray-300">Python</p>
                </div>

                {{-- C++ --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-text-center tw-border tw-border-gray-700/50 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-scale-105">
                    <img src="{{ asset("icons/Cplusplus.png") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-3 tw-object-contain" alt="C++" />
                    <p class="tw-text-sm tw-text-gray-300">C++</p>
                </div>

                {{-- MQTT --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-text-center tw-border tw-border-gray-700/50 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-scale-105">
                    <img src="{{ asset("icons/MQTT.png") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-3 tw-object-contain" alt="MQTT" />
                    <p class="tw-text-sm tw-text-gray-300">MQTT</p>
                </div>

                {{-- EasyEDA --}}
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-text-center tw-border tw-border-gray-700/50 tw-transition-all tw-duration-300 hover:tw-border-cyan-400 hover:tw-scale-105">
                    <img src="{{ asset("icons/EasyEDA.jpg") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-3 tw-object-contain" alt="EasyEDA" />
                    <p class="tw-text-sm tw-text-gray-300">EasyEDA</p>
                </div>
            </div>
        </div>
        <div class="tw-mt-16 tw-px-4 md:tw-px-4 lg:tw-px-0">
            <h4 class="tw-mt-3 tw-text-lg lg:tw-text-xl tw-font-medium tw-leading-6 tw-text-cyan-300">Struktur Department</h4>

            @if ($departments && $departments->count() > 0)
                @php
                    $colors = [
                        ["from" => "tw-from-cyan-500", "to" => "tw-to-blue-600", "text" => "tw-text-cyan-300"],
                        ["from" => "tw-from-yellow-400", "to" => "tw-to-orange-500", "text" => "tw-text-yellow-300"],
                        ["from" => "tw-from-green-400", "to" => "tw-to-emerald-500", "text" => "tw-text-green-300"],
                        ["from" => "tw-from-purple-400", "to" => "tw-to-pink-500", "text" => "tw-text-purple-300"],
                        ["from" => "tw-from-blue-400", "to" => "tw-to-indigo-500", "text" => "tw-text-blue-300"],
                        ["from" => "tw-from-red-400", "to" => "tw-to-rose-500", "text" => "tw-text-red-300"],
                        ["from" => "tw-from-teal-400", "to" => "tw-to-cyan-500", "text" => "tw-text-teal-300"],
                        ["from" => "tw-from-indigo-400", "to" => "tw-to-purple-500", "text" => "tw-text-indigo-300"],
                    ];

                    // Separate Ketua from other departments
                    $ketuaDept = $departments
                        ->filter(function ($dept) {
                            return stripos($dept->kategori, "ketua") !== false || stripos($dept->nama_department, "ketua") !== false;
                        })
                        ->first();

                    $otherDepts = $departments->filter(function ($dept) {
                        return stripos($dept->kategori, "ketua") === false && stripos($dept->nama_department, "ketua") === false;
                    });
                @endphp

                {{-- Card Ketua & Wakil (if exists) --}}
                @if ($ketuaDept)
                    <div class="tw-mt-8">
                        <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-6 tw-shadow-lg tw-shadow-slate-950">
                            <div class="tw-flex tw-items-center tw-mb-4">
                                <div class="tw-bg-gradient-to-br tw-from-cyan-500 tw-to-blue-600 tw-rounded-lg tw-p-3 tw-mr-4">
                                    <i class="fas {{ $ketuaDept->ikon ?: "fa-crown" }} tw-text-white tw-text-2xl"></i>
                                </div>
                                <div>
                                    <h5 class="tw-text-xl tw-font-bold tw-text-cyan-300">{{ $ketuaDept->nama_department }}</h5>
                                    @if ($ketuaDept->kategori)
                                        <p class="tw-text-sm tw-text-gray-400 tw-mt-1">{{ $ketuaDept->kategori }}</p>
                                    @endif
                                </div>
                            </div>
                            @if ($ketuaDept->deskripsi)
                                @php
                                    $shortDesc = Str::limit(strip_tags($ketuaDept->deskripsi), 200);
                                    $isLong = strlen(strip_tags($ketuaDept->deskripsi)) > 200;
                                @endphp

                                <div>
                                    <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed dept-desc-short-{{ $ketuaDept->id }}">
                                        {{ $shortDesc }}
                                        @if ($isLong)
                                            <button onclick="toggleDesc({{ $ketuaDept->id }})" class="tw-text-cyan-400 tw-font-semibold hover:tw-text-cyan-300 tw-ml-1">Baca Selengkapnya</button>
                                        @endif
                                    </p>
                                    @if ($isLong)
                                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed tw-hidden dept-desc-full-{{ $ketuaDept->id }}">
                                            {{ strip_tags($ketuaDept->deskripsi) }}
                                            <button onclick="toggleDesc({{ $ketuaDept->id }})" class="tw-text-cyan-400 tw-font-semibold hover:tw-text-cyan-300 tw-ml-1">Sembunyikan</button>
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Other Departments in Grid --}}
                @if ($otherDepts->count() > 0)
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-5 tw-mt-6">
                        @foreach ($otherDepts as $index => $dept)
                            @php
                                $color = $colors[$index % count($colors)];
                            @endphp

                            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-5 tw-shadow-md tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-scale-105">
                                <div class="tw-flex tw-items-center tw-mb-3">
                                    <div class="tw-bg-gradient-to-br {{ $color["from"] }} {{ $color["to"] }} tw-rounded-lg tw-p-2.5 tw-mr-3">
                                        <i class="fas {{ $dept->ikon ?: "fa-users" }} tw-text-white tw-text-lg"></i>
                                    </div>
                                    <h5 class="tw-text-lg tw-font-semibold {{ $color["text"] }}">{{ $dept->nama_department }}</h5>
                                </div>
                                @if ($dept->kategori)
                                    <p class="tw-text-gray-400 tw-text-xs tw-mb-2 tw-font-medium">{{ $dept->kategori }}</p>
                                @endif

                                @if ($dept->deskripsi)
                                    @php
                                        $shortDesc = Str::limit(strip_tags($dept->deskripsi), 150);
                                        $isLong = strlen(strip_tags($dept->deskripsi)) > 150;
                                    @endphp

                                    <div>
                                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed dept-desc-short-{{ $dept->id }}">
                                            {{ $shortDesc }}
                                            @if ($isLong)
                                                <button onclick="toggleDesc({{ $dept->id }})" class="tw-text-cyan-400 tw-font-semibold hover:tw-text-cyan-300 tw-text-xs tw-block tw-mt-2">Selengkapnya →</button>
                                            @endif
                                        </p>
                                        @if ($isLong)
                                            <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed tw-hidden dept-desc-full-{{ $dept->id }}">
                                                {{ strip_tags($dept->deskripsi) }}
                                                <button onclick="toggleDesc({{ $dept->id }})" class="tw-text-cyan-400 tw-font-semibold hover:tw-text-cyan-300 tw-text-xs tw-block tw-mt-2">← Sembunyikan</button>
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="tw-text-center tw-py-12 tw-mt-8">
                    <i class="fas fa-sitemap tw-text-6xl tw-text-gray-700 tw-mb-4"></i>
                    <p class="tw-text-gray-500 tw-text-lg">Struktur department belum tersedia</p>
                </div>
            @endif
        </div>
        <div class="tw-mt-16 tw-px-4 md:tw-px-4 lg:tw-mt-20 lg:tw-px-0">
            <div class="tw-flex tw-justify-between">
                <h4 class="tw-mt-3 tw-text-lg lg:tw-text-xl tw-font-medium tw-leading-6 tw-text-cyan-300">Showcase Hasil Karya</h4>
                <a href="{{ url("/projects") }}" class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-800 tw-shadow tw-shadow-slate-800 tw-px-4 tw-py-1 tw-rounded-full tw-text-base tw-tracking-wide tw-mt-4">
                    Lihat Semua Karya
                    <span class="tw-text-yellow-300">({{ $totalProjects }})</span>
                </a>
            </div>
            <div class="tw-mt-5 tw-grid tw-grid-cols-1 lg:tw-grid-cols-4 tw-gap-4 tw-text-wide">
                @forelse ($latestProjects as $project)
                    <div wire:key="project-{{ $project->id }}" class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-shadow-md tw-shadow-slate-950 tw-flex tw-flex-col">
                        <div class="tw-p-0 tw-rounded-tr-xl tw-rounded-tl-xl tw-border-2 tw-border-[#00eaff] tw-shadow-[0_0_0px_#00eaff,0_0_4px_#00bfff,0_0_4px_#0066ff]">
                            @if ($project->thumbnail)
                                <img src="{{ asset("storage/" . $project->thumbnail) }}" class="tw-rounded-tr-xl tw-rounded-tl-xl tw-object-cover tw-h-48 tw-w-full" alt="{{ $project->nama_project }}" />
                            @else
                                <img src="{{ asset("psychorobotic/project-2.jpeg") }}" class="tw-rounded-tr-xl tw-rounded-tl-xl tw-object-cover tw-h-48 tw-w-full" alt="{{ $project->nama_project }}" />
                            @endif
                        </div>
                        <hr class="tw-border-dashed tw-border-slate-800 tw-border-[1.5px]" />
                        <div class="tw-p-4 tw-text-center tw-flex tw-flex-col tw-flex-grow">
                            <p class="tw-font-medium tw-text-base tw-tracking-wide tw-text-cyan-300">{{ Str::limit($project->nama_project, 40) }}</p>
                            <p class="tw-mt-4">
                                <span class="tw-bg-gray-800 tw-px-4 tw-py-1.5 tw-rounded-full tw-text-sm">
                                    @if ($project->status == "selesai")
                                        ✅ {{ ucfirst($project->status) }}
                                    @elseif ($project->status == "berjalan")
                                        🔥 {{ ucfirst($project->status) }}
                                    @elseif ($project->status == "ditunda")
                                        ⏸️ {{ ucfirst($project->status) }}
                                    @else
                                        📦 {{ ucfirst($project->status) }}
                                    @endif
                                </span>
                            </p>
                            <p class="tw-mt-4 tw-text-left tw-text-sm tw-text-gray-400">{{ Str::limit(strip_tags($project->deskripsi), 60) }}</p>
                        </div>
                        <hr class="tw-border-slate-800 lg:tw-border-slate-900" />
                        <div class="tw-p-4 tw-flex tw-mt-auto tw-justify-between tw-items-center">
                            <p class="tw-text-xs tw-text-gray-500">{{ \Carbon\Carbon::parse($project->tanggal_mulai)->format("d M Y") }}</p>
                            <a href="{{ url("/project/" . $project->id) }}" class="tw-bg-transparent tw-border tw-border-cyan-300 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-tracking-wide hover:tw-bg-cyan-400 hover:tw-text-black tw-transition">
                                <i class="fas fa-link tw-mr-1"></i>
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="tw-col-span-4 tw-text-center tw-py-12">
                        <i class="fas fa-folder-open tw-text-6xl tw-text-gray-700 tw-mb-4"></i>
                        <p class="tw-text-gray-500 tw-text-lg">Belum ada project yang ditampilkan</p>
                        <p class="tw-text-gray-600 tw-text-sm tw-mt-2">Project terbaru akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="tw-mt-16 tw-px-4 md:tw-px-4 lg:tw-px-0">
            <div class="tw-flex tw-justify-between">
                <h4 class="tw-mt-3 tw-text-lg lg:tw-text-xl tw-font-medium tw-leading-6 tw-text-cyan-300">Kegiatan Kami</h4>
                <a href="{{ url("/pertemuan") }}" class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-800 tw-shadow tw-shadow-slate-800 tw-px-4 tw-py-1 tw-rounded-full tw-text-base tw-tracking-wide tw-mt-4">Lihat Semua Kegiatan</a>
            </div>
            <p class="tw-mt-4 tw-text-gray-300 tw-text-sm tw-leading-relaxed">Workshop, kelas materi, kompetisi, hingga project-based learning — semua kami jalankan untuk meningkatkan keterampilan anggota.</p>

            {{-- Timeline Events --}}
            <div class="tw-relative tw-mt-10">
                {{-- Vertical Line --}}
                <div class="tw-absolute tw-left-4 lg:tw-left-6 tw-top-0 tw-bottom-0 tw-w-0.5 tw-bg-gradient-to-b tw-from-cyan-400 tw-via-blue-400 tw-to-purple-400"></div>

                @php
                    $iconClasses = [
                        "tw-from-cyan-300 tw-to-blue-400",
                        "tw-from-blue-300 tw-to-indigo-400",
                        "tw-from-purple-300 tw-to-pink-400",
                        "tw-from-pink-300 tw-to-rose-400",
                    ];
                    $titleClasses = [
                        "tw-text-cyan-300",
                        "tw-text-blue-300",
                        "tw-text-purple-300",
                        "tw-text-pink-300",
                    ];
                    $icons = ["fa-microchip", "fa-robot", "fa-trophy", "fa-users"];
                @endphp

                @forelse ($recentActivities as $index => $activity)
                    <div class="tw-relative tw-flex tw-items-start {{ $loop->last ? "" : "tw-mb-10" }}">
                        <div class="tw-absolute tw-left-0 lg:tw-left-2 tw-w-8 tw-h-8 lg:tw-w-10 lg:tw-h-10 tw-bg-gradient-to-br {{ $iconClasses[$index % 4] }} tw-rounded-full tw-flex tw-items-center tw-justify-center">
                            <i class="fas {{ $icons[$index % 4] }} tw-text-white tw-text-sm"></i>
                        </div>
                        <div class="tw-ml-14 lg:tw-ml-20 tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-lg tw-p-5 tw-shadow-md tw-shadow-slate-950 tw-w-full">
                            <div class="tw-flex tw-justify-between tw-items-start tw-mb-2">
                                <h5 class="tw-text-lg tw-font-semibold {{ $titleClasses[$index % 4] }}">{{ $activity->judul_pertemuan }}</h5>
                                <span class="tw-text-xs tw-text-gray-400">{{ \Carbon\Carbon::parse($activity->tanggal)->format("d M Y") }}</span>
                            </div>

                            @if ($activity->deskripsi)
                                <p class="tw-text-gray-300 tw-text-sm tw-mt-2">{{ Str::limit(strip_tags($activity->deskripsi), 120) }}</p>
                            @else
                                <p class="tw-text-gray-400 tw-text-sm tw-mt-2 tw-italic">Kegiatan {{ strtolower($activity->jenis_pertemuan) }} untuk meningkatkan kemampuan anggota</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="tw-text-center tw-py-8">
                        <p class="tw-text-gray-500">Belum ada kegiatan yang terdaftar</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@push("scripts")
    <script>
        // Toggle Description Function for Departments
        function toggleDesc(id) {
            const shortDesc = document.querySelector('.dept-desc-short-' + id);
            const fullDesc = document.querySelector('.dept-desc-full-' + id);

            if (shortDesc && fullDesc) {
                shortDesc.classList.toggle('tw-hidden');
                fullDesc.classList.toggle('tw-hidden');
            }
        }
    </script>
    <script>
        // Ambil semua tombol dengan class .see-more
        document.querySelectorAll('.see-more').forEach((button) => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const target = document.getElementById(targetId);
                if (target) {
                    target.style.display = target.style.display === 'none' || target.style.display === '' ? 'block' : 'none';
                }
            });
        });
    </script>
    <script>
        const textElement = document.getElementById('typing-effect');
        const textsToType = ['KSM Psychorobotic 2026', 'dari Politeknik Negeri Jakarta.'];
        let currentTextIndex = 0;

        function typeText(text, delay) {
            let index = 0;
            const typingInterval = setInterval(function () {
                textElement.textContent += text[index];
                index++;

                if (index === text.length) {
                    clearInterval(typingInterval);
                    setTimeout(function () {
                        eraseText(text, delay);
                    }, 1000); // Jeda sebelum menghapus teks
                }
            }, delay);
        }

        function eraseText(text, delay) {
            const erasingInterval = setInterval(function () {
                textElement.textContent = textElement.textContent.slice(0, -1);

                if (textElement.textContent === '') {
                    clearInterval(erasingInterval);
                    currentTextIndex = (currentTextIndex + 1) % textsToType.length; // Ganti ke teks berikutnya
                    setTimeout(function () {
                        typeText(textsToType[currentTextIndex], delay);
                    }, 500); // Jeda sebelum mengetik ulang
                }
            }, delay / 2);
        }

        // Memulai efek tulisan mengetik pertama kali
        typeText(textsToType[currentTextIndex], 100);
    </script>
@endpush
