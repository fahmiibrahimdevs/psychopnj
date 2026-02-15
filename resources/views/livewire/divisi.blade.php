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
                Divisi
                <span class="tw-text-cyan-300">Psychorobotic</span>
            </h1>
            <p class="tw-text-gray-400 tw-text-lg tw-max-w-3xl tw-mx-auto">3 Divisi Utama yang Saling Berkolaborasi Menciptakan Inovasi Robotika</p>
        </div>

        {{-- Divisi Cards --}}
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-8 tw-mb-20">
            @foreach ($divisiUtama as $divisi)
                <div class="tw-bg-gradient-to-br tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-border {{ $divisi["border"] }} tw-overflow-hidden tw-transition-all tw-duration-300 hover:tw-scale-105 hover:tw-shadow-xl hover:tw-shadow-{{ $divisi["text"] }}/20">
                    {{-- Header --}}
                    <div class="tw-p-8 tw-text-center">
                        <div class="tw-w-24 tw-h-24 tw-bg-gradient-to-br {{ $divisi["gradient"] }} tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-mx-auto tw-mb-6 tw-shadow-lg">
                            <i class="fas {{ $divisi["icon"] }} tw-text-white tw-text-5xl"></i>
                        </div>
                        <h3 class="tw-text-3xl tw-font-bold {{ $divisi["text"] }} tw-mb-4">{{ $divisi["nama"] }}</h3>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">{{ $divisi["deskripsi"] }}</p>
                    </div>

                    {{-- Skills/Keahlian --}}
                    <div class="tw-border-t tw-border-gray-700/50 {{ $divisi["bg"] }} tw-p-6">
                        <h4 class="tw-text-sm tw-font-bold tw-text-gray-300 tw-mb-4 tw-uppercase tw-tracking-wide">Keahlian yang Dipelajari:</h4>
                        <ul class="tw-space-y-2">
                            @foreach ($divisi["keahlian"] as $skill)
                                <li class="tw-flex tw-items-start tw-gap-2">
                                    <i class="fas fa-check-circle {{ $divisi["text"] }} tw-mt-1 tw-text-sm"></i>
                                    <span class="tw-text-gray-400 tw-text-sm">{{ $skill }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Action Button --}}
                    <div class="tw-p-6 tw-pt-0">
                        <button onclick="document.getElementById('modal-divisi-{{ $divisi["id"] }}').classList.remove('tw-hidden')" class="tw-w-full tw-bg-gradient-to-r {{ $divisi["gradient"] }} tw-px-6 tw-py-3 tw-rounded-xl tw-text-white tw-font-semibold tw-shadow-lg hover:tw-shadow-xl tw-transition-all tw-duration-300 hover:tw-scale-105">
                            <i class="fas fa-info-circle tw-mr-2"></i>
                            Pelajari Lebih Lanjut
                        </button>
                    </div>
                </div>

                {{-- Modal Detail --}}
                <div id="modal-divisi-{{ $divisi["id"] }}" class="tw-hidden tw-fixed tw-inset-0 tw-bg-black/80 tw-z-50 tw-flex tw-items-center tw-justify-center tw-p-4" onclick="if(event.target === this) this.classList.add('tw-hidden')">
                    <div class="tw-bg-gradient-to-br tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-border {{ $divisi["border"] }} tw-max-w-3xl tw-w-full tw-max-h-[90vh] tw-overflow-y-auto scrollbar-hide" onclick="event.stopPropagation()">
                        <div class="tw-flex tw-items-center tw-justify-between tw-mb-8">
                            <div class="tw-flex tw-items-center">
                                <div class="tw-w-16 tw-h-16 tw-bg-gradient-to-br {{ $divisi["gradient"] }} tw-rounded-xl tw-flex tw-items-center tw-justify-center tw-mr-4">
                                    <i class="fas {{ $divisi["icon"] }} tw-text-white tw-text-3xl"></i>
                                </div>
                                <div>
                                    <h3 class="tw-text-3xl tw-font-bold {{ $divisi["text"] }}">Divisi {{ $divisi["nama"] }}</h3>
                                    <p class="tw-text-gray-400 tw-text-sm">Psychorobotic PNJ</p>
                                </div>
                            </div>
                            <button onclick="document.getElementById('modal-divisi-{{ $divisi["id"] }}').classList.add('tw-hidden')" class="tw-text-gray-400 hover:tw-text-white tw-transition-colors">
                                <i class="fas fa-times tw-text-3xl"></i>
                            </button>
                        </div>

                        {{-- Deskripsi Lengkap --}}
                        <div class="tw-mb-8">
                            <h4 class="tw-text-xl tw-font-bold tw-text-white tw-mb-4">Tentang Divisi</h4>
                            <p class="tw-text-gray-300 tw-leading-relaxed">{{ $divisi["deskripsi"] }}</p>
                        </div>

                        {{-- Keahlian Detail --}}
                        <div class="tw-mb-8">
                            <h4 class="tw-text-xl tw-font-bold tw-text-white tw-mb-4">Keahlian yang Akan Dipelajari</h4>
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-3">
                                @foreach ($divisi["keahlian"] as $skill)
                                    <div class="tw-flex tw-items-start tw-gap-3 tw-p-3 tw-rounded-lg {{ $divisi["bg"] }} tw-border {{ $divisi["border"] }}">
                                        <i class="fas fa-check-circle {{ $divisi["text"] }} tw-mt-1"></i>
                                        <span class="tw-text-gray-300 tw-text-sm">{{ $skill }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Project Examples --}}
                        <div class="tw-mb-8">
                            <h4 class="tw-text-xl tw-font-bold tw-text-white tw-mb-4">Contoh Project</h4>
                            <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4">
                                @if ($divisi["nama"] === "Mechanical")
                                    <div class="{{ $divisi["bg"] }} tw-p-4 tw-rounded-lg tw-border {{ $divisi["border"] }}">
                                        <h5 class="{{ $divisi["text"] }} tw-font-semibold tw-mb-2">Robot Chassis Design</h5>
                                        <p class="tw-text-gray-400 tw-text-sm">Desain dan fabrikasi chassis robot untuk kompetisi</p>
                                    </div>
                                    <div class="{{ $divisi["bg"] }} tw-p-4 tw-rounded-lg tw-border {{ $divisi["border"] }}">
                                        <h5 class="{{ $divisi["text"] }} tw-font-semibold tw-mb-2">Gripper Mechanism</h5>
                                        <p class="tw-text-gray-400 tw-text-sm">Sistem gripper untuk robot manipulator</p>
                                    </div>
                                @elseif ($divisi["nama"] === "Electrical")
                                    <div class="{{ $divisi["bg"] }} tw-p-4 tw-rounded-lg tw-border {{ $divisi["border"] }}">
                                        <h5 class="{{ $divisi["text"] }} tw-font-semibold tw-mb-2">Motor Driver PCB</h5>
                                        <p class="tw-text-gray-400 tw-text-sm">Custom PCB untuk control motor DC brushless</p>
                                    </div>
                                    <div class="{{ $divisi["bg"] }} tw-p-4 tw-rounded-lg tw-border {{ $divisi["border"] }}">
                                        <h5 class="{{ $divisi["text"] }} tw-font-semibold tw-mb-2">Sensor Array System</h5>
                                        <p class="tw-text-gray-400 tw-text-sm">Integrasi multi sensor untuk line follower</p>
                                    </div>
                                @else
                                    <div class="{{ $divisi["bg"] }} tw-p-4 tw-rounded-lg tw-border {{ $divisi["border"] }}">
                                        <h5 class="{{ $divisi["text"] }} tw-font-semibold tw-mb-2">Autonomous Navigation</h5>
                                        <p class="tw-text-gray-400 tw-text-sm">Algoritma path planning untuk robot otonom</p>
                                    </div>
                                    <div class="{{ $divisi["bg"] }} tw-p-4 tw-rounded-lg tw-border {{ $divisi["border"] }}">
                                        <h5 class="{{ $divisi["text"] }} tw-font-semibold tw-mb-2">Object Detection</h5>
                                        <p class="tw-text-gray-400 tw-text-sm">Computer vision untuk deteksi objek real-time</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Info Section --}}
        <div class="tw-bg-gradient-to-r tw-from-cyan-500/10 tw-to-purple-500/10 tw-rounded-2xl tw-p-8 tw-border tw-border-cyan-500/20 tw-mb-20">
            <div class="tw-text-center">
                <h3 class="tw-text-2xl tw-font-bold tw-text-white tw-mb-4">Kolaborasi Antar Divisi</h3>
                <p class="tw-text-gray-300 tw-max-w-3xl tw-mx-auto tw-mb-6">Ketiga divisi ini bekerja secara terintegrasi dalam setiap project robotika. Mechanical merancang struktur, Electrical mengatur sistem kelistrikan, dan Programming mengembangkan intelligence - menghasilkan robot yang komprehensif dan inovatif.</p>
                <div class="tw-flex tw-justify-center tw-items-center tw-gap-4 tw-flex-wrap">
                    <span class="tw-bg-blue-500/20 tw-text-blue-300 tw-px-4 tw-py-2 tw-rounded-full tw-text-sm tw-font-semibold">
                        <i class="fas fa-cog tw-mr-2"></i>
                        Mechanical
                    </span>
                    <i class="fas fa-arrows-alt-h tw-text-gray-500"></i>
                    <span class="tw-bg-yellow-500/20 tw-text-yellow-300 tw-px-4 tw-py-2 tw-rounded-full tw-text-sm tw-font-semibold">
                        <i class="fas fa-bolt tw-mr-2"></i>
                        Electrical
                    </span>
                    <i class="fas fa-arrows-alt-h tw-text-gray-500"></i>
                    <span class="tw-bg-purple-500/20 tw-text-purple-300 tw-px-4 tw-py-2 tw-rounded-full tw-text-sm tw-font-semibold">
                        <i class="fas fa-code tw-mr-2"></i>
                        Programming
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
