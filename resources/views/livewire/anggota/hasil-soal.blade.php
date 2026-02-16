<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Hasil Soal Saya</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3 class="tw-tracking-wider tw-text-[#34395e] tw-ml-6 tw-mt-6 tw-mb-2 tw-text-base tw-font-semibold">Riwayat Pengerjaan Soal</h3>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead>
                                <tr class="tw-text-gray-700 text-center">
                                    <th width="5%">No</th>
                                    <th class="text-left" style="min-width: 120px">Part</th>
                                    <th class="text-left" style="min-width: 150px">Nama Part</th>
                                    <th>PG</th>
                                    <th>PK</th>
                                    <th>JO</th>
                                    <th>IS</th>
                                    <th>ES</th>
                                    <th>Total</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hasilList as $programName => $pertemuanGroups)
                                    <tr class="tw-bg-gray-100">
                                        <td colspan="12" class="text-left tw-px-4 tw-py-2 tw-font-bold tw-text-gray-700 tw-tracking-wider">
                                            {{ $programName }}
                                        </td>
                                    </tr>
                                    @foreach ($pertemuanGroups as $pertemuanKey => $items)
                                        @php
                                            // Get first item to extract pertemuan info
                                            $firstItem = $items->first();
                                            $pertemuanKe = $firstItem->pertemuan_ke ?? "";
                                            $judulPertemuan = $firstItem->judul_pertemuan ?? "";
                                            $tanggal = $firstItem->tanggal ?? "";
                                        @endphp

                                        <tr class="tw-bg-gray-50">
                                            <td colspan="12" class="text-left tw-px-8 tw-py-2 tw-font-semibold tw-text-gray-600 tw-tracking-wider tw-text-sm">
                                                Pertemuan {{ $pertemuanKe }} - {{ Str::limit($judulPertemuan, 50) }} • {{ \Carbon\Carbon::parse($tanggal)->format("d M Y") }}
                                            </td>
                                        </tr>
                                        @foreach ($items as $index => $hasil)
                                            <tr class="text-center tw-border-b tw-border-gray-100 hover:tw-bg-gray-50">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-left tw-align-middle tw-pl-12">
                                                    <span class="badge badge-info">Part {{ $hasil->part_urutan }}</span>
                                                </td>
                                                <td class="text-left tw-align-middle">
                                                    <span class="tw-text-sm tw-text-gray-600">{{ Str::limit($hasil->nama_part, 40) }}</span>
                                                </td>
                                                <td class="tw-text-sm tw-tracking-wider">{{ number_format($hasil->nilai_pg, 1) }}</td>
                                                <td class="tw-text-sm tw-tracking-wider">{{ number_format($hasil->nilai_pk, 1) }}</td>
                                                <td class="tw-text-sm tw-tracking-wider">{{ number_format($hasil->nilai_jo, 1) }}</td>
                                                <td class="tw-text-sm tw-tracking-wider">{{ number_format($hasil->nilai_is, 1) }}</td>
                                                <td class="tw-text-sm tw-tracking-wider">{{ number_format($hasil->nilai_es, 1) }}</td>
                                                <td class="tw-text-sm tw-tracking-wider">
                                                    <span class="tw-font-bold tw-text-lg {{ $hasil->total_nilai >= $hasil->total_bobot * 0.7 ? "tw-text-green-600" : "tw-text-red-600" }}">
                                                        {{ number_format($hasil->total_nilai, 1) }}
                                                    </span>
                                                </td>
                                                <td class="tw-whitespace-nowrap tw-text-sm tw-tracking-wider">{{ $hasil->lama_ujian ?? "-" }}</td>
                                                <td>
                                                    @if ($hasil->dikoreksi == "1")
                                                        <span class="tw-bg-green-50 tw-text-xs tw-text-green-600 tw-px-2 tw-py-1 tw-rounded-lg tw-font-semibold">Dikoreksi</span>
                                                    @else
                                                        <span class="tw-bg-yellow-50 tw-text-xs tw-text-yellow-600 tw-px-2 tw-py-1 tw-rounded-lg tw-font-semibold">Menunggu</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button wire:click="showDetail({{ $hasil->id }})" class="btn btn-primary btn-sm rounded-circle" data-toggle="modal" data-target="#detailSoalModal" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center tw-py-8 tw-text-gray-500">
                                            <i class="fas fa-inbox tw-text-4xl tw-mb-3 tw-text-gray-300 tw-block"></i>
                                            Belum ada hasil pengerjaan soal
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Detail Soal -->
    <div wire:ignore.self class="modal fade" id="detailSoalModal" tabindex="-1" role="dialog" aria-labelledby="detailSoalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailSoalModalLabel">Detail Hasil: {{ $selectedHasil->pertemuan->judul_pertemuan ?? "-" }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeDetail">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-bg-gray-50">
                    @if ($detailSoal)
                        <div class="tw-space-y-6">
                            @foreach ($detailSoal as $soal)
                                <div class="tw-bg-white tw-p-6 tw-rounded-xl tw-shadow-sm border">
                                    <div class="tw-flex tw-justify-between tw-mb-4">
                                        <span class="tw-font-bold tw-text-lg tw-text-blue-600">No. {{ $soal["no_soal"] }}</span>
                                        <span class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase">
                                            @if ($soal["jenis"] == "1")
                                                Pilihan Ganda
                                            @elseif ($soal["jenis"] == "2")
                                                Pilihan Ganda Kompleks
                                            @elseif ($soal["jenis"] == "3")
                                                Menjodohkan
                                            @elseif ($soal["jenis"] == "4")
                                                Isian Singkat
                                            @elseif ($soal["jenis"] == "5")
                                                Esai
                                            @else
                                                Lainnya
                                            @endif
                                        </span>
                                    </div>

                                    <div class="tw-mb-4 tw-text-gray-800 prose">
                                        {!! $soal["soal"] !!}
                                    </div>

                                    @if ($soal["jenis"] == "1")
                                        <!-- PG -->
                                        <div class="tw-space-y-2">
                                            @foreach (["A", "B", "C", "D", "E"] as $opt)
                                                @php
                                                    $isUserAnswer = $soal["jawaban_anggota"] == $opt;
                                                    $isCorrectKey = ($soal["alias_map"][$opt] ?? "") == $soal["kunci_jawaban"];

                                                    $bgClass = "tw-bg-gray-50 tw-border-gray-200";
                                                    $icon = "";

                                                    if ($isUserAnswer && $isCorrectKey) {
                                                        $bgClass = "tw-bg-green-100 tw-border-green-500 tw-text-green-800";
                                                        $icon = '<i class="fas fa-check-circle tw-text-green-600"></i>';
                                                    } elseif ($isUserAnswer && ! $isCorrectKey) {
                                                        $bgClass = "tw-bg-red-100 tw-border-red-500 tw-text-red-800";
                                                        $icon = '<i class="fas fa-times-circle tw-text-red-600"></i>';
                                                    } elseif (! $isUserAnswer && $isCorrectKey) {
                                                        $bgClass = "tw-bg-green-50 tw-border-green-300 tw-text-green-800";
                                                        $icon = '<i class="fas fa-check tw-text-green-600"></i>';
                                                    }
                                                @endphp

                                                @if (! empty($soal["opsi_display"][$opt]))
                                                    <div class="tw-flex tw-items-center tw-p-3 tw-rounded-lg tw-border {{ $bgClass }}">
                                                        <span class="tw-font-bold tw-mr-3 tw-w-6">{{ $opt }}.</span>
                                                        <div class="tw-flex-1">
                                                            {!! $soal["opsi_display"][$opt] !!}
                                                        </div>
                                                        <div class="tw-ml-3">
                                                            {!! $icon !!}
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @elseif ($soal["jenis"] == "2")
                                        <!-- PG Kompleks -->
                                        <div class="tw-mt-4 tw-space-y-2">
                                            @forelse ($soal["options_pg_kompleks"] ?? [] as $idx => $label)
                                                @php
                                                    $idxStr = (string) $idx;
                                                    $userSelected = in_array($idxStr, $soal["formatted_jawaban"] ?? []);
                                                    $isCorrect = in_array($idxStr, $soal["formatted_kunci"] ?? []);

                                                    $bgClass = "tw-bg-gray-50 tw-border-gray-200";
                                                    $icon = "";

                                                    if ($userSelected && $isCorrect) {
                                                        $bgClass = "tw-bg-green-100 tw-border-green-500 tw-text-green-800";
                                                        $icon = '<i class="fas fa-check-circle tw-text-green-600"></i>';
                                                    } elseif ($userSelected && ! $isCorrect) {
                                                        $bgClass = "tw-bg-red-100 tw-border-red-500 tw-text-red-800";
                                                        $icon = '<i class="fas fa-times-circle tw-text-red-600"></i>';
                                                    } elseif (! $userSelected && $isCorrect) {
                                                        $bgClass = "tw-bg-green-50 tw-border-green-300 tw-text-green-800"; // Missed correct answer
                                                        $icon = '<i class="fas fa-check tw-text-green-600"></i>';
                                                    }
                                                @endphp

                                                <div class="tw-flex tw-items-center tw-p-3 tw-rounded-lg tw-border {{ $bgClass }}">
                                                    <div class="tw-mr-3">
                                                        <input type="checkbox" disabled {{ $userSelected ? "checked" : "" }} class="tw-w-5 tw-h-5 tw-text-blue-600 tw-rounded focus:tw-ring-transparent" />
                                                    </div>
                                                    <div class="tw-flex-1">
                                                        {!! $label !!}
                                                    </div>
                                                    <div class="tw-ml-3">
                                                        {!! $icon !!}
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="tw-text-gray-500 tw-italic">Tidak ada opsi</p>
                                            @endforelse
                                        </div>
                                    @elseif ($soal["jenis"] == "3")
                                        <!-- Jodohkan -->
                                        <div class="tw-mt-4" data-user-answer="{{ $soal["jawaban_anggota"] }}" data-key-answer="{{ $soal["kunci_jawaban"] }}" x-data="{
                                            initJodohkan() {
                                                setTimeout(() => {
                                                    let userRaw = $el.dataset.userAnswer
                                                    let keyRaw = $el.dataset.keyAnswer
                                                    let userData = null
                                                    let keyData = null

                                                    try {
                                                        if (userRaw) userData = JSON.parse(userRaw)
                                                        if (keyRaw) keyData = JSON.parse(keyRaw)

                                                        // Robustness: handle double-encoded JSON if applicable
                                                        if (typeof userData === 'string')
                                                            userData = JSON.parse(userData)
                                                        if (typeof keyData === 'string') keyData = JSON.parse(keyData)
                                                    } catch (e) {
                                                        console.error('Jodohkan Parse Error', e)
                                                    }

                                                    if (userData) {
                                                        $(this.$refs.userContainer).html('').linkerList({
                                                            data: userData,
                                                            viewMode: '1',
                                                        })
                                                    }

                                                    if (keyData) {
                                                        $(this.$refs.keyContainer).html('').linkerList({
                                                            data: keyData,
                                                            viewMode: '1',
                                                        })
                                                    }

                                                    $(this.$root).find('canvas').css('pointer-events', 'none')
                                                }, 500)
                                            },
                                        }" x-init="initJodohkan()">
                                            <p class="tw-font-bold tw-mb-2">Jawaban Anda:</p>
                                            <div x-ref="userContainer" class="jodohkan-container" wire:ignore></div>

                                            <p class="tw-font-bold tw-mb-2 tw-mt-4 tw-text-green-600">Kunci Jawaban:</p>
                                            <div x-ref="keyContainer" class="jodohkan-container" wire:ignore></div>
                                        </div>
                                    @else
                                        <!-- Isian / Esai / Lainnya -->
                                        <div class="tw-mt-4 tw-p-4 tw-bg-gray-100 tw-rounded-lg">
                                            <p class="tw-font-bold tw-mb-1">Jawaban Anda:</p>
                                            <div class="tw-mb-3">{!! $soal["jawaban_anggota"] ?? "-" !!}</div>

                                            <p class="tw-font-bold tw-mb-1 tw-text-green-600">Kunci Jawaban:</p>
                                            <div>{!! $soal["kunci_jawaban"] ?? "-" !!}</div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="closeDetail">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    <link rel="stylesheet" href="{{ asset("assets/summernote/fieldsLinker.css") }}" />
@endpush

@push("js-libraries")
    <script src="{{ asset("assets/summernote/ResizeSensor.js") }}"></script>
    <script src="{{ asset("assets/summernote/linker-list.js") }}"></script>
@endpush
