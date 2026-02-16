<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>{{ $isProgramView ? "Program Kegiatan" : "Daftar Pertemuan" }}</h1>
        </div>

        <div class="section-body">
            @if ($isProgramView)
                <div class="card tw-mb-6">
                    <h3>Program Kegiatan</h3>
                    <div class="card-body">
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
                    </div>
                </div>

                {{-- Program List View --}}
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-x-4 tw-px-4 lg:tw-px-0">
                    @forelse ($programs as $row)
                        <div class="card tw-rounded-xl hover:tw-shadow-lg tw-transition-all tw-cursor-pointer" wire:click="selectProgram({{ $row->id }})">
                            <div class="program-card-img-container">
                                @if ($row->thumbnail)
                                    <img src="{{ Storage::url($row->thumbnail) }}" class="card-img-top program-card-img tw-rounded-t-xl lg:tw-rounded-t-xl" alt="{{ $row->nama_program }}" />
                                @else
                                    <div class="program-card-placeholder tw-rounded-xl lg:tw-rounded-t-xl">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                @endif
                                <div style="position: absolute; top: 12px; right: 12px">
                                    <span class="badge badge-info" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; box-shadow: 0 2px 8px rgba(6, 182, 212, 0.4)">{{ $row->tahunKepengurusan->nama_tahun ?? "Active" }}</span>
                                </div>
                            </div>
                            <div class="card-body tw-py-3">
                                <div class="tw-px-4">
                                    <p class="font-bagus tw-font-semibold tw-text-lg">{{ $row->nama_program }}</p>
                                    <div x-data="{ expanded: false }" class="font-bagus tw-text-sm tw-tracking-normal tw-mt-2">
                                        <p x-show="!expanded" class="tw-text-gray-600">{{ Str::limit($row->deskripsi, 100) }}</p>
                                        <p x-show="expanded" class="tw-text-gray-600">{{ $row->deskripsi }}</p>
                                        @if (strlen($row->deskripsi) > 100)
                                            <button @click.stop="expanded = !expanded" class="tw-text-blue-500 hover:tw-text-blue-700 tw-text-xs tw-mt-1 tw-font-semibold">
                                                <span x-show="!expanded">Show More</span>
                                                <span x-show="expanded">Show Less</span>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-justify-center tw-mt-4 font-bagus tw-text-sm tw-tracking-normal tw-text-gray-500">
                                        <div>
                                            <p>
                                                <i class="fas fa-chalkboard-teacher tw-mr-1"></i>
                                                {{ $row->jumlah_pertemuan }}x Pertemuan
                                            </p>
                                        </div>
                                        <div>
                                            <p>
                                                <i class="fas fa-users tw-mr-1"></i>
                                                {{ $row->penyelenggara }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="program-card-footer tw-px-3 tw-mt-3 tw-flex tw-justify-between tw-items-center">
                                    <span class="badge program-badge-{{ $row->jenis_program }}">
                                        {{ ucfirst($row->jenis_program) }}
                                    </span>
                                    <button class="btn btn-primary btn-sm tw-rounded-full">
                                        Lihat Pertemuan
                                        <i class="fas fa-arrow-right tw-ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="tw-col-span-1 lg:tw-col-span-3 tw-w-full tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-16 tw-px-4">
                            <div class="tw-text-gray-400 tw-mb-4">
                                <i class="fas fa-inbox tw-text-6xl"></i>
                            </div>
                            <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-2">Tidak Ada Data</h3>
                            <p class="tw-text-gray-500 tw-text-center">Belum ada program kegiatan yang tersedia untuk Anda.</p>
                        </div>
                    @endforelse
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="px-3 -tw-mt-2 -tw-mb-2">
                            {{ $programs->links() }}
                        </div>
                    </div>
                </div>
            @else
                {{-- Pertemuan List View --}}
                <div class="tw-mb-4 tw-px-4 lg:tw-px-0">
                    <button wire:click="backToPrograms" class="btn btn-primary tw-shadow-sm tw-mt-2">
                        <i class="fas fa-arrow-left tw-mr-2"></i>
                        Kembali ke Program
                    </button>
                </div>

                <div class="card tw-mb-6">
                    <h3>Daftar Pertemuan</h3>
                    <div class="card-body">
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
                    </div>
                </div>

                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-x-4 tw-gap-y-6 tw-px-4 lg:tw-px-0">
                    @forelse ($pertemuans as $pertemuan)
                        @php
                            $isLocked = \Carbon\Carbon::parse($pertemuan->tanggal)->isFuture();
                        @endphp

                        <div class="card tw-rounded-xl tw-mb-2 tw-h-full tw-transition-all tw-duration-300 {{ $isLocked ? "tw-grayscale tw-opacity-75 tw-cursor-not-allowed" : "" }}">
                            <div class="program-card-img-container tw-relative">
                                @if ($pertemuan->thumbnail)
                                    <img src="{{ Storage::url($pertemuan->thumbnail) }}" class="card-img-top program-card-img tw-rounded-t-xl" alt="{{ $pertemuan->judul_pertemuan }}" />
                                @else
                                    <div class="program-card-placeholder tw-rounded-t-xl">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                @endif
                                <div style="position: absolute; top: 12px; right: 12px">
                                    <span class="badge badge-info" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); border: none; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; box-shadow: 0 2px 8px rgba(6, 182, 212, 0.4)">Pertemuan {{ $pertemuan->pertemuan_ke }}</span>
                                </div>

                                @if ($isLocked)
                                    <div class="tw-absolute tw-inset-0 tw-bg-black/50 tw-flex tw-items-center tw-justify-center tw-rounded-t-xl tw-z-10">
                                        <div class="tw-text-white tw-text-center">
                                            <i class="fas fa-lock tw-text-3xl tw-mb-2"></i>
                                            <p class="tw-font-bold tw-text-sm">Belum Dibuka</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body tw-py-3 tw-flex tw-flex-col {{ $isLocked ? "tw-pointer-events-none" : "" }}">
                                <div class="tw-px-4 tw-flex-1">
                                    <p class="font-bagus tw-font-semibold tw-text-base">{{ $pertemuan->judul_pertemuan }}</p>
                                    <p class="font-bagus tw-text-xs tw-mt-2 tw-font-semibold tw-text-gray-500">Dilaksanakan: {{ $pertemuan->tanggal ? \Carbon\Carbon::parse($pertemuan->tanggal)->format("d F Y") : "-" }}</p>

                                    <div x-data="{ expanded: false }" class="font-bagus tw-text-sm tw-tracking-normal tw-mt-4">
                                        <p x-show="!expanded" class="tw-text-gray-600">{{ Str::limit($pertemuan->deskripsi ?? "Tidak ada deskripsi", 100) }}</p>
                                        <p x-show="expanded" class="tw-text-gray-600">{{ $pertemuan->deskripsi ?? "Tidak ada deskripsi" }}</p>
                                        @if (strlen($pertemuan->deskripsi ?? "") > 100)
                                            <button @click="expanded = !expanded" class="tw-text-blue-500 hover:tw-text-blue-700 tw-text-xs tw-mt-1 tw-font-semibold">
                                                <span x-show="!expanded">Show More</span>
                                                <span x-show="expanded">Show Less</span>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-justify-center tw-mt-4 font-bagus tw-text-sm tw-tracking-normal tw-text-gray-600">
                                        <div class="tw-col-span-2">
                                            <p>
                                                <i class="fas fa-book tw-mr-1"></i>
                                                {{ $pertemuan->nama_program }}
                                            </p>
                                        </div>
                                        <div>
                                            <p>
                                                <i class="fas fa-clock tw-mr-1"></i>
                                                Minggu ke-{{ $pertemuan->minggu_ke }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Parts List --}}
                                    @php
                                        $pertemuanParts = $parts[$pertemuan->id] ?? collect();
                                    @endphp

                                    @if ($pertemuanParts->count() > 0)
                                        <div class="tw-mt-4 tw-pt-3 tw-border-t tw-border-gray-200" x-data="{ expanded: false }">
                                            <div class="tw-flex tw-justify-between tw-items-center tw-mb-3 tw-cursor-pointer" @click="expanded = !expanded">
                                                <h6 class="tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-0">
                                                    <i class="fas fa-puzzle-piece tw-mr-1 tw-text-blue-500"></i>
                                                    Daftar Part ({{ $pertemuanParts->count() }})
                                                </h6>
                                                <button type="button" class="tw-flex tw-items-center tw-gap-2 tw-text-xs tw-font-medium tw-text-blue-600 hover:tw-text-blue-700 tw-transition-colors">
                                                    <span x-show="!expanded">Lihat Part</span>
                                                    <span x-show="expanded">Tutup</span>
                                                    <i class="fas fa-chevron-down tw-transition-transform tw-duration-200" :class="{ 'tw-rotate-180': expanded }"></i>
                                                </button>
                                            </div>

                                            <div x-show="expanded" x-collapse class="tw-space-y-2">
                                                @foreach ($pertemuanParts as $part)
                                                    <div class="tw-bg-gradient-to-r tw-from-blue-50 tw-to-indigo-50 tw-rounded-lg tw-p-3 tw-border tw-border-blue-100 hover:tw-shadow-sm tw-transition-all">
                                                        <div class="tw-flex tw-justify-between tw-items-start tw-gap-3">
                                                            <div class="tw-flex-1 tw-min-w-0">
                                                                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                                                    <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-bold tw-bg-blue-500 tw-text-white">Part {{ $part->urutan }}</span>
                                                                    <p class="tw-font-semibold tw-text-sm tw-text-gray-800 tw-truncate">
                                                                        {{ $part->nama_part }}
                                                                    </p>
                                                                </div>

                                                                @if ($part->deskripsi)
                                                                    <p class="tw-text-xs tw-text-gray-600 tw-mb-2 tw-line-clamp-2">
                                                                        {{ $part->deskripsi }}
                                                                    </p>
                                                                @endif

                                                                <div class="tw-flex tw-flex-wrap tw-gap-2 tw-text-xs">
                                                                    @if ($part->total_soal > 0)
                                                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-1 tw-bg-white tw-rounded-md tw-text-gray-700 tw-border tw-border-gray-200">
                                                                            <i class="fas fa-list-ol tw-mr-1 tw-text-blue-500"></i>
                                                                            {{ $part->total_soal }} Soal
                                                                        </span>
                                                                    @endif

                                                                    @if ($part->part_files_count > 0)
                                                                        <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-1 tw-bg-white tw-rounded-md tw-text-gray-700 tw-border tw-border-gray-200">
                                                                            <i class="fas fa-file tw-mr-1 tw-text-green-500"></i>
                                                                            {{ $part->part_files_count }} File
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="tw-flex-shrink-0">
                                                                @if ($part->total_soal > 0)
                                                                    @if ($part->status_ujian == "1")
                                                                        <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-text-xs tw-font-semibold tw-bg-green-500 tw-text-white">
                                                                            <i class="fas fa-check-circle tw-mr-1"></i>
                                                                            Selesai
                                                                        </span>
                                                                    @elseif ($part->status_ujian == "0")
                                                                        <a href="{{ url("/anggota/mengerjakan/" . Crypt::encryptString($part->id)) }}" class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-text-xs tw-font-semibold tw-bg-yellow-500 hover:tw-bg-yellow-600 tw-text-white tw-transition-colors tw-no-underline" title="Lanjutkan Ujian">
                                                                            <i class="fas fa-edit tw-mr-1"></i>
                                                                            Lanjut
                                                                        </a>
                                                                    @else
                                                                        @if ($part->bank_soal_status == "1")
                                                                            <a href="{{ url("/anggota/konfirmasi/" . Crypt::encryptString($part->id)) }}" class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-text-xs tw-font-semibold tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-transition-colors tw-no-underline" title="Mulai Ujian">
                                                                                <i class="fas fa-play-circle tw-mr-1"></i>
                                                                                Mulai
                                                                            </a>
                                                                        @else
                                                                            <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-text-xs tw-font-semibold tw-bg-gray-400 tw-text-white">
                                                                                <i class="fas fa-lock tw-mr-1"></i>
                                                                                Terkunci
                                                                            </span>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1.5 tw-rounded-lg tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-600">
                                                                        <i class="fas fa-info-circle tw-mr-1"></i>
                                                                        Belum Ada Soal
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{--
                                            <div class="tw-mt-3 tw-pt-3 tw-border-t tw-border-gray-200">
                                            <p class="tw-text-sm tw-text-gray-500 tw-text-center tw-py-2">
                                            <i class="fas fa-info-circle tw-mr-1"></i>
                                            Belum ada part untuk pertemuan ini
                                            </p>
                                            </div>
                                        --}}
                                    @endif
                                </div>

                                <div class="d-flex justify-content-between align-items-center program-card-footer tw-px-3" style="min-height: 48px">
                                    <p class="font-bagus tw-text-sm tw-font-semibold tw-mb-0">Pemateri: {{ $pertemuan->nama_pemateri }}</p>
                                    <div class="d-flex program-btn-container tw-gap-1">
                                        @if ($pertemuan->gallery_count > 0)
                                            <button wire:click="openGallery({{ $pertemuan->id }})" class="btn tw-bg-purple-500 hover:tw-bg-purple-600 text-white" title="Lihat Gallery ({{ $pertemuan->gallery_count }})">
                                                <i class="fas fa-images"></i>
                                            </button>
                                        @endif

                                        @if ($pertemuan->files_count > 0)
                                            <button wire:click="openFiles({{ $pertemuan->id }})" class="btn tw-bg-indigo-500 hover:tw-bg-indigo-600 text-white" title="Lihat Files ({{ $pertemuan->files_count }})">
                                                <i class="fas fa-file-download"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="tw-col-span-1 lg:tw-col-span-3 tw-w-full tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-16 tw-px-4">
                            <div class="tw-text-gray-400 tw-mb-4">
                                <i class="fas fa-inbox tw-text-6xl"></i>
                            </div>
                            <h3 class="tw-text-xl tw-font-semibold tw-text-gray-700 tw-mb-2">Tidak Ada Data</h3>
                            <p class="tw-text-gray-500 tw-text-center">Belum ada pertemuan untuk program ini.</p>
                        </div>
                    @endforelse
                </div>

                <div class="card tw-mt-4">
                    <div class="card-body">
                        <div class="px-3 -tw-mt-2 -tw-mb-2">
                            {{ $pertemuans->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Gallery Modal --}}
    <div wire:ignore.self class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel">
                        <i class="fas fa-images tw-mr-2"></i>
                        Gallery - {{ $selectedPertemuan->judul_pertemuan ?? "" }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (count($galleries) > 0)
                        <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-4 tw-gap-4">
                            @foreach ($galleries as $gallery)
                                <div class="tw-relative tw-group tw-rounded-lg tw-overflow-hidden tw-shadow-md hover:tw-shadow-xl tw-transition-shadow">
                                    @if ($gallery->tipe == "image")
                                        <a href="{{ Storage::url($gallery->file_path) }}" data-lightbox="gallery-{{ $selectedPertemuan->id }}" data-title="{{ $gallery->caption }}" class="tw-block">
                                            <img src="{{ Storage::url($gallery->file_path) }}" alt="{{ $gallery->caption }}" class="tw-w-full tw-h-48 tw-object-cover tw-cursor-pointer" />
                                        </a>
                                    @elseif ($gallery->tipe == "video")
                                        <video class="tw-w-full tw-h-48 tw-object-cover" controls>
                                            <source src="{{ Storage::url($gallery->file_path) }}" type="video/mp4" />
                                        </video>
                                    @endif
                                    <div class="tw-absolute tw-top-2 tw-right-2">
                                        <a href="{{ Storage::url($gallery->file_path) }}" download class="tw-bg-white tw-text-gray-800 tw-w-8 tw-h-8 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-shadow-lg hover:tw-bg-gray-100 tw-transition">
                                            <i class="fas fa-download tw-text-xs"></i>
                                        </a>
                                    </div>
                                    @if ($gallery->caption)
                                        <div class="tw-p-2 tw-bg-white">
                                            <p class="tw-text-xs tw-text-gray-600 tw-truncate">{{ $gallery->caption }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @php
                            $totalGallery = DB::table("pertemuan_galeri")
                                ->where("id_pertemuan", $selectedPertemuan->id ?? 0)
                                ->count();
                        @endphp

                        @if (count($galleries) < $totalGallery)
                            <div class="tw-text-center tw-mt-4">
                                <button wire:click="loadMoreGallery" class="btn btn-primary">
                                    <i class="fas fa-plus-circle tw-mr-2"></i>
                                    Load More Gallery
                                </button>
                            </div>
                        @endif
                    @else
                        <div class="tw-text-center tw-py-8">
                            <i class="fas fa-images tw-text-5xl tw-text-gray-300 tw-mb-3"></i>
                            <p class="tw-text-gray-500">Belum ada gallery untuk pertemuan ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Files Modal --}}
    <div wire:ignore.self class="modal fade" id="filesModal" tabindex="-1" role="dialog" aria-labelledby="filesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filesModalLabel">
                        <i class="fas fa-file-download tw-mr-2"></i>
                        Files - {{ $selectedPertemuan->judul_pertemuan ?? "" }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($selectedPertemuan && $selectedPertemuan->parts->count() > 0)
                        @foreach ($selectedPertemuan->parts as $part)
                            <div class="tw-mb-4">
                                <h6 class="tw-font-semibold tw-text-gray-700 tw-mb-3">
                                    <span class="badge badge-info tw-mr-2">Part {{ $part->urutan }}</span>
                                    {{ $part->nama_part }}
                                </h6>
                                @if ($part->files->count() > 0)
                                    <div class="tw-space-y-2">
                                        @foreach ($part->files as $file)
                                            <div class="tw-flex tw-flex-col md:tw-flex-row tw-items-start md:tw-items-center tw-justify-between tw-p-3 tw-bg-gray-50 tw-rounded-lg hover:tw-bg-gray-100 tw-transition tw-gap-3">
                                                <div class="tw-flex tw-items-center tw-gap-3 tw-w-full md:tw-w-auto tw-min-w-0">
                                                    <div class="tw-w-10 tw-h-10 tw-bg-blue-500 tw-rounded-lg tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                                                        <i class="fas fa-file tw-text-white"></i>
                                                    </div>
                                                    <div class="tw-min-w-0 tw-flex-1">
                                                        <p class="tw-font-semibold tw-text-gray-800 tw-mb-0 tw-text-sm md:tw-text-base tw-truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                                                        <p class="tw-text-xs tw-text-gray-500 tw-mb-0">{{ $file->ukuran_file ? number_format($file->ukuran_file / 1024, 2) . " KB" : "Unknown size" }}</p>
                                                    </div>
                                                </div>
                                                <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-sm btn-primary tw-w-full md:tw-w-auto tw-flex-shrink-0">
                                                    <i class="fas fa-download tw-mr-1"></i>
                                                    Download
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="tw-text-sm tw-text-gray-500 tw-ml-4">Tidak ada file</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="tw-text-center tw-py-8">
                            <i class="fas fa-file-alt tw-text-5xl tw-text-gray-300 tw-mb-3"></i>
                            <p class="tw-text-gray-500">Belum ada file untuk pertemuan ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
@endpush

@push("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', (data) => {
                $('#' + data.modal).modal('show');
                // Reinitialize lightbox after modal is shown
                if (data.modal === 'galleryModal') {
                    setTimeout(() => {
                        lightbox.option({
                            resizeDuration: 200,
                            wrapAround: true,
                            albumLabel: 'Image %1 of %2',
                        });
                    }, 100);
                }
            });
        });
    </script>
@endpush
