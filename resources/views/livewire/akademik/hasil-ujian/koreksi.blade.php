<div>
    <section class="section custom-section">
        <div class="section-header tw-block">
            <div class="tw-flex">
                <a href="{{ url("hasil-ujian-pertemuan") }}" class="btn btn-muted">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="tw-text-lg">Koreksi Hasil Anggota</h1>
                @if ($detail && $detail->dikoreksi != null)
                    <button class="btn btn-primary ml-auto tw-tracking-wider" wire:click.prevent="sudahDikoreksi()">
                        <i class="fas fa-check"></i>
                        Tandai Sudah Dikoreksi
                    </button>
                @endif
            </div>
        </div>

        <div class="section-body">
            @if ($detail)
                <div class="card">
                    <div class="card-body px-4">
                        <div class="row">
                            <div class="col-lg-4">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td width="35%" class="tw-border tw-border-gray-200">Nama</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nama_lengkap }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">Jurusan</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->jurusan_prodi_kelas }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">Status</td>
                                            <td class="tw-border tw-border-gray-200">{{ ucfirst($detail->status_anggota) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td width="45%" class="tw-border tw-border-gray-200">Program</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nama_program }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">Pertemuan</td>
                                            <td class="tw-border tw-border-gray-200">Pert. {{ $detail->pertemuan_ke }}: {{ $detail->judul_pertemuan }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">Pemateri</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nama_pemateri ?? "-" }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td width="25%" class="tw-border tw-border-gray-200">PG</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nilai_pg }}</td>
                                            <td class="tw-border tw-tracking-wider tw-border-gray-200 tw-align-top text-center tw-font-semibold" rowspan="3">
                                                <span>NILAI</span>
                                                <br />
                                                <br />
                                                <span class="tw-text-3xl">
                                                    {{ (float) $detail->nilai_pg + (float) $detail->nilai_pk + (float) $detail->nilai_jo + (float) $detail->nilai_is + (float) $detail->nilai_es }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">PK</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nilai_pk }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">JO</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nilai_jo }}</td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">IS</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nilai_is }}</td>
                                            <td class="tw-border tw-tracking-wider tw-border-gray-200 text-center tw-font-semibold" rowspan="2">
                                                @if ($detail->dikoreksi == null)
                                                    <span class="tw-text-red-700">
                                                        <i class="fas fa-times mr-2"></i>
                                                        Tidak ada soal
                                                    </span>
                                                @elseif ($detail->dikoreksi == "0")
                                                    <span class="tw-text-red-700">
                                                        <i class="fas fa-times mr-2"></i>
                                                        Belum dikoreksi
                                                    </span>
                                                @else
                                                    <span class="tw-text-green-700">
                                                        <i class="fas fa-check mr-2"></i>
                                                        Sudah dikoreksi
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tw-border tw-border-gray-200">ES</td>
                                            <td class="tw-border tw-border-gray-200">{{ $detail->nilai_es }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    $jenisSoalNames = [
                        1 => "Pilihan Ganda",
                        2 => "Pilihan Ganda Kompleks",
                        3 => "Menjodohkan",
                        4 => "Isian Singkat",
                        5 => "Essay / Uraian",
                    ];

                    $groupedSoals = $soals->groupBy("jenis_soal");
                @endphp

                @foreach ($groupedSoals as $jenisSoal => $soalsGroup)
                    @php
                        $correctAnswers = $soalsGroup->filter(function ($item) {
                            return $item->jawaban_alias === $item->jawaban_anggota;
                        });

                        $incorrectAnswers = $soalsGroup->filter(function ($item) {
                            return $item->jawaban_alias !== $item->jawaban_anggota;
                        });

                        $correctScore = $correctAnswers->sum(function ($item) {
                            if ($item->nilai_koreksi > 0) {
                                return $item->nilai_koreksi;
                            }
                            return $item->jenis_soal == "5" ? $item->point_essai : $item->point_soal;
                        });

                        $incorrectScore = $incorrectAnswers->sum(function ($item) {
                            return $item->nilai_koreksi;
                        });

                        $totalScore = $correctScore + $incorrectScore;
                    @endphp

                    <div class="card">
                        <div class="card-header">
                            <h4 class="tw-font-semibold">{{ $jenisSoalNames[$jenisSoal] ?? "Soal" }}</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse-{{ $jenisSoal }}" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse-{{ $jenisSoal }}">
                            <div class="card-body px-0 py-0">
                                <div class="tw-overflow-x-auto no-scrollbar">
                                    <table class="tw-w-full tw-min-w-full tw-table-auto">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Soal</th>
                                                @if ($jenisSoal == "1" || $jenisSoal == "2")
                                                    <th>Pilihan</th>
                                                @endif

                                                <th class="tw-whitespace-nowrap">JWB Benar</th>
                                                <th class="tw-whitespace-nowrap">JWB Anggota</th>
                                                <th>Analisa</th>
                                                <th class="tw-whitespace-nowrap">Point Max. {{ $jenisSoal == "5" ? $soalsGroup[0]->point_essai : $soalsGroup[0]->point_soal }}</th>
                                                @if ($jenisSoal != "1")
                                                    <th><i class="fas fa-cogs"></i></th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $countTotalScore = $jenisSoal == "2" ? 7 : 6;
                                            @endphp

                                            @foreach ($soalsGroup as $soal)
                                                <tr class="tw-align-top">
                                                    <td class="text-center tw-py-4">{{ $soal->no_soal_alias }}.</td>
                                                    <td class="tw-py-3 tw-w-1/3">
                                                        <p>{!! $soal->soal !!}</p>
                                                    </td>
                                                    @if ($jenisSoal == "1")
                                                        <td class="tw-whitespace-nowrap tw-py-3">
                                                            @php
                                                                $optionLabels = [
                                                                    "A" => $soal->opsi_alias_a,
                                                                    "B" => $soal->opsi_alias_b,
                                                                    "C" => $soal->opsi_alias_c,
                                                                    "D" => $soal->opsi_alias_d,
                                                                    "E" => $soal->opsi_alias_e,
                                                                ];
                                                                $optionCount = intval($detail->opsi ?? 5);
                                                                $optionsToShow = array_slice($optionLabels, 0, $optionCount, true);
                                                            @endphp

                                                            @if (! empty($optionsToShow))
                                                                <ul>
                                                                    @foreach ($optionsToShow as $label => $option)
                                                                        <li class="tw-flex">{{ $label }}. &nbsp; {!! $option !!}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </td>
                                                    @elseif ($jenisSoal == "2")
                                                        <td class="tw-whitespace-nowrap tw-py-3">
                                                            @php
                                                                $opsiKompleks = json_decode($soal->opsi_alias_a ?? "[]");
                                                            @endphp

                                                            @if ($opsiKompleks)
                                                                <ul>
                                                                    @foreach ($opsiKompleks as $key => $opsi_kompleks)
                                                                        <li class="tw-flex">{{ $key }}. &nbsp; {!! $opsi_kompleks !!}</li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    {{-- Jawaban Benar & Jawaban Anggota --}}

                                                    @if ($jenisSoal == "1")
                                                        <td class="text-center tw-py-3">{!! $soal->jawaban_alias !!}</td>
                                                        <td class="text-center tw-py-3">{!! $soal->jawaban_anggota !!}</td>
                                                    @elseif ($jenisSoal == "2")
                                                        <td class="text-center tw-py-3">
                                                            @php
                                                                $jwbAlias = json_decode($soal->jawaban_alias, true);
                                                            @endphp

                                                            {{ is_array($jwbAlias) ? implode(", ", $jwbAlias) : $soal->jawaban_alias }}
                                                        </td>
                                                        <td class="text-center tw-py-3">
                                                            @php
                                                                $jwbAnggota = json_decode($soal->jawaban_anggota, true);
                                                            @endphp

                                                            {{ is_array($jwbAnggota) ? implode(", ", $jwbAnggota) : $soal->jawaban_anggota }}
                                                        </td>
                                                    @elseif ($jenisSoal == "3")
                                                        <td class="tw-w-1/3">
                                                            @php
                                                                $jawabanAlias = json_decode($soal->jawaban_alias, true);
                                                                $itemsAlias = [];
                                                                if (isset($jawabanAlias["jawaban"]) && count($jawabanAlias["jawaban"]) > 1) {
                                                                    $descriptionsAlias = $jawabanAlias["jawaban"][0];
                                                                    foreach ($jawabanAlias["jawaban"] as $index => $row) {
                                                                        if ($index > 0) {
                                                                            $position = array_search("1", $row);
                                                                            if ($position !== false) {
                                                                                $itemsAlias[] = ["name" => $row[0], "description" => $descriptionsAlias[$position]];
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            @endphp

                                                            @foreach ($itemsAlias as $item)
                                                                {!! $item["name"] !!}
                                                                <span>--</span>
                                                                {!! $item["description"] !!}
                                                                <br />
                                                            @endforeach
                                                        </td>
                                                        <td class="tw-w-1/3">
                                                            @php
                                                                $jawabanAnggota = json_decode($soal->jawaban_anggota, true);
                                                                $itemsAnggota = [];
                                                                if (isset($jawabanAnggota["jawaban"]) && count($jawabanAnggota["jawaban"]) > 1) {
                                                                    $descriptionsAnggota = $jawabanAnggota["jawaban"][0];
                                                                    foreach ($jawabanAnggota["jawaban"] as $index => $row) {
                                                                        if ($index > 0) {
                                                                            $position = array_search("1", $row);
                                                                            if ($position !== false) {
                                                                                $itemsAnggota[] = ["name" => $row[0], "description" => $descriptionsAnggota[$position]];
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            @endphp

                                                            @foreach ($itemsAnggota as $item)
                                                                {!! $item["name"] !!}
                                                                <span>--</span>
                                                                {!! $item["description"] !!}
                                                                <br />
                                                            @endforeach
                                                        </td>
                                                    @elseif ($jenisSoal == "5")
                                                        <td class="text-left tw-w-1/2">{!! $soal->jawaban_alias !!}</td>
                                                        <td class="text-left tw-w-1/2">{!! $soal->jawaban_anggota !!}</td>
                                                    @else
                                                        <td class="text-left tw-py-3">{!! $soal->jawaban_alias !!}</td>
                                                        <td class="text-left tw-py-3">{!! $soal->jawaban_anggota !!}</td>
                                                    @endif

                                                    {{-- Analisa --}}
                                                    <td class="text-center tw-py-3">
                                                        @php
                                                            $isCorrect = false;
                                                            if ($jenisSoal == "2") {
                                                                $a1 = json_decode($soal->jawaban_alias, true) ?? [];
                                                                $a2 = json_decode($soal->jawaban_anggota, true) ?? [];
                                                                sort($a1);
                                                                sort($a2);
                                                                $isCorrect = $a1 == $a2 && ! empty($a2);
                                                            } elseif ($jenisSoal == "3") {
                                                                $d1 = json_decode($soal->jawaban_alias, true) ?? [];
                                                                $d2 = json_decode($soal->jawaban_anggota, true) ?? [];
                                                                $m1 = array_slice($d1["jawaban"] ?? [], 1);
                                                                $m2 = array_slice($d2["jawaban"] ?? [], 1);
                                                                $isCorrect = $m1 == $m2 && ! empty($m2);
                                                            } else {
                                                                $isCorrect = $soal->jawaban_anggota === $soal->jawaban_alias;
                                                            }
                                                        @endphp

                                                        @if ($isCorrect)
                                                            <i class="fas fa-check-circle text-success tw-text-lg"></i>
                                                        @else
                                                            <i class="fas fa-times text-danger tw-text-lg"></i>
                                                        @endif
                                                    </td>

                                                    {{-- Point --}}
                                                    @php
                                                        $maxPoint = $jenisSoal == "5" ? $soal->point_essai : $soal->point_soal;
                                                    @endphp

                                                    <td class="text-center tw-py-3">
                                                        @if ($isCorrect)
                                                            <center>
                                                                <input type="number" class="tw-w-[50px] form-control tw-text-center" value="{{ (int) $maxPoint }}" id="input{{ $soal->id }}" style="display: none" data-point="{{ $maxPoint }}" wire:model="point.{{ $soal->id }}" />
                                                            </center>
                                                            <span data-idsoal="{{ $soal->id }}" id="span{{ $soal->id }}" wire:ignore>
                                                                @if ($soal->nilai_koreksi != 0)
                                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                                    {{ $soal->nilai_koreksi }}
                                                                @else
                                                                    {{ $maxPoint }}
                                                                @endif
                                                            </span>
                                                        @else
                                                            <center>
                                                                <input type="number" class="tw-w-[50px] form-control tw-text-center" value="{{ $soal->nilai_koreksi }}" id="input{{ $soal->id }}" style="display: none" data-point="{{ $maxPoint }}" wire:model="point.{{ $soal->id }}" />
                                                            </center>
                                                            <span data-idsoal="{{ $soal->id }}" id="span{{ $soal->id }}" wire:ignore>
                                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                                                {{ $soal->nilai_koreksi }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    {{-- Edit button (non-PG only) --}}
                                                    @if ($jenisSoal != "1")
                                                        @if ($soal->jawaban_anggota !== $soal->jawaban_alias)
                                                            <td class="tw-py-3">
                                                                <div class="tw-flex tw-justify-center">
                                                                    <button id="edit{{ $soal->id }}" onclick="edit({{ $soal->id }})" class="btn btn-sm btn-primary mr-3"><i class="fas fa-edit"></i></button>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <thead>
                                            <tr>
                                                <th class="text-right" colspan="{{ $countTotalScore }}">TOTAL SCORE {{ $jenisSoalNames[$jenisSoal] ?? "" }}</th>
                                                <th class="text-right">{{ $totalScore }}</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <p class="tw-text-gray-500">Data tidak ditemukan.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

@push("general-css")
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type='number'] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push("scripts")
    <script>
        function edit(id) {
            var input = $(`#input${id}`);
            var span = $(`#span${id}`);
            var btnedit = $(`#edit${id}`);

            if (input.is(':visible')) {
                let val = input.val();
                let point_soal = input.data('point');
                if (parseFloat(val) < 0) {
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Nilai tidak boleh minus!',
                        icon: 'warning',
                        confirmButtonText: 'Okay',
                    });
                } else {
                    if (parseFloat(val) > parseFloat(point_soal)) {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: 'Nilai yang anda inputkan melebihi batas point max, point max: ' + parseFloat(point_soal),
                            icon: 'warning',
                            confirmButtonText: 'Okay',
                        });
                    } else {
                        Livewire.dispatch('updatePoint');
                        input.hide();
                        span.text(input.val()).show();
                        btnedit.html(`<i class="fa fa-pencil"></i>`);
                    }
                }
            } else {
                span.hide();
                input.val(span.text()).show();
                btnedit.html(`<i class="fa fa-check"></i>`);
            }
        }
    </script>
@endpush
