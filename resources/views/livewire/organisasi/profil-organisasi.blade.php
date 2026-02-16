<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Profil Organisasi</h1>
        </div>

        <div class="section-body">
            @forelse ($data as $row)
                <!-- Hero Section Minimalis -->
                <div class="card tw-border-0 tw-shadow-sm tw-overflow-hidden tw-mb-6">
                    <div class="tw-bg-gray-600 tw-p-8 tw-text-white">
                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-start tw-gap-4">
                            <div class="tw-flex-1">
                                <h1 class="tw-text-3xl md:tw-text-4xl tw-font-light tw-mb-2 tw-tracking-tight">{{ $row->headline }}</h1>
                                <p class="tw-text-lg tw-text-gray-300 tw-mb-4 tw-font-light">{{ $row->tagline }}</p>
                                <div class="tw-flex tw-items-center tw-gap-2 tw-text-sm tw-text-gray-400">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $row->nama_tahun }}</span>
                                </div>
                            </div>
                            @if ($this->can("profil_organisasi.edit"))
                                <button wire:click.prevent="edit({{ $row->id }})" class="btn tw-bg-white tw-text-gray-900 hover:tw-bg-gray-100 tw-border-0 tw-transition-colors" data-toggle="modal" data-target="#formDataModal">
                                    <i class="fas fa-edit tw-mr-2"></i>
                                    Edit Profil
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Organisasi -->
                    @if ($row->foto)
                        <div class="tw-relative tw-bg-gray-100">
                            <img src="{{ storageUrl($row->foto) }}" alt="Profil Organisasi" class="tw-w-full tw-h-auto" style="max-height: 480px; object-fit: cover" />
                        </div>
                    @endif

                    <div class="card-body tw-p-8 md:tw-p-12">
                        <!-- Deskripsi Section -->
                        @if ($row->deskripsi)
                            <div class="tw-mb-12">
                                <div class="tw-mb-6">
                                    <h3 class="tw-text-2xl tw-font-light tw-text-gray-900 tw-border-b tw-border-gray-200 tw-pb-3">Tentang Organisasi</h3>
                                </div>
                                <div class="tw-bg-gray-50 tw-p-6 tw-rounded-lg">
                                    <div class="tw-text-gray-700 tw-leading-relaxed tw-text-justify prose prose-sm max-w-none">{!! $row->deskripsi !!}</div>
                                </div>
                            </div>
                        @endif

                        <!-- Visi & Misi Grid Minimalis -->
                        <div class="row tw-gap-y-6">
                            <!-- Visi Card -->
                            @if ($row->visi)
                                <div class="col-lg-6">
                                    <div class="tw-h-full tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-overflow-hidden hover:tw-shadow-md tw-transition-shadow">
                                        <div class="tw-bg-gray-600 tw-p-4">
                                            <div class="tw-flex tw-items-center tw-gap-3">
                                                <div class="tw-w-10 tw-h-10 tw-bg-white tw-bg-opacity-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                                    <i class="fas fa-eye tw-text-white tw-text-lg"></i>
                                                </div>
                                                <h3 class="tw-text-xl tw-font-light tw-text-white">Visi</h3>
                                            </div>
                                        </div>
                                        <div class="tw-p-6">
                                            <div class="tw-text-gray-700 tw-leading-relaxed tw-text-justify prose prose-sm max-w-none">{!! $row->visi !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Misi Card -->
                            @if ($row->misi)
                                <div class="col-lg-6">
                                    <div class="tw-h-full tw-bg-white tw-border tw-border-gray-200 tw-rounded-lg tw-overflow-hidden hover:tw-shadow-md tw-transition-shadow">
                                        <div class="tw-bg-gray-600 tw-p-4">
                                            <div class="tw-flex tw-items-center tw-gap-3">
                                                <div class="tw-w-10 tw-h-10 tw-bg-white tw-bg-opacity-10 tw-rounded-lg tw-flex tw-items-center tw-justify-center">
                                                    <i class="fas fa-bullseye tw-text-white tw-text-lg"></i>
                                                </div>
                                                <h3 class="tw-text-xl tw-font-light tw-text-white">Misi</h3>
                                            </div>
                                        </div>
                                        <div class="tw-p-6">
                                            <div class="tw-text-gray-700 tw-leading-relaxed tw-text-justify prose prose-sm max-w-none">{!! $row->misi !!}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State Minimalis -->
                <div class="card tw-border-0 tw-shadow-sm tw-overflow-hidden">
                    <div class="tw-bg-gray-50 tw-py-20">
                        <div class="card-body tw-text-center">
                            <div class="tw-mb-8">
                                <div class="tw-w-24 tw-h-24 tw-mx-auto tw-bg-gray-200 tw-rounded-full tw-flex tw-items-center tw-justify-center">
                                    <i class="fas fa-building tw-text-5xl tw-text-gray-400"></i>
                                </div>
                            </div>

                            <h3 class="tw-text-2xl tw-font-light tw-text-gray-900 tw-mb-3">Profil Organisasi Belum Tersedia</h3>
                            <p class="tw-text-gray-600 tw-mb-8 tw-max-w-md tw-mx-auto">Tambahkan profil organisasi untuk tahun kepengurusan yang sedang aktif</p>

                            @if ($this->can("profil_organisasi.edit"))
                                <button wire:click.prevent="isEditingMode(false)" class="btn tw-bg-gray-600 tw-text-white hover:tw-bg-gray-800 tw-border-0 tw-transition-colors" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
                                    <i class="far fa-plus tw-mr-2"></i>
                                    Tambah Profil Organisasi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Data" : "Add Data" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        {{--
                            <div class="form-group">
                            <label for="id_tahun">Tahun Kepengurusan</label>
                            <input type="text" wire:model="id_tahun" id="id_tahun" class="form-control" />
                            @error("id_tahun")
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        --}}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="headline" class="tw-font-medium tw-text-gray-700">
                                        Headline
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="headline" id="headline" class="form-control" placeholder="Masukkan headline organisasi" />
                                    @error("headline")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tagline" class="tw-font-medium tw-text-gray-700">Tagline</label>
                                    <input type="text" wire:model="tagline" id="tagline" class="form-control" placeholder="Masukkan tagline" />
                                    @error("tagline")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi" class="tw-font-medium tw-text-gray-700 tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-info-circle tw-text-gray-500"></i>
                                Deskripsi
                            </label>
                            <div wire:ignore>
                                <textarea wire:model="deskripsi" id="deskripsi" class="form-control" style="height: 150px !important"></textarea>
                            </div>
                            @error("deskripsi")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="visi" class="tw-font-medium tw-text-gray-700 tw-flex tw-items-center tw-gap-2">
                                        <i class="fas fa-eye tw-text-gray-500"></i>
                                        Visi
                                    </label>
                                    <div wire:ignore>
                                        <textarea wire:model="visi" id="visi" class="form-control" style="height: 150px !important"></textarea>
                                    </div>
                                    @error("visi")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="misi" class="tw-font-medium tw-text-gray-700 tw-flex tw-items-center tw-gap-2">
                                        <i class="fas fa-tasks tw-text-gray-500"></i>
                                        Misi
                                    </label>
                                    <div wire:ignore>
                                        <textarea wire:model="misi" id="misi" class="form-control" style="height: 150px !important"></textarea>
                                    </div>
                                    @error("misi")
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="foto" class="tw-font-medium tw-text-gray-700 tw-flex tw-items-center tw-gap-2">
                                <i class="fas fa-image tw-text-gray-500"></i>
                                Foto Organisasi
                            </label>
                            <input type="file" wire:model="foto" id="foto" class="form-control" accept="image/*" />
                            <small class="form-text text-muted">Format: JPG, PNG, atau JPEG (Max: 2MB)</small>
                            @error("foto")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer tw-bg-gray-50 tw-border-t">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-border-gray-300" data-dismiss="modal">
                            <i class="fas fa-times tw-mr-1"></i>
                            Batal
                        </button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn tw-bg-gray-600 tw-text-white hover:tw-bg-gray-800 tw-border-0">
                            <i class="fas fa-save tw-mr-1"></i>
                            <span wire:loading.remove>{{ $isEditing ? "Update Data" : "Simpan Data" }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    <link href="{{ asset("assets/summernote/summernote-lite.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("assets/summernote/summernote-list-styles-bs4.css") }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset("assets/katex/katex.min.css") }}" />
