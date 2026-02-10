<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Administrasi Surat & Dokumen</h1>
        </div>

        <div class="section-body tw-mt-3">
            <!-- Tabs -->
            <ul class="nav nav-pills" id="myTab3" role="tablist" style="display: flex; flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; -ms-overflow-style: none; scrollbar-width: none">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "surat-masuk" ? "active" : "" }} tw-whitespace-nowrap" wire:click.prevent="switchTab('surat-masuk')" href="#" role="tab">
                        <i class="fas fa-inbox tw-mr-2"></i>
                        Surat Masuk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === "surat-keluar" ? "active" : "" }} tw-whitespace-nowrap" wire:click.prevent="switchTab('surat-keluar')" href="#" role="tab">
                        <i class="fas fa-paper-plane tw-mr-2"></i>
                        Surat Keluar
                    </a>
                </li>
                @foreach ($kategoriList as $kategori)
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === $kategori->slug ? "active" : "" }} tw-whitespace-nowrap" wire:click.prevent="switchTab('{{ $kategori->slug }}')" href="#" role="tab">
                            <i class="fas fa-folder tw-mr-2"></i>
                            {{ $kategori->nama_kategori }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <style>
                /* Hide scrollbar */
                #myTab3::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <div class="tab-content" id="myTabContent2">
                <div class="card tw-mt-5">
                    <div class="card-header tw-flex tw-justify-between tw-items-center">
                        <h3 class="font-bagus">
                            @if ($activeTab === "surat-masuk")
                                Tabel Surat Masuk
                            @elseif ($activeTab === "surat-keluar")
                                Tabel Surat Keluar
                            @else
                                Tabel {{ $currentKategori ? $currentKategori->nama_kategori : "Dokumen" }}
                            @endif
                        </h3>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#formSuratModal">
                            <i class="fas fa-plus"></i>
                            Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="show-entries">
                            <p class="show-entries-show">Show</p>
                            <select wire:model.live="lengthData" id="length-data">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <p class="show-entries-entries">Entries</p>
                        </div>
                        <div class="search-column">
                            <p>Search:</p>
                            <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Search here..." class="form-control" />
                        </div>

                        <div class="table-responsive">
                            <table class="tw-table-auto tw-w-full">
                                <thead class="tw-sticky tw-top-0">
                                    <tr class="tw-text-gray-700">
                                        <th width="5%" class="text-center tw-whitespace-nowrap">No</th>

                                        @if ($activeTab === "surat-masuk")
                                            <th class="tw-whitespace-nowrap">Nomor Surat</th>
                                            <th class="tw-whitespace-nowrap">Perihal</th>
                                            <th class="tw-whitespace-nowrap">Pengirim</th>
                                            <th class="tw-whitespace-nowrap">Ditujukan Kepada</th>
                                            <th class="tw-whitespace-nowrap">Tanggal Masuk</th>
                                        @elseif ($activeTab === "surat-keluar")
                                            <th class="tw-whitespace-nowrap">Nomor Surat</th>
                                            <th class="tw-whitespace-nowrap">Perihal</th>
                                            <th class="tw-whitespace-nowrap">Penerima</th>
                                            <th class="tw-whitespace-nowrap">Tanggal Keluar</th>
                                            <th class="text-center tw-whitespace-nowrap">Status</th>
                                        @else
                                            <th class="tw-whitespace-nowrap">Nama Dokumen</th>
                                            <th class="tw-whitespace-nowrap">Nomor Dokumen</th>
                                            <th class="tw-whitespace-nowrap">Deskripsi</th>
                                            <th class="tw-whitespace-nowrap">Tanggal</th>
                                        @endif

                                        <th class="text-center tw-whitespace-nowrap"><i class="fas fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $lastMonth = null;
                                    @endphp

                                    @forelse ($data as $index => $row)
                                        @php
                                            $date =
                                                $activeTab === "surat-masuk"
                                                    ? $row->tanggal_masuk
                                                    : ($activeTab === "surat-keluar"
                                                        ? $row->tanggal_keluar
                                                        : $row->tanggal);

                                            // Format: Bulan Mei 2026
                                            $currentMonth = "Bulan " . \Carbon\Carbon::parse($date)->translatedFormat("F Y");
                                        @endphp

                                        @if ($lastMonth !== $currentMonth)
                                            <tr class="tw-bg-gray-100">
                                                <td colspan="7" class="text-left tw-tracking-wider font-weight-bold tw-px-4 tw-py-2 tw-text-gray-700">
                                                    {{ $currentMonth }}
                                                </td>
                                            </tr>
                                            @php
                                                $lastMonth = $currentMonth;
                                            @endphp
                                        @endif

                                        <tr class="text-center hover:tw-bg-gray-50">
                                            <td>{{ $data->firstItem() + $index }}</td>

                                            @if ($activeTab === "surat-masuk")
                                                <td class="text-left filter-link">{{ $row->nomor_surat }}</td>
                                                <td class="text-left">{{ $row->perihal }}</td>
                                                <td class="text-left">{{ $row->pengirim }}</td>
                                                <td class="text-left">{{ $row->ditujukan_kepada }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_masuk)->format("d M Y") }}</td>
                                            @elseif ($activeTab === "surat-keluar")
                                                <td class="text-left filter-link">{{ $row->nomor_surat }}</td>
                                                <td class="text-left">{{ $row->perihal }}</td>
                                                <td class="text-left">{{ $row->penerima }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_keluar)->format("d M Y") }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $badges = [
                                                            "Draft" => "badge-secondary",
                                                            "Pending" => "badge-warning",
                                                            "Disetujui" => "badge-info",
                                                            "Terkirim" => "badge-success",
                                                            "Ditolak" => "badge-danger",
                                                        ];
                                                    @endphp

                                                    <div class="badge {{ $badges[$row->status] ?? "badge-light" }}">
                                                        {{ $row->status }}
                                                    </div>
                                                </td>
                                            @else
                                                <td class="text-left">{{ $row->nama_dokumen }}</td>
                                                <td class="text-left">{{ $row->nomor_dokumen ?? "-" }}</td>
                                                <td class="text-left">{{ Str::limit($row->deskripsi, 50) ?? "-" }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal)->format("d M Y") }}</td>
                                            @endif

                                            <td class="tw-whitespace-nowrap">
                                                @if ($row->files->count() > 0)
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-eye"></i>
                                                            ({{ $row->files->count() }})
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @foreach ($row->files as $file)
                                                                <a class="dropdown-item" href="javascript:void(0)" onclick="previewFile('{{ asset("storage/" . $file->file_path) }}', '{{ Storage::mimeType("public/" . $file->file_path) }}')">
                                                                    {{ Str::limit($file->file_name, 20) }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <button class="btn btn-secondary" disabled><i class="fas fa-eye-slash"></i></button>
                                                @endif

                                                <button wire:click="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formSuratModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button wire:click="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No data availabe in the table</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="tw-mt-4">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Form -->
    <div class="modal fade" id="formSuratModal" tabindex="-1" role="dialog" aria-labelledby="formSuratModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formSuratModalLabel">
                        {{ $isEditing ? "Edit" : "Tambah" }}
                        @if ($activeTab === "surat-masuk")
                            Surat Masuk
                        @elseif ($activeTab === "surat-keluar")
                            Surat Keluar
                        @else
                            {{ $currentKategori ? $currentKategori->nama_kategori : "Dokumen" }}
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" wire:click="cancel" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{ $isEditing ? "update" : "store" }}">
                        @if ($activeTab === "surat-masuk")
                            <!-- Form Surat Masuk -->
                            <div class="form-group">
                                <label>Nomor Surat</label>
                                <input type="text" wire:model="sm_nomor_surat" class="form-control @error("sm_nomor_surat") is-invalid @enderror" />
                                @error("sm_nomor_surat")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Perihal Surat</label>
                                <input type="text" wire:model="sm_perihal" class="form-control @error("sm_perihal") is-invalid @enderror" />
                                @error("sm_perihal")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Ditujukan Kepada</label>
                                <input type="text" wire:model="sm_ditujukan_kepada" class="form-control @error("sm_ditujukan_kepada") is-invalid @enderror" />
                                @error("sm_ditujukan_kepada")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Pengirim</label>
                                <input type="text" wire:model="sm_pengirim" class="form-control @error("sm_pengirim") is-invalid @enderror" />
                                @error("sm_pengirim")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="date" wire:model="sm_tanggal_masuk" class="form-control @error("sm_tanggal_masuk") is-invalid @enderror" />
                                @error("sm_tanggal_masuk")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Upload Files (Scan Surat)</label>
                                <div class="custom-file">
                                    <input type="file" wire:model="sm_files" class="custom-file-input" id="smFiles" multiple />
                                    <label class="custom-file-label" for="smFiles">
                                        {{ $sm_files ? count($sm_files) . " File dipilih" : "Pilih Banyak File" }}
                                    </label>
                                </div>
                                @error("sm_files.*")
                                    <span class="text-danger small d-block">{{ $message }}</span>
                                @enderror

                                <div wire:loading wire:target="sm_files" class="tw-mt-2 tw-text-blue-500">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Mengupload...
                                </div>
                            </div>
                        @elseif ($activeTab === "surat-keluar")
                            <!-- Form Surat Keluar -->
                            <div class="form-group">
                                <label>Nomor Surat</label>
                                <input type="text" wire:model="sk_nomor_surat" class="form-control @error("sk_nomor_surat") is-invalid @enderror" />
                                @error("sk_nomor_surat")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Perihal Surat</label>
                                <input type="text" wire:model="sk_perihal" class="form-control @error("sk_perihal") is-invalid @enderror" />
                                @error("sk_perihal")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Penerima</label>
                                <input type="text" wire:model="sk_penerima" class="form-control @error("sk_penerima") is-invalid @enderror" />
                                @error("sk_penerima")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Tanggal Keluar</label>
                                <input type="date" wire:model="sk_tanggal_keluar" class="form-control @error("sk_tanggal_keluar") is-invalid @enderror" />
                                @error("sk_tanggal_keluar")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select wire:model="sk_status" class="form-control @error("sk_status") is-invalid @enderror">
                                    <option value="Draft">Draft</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Terkirim">Terkirim</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                                @error("sk_status")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Upload Files (Arsip Surat)</label>
                                <div class="custom-file">
                                    <input type="file" wire:model="sk_files" class="custom-file-input" id="skFiles" multiple />
                                    <label class="custom-file-label" for="skFiles">
                                        {{ $sk_files ? count($sk_files) . " File dipilih" : "Pilih Banyak File" }}
                                    </label>
                                </div>
                                @error("sk_files.*")
                                    <span class="text-danger small d-block">{{ $message }}</span>
                                @enderror

                                <div wire:loading wire:target="sk_files" class="tw-mt-2 tw-text-blue-500">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Mengupload...
                                </div>
                            </div>
                        @else
                            <!-- Form Dokumen Organisasi (Dynamic) -->
                            <div class="form-group">
                                <label>Nama Dokumen</label>
                                <input type="text" wire:model="do_nama" class="form-control @error("do_nama") is-invalid @enderror" placeholder="Contoh: Proposal Kegiatan X" />
                                @error("do_nama")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nomor Dokumen (Opsional)</label>
                                <input type="text" wire:model="do_nomor" class="form-control @error("do_nomor") is-invalid @enderror" placeholder="Contoh: 001/PROPOSAL/2026" />
                                @error("do_nomor")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Deskripsi/Keterangan</label>
                                <textarea wire:model="do_deskripsi" class="form-control @error("do_deskripsi") is-invalid @enderror" rows="3"></textarea>
                                @error("do_deskripsi")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Tanggal Dokumen</label>
                                <input type="date" wire:model="do_tanggal" class="form-control @error("do_tanggal") is-invalid @enderror" />
                                @error("do_tanggal")
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Upload Files</label>
                                <div class="custom-file">
                                    <input type="file" wire:model="do_files" class="custom-file-input" id="doFiles" multiple />
                                    <label class="custom-file-label" for="doFiles">
                                        {{ $do_files ? count($do_files) . " File dipilih" : "Pilih Banyak File" }}
                                    </label>
                                </div>
                                @error("do_files.*")
                                    <span class="text-danger small d-block">{{ $message }}</span>
                                @enderror

                                <div wire:loading wire:target="do_files" class="tw-mt-2 tw-text-blue-500">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Mengupload...
                                </div>
                            </div>
                        @endif

                        <!-- Existing Files Display (Global) -->
                        @if ($isEditing && count($existing_files) > 0)
                            <div class="form-group border rounded p-3 bg-light mt-3">
                                <label class="font-weight-bold">File Tersimpan:</label>
                                <ul class="list-group list-group-flush">
                                    @foreach ($existing_files as $file)
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                                            <a href="javascript:void(0)" onclick="previewFile('{{ asset("storage/" . $file->file_path) }}', '{{ Storage::mimeType("public/" . $file->file_path) }}')">
                                                <i class="fas fa-file mr-2"></i>
                                                {{ $file->file_name }}
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger rounded-circle" wire:click="deleteFileConfirm({{ $file->id }})" title="Hapus File">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="cancel">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="{{ $isEditing ? "update" : "store" }}">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview File</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" id="filePreviewContent" style="min-height: 400px; display: flex; align-items: center; justify-content: center; background: #f8f9fa">
                    <!-- Content injected by JS -->
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            function previewFile(fileUrl, fileType) {
                let content = '';
                const isImage = fileUrl.match(/\.(jpeg|jpg|gif|png)$/) != null || fileType.includes('image');
                const isPdf = fileUrl.match(/\.pdf$/) != null || fileType === 'application/pdf';

                if (isImage) {
                    content = `<img src="${fileUrl}" class="img-fluid" style="max-height: 80vh;" />`;
                } else if (isPdf) {
                    content = `<iframe src="${fileUrl}" style="width: 100%; height: 80vh;" frameborder="0"></iframe>`;
                } else {
                    content = `<div class="text-center p-5">
                                <i class="fas fa-file fa-3x mb-3"></i>
                                <p>Preview tidak tersedia untuk tipe file ini.</p>
                                <a href="${fileUrl}" class="btn btn-primary" target="_blank" download>
                                    <i class="fas fa-download"></i> Download File
                                </a>
                               </div>`;
                }

                document.getElementById('filePreviewContent').innerHTML = content;
                $('#filePreviewModal').modal('show');
            }

            window.addEventListener('closeModal', event => {
                $('#formSuratModal').modal('hide');
            });

            window.addEventListener('alert', event => {
                Swal.fire({
                    icon: event.detail[0].type,
                    title: event.detail[0].type === 'success' ? 'Berhasil!' : 'Gagal!',
                    text: event.detail[0].message,
                    timer: 3000,
                    showConfirmButton: false
                });
            });

            window.addEventListener('showDeleteConfirmation', event => {
                 // Handle array or single value
                let idToDelete = Array.isArray(event.detail) ? event.detail[0] : event.detail;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('delete', idToDelete);
                    }
                });
            });

            window.addEventListener('swal:confirm-file-delete', event => {
                 // Livewire 3 returns params inside detail array
                let eventData = Array.isArray(event.detail) ? event.detail[0] : event.detail;

                Swal.fire({
                    title: eventData.message,
                    text: eventData.text,
                    icon: eventData.type,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteFile', eventData.id);
                    }
                });
            });
        </script>
    @endpush
</div>
