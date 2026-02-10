<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>SOAL NO. {{ $currentQuestionIndex + 1 }}</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="tw-flex tw-flex-col md:tw-flex-row md:tw-justify-between md:tw-items-center tw-gap-4 tw-mt-4 tw-px-6">
                    <div class="tw-flex tw-items-center tw-justify-between md:tw-justify-start">
                        <span class="tw-text-base tw-font-semibold tw-text-gray-700">Ukuran font soal:</span>
                        <div class="tw-flex tw-items-baseline">
                            <button class="tw-text-blue-900 tw-ml-3 tw-text-base hover:tw-text-blue-700 tw-font-bold" wire:click="changeSize('base')">A</button>
                            <button class="tw-text-blue-900 tw-ml-3 tw-text-lg hover:tw-text-blue-700 tw-font-bold" wire:click="changeSize('lg')">A</button>
                            <button class="tw-text-blue-900 tw-ml-3 tw-text-2xl hover:tw-text-blue-700 tw-font-bold" wire:click="changeSize('2xl')">A</button>
                        </div>
                    </div>

                    <div class="tw-grid tw-grid-cols-2 tw-gap-2 md:tw-flex md:tw-gap-3">
                        <a href="" class="btn btn-primary tw-rounded-full tw-flex tw-space-x-2 tw-items-center tw-justify-center tw-shadow-sm">
                            <i class="fas fa-sync md:tw-mr-2"></i>
                            <span class="tw-text-xs md:tw-text-sm">Refresh</span>
                        </a>
                        <button id="toggle-btn" class="btn btn-info tw-rounded-full tw-flex tw-space-x-2 tw-items-center tw-justify-center tw-shadow-sm">
                            <i class="fas fa-th md:tw-mr-2"></i>
                            <span class="tw-text-xs md:tw-text-sm">Daftar Soal</span>
                        </button>
                    </div>
                </div>
                <div class="card-body px-4">
                    @if (count($soals) > 0 && ! empty($soals["data"]))
                        <div class="tw-border-4 tw-border-gray-300 tw-h-auto tw-px-4 tw-py-0 tw-text-gray-800 tw-tracking-wide @if ($size == "lg")
                            tw-text-lg
                        @elseif ($size == "2xl")
                            tw-text-2xl
                        @else
                            tw-text-sm
                        @endif">
                            <div class="tw-mt-4">
                                {!! $soals["data"][$currentQuestionIndex]["soal"] !!}
                            </div>

                            {{-- PG --}}
                            @if ($soals["data"][$currentQuestionIndex]["jenis_soal"] == "1")
                                <div class="tw-space-y-2 tw-mt-4 tw-mb-6" wire:key="{{ rand() }}">
                                    @foreach (["A", "B", "C", "D", "E"] as $option)
                                        @php
                                            $optionContent = $soals["data"][$this->currentQuestionIndex]["opsi_alias_" . strtolower($option)] ?? null;
                                        @endphp

                                        @if ($optionContent && trim(strip_tags($optionContent)) !== "")
                                            <label class="tw-flex tw-items-center">
                                                <input type="radio" name="opsi" class="tw-hidden tw-peer" value="{{ $option }}" wire:model="opsi" wire:change="selectOption('{{ $option }}')" {{ $this->opsi == $option ? "checked" : "" }} />
                                                <span class="tw-mr-2 tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-full tw-border-4 tw-font-bold {{ $this->opsi == $option ? "tw-border-blue-500 tw-bg-blue-500 tw-text-white" : "tw-border-gray-300 tw-text-gray-400" }}">
                                                    {{ $option }}
                                                </span>
                                                <div class="tw-text-gray-800">{!! $optionContent !!}</div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>

                                {{-- PG Kompleks --}}
                            @elseif ($soals["data"][$currentQuestionIndex]["jenis_soal"] == "2")
                                @foreach (json_decode($soals["data"][$currentQuestionIndex]["opsi_a"]) as $key => $opsi_kompleks)
                                    <div class="tw-space-y-2">
                                        <input type="checkbox" id="pg_kompleks_{{ $key }}" wire:model.blur="pg_kompleks" value="{{ $key }}" class="tw-mr-1.5 tw-border tw-border-gray-300 tw-p-2.5 tw-rounded-sm tw-shadow-inner tw-shadow-gray-200 checked:tw-shadow-none" />
                                        <label for="pg_kompleks_{{ $key }}">{!! $opsi_kompleks !!}</label>
                                    </div>
                                @endforeach

                                <div class="mb-4"></div>

                                {{-- Jodohkan --}}
                            @elseif ($soals["data"][$currentQuestionIndex]["jenis_soal"] == "3")
                                <div wire:ignore id="jawaban-jodohkan" class="mt-3"></div>

                                {{-- Isian Singkat --}}
                            @elseif ($soals["data"][$currentQuestionIndex]["jenis_soal"] == "4")
                                <div class="form-group mt-3" wire:key="{{ rand() }}">
                                    <input type="text" wire:model.blur="isian_singkat" class="form-control" />
                                </div>

                                {{-- Esai --}}
                            @elseif ($soals["data"][$currentQuestionIndex]["jenis_soal"] == "5")
                                <div class="form-group mt-3" wire:ignore>
                                    <textarea id="essai" class="form-control"></textarea>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="tw-p-4 tw-text-center">
                            <p>Tidak ada soal yang tersedia.</p>
                        </div>
                    @endif

                    <div class="tw-grid tw-grid-cols-3 tw-gap-2 tw-mt-8 md:tw-flex md:tw-justify-between">
                        <button class="btn btn-lg btn-primary tw-flex tw-items-center tw-justify-center tw-w-full md:tw-w-auto tw-shadow-md" wire:click="previousQuestion" @if ($currentQuestionIndex == 0) disabled @endif>
                            <i class="fas fa-chevron-left md:tw-mr-2"></i>
                            <span class="tw-hidden md:tw-inline">SOAL SEBELUMNYA</span>
                        </button>

                        <button class="btn btn-lg btn-warning tw-w-full md:tw-w-auto tw-p-1 md:tw-px-4 tw-shadow-md">
                            <div wire:key="ragu-{{ $currentQuestionIndex }}" class="tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-justify-center">
                                <input type="checkbox" class="tw-w-5 tw-h-5 md:tw-mr-2 tw-accent-white tw-cursor-pointer" wire:click="selectRagu({{ $soals["data"][$currentQuestionIndex]["ragu"] ?? 0 ? "0" : "1" }})" {{ $soals["data"][$currentQuestionIndex]["ragu"] ?? 0 ? "checked" : "" }} />
                                <span class="tw-text-[10px] md:tw-text-base tw-font-bold tw-mt-1 md:tw-mt-0 tw-leading-tight">RAGU-RAGU</span>
                            </div>
                        </button>

                        @if ($currentQuestionIndex < count($soals["data"]) - 1)
                            <button class="btn btn-lg btn-primary tw-flex tw-items-center tw-justify-center tw-w-full md:tw-w-auto tw-shadow-md" wire:click="nextQuestion">
                                <span class="tw-hidden md:tw-inline md:tw-mr-2">SOAL BERIKUTNYA</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @else
                            <button class="btn btn-lg btn-info tw-flex tw-items-center tw-justify-center tw-w-full md:tw-w-auto tw-shadow-md" wire:click="confirmFinish">
                                <span class="tw-mr-2 tw-text-sm md:tw-text-base">SELESAI</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Panel Nomor Soal --}}
    <div id="panel" class="tw-z-[999] tw-panel tw-w-64 tw-bg-white tw-h-full tw-overflow-y-auto tw-shadow-lg">
        <div class="tw-p-4">
            <h3 class="mb-3 tw-tracking-wider tw-text-[#34395e] tw-text-base tw-font-semibold">NOMOR SOAL</h3>
            @if (! empty($soals["data"]))
                <div class="tw-grid tw-grid-cols-4 tw-gap-y-4 tw-gap-x-2">
                    @foreach ($soals["data"] as $index => $soal)
                        <div class="tw-relative">
                            <button wire:click="goToQuestion({{ $index }})" class="tw-w-10 tw-h-10 tw-font-semibold {{ $index == $currentQuestionIndex ? "tw-bg-sky-600 tw-text-white" : "tw-bg-gray-50 tw-border-2 tw-border-gray-600 tw-text-black" }}">
                                {{ $index + 1 }}
                            </button>
                            <span class="tw-absolute tw--top-2 tw--right-0 tw-w-6 tw-h-6 tw-text-xs tw-font-bold tw-text-white @if ($soal["ragu"] ?? false)
                                tw-bg-yellow-400
                            @elseif (empty($soal["jawaban_anggota"]) || $soal["jawaban_anggota"] === "[]" || $soal["jawaban_anggota"] === "<p>...</p>")
                                tw-bg-white
                            @else
                                tw-bg-green-400
                            @endif tw-border-2 tw-border-gray-600 tw-rounded-full tw-flex tw-items-center tw-justify-center">
                                @if ($soal["jenis_soal"] == "1")
                                    {{ $soal["jawaban_anggota"] ?? "" }}
                                @else
                                    @if (! empty($soal["jawaban_anggota"]) && $soal["jawaban_anggota"] !== "[]")
                                        <i class="fas fa-check text-white"></i>
                                    @endif
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push("general-css")
    <style>
        body {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .tw-panel {
            transition: transform 0.3s ease;
            transform: translateX(100%);
            position: fixed;
            right: 0;
            top: 0;
            height: 100%;
            z-index: 1000;
        }

        .tw-show-panel {
            transform: translateX(0);
        }
    </style>
    <link href="{{ asset("assets/summernote/summernote-lite.min.css") }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset("assets/katex/katex.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/summernote/fieldsLinker.css") }}" />
@endpush

@push("js-libraries")
    <script src="{{ asset("assets/summernote/summernote-lite.min.js") }}"></script>
    <script src="{{ asset("assets/summernote/summernote-math.js") }}"></script>
    <script src="{{ asset("assets/katex/katex.min.js") }}"></script>
    <script src="{{ asset("assets/summernote/ResizeSensor.js") }}"></script>
    <script src="{{ asset("assets/summernote/linker-list.js") }}"></script>
@endpush

@push("scripts")
    <script>
        // Prevent right-click
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });

        // Prevent keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'u' || e.key === 's')) || e.key === 'F12') {
                e.preventDefault();
            }
        });
    </script>

    {{-- Jodohkan --}}
    <script>
        window.addEventListener('initSummernoteJDH', event => {
            $(document).ready(function() {
                let konten = $('#jawaban-jodohkan')
                konten.html('')
                initJawaban = @this.get('jawaban_jodohkan')

                if (initJawaban == "") {
                    jawaban = { jawaban: [], model: '1', type: '2' }
                } else {
                    jawaban = initJawaban
                    if (typeof jawaban == "string") {
                        jawaban = JSON.parse(initJawaban)
                    }

                    processLinks(jawaban);

                    function processLinks(jawaban) {
                        function detectFormat(links) {
                            if (Array.isArray(links)) return 'arrayOfArrays';
                            else if (typeof links === 'object' && links !== null) return 'objectOfArrays';
                            return 'unknown';
                        }

                        function hasValidLinks(links) {
                            if (Array.isArray(links)) {
                                return links.some(value => Array.isArray(value) && value.length > 0);
                            } else if (typeof links === 'object' && links !== null) {
                                return Object.values(links).some(value => Array.isArray(value) && value.length > 0);
                            }
                            return false;
                        }

                        const format = detectFormat(jawaban.links);

                        if (!hasValidLinks(jawaban.links) || jawaban == "") {
                            if (format === 'arrayOfArrays') {
                                jawaban.jawaban.forEach((row, index) => {
                                    if (index > 0) {
                                        for (let i = 1; i < row.length; i++) { row[i] = "0"; }
                                    }
                                });
                                jawaban.links = jawaban.links.reduce((acc, value, index) => {
                                    acc[(index + 1).toString()] = Array.isArray(value) ? value.map(() => "") : [""];
                                    return acc;
                                }, {});
                            } else if (format === 'objectOfArrays') {
                                jawaban.jawaban.forEach((row, index) => {
                                    if (index > 0) {
                                        for (let i = 1; i < row.length; i++) { row[i] = "0"; }
                                    }
                                });
                                jawaban.links = Object.keys(jawaban.links).reduce((acc, key) => {
                                    const value = jawaban.links[key];
                                    acc[key] = Array.isArray(value) ? value.map(() => "") : [""];
                                    return acc;
                                }, {});
                            }
                        }
                    }
                }

                konten.linkerList({
                    data: jawaban,
                    viewMode: '2',
                    callback: function(id, data, hasLinks) {
                        @this.set('jawaban_jodohkan', data)
                    }
                });
            });
        })
    </script>

    {{-- Confirm & Finish --}}
    <script>
        window.addEventListener('confirm:ujian', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('finishUjian')
                }
            })
        })

        window.addEventListener('swal:modal', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
            })
        })

        window.addEventListener('swal:finish', event => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
            }).then((result) => {
                window.location.href = '/anggota/daftar-pertemuan';
            })
        })

        // Panel toggle
        $(document).on('click', '#toggle-btn', function(e) {
            e.preventDefault();
            $('#panel').toggleClass('tw-show-panel');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#panel, #toggle-btn').length) {
                $('#panel').removeClass('tw-show-panel');
            }
        });

        // Radio button fix
        document.addEventListener('livewire:init', () => {
            $('input[name="opsi"]').on('change', function() {
                if (this.checked) {
                    $('input[name="opsi"]').not(this).prop('checked', false);
                    @this.set('opsi', this.value)
                }
            });
        })
    </script>

    {{-- Summernote Esai --}}
    <script>
        window.addEventListener('initSummernote', event => {
            $(document).ready(function() {
                initializeSummernote('#essai', 'essai');
            });
        })

        function initializeSummernote(selector, wiremodel) {
            $(selector).summernote('destroy');
            $(selector).summernote({
                toolbar: [
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ],
                callbacks: {
                    onBlur: function() {
                        const contents = $(selector).summernote('code');
                        if (contents === '' || contents === '<br>' || !contents.includes('<p>')) {
                            $(selector).summernote('code', '<p>' + contents + '</p>');
                        }
                        @this.set(wiremodel, contents);
                    },
                    onPaste: function(e) {
                        e.preventDefault();
                        var clipboardData = (e.originalEvent || e).clipboardData;
                        var text = clipboardData.getData('text/plain');
                        document.execCommand('insertHTML', false, '<p>' + text + '</p>');
                    },
                    onInit: function() {
                        let currentContent = @this.get(wiremodel);
                        if (!currentContent) { currentContent = '<p>...</p>'; }
                        @this.set(wiremodel, currentContent);
                        $(selector).summernote('code', currentContent);
                    }
                },
            });
        }
    </script>
@endpush