@endpush

@push("js-libraries")
    <script src="{{ asset("assets/summernote/summernote-lite.min.js") }}"></script>
    <script src="{{ asset("assets/summernote/summernote-math.js") }}"></script>
    <script src="{{ asset("assets/summernote/summernote-list-styles-bs4.js") }}"></script>
    <script src="{{ asset("assets/katex/katex.min.js") }}"></script>
@endpush

@push("scripts")
    <script>
        window.addEventListener('initSummernote', (event) => {
            $(document).ready(function () {
                initializeSummernote('#deskripsi', 'deskripsi');
                initializeSummernote('#visi', 'visi');
                initializeSummernote('#misi', 'misi');
            });
        });
    </script>
    <script>
        function initializeSummernote(selector, wiremodel) {
            $(selector).summernote('destroy')
            $(selector).summernote({
                height: 75,
                imageAttributes: {
                    icon: '<i class="note-icon-pencil"/>',
                    removeEmpty: false,
                    disableUpload: false
                },
                popover: {
                    image: [
                        ['custom', ['imageAttributes']],
                        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                        ['float', ['floatLeft', 'floatCenter', 'floatRight', 'floatNone']],
                        ['remove', ['removeMedia']]
                    ],
                },
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'listStyles', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'math']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                grid: {
                    wrapper: "row",
                    columns: [
                        "col-md-12",
                        "col-md-6",
                        "col-md-4",
                        "col-md-3",
                        "col-md-24",
                    ]
                },
                callbacks: {
                    onImageUpload: function (image) {
                        sendFile(image[0], selector);
                    },
                    onMediaDelete: function (target) {
                        deleteFile(target[0].src)
                    },
                    onBlur: function () {
                        const contents = $(selector).summernote('code');
                        if (contents === '' || contents === '<br>' || !contents.includes('<p>')) {
                            $(selector).summernote('code', '<p>' + contents + '</p>');
                        }
                        @this.set(wiremodel, contents)
                    },
                    onPaste: function (e) {
                        e.preventDefault();
                        var clipboardData = (e.originalEvent || e).clipboardData;
                        var text = clipboardData.getData('text/plain');
                        var containsPreHljs = /<pre\s+class="hljs"[^>]*>[^]*?<\/pre>/i.test(text);

                        if (containsPreHljs) {
                            document.execCommand('insertHTML', false, '<p>' + text + '</p>');
                        } else {
                            text = text.replace(/<([a-z]+)([^>]*)>/gi, function (match, p1, p2) {
                                return '<code>&lt;' + p1 + p2 + '&gt;</code>';
                            });
                            text = text.replace(/-([^\s]+?)-/g, function (match, p1) {
                                return '<span id="codeselector">' + p1 + '</span>';
                            });
                            text = text.replace(/-([^-]+?)-/g, function (match, p1) {
                                return '<span id="codeselector">' + p1 + '</span>';
                            });
                            document.execCommand('insertHTML', false, '<p>' + text + '</p>');
                        }
                    },
                    onInit: function () {
                        let currentContent = @this.get(wiremodel);
                        if (!currentContent) {
                            currentContent = '<p>Teks</p>'; // Paragraf default kosong
                        }
                        @this.set(wiremodel, currentContent)
                        $(selector).summernote('code', currentContent);
                    }
                },
                icons: {
                    grid: "bi bi-grid-3x2"
                },
            });
        }
    </script>
    <script>
        function sendFile(file, editor, welEditable) {
            token = '{{ csrf_token() }}';
            data = new FormData();
            data.append('file', file);
            data.append('_token', token);
            $('#loading-image-summernote').show();
            $(editor).summernote('disable');
            $.ajax({
                data: data,
                type: 'POST',
                url: '{{ url("/summernote/file/upload") }}',
                cache: false,
                contentType: false,
                processData: false,
                success: function (url) {
                    console.log(url);
                    if (url['status'] == 'success') {
                        $(editor).summernote('enable');
                        $('#loading-image-summernote').hide();
                        $(editor).summernote('editor.saveRange');
                        $(editor).summernote('editor.restoreRange');
                        $(editor).summernote('editor.focus');
                        $(editor).summernote('editor.insertImage', url['image_url']);
                    }
                    $('img').addClass('img-fluid');
                },
                error: function (data) {
                    console.log(data);
                    $(editor).summernote('enable');
                    $('#loading-image-summernote').hide();
                },
            });
        }

        function deleteFile(target) {
            token = '{{ csrf_token() }}';
            data = new FormData();
            data.append('target', target);
            data.append('_token', token);
            $('#loading-image-summernote').show();
            $('.summernote').summernote('disable');
            $.ajax({
                data: data,
                type: 'POST',
                url: '{{ url("/summernote/file/delete") }}',
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    console.log(result);
                    if (result['status'] == 'success') {
                        $('.summernote').summernote('enable');
                        $('#loading-image-summernote').hide();
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Gambar berhasil dihapus.',
                            icon: 'success',
                        });
                    }
                },
                error: function (data) {
                    console.log(data);
                    $('.summernote').summernote('enable');
                    $('#loading-image-summernote').hide();
                },
            });
        }
    </script>
@endpush
