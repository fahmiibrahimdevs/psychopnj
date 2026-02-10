<div>
    <section class="section custom-section">
        <div class="section-header tw-block">
            <div class="tw-flex">
                <h1 class="tw-text-lg">Status Anggota Ujian</h1>
                <div class="ml-auto">
                    <button wire:click.prevent="refresh()" class="btn btn-info mr-2" @if ($id_pertemuan == '0') disabled @endif>
                        <i class="fas fa-sync mr-1"></i>
                        Refresh
                    </button>
                    <button wire:click.prevent="terapkanAksiConfirm()" class="btn btn-primary" @if (!((int) $id_pertemuan > 0 && (!empty($paksa_selesai) || !empty($ulang)))) disabled @endif>
                        <i class="fas fa-check mr-1"></i>
                        Terapkan Aksi
                    </button>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body px-4 py-0">
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="id_pertemuan">Pertemuan</label>
                                <select wire:model="id_pertemuan" id="id_pertemuan" class="form-control select2" style="width: 100%">
                                    <option value="0">-- Pilih Pertemuan --</option>
                                    @foreach ($pertemuans as $program => $items)
                                        <optgroup label="{{ $program }}">
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">Pertemuan {{ $item->pertemuan_ke }}: {{ $item->judul_pertemuan }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body py-0">
                    <div class="tw-overflow-x-auto no-scrollbar">
                        <table class="tw-w-full tw-min-w-full tw-table-auto">
                            <thead>
                                <tr class="text-center">
                                    <th rowspan="2">No</th>
                                    <th rowspan="2" class="text-left">Nama Anggota</th>
                                    <th rowspan="2">Jurusan/Kelas</th>
                                    <th colspan="3">Status</th>
                                    <th colspan="2">Aksi</th>
                                </tr>
                                <tr class="text-center">
                                    <th>Mulai</th>
                                    <th>Stat</th>
                                    <th>Durasi</th>
                                    <th>
                                        Paksa Selesai
                                        <br />
                                        <input type="checkbox" id="all-paksa-selesai" class="tw-border tw-border-gray-200 tw-rounded-sm tw-p-2 mt-1" />
                                    </th>
                                    <th>
                                        Ulang
                                        <br />
                                        <input type="checkbox" id="all-ulang" class="tw-border tw-border-gray-200 tw-rounded-sm tw-p-2 mt-1" />
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($id_pertemuan != "0")
                                    @forelse ($data as $row)
                                        <tr class="tw-whitespace-nowrap text-center">
                                            <td class="tw-py-3">{{ $loop->index + 1 }}</td>
                                            <td class="tw-py-3 text-left">{{ $row->nama_lengkap }}</td>
                                            <td class="tw-py-3">{{ $row->jurusan_prodi_kelas }}</td>
                                            <td class="tw-py-3">
                                                @if ($row->status == "0")
                                                    {{ $row->mulai ?? "--:--" }}
                                                @else
                                                    {{ $row->mulai }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($row->status == "0")
                                                    <span class="tw-bg-blue-50 tw-text-xs tw-text-blue-600 tw-px-2.5 tw-py-1.5 tw-rounded-lg tw-font-semibold">Proses</span>
                                                @else
                                                    <span class="tw-bg-green-50 tw-text-xs tw-text-green-600 tw-px-2.5 tw-py-1.5 tw-rounded-lg tw-font-semibold">Selesai</span>
                                                @endif
                                            </td>
                                            <td class="tw-py-3">
                                                {{ $row->status == "1" ? $row->lama_ujian : "--" }}
                                            </td>
                                            <td class="tw-py-3" wire:key="{{ rand() }}">
                                                <input type="checkbox" @if ($row->status == '1') disabled @else wire:model.blur="paksa_selesai" value="{{ $row->id_anggota }}" @endif class="tw-border tw-border-gray-200 tw-rounded-sm tw-p-2 disabled:tw-bg-gray-100 paksa-selesai" />
                                            </td>
                                            <td class="tw-py-3" wire:key="{{ rand() }}">
                                                <input type="checkbox" @if ($row->status == '0') disabled @else wire:model.blur="ulang" value="{{ $row->id_anggota }}" @endif class="tw-border tw-border-gray-200 tw-rounded-sm tw-p-2 disabled:tw-bg-gray-100 ulang" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center py-3" colspan="8">Belum ada anggota yang mengerjakan</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td class="text-center py-3" colspan="8">Pilih pertemuan terlebih dahulu</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push("general-css")
    <link href="{{ asset("assets/midragon/select2/select2.min.css") }}" rel="stylesheet" />
@endpush

@push("js-libraries")
    <script src="{{ asset("assets/midragon/select2/select2.full.min.js") }}"></script>
@endpush

@push("scripts")
    <script>
        window.addEventListener('swal:confirm:aksi', (event) => {
            Swal.fire({
                title: event.detail[0].message,
                text: event.detail[0].text,
                icon: event.detail[0].type,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('terapkanAksi');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            handleCheckboxChange('#all-paksa-selesai', 'input[type="checkbox"].paksa-selesai', 'paksa_selesai');
            handleCheckboxChange('#all-ulang', 'input[type="checkbox"].ulang', 'ulang');

            function handleCheckboxChange(allSelector, itemClass, livewireKey) {
                $(allSelector).change(function() {
                    var isChecked = $(this).is(':checked');
                    $(itemClass + ':not(:disabled)').prop('checked', isChecked);

                    var checkedValues = [];
                    $(itemClass + ':not(:disabled)').each(function() {
                        if ($(this).is(':checked')) {
                            checkedValues.push($(this).val());
                        }
                    });
                    @this.set(livewireKey, checkedValues);
                });
            }
        });
    </script>
    <script>
        window.addEventListener('initSelect2', event => {
            $(document).ready(function() {
                $('.select2').select2();

                $('.select2').on('change', function(e) {
                    var id = $(this).attr('id');
                    var data = $(this).select2("val");
                    @this.set(id, data);
                });
            });
        })
    </script>
@endpush
