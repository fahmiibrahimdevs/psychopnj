<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Jenis Anggaran</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Daftar Jenis Anggaran</h3>
                <div class="card-body">
                    <div class="search-column">
                        <p>Search:</p>
                        <input type="search" wire:model.live.debounce.750ms="searchTerm" id="search-data" placeholder="Cari jenis anggaran..." class="form-control" />
                    </div>
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="5%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Nama Jenis</th>
                                    <th class="text-center tw-whitespace-nowrap" width="15%"><i class="fas fa-cog"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = 1;
                                @endphp

                                @forelse ($data as $kategori => $jenisGroup)
                                    <!-- Header Kategori -->
                                    <tr>
                                        <td class="tw-font-semibold tw-tracking-wider tw-bg-gray-100" colspan="3">{{ strtoupper($kategori) }}</td>
                                    </tr>
                                    @foreach ($jenisGroup as $row)
                                        <tr class="text-center">
                                            <td>{{ $counter++ }}</td>
                                            <td class="text-left">{{ $row->nama_jenis }}</td>
                                            <td class="tw-whitespace-nowrap">
                                                @if ($this->can("jenis_anggaran.edit"))
                                                    <button wire:click.prevent="edit({{ $row->id }})" class="btn btn-primary" data-toggle="modal" data-target="#formDataModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if ($this->can("jenis_anggaran.delete"))
                                                    <button wire:click.prevent="deleteConfirm({{ $row->id }})" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data jenis anggaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($this->can("jenis_anggaran.create"))
            <button wire:click.prevent="isEditingMode(false)" class="btn-modal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#formDataModal">
                <i class="far fa-plus"></i>
            </button>
        @endif
    </section>

    <!-- Modal Form -->
    <div class="modal fade" wire:ignore.self id="formDataModal" aria-labelledby="formDataModalLabel" aria-hidden="true">
        <div class="modal-dialog tw-w-full tw-m-0 sm:tw-w-auto sm:tw-m-[1.75rem_auto]">
            <div class="modal-content tw-rounded-none lg:tw-rounded-md">
                <div class="modal-header tw-px-4 lg:tw-px-6">
                    <h5 class="modal-title" id="formDataModalLabel">{{ $isEditing ? "Edit Jenis Anggaran" : "Tambah Jenis Anggaran" }}</h5>
                    <button type="button" wire:click="cancel()" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body tw-px-4 lg:tw-px-6">
                        <div class="form-group">
                            <label for="nama_kategori">Kategori</label>
                            <select wire:model="nama_kategori" id="nama_kategori" class="form-control select2">
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                            @error("nama_kategori")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama_jenis">Nama Jenis</label>
                            <input type="text" wire:model="nama_jenis" id="nama_jenis" class="form-control" placeholder="Contoh: Saldo Awal, Iuran Kas, Departemen, Project" />
                            @error("nama_jenis")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <small class="form-text text-muted">
                                <strong>Catatan:</strong>
                                Untuk jenis yang memerlukan dept/project, gunakan nilai:
                                <code>Departemen</code>
                                atau
                                <code>Project</code>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="cancel()" class="btn btn-secondary tw-bg-gray-300" data-dismiss="modal">Close</button>
                        <button type="submit" wire:click.prevent="{{ $isEditing ? "update()" : "store()" }}" wire:loading.attr="disabled" class="btn btn-primary tw-bg-blue-500">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push("general-css")
    <link href="{{ asset("assets/midragon/select2/select2.min.css") }}" rel="stylesheet" />
@endpush

@push("js-libraries")
    <script src="{{ asset("assets/midragon/select2/select2.full.min.js") }}"></script>
@endpush

@push("scripts")
    <script>
        $('#formDataModal').on('shown.bs.modal', function () {
            $('.select2').select2({
                dropdownParent: $('#formDataModal')
            });

            $('.select2').on('change', function(e) {
                var id = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(id, data);
            });
        });

        window.addEventListener('initSelect2', event => {
            $(document).ready(function() {
                $('.select2').select2({
                    dropdownParent: $('#formDataModal')
                });

                $('.select2').on('change', function(e) {
                    var id = $(this).attr('id');
                    var data = $(this).select2("val");
                    @this.set(id, data);
                });
            });
        })
    </script>
@endpush
