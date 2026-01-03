<div>
    <div class="tw-flex-grow tw-container tw-max-w-7xl tw-mx-auto tw-px-4 lg:tw-px-0">
        {{-- Header Section --}}
        <div class="tw-text-center tw-mb-12">
            <h1 class="tw-text-3xl lg:tw-text-4xl tw-font-bold tw-text-cyan-300 tw-mb-4">Cek Sertifikat</h1>
            <p class="tw-text-gray-300 tw-text-sm lg:tw-text-base">Verifikasi keaslian sertifikat yang diterbitkan oleh KSM Psychorobotic</p>
        </div>

        {{-- Search Card --}}
        <div class="tw-max-w-2xl tw-mx-auto tw-mb-16">
            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-shadow-lg tw-shadow-slate-950">
                <div class="tw-flex tw-items-center tw-mb-6">
                    <div class="tw-bg-gradient-to-br tw-from-cyan-500 tw-to-blue-600 tw-rounded-lg tw-p-3 tw-mr-4">
                        <i class="fas fa-search tw-text-white tw-text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="tw-text-xl tw-font-bold tw-text-cyan-300">Cari Sertifikat</h3>
                        <p class="tw-text-sm tw-text-gray-400 tw-mt-1">Masukkan kode sertifikat Anda</p>
                    </div>
                </div>

                <div class="tw-space-y-4">
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-300 tw-mb-2">Kode Sertifikat</label>
                        <input type="text" placeholder="Contoh: CERT-2025-001" class="tw-w-full tw-px-4 tw-py-3 tw-bg-slate-800 tw-border tw-border-slate-700 tw-rounded-lg tw-text-white tw-placeholder-gray-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-cyan-500 focus:tw-border-transparent" />
                    </div>
                    <button type="button" onclick="checkCertificate()" class="tw-w-full tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-font-semibold tw-py-3 tw-rounded-lg hover:tw-from-cyan-600 hover:tw-to-blue-700 tw-transition-all tw-duration-300">
                        <i class="fas fa-search tw-mr-2"></i>
                        Cek Sertifikat
                    </button>
                </div>
            </div>
        </div>

        {{-- Certificate Result Section (Valid) --}}
        <div id="certificateValid" class="tw-hidden">
            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-shadow-lg tw-shadow-slate-950 tw-max-w-4xl tw-mx-auto">
                {{-- Status Badge --}}
                <div class="tw-flex tw-justify-center tw-mb-6">
                    <div class="tw-bg-gradient-to-r tw-from-green-500 tw-to-emerald-600 tw-px-6 tw-py-2 tw-rounded-full tw-flex tw-items-center">
                        <i class="fas fa-check-circle tw-text-white tw-mr-2"></i>
                        <span class="tw-text-white tw-font-semibold">Sertifikat Valid</span>
                    </div>
                </div>

                {{-- Certificate Details --}}
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-6 tw-mb-8">
                    <div class="tw-space-y-4">
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Nama Lengkap</label>
                            <p class="tw-text-lg tw-font-semibold tw-text-cyan-300">Fahmi Ibrahim</p>
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Kode Sertifikat</label>
                            <p class="tw-text-lg tw-font-semibold tw-text-white">CERT-2025-001</p>
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">NIM / NPM</label>
                            <p class="tw-text-lg tw-font-semibold tw-text-white">2207421001</p>
                        </div>
                    </div>
                    <div class="tw-space-y-4">
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Periode Kepengurusan</label>
                            <p class="tw-text-lg tw-font-semibold tw-text-white">2025 - 2026</p>
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Posisi</label>
                            <p class="tw-text-lg tw-font-semibold tw-text-white">Anggota</p>
                        </div>
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-400 tw-mb-1">Status</label>
                            <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-bg-green-500/20 tw-text-green-300 tw-text-sm tw-font-medium">
                                <i class="fas fa-check tw-mr-2"></i>
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Certificate Preview --}}
                <div class="tw-bg-gradient-to-br tw-from-slate-800 tw-to-slate-900 tw-rounded-xl tw-p-6 tw-border tw-border-slate-700">
                    <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                        <h4 class="tw-text-lg tw-font-semibold tw-text-cyan-300">Preview Sertifikat</h4>
                        <button class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-px-4 tw-py-2 tw-rounded-lg tw-text-sm hover:tw-from-cyan-600 hover:tw-to-blue-700 tw-transition-all">
                            <i class="fas fa-download tw-mr-2"></i>
                            Download
                        </button>
                    </div>
                    <div class="tw-bg-white tw-rounded-lg tw-p-8 tw-text-center">
                        <div class="tw-text-gray-800">
                            <div class="tw-mb-4">
                                <img src="{{ asset("icons/logo-psychorobotic.png") }}" class="tw-w-16 tw-h-16 tw-mx-auto tw-mb-2" alt="Logo" />
                                <h3 class="tw-text-2xl tw-font-bold tw-text-slate-800">KSM PSYCHOROBOTIC</h3>
                                <p class="tw-text-sm tw-text-gray-600">Politeknik Negeri Jakarta</p>
                            </div>
                            <div class="tw-border-t tw-border-b tw-border-gray-300 tw-py-6 tw-my-6">
                                <h2 class="tw-text-3xl tw-font-bold tw-text-cyan-600 tw-mb-2">SERTIFIKAT</h2>
                                <p class="tw-text-sm tw-text-gray-600 tw-mb-4">Diberikan kepada:</p>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-slate-800 tw-mb-4">Fahmi Ibrahim</h3>
                                <p class="tw-text-sm tw-text-gray-700 tw-leading-relaxed">
                                    Telah mengikuti dan menyelesaikan
                                    <br />
                                    <span class="tw-font-semibold">Workshop IoT & Arduino</span>
                                    <br />
                                    Pada tanggal 15 November 2025
                                </p>
                            </div>
                            <div class="tw-flex tw-justify-around tw-mt-6">
                                <div>
                                    <div class="tw-w-32 tw-h-px tw-bg-gray-400 tw-mb-2"></div>
                                    <p class="tw-text-xs tw-text-gray-600">Ketua KSM</p>
                                </div>
                                <div>
                                    <div class="tw-w-32 tw-h-px tw-bg-gray-400 tw-mb-2"></div>
                                    <p class="tw-text-xs tw-text-gray-600">Koordinator</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="tw-flex tw-justify-center tw-mt-6">
                    <button onclick="resetSearch()" class="tw-bg-slate-700 tw-text-white tw-px-6 tw-py-2 tw-rounded-lg hover:tw-bg-slate-600 tw-transition-all">
                        <i class="fas fa-arrow-left tw-mr-2"></i>
                        Cari Lagi
                    </button>
                </div>
            </div>
        </div>

        {{-- Certificate Not Found Section --}}
        <div id="certificateNotFound" class="tw-hidden">
            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-p-8 tw-shadow-lg tw-shadow-slate-950 tw-max-w-2xl tw-mx-auto tw-text-center">
                {{-- Status Badge --}}
                <div class="tw-flex tw-justify-center tw-mb-6">
                    <div class="tw-bg-gradient-to-r tw-from-red-500 tw-to-rose-600 tw-px-6 tw-py-2 tw-rounded-full tw-flex tw-items-center">
                        <i class="fas fa-times-circle tw-text-white tw-mr-2"></i>
                        <span class="tw-text-white tw-font-semibold">Sertifikat Tidak Ditemukan</span>
                    </div>
                </div>

                {{-- Error Icon --}}
                <div class="tw-mb-6">
                    <div class="tw-bg-gradient-to-br tw-from-red-400 tw-to-rose-500 tw-rounded-full tw-w-24 tw-h-24 tw-flex tw-items-center tw-justify-center tw-mx-auto">
                        <i class="fas fa-exclamation-triangle tw-text-white tw-text-4xl"></i>
                    </div>
                </div>

                {{-- Message --}}
                <h3 class="tw-text-2xl tw-font-bold tw-text-white tw-mb-4">Kode Sertifikat Tidak Valid</h3>
                <p class="tw-text-gray-300 tw-mb-6 tw-leading-relaxed">Kode sertifikat yang Anda masukkan tidak ditemukan dalam database kami. Pastikan Anda memasukkan kode yang benar atau hubungi admin untuk informasi lebih lanjut.</p>

                {{-- Info Box --}}
                <div class="tw-bg-slate-800 tw-rounded-lg tw-p-6 tw-mb-6 tw-text-left">
                    <h4 class="tw-text-lg tw-font-semibold tw-text-cyan-300 tw-mb-4 tw-flex tw-items-center">
                        <i class="fas fa-info-circle tw-mr-2"></i>
                        Tips Pencarian:
                    </h4>
                    <ul class="tw-space-y-2 tw-text-sm tw-text-gray-300">
                        <li class="tw-flex tw-items-start">
                            <i class="fas fa-check tw-text-cyan-400 tw-mr-2 tw-mt-1"></i>
                            <span>Pastikan format kode sertifikat benar (Contoh: CERT-2025-001)</span>
                        </li>
                        <li class="tw-flex tw-items-start">
                            <i class="fas fa-check tw-text-cyan-400 tw-mr-2 tw-mt-1"></i>
                            <span>Periksa kembali huruf besar/kecil dan tanda hubung</span>
                        </li>
                        <li class="tw-flex tw-items-start">
                            <i class="fas fa-check tw-text-cyan-400 tw-mr-2 tw-mt-1"></i>
                            <span>Hubungi admin jika Anda yakin kode sudah benar</span>
                        </li>
                    </ul>
                </div>

                {{-- Actions --}}
                <div class="tw-flex tw-justify-center tw-gap-4">
                    <button onclick="resetSearch()" class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-px-6 tw-py-2 tw-rounded-lg hover:tw-from-cyan-600 hover:tw-to-blue-700 tw-transition-all">
                        <i class="fas fa-search tw-mr-2"></i>
                        Coba Lagi
                    </button>
                    <a href="#" class="tw-bg-slate-700 tw-text-white tw-px-6 tw-py-2 tw-rounded-lg hover:tw-bg-slate-600 tw-transition-all">
                        <i class="fas fa-envelope tw-mr-2"></i>
                        Hubungi Admin
                    </a>
                </div>
            </div>
        </div>

        {{-- Info Section --}}
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-6 tw-mt-16">
            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-shadow-md tw-shadow-slate-950">
                <div class="tw-bg-gradient-to-br tw-from-cyan-400 tw-to-blue-500 tw-rounded-lg tw-p-3 tw-w-12 tw-h-12 tw-flex tw-items-center tw-justify-center tw-mb-4">
                    <i class="fas fa-shield-alt tw-text-white tw-text-xl"></i>
                </div>
                <h4 class="tw-text-lg tw-font-semibold tw-text-cyan-300 tw-mb-2">Terverifikasi</h4>
                <p class="tw-text-sm tw-text-gray-300 tw-leading-relaxed">Setiap sertifikat dilengkapi dengan kode unik yang dapat diverifikasi keasliannya.</p>
            </div>

            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-shadow-md tw-shadow-slate-950">
                <div class="tw-bg-gradient-to-br tw-from-purple-400 tw-to-pink-500 tw-rounded-lg tw-p-3 tw-w-12 tw-h-12 tw-flex tw-items-center tw-justify-center tw-mb-4">
                    <i class="fas fa-clock tw-text-white tw-text-xl"></i>
                </div>
                <h4 class="tw-text-lg tw-font-semibold tw-text-purple-300 tw-mb-2">Real-time</h4>
                <p class="tw-text-sm tw-text-gray-300 tw-leading-relaxed">Pengecekan sertifikat dilakukan secara real-time dan dapat diakses kapan saja.</p>
            </div>

            <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-xl tw-p-6 tw-shadow-md tw-shadow-slate-950">
                <div class="tw-bg-gradient-to-br tw-from-green-400 tw-to-emerald-500 tw-rounded-lg tw-p-3 tw-w-12 tw-h-12 tw-flex tw-items-center tw-justify-center tw-mb-4">
                    <i class="fas fa-download tw-text-white tw-text-xl"></i>
                </div>
                <h4 class="tw-text-lg tw-font-semibold tw-text-green-300 tw-mb-2">Download</h4>
                <p class="tw-text-sm tw-text-gray-300 tw-leading-relaxed">Sertifikat dapat diunduh dalam format digital berkualitas tinggi.</p>
            </div>
        </div>
    </div>
