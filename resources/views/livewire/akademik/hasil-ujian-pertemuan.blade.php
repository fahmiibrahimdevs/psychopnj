<div>
    <section class="section custom-section">
        <div class="section-header tw-block">
            <div class="tw-flex tw-items-center tw-justify-between">
                <h1 class="tw-text-lg tw-font-bold tw-text-gray-800 tw-mb-0">
                    <i class="fas fa-chart-bar tw-mr-2 tw-text-blue-500"></i>
                    Hasil Ujian Pertemuan
                </h1>
                @if ($this->can("ujian.view_hasil"))
                    <div class="tw-flex tw-gap-2">
                        <button wire:click.prevent="refresh()" class="tw-px-4 tw-py-2 tw-bg-cyan-500 hover:tw-bg-cyan-600 tw-text-white tw-rounded-lg tw-transition-colors tw-duration-200 tw-flex tw-items-center tw-gap-2 tw-font-medium" @if ($id_part == '0') disabled @endif>
                            <i class="fas fa-sync"></i>
                            <span>Refresh</span>
                        </button>
                        @if ($this->can("ujian.koreksi"))
                            @if (! empty($hasil_ujian) && count($hasil_ujian) > 0)
                                <button wire:click.prevent="tandaiSemua()" class="tw-px-4 tw-py-2 tw-bg-green-500 hover:tw-bg-green-600 tw-text-white tw-rounded-lg tw-transition-colors tw-duration-200 tw-flex tw-items-center tw-gap-2 tw-font-medium">
                                    <i class="fas fa-check-double"></i>
                                    <span>Tandai Semua Dikoreksi</span>
                                </button>
                                <button wire:click.prevent="inputNilai()" class="tw-px-4 tw-py-2 tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-rounded-lg tw-transition-colors tw-duration-200 tw-flex tw-items-center tw-gap-2 tw-font-medium" data-toggle="modal" data-target="#inputNilaiModal">
                                    <i class="fas fa-edit"></i>
                                    <span>Input Nilai</span>
                                </button>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="section-body">
            <div class="tw-bg-white tw-rounded-xl tw-shadow-md tw-border-0 tw-mb-6">
                <div class="tw-p-6">
                    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-6">
                        <div>
                            <label for="id_part" class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-2">
                                <i class="fas fa-puzzle-piece tw-mr-1 tw-text-blue-500"></i>
                                Pilih Part
                            </label>
                            <select wire:model="id_part" id="id_part" class="form-control select2 tw-border-gray-300 tw-rounded-lg">
                                <option value="0">-- Pilih Part --</option>
                                @foreach ($parts as $program => $items)
                                    <optgroup label="{{ $program }}">
                                        @foreach ($items as $item)
                                            <option value="{{ is_array($item) ? $item["id"] : $item->id }}">
                                                {{ is_array($item) ? $item["display_name"] : $item->display_name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        @if ($partInfo)
                            <div>
                                <label class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700 tw-mb-2">
                                    <i class="fas fa-info-circle tw-mr-1 tw-text-blue-500"></i>
                                    Informasi Part
                                </label>
                                <div class="tw-bg-gradient-to-r tw-from-blue-50 tw-to-indigo-50 tw-p-4 tw-rounded-lg tw-border tw-border-blue-200">
                                    <div class="tw-space-y-2 tw-text-sm">
                                        <div class="tw-flex tw-items-start tw-gap-2">
                                            <span class="tw-font-semibold tw-text-gray-700 tw-min-w-[100px]">Pemateri:</span>
                                            <span class="tw-text-gray-600">{{ $partInfo->nama_pemateri ?? "-" }}</span>
                                        </div>
                                        <div class="tw-flex tw-items-start tw-gap-2">
                                            <span class="tw-font-semibold tw-text-gray-700 tw-min-w-[100px]">Part:</span>
                                            <span class="tw-flex tw-items-center tw-gap-2">
                                                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-bold tw-bg-blue-500 tw-text-white">Part {{ $partInfo->urutan }}</span>
                                                <span class="tw-text-gray-600">{{ $partInfo->nama_part }}</span>
                                            </span>
                                        </div>
                                        <div class="tw-flex tw-items-start tw-gap-2">
                                            <span class="tw-font-semibold tw-text-gray-700 tw-min-w-[100px]">Jumlah Soal:</span>
                                            <span class="tw-text-gray-600">{{ $partInfo->total_soal ?? 0 }} soal</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tw-bg-white tw-rounded-xl tw-shadow-md tw-border-0">
                <div class="tw-p-6">
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-min-w-full tw-table-auto">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th class="text-left">Nama Anggota</th>
                                    <th>Mulai</th>
                                    <th>Durasi</th>
                                    <th>PG B</th>
                                    <th>PG S</th>
                                    <th>N.PG</th>
                                    <th>N.PK</th>
                                    <th>N.JO</th>
                                    <th>N.IS</th>
                                    <th>N.ES</th>
                                    <th>Total</th>
                                    <th>Koreksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($id_part != "0")
                                    @forelse ($hasil_ujian as $row)
                                        <tr class="tw-whitespace-nowrap text-center hover:tw-bg-gray-50 tw-transition-colors">
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td class="text-left">
                                                <div class="tw-font-semibold">{{ $row->nama_lengkap }}</div>
                                                <small class="tw-text-gray-500">{{ $row->jurusan_prodi_kelas }}</small>
                                            </td>
                                            <td>{{ $row->mulai ?? "--:--" }}</td>
                                            <td>{{ $row->lama_ujian ?: "--" }}</td>
                                            <td class="tw-text-green-600 tw-font-medium">{{ $row->pg_benar ?? 0 }}</td>
                                            <td class="tw-text-red-600 tw-font-medium">{{ $row->pg_salah ?? 0 }}</td>
                                            <td>{{ number_format($row->nilai_pg ?? 0, 1) }}</td>
                                            <td>{{ number_format($row->nilai_pk ?? 0, 1) }}</td>
                                            <td>{{ number_format($row->nilai_jo ?? 0, 1) }}</td>
                                            <td>{{ number_format($row->nilai_is ?? 0, 1) }}</td>
                                            <td>{{ number_format($row->nilai_es ?? 0, 1) }}</td>
                                            <td>
                                                <span class="tw-font-bold tw-text-lg">
                                                    {{ number_format($row->total_nilai ?? 0, 1) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($row->status == "0")
                                                    <span class="tw-bg-blue-50 tw-text-xs tw-text-blue-600 tw-px-2 tw-py-1 tw-rounded-lg tw-font-semibold tw-inline-flex tw-items-center tw-gap-1.5">
                                                        <svg class="tw-animate-spin tw-h-3 tw-w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Proses
                                                    </span>
                                                @elseif ($row->dikoreksi == "1")
                                                    <a target="_blank" href="{{ url("/hasil-ujian-pertemuan/koreksi/" . $row->id_part . "/" . $row->id_anggota) }}" class="tw-no-underline tw-bg-green-100 tw-text-xs tw-tracking-wider tw-text-green-600 tw-px-2.5 tw-py-1.5 tw-rounded-md tw-font-semibold tw-whitespace-nowrap">
                                                        <i class="fas fa-check tw-text-xs tw-text-green-600"></i>
                                                        KOREKSI
                                                    </a>
                                                @else
                                                    <a target="_blank" href="{{ url("/hasil-ujian-pertemuan/koreksi/" . $row->id_part . "/" . $row->id_anggota) }}" class="tw-no-underline tw-bg-orange-100 tw-text-xs tw-tracking-wider tw-text-orange-600 tw-px-2.5 tw-py-1.5 tw-rounded-md tw-font-semibold tw-whitespace-nowrap">
                                                        <i class="fas fa-exclamation-triangle tw-text-xs tw-text-orange-600"></i>
                                                        KOREKSI
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center py-4" colspan="13">
                                                <div class="tw-text-gray-500">
                                                    <i class="fas fa-inbox tw-text-4xl tw-mb-2 tw-text-gray-300"></i>
                                                    <p class="tw-text-sm">Belum ada anggota yang mengerjakan</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td class="text-center py-4" colspan="13">
                                            <div class="tw-text-gray-500">
                                                <i class="fas fa-hand-pointer tw-text-4xl tw-mb-2 tw-text-gray-300"></i>
                                                <p class="tw-text-sm">Pilih part terlebih dahulu</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal Input Nilai --}}
    <div class="modal fade" wire:ignore.self id="inputNilaiModal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content tw-rounded-xl tw-border-0 tw-shadow-2xl">
                <div class="modal-header tw-bg-gradient-to-r tw-from-blue-500 tw-to-indigo-500 tw-text-white tw-border-0">
                    <h5 class="modal-title tw-font-bold tw-text-lg">
                        <i class="fas fa-edit tw-mr-2"></i>
                        Input Nilai Manual (PK, JO, IS, ES)
                    </h5>
                    <button type="button" class="close tw-text-white hover:tw-text-gray-200" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body tw-p-6">
                    <div class="tw-overflow-x-auto">
                        <table class="tw-w-full tw-border-collapse">
                            <thead>
                                <tr class="tw-bg-gray-50">
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">No</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">Nama</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">N. PG</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">N. PK</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">N. JO</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">N. IS</th>
                                    <th class="tw-px-4 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-text-gray-700 tw-uppercase tw-border-b-2 tw-border-gray-200">N. ES</th>
                                </tr>
                            </thead>
                            <tbody class="tw-divide-y tw-divide-gray-200">
                                @foreach ($nilais as $index => $nilai)
                                    <tr class="hover:tw-bg-gray-50 tw-transition-colors">
                                        <td class="tw-px-4 tw-py-3 tw-text-center tw-text-sm tw-text-gray-900">{{ $index + 1 }}</td>
                                        <td class="tw-px-4 tw-py-3 tw-text-sm tw-font-medium tw-text-gray-900">{{ $nilai->nama_lengkap }}</td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-lg tw-text-sm tw-font-semibold tw-bg-blue-100 tw-text-blue-800">
                                                {{ number_format($nilai->nilai_pg, 1) }}
                                            </span>
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_pk" class="tw-w-20 tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-center focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-border-blue-500" />
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_jo" class="tw-w-20 tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-center focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-border-blue-500" />
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_is" class="tw-w-20 tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-center focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-border-blue-500" />
                                        </td>
                                        <td class="tw-px-4 tw-py-3 tw-text-center">
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_es" class="tw-w-20 tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-lg tw-text-center focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-border-blue-500" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer tw-bg-gray-50 tw-border-0">
                    <button type="button" class="tw-px-4 tw-py-2 tw-bg-gray-500 hover:tw-bg-gray-600 tw-text-white tw-rounded-lg tw-transition-colors tw-font-medium" data-dismiss="modal">
                        <i class="fas fa-times tw-mr-1"></i>
                        Batal
                    </button>
                    <button wire:click.prevent="updateNilai()" class="tw-px-4 tw-py-2 tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-rounded-lg tw-transition-colors tw-font-medium">
                        <i class="fas fa-save tw-mr-1"></i>
                        Simpan Nilai
                    </button>
                </div>
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
