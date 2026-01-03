<div class="tw-min-h-screen tw-py-0">
    <div class="tw-container tw-mx-auto tw-px-4 tw-max-w-7xl">
        {{-- Header Section --}}
        <div class="tw-text-center tw-mb-12">
            <h1 class="tw-text-4xl md:tw-text-5xl tw-font-bold tw-bg-gradient-to-r tw-from-cyan-400 tw-to-blue-500 tw-bg-clip-text tw-text-transparent tw-mb-4">Galeri Kegiatan</h1>
            <p class="tw-text-gray-300 tw-text-lg tw-max-w-2xl tw-mx-auto">Dokumentasi kegiatan dan momen berharga KSM Psychorobotic</p>
        </div>

        {{-- Year Filter --}}
        <div class="tw-flex tw-justify-center tw-mb-12">
            <div class="tw-flex tw-gap-3 tw-flex-wrap tw-justify-center">
                <button onclick="filterYear('all')" class="year-filter tw-active tw-px-6 tw-py-2.5 tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-font-semibold tw-rounded-xl hover:tw-from-cyan-600 hover:tw-to-blue-700 tw-transition-all" data-year="all">Semua</button>
                <button onclick="filterYear('2025')" class="year-filter tw-px-6 tw-py-2.5 tw-bg-slate-800 tw-text-gray-300 tw-font-semibold tw-rounded-xl hover:tw-bg-slate-700 tw-transition-all" data-year="2025">2025</button>
                <button onclick="filterYear('2024')" class="year-filter tw-px-6 tw-py-2.5 tw-bg-slate-800 tw-text-gray-300 tw-font-semibold tw-rounded-xl hover:tw-bg-slate-700 tw-transition-all" data-year="2024">2024</button>
                <button onclick="filterYear('2023')" class="year-filter tw-px-6 tw-py-2.5 tw-bg-slate-800 tw-text-gray-300 tw-font-semibold tw-rounded-xl hover:tw-bg-slate-700 tw-transition-all" data-year="2023">2023</button>
            </div>
        </div>

        {{-- Gallery Grid --}}
        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-6" id="galleryGrid">
            {{-- 2025 Images --}}
            <div class="gallery-item tw-group" data-year="2025">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Workshop+IoT+2025" alt="Workshop IoT 2025" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2025</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Workshop IoT & Arduino</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">15 November 2025</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Pelatihan Internet of Things dan pemrograman Arduino untuk anggota baru</p>
                    </div>
                </div>
            </div>

            <div class="gallery-item tw-group" data-year="2025">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Bootcamp+Robotika+2025" alt="Bootcamp Robotika 2025" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2025</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Bootcamp Robotika</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">10 November 2025</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Pelatihan intensif pembuatan robot untuk persiapan kompetisi</p>
                    </div>
                </div>
            </div>

            <div class="gallery-item tw-group" data-year="2025">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Gathering+2025" alt="Gathering 2025" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2025</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Gathering Anggota</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">5 Oktober 2025</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Kegiatan kekeluargaan dan refreshing bersama seluruh anggota</p>
                    </div>
                </div>
            </div>

            {{-- 2024 Images --}}
            <div class="gallery-item tw-group" data-year="2024">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Kompetisi+Robot+2024" alt="Kompetisi Robot 2024" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2024</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Kompetisi Robot Nasional</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">20 Agustus 2024</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Partisipasi tim dalam kompetisi robotika tingkat nasional</p>
                    </div>
                </div>
            </div>

            <div class="gallery-item tw-group" data-year="2024">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Open+Recruitment+2024" alt="Open Recruitment 2024" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2024</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Open Recruitment</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">15 Februari 2024</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Penerimaan anggota baru periode 2024-2025</p>
                    </div>
                </div>
            </div>

            {{-- 2023 Images --}}
            <div class="gallery-item tw-group" data-year="2023">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Pelantikan+Pengurus+2023" alt="Pelantikan Pengurus 2023" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2023</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Pelantikan Pengurus</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">10 Desember 2023</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Pelantikan kepengurusan periode 2023-2024</p>
                    </div>
                </div>
            </div>

            <div class="gallery-item tw-group" data-year="2023">
                <div class="tw-bg-gradient-to-tl tw-from-[#010022] tw-to-slate-900 tw-rounded-2xl tw-overflow-hidden tw-shadow-lg tw-shadow-slate-950 tw-transition-all tw-duration-300 hover:tw-transform hover:tw-scale-[1.02]">
                    <div class="tw-relative tw-overflow-hidden tw-aspect-video">
                        <img src="https://placehold.co/800x600/1e293b/64748b?text=Seminar+Teknologi+2023" alt="Seminar Teknologi 2023" class="tw-w-full tw-h-full tw-object-cover group-hover:tw-scale-110 tw-transition-transform tw-duration-300" />
                        <div class="tw-absolute tw-inset-0 tw-bg-gradient-to-t tw-from-slate-900 tw-to-transparent tw-opacity-60"></div>
                        <div class="tw-absolute tw-top-4 tw-right-4">
                            <span class="tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-text-sm tw-font-semibold tw-px-3 tw-py-1 tw-rounded-lg">2023</span>
                        </div>
                    </div>
                    <div class="tw-p-6">
                        <h3 class="tw-text-xl tw-font-bold tw-text-white tw-mb-2">Seminar Teknologi</h3>
                        <p class="tw-text-gray-400 tw-text-sm tw-mb-3">25 September 2023</p>
                        <p class="tw-text-gray-300 tw-text-sm tw-leading-relaxed">Seminar tentang perkembangan teknologi IoT dan AI</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterYear(year) {
        const items = document.querySelectorAll('.gallery-item');
        const buttons = document.querySelectorAll('.year-filter');

        // Update button styles
        buttons.forEach((btn) => {
            if (btn.dataset.year === year) {
                btn.className = 'year-filter tw-px-6 tw-py-2.5 tw-bg-gradient-to-r tw-from-cyan-500 tw-to-blue-600 tw-text-white tw-font-semibold tw-rounded-xl hover:tw-from-cyan-600 hover:tw-to-blue-700 tw-transition-all';
            } else {
                btn.className = 'year-filter tw-px-6 tw-py-2.5 tw-bg-slate-800 tw-text-gray-300 tw-font-semibold tw-rounded-xl hover:tw-bg-slate-700 tw-transition-all';
            }
        });

        // Filter items
        items.forEach((item) => {
            if (year === 'all' || item.dataset.year === year) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 10);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }
</script>