</div>

@push("scripts")
    <script>
        // Database simulasi sertifikat yang valid
        const validCertificates = {
            'CERT-2025-001': {
                name: 'Fahmi Ibrahim',
                nim: '2207421001',
                period: '2025 - 2026',
                position: 'Anggota',
                status: 'Aktif',
            },
            'CERT-2025-002': {
                name: 'Hadzoti Fawwaz Azifa',
                nim: '2207421072',
                period: '2025 - 2026',
                position: 'Anggota',
                status: 'Aktif',
            },
            'CERT-2025-003': {
                name: 'Siti Aminah',
                nim: '2207421023',
                period: '2025 - 2026',
                position: 'Bendahara',
                status: 'Aktif',
            },
        };

        function checkCertificate() {
            const input = document.querySelector('input[type="text"]');
            const searchForm = input.closest('.tw-max-w-2xl');
            const validResult = document.getElementById('certificateValid');
            const notFoundResult = document.getElementById('certificateNotFound');
            const code = input.value.trim().toUpperCase();

            if (code === '') {
                alert('Mohon masukkan kode sertifikat');
                return;
            }

            // Cek apakah kode sertifikat ada di database
            if (validCertificates[code]) {
                // Sertifikat ditemukan - update data
                const cert = validCertificates[code];

                // Update nama lengkap
                document.querySelector('#certificateValid .tw-text-cyan-300').textContent = cert.name;

                // Update kode sertifikat
                document.querySelectorAll('#certificateValid .tw-text-lg.tw-font-semibold.tw-text-white')[0].textContent = code;

                // Update NIM/NPM
                document.querySelectorAll('#certificateValid .tw-text-lg.tw-font-semibold.tw-text-white')[1].textContent = cert.nim;

                // Update periode kepengurusan
                document.querySelectorAll('#certificateValid .tw-text-lg.tw-font-semibold.tw-text-white')[2].textContent = cert.period;

                // Update posisi
                document.querySelectorAll('#certificateValid .tw-text-lg.tw-font-semibold.tw-text-white')[3].textContent = cert.position;

                // Update preview sertifikat
                document.querySelectorAll('#certificateValid .tw-text-2xl.tw-font-bold.tw-text-slate-800')[0].textContent = cert.name;
                document.querySelector('#certificateValid .tw-font-semibold.tw-text-lg').textContent = cert.position;
                document.querySelector('#certificateValid .tw-text-sm.tw-text-gray-700').innerHTML = `Terdaftar sebagai<br /><span class="tw-font-semibold tw-text-lg">${cert.position}</span><br />KSM Psychorobotic Periode ${cert.period}`;

                // Tampilkan hasil valid
                searchForm.classList.add('tw-hidden');
                validResult.classList.remove('tw-hidden');
                notFoundResult.classList.add('tw-hidden');

                validResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                // Sertifikat tidak ditemukan
                searchForm.classList.add('tw-hidden');
                validResult.classList.add('tw-hidden');
                notFoundResult.classList.remove('tw-hidden');

                notFoundResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function resetSearch() {
            const searchForm = document.querySelector('.tw-max-w-2xl');
            const validResult = document.getElementById('certificateValid');
            const notFoundResult = document.getElementById('certificateNotFound');

            searchForm.classList.remove('tw-hidden');
            validResult.classList.add('tw-hidden');
            notFoundResult.classList.add('tw-hidden');

            document.querySelector('input[type="text"]').value = '';

            searchForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Allow enter key to search
        document.querySelector('input[type="text"]').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                checkCertificate();
            }
        });
    </script>
@endpush
