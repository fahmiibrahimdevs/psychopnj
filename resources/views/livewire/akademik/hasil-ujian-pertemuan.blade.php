<div>
    <section class="section custom-section">
        <div class="section-header tw-block">
            <div class="tw-flex">
                <h1 class="tw-text-lg">Hasil Ujian Pertemuan</h1>
                @if ($this->can("ujian.view_hasil"))
                    <div class="ml-auto">
                        <button wire:click.prevent="refresh()" class="btn btn-info mr-2" @if ($id_pertemuan == '0') disabled @endif>
                            <i class="fas fa-sync mr-1"></i>
                            Refresh
                        </button>
                        @if ($this->can("ujian.koreksi"))
                            @if (! empty($hasil_ujian) && count($hasil_ujian) > 0)
                                <button wire:click.prevent="tandaiSemua()" class="btn btn-success mr-2">
                                    <i class="fas fa-check-double mr-1"></i>
                                    Tandai Semua Dikoreksi
                                </button>
                                <button wire:click.prevent="inputNilai()" class="btn btn-warning" data-toggle="modal" data-target="#inputNilaiModal">
                                    <i class="fas fa-edit mr-1"></i>
                                    Input Nilai
                                </button>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body px-4 py-0">
                    <div class="row mt-4">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="id_pertemuan">Pertemuan</label>
                                <select wire:model="id_pertemuan" id="id_pertemuan" class="form-control select2">
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
                        @if ($pertemuanInfo)
                            <div class="col-lg-6">
                                <div class="tw-bg-blue-50 tw-p-3 tw-rounded-lg">
                                    <p class="tw-mb-1">
                                        <strong>Pemateri:</strong>
                                        {{ $pertemuanInfo->nama_pemateri ?? "-" }}
                                    </p>
                                    <p class="tw-mb-0">
                                        <strong>Total Soal:</strong>
                                        {{ $pertemuanInfo->total_soal ?? 0 }} soal
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body py-0">
                    <div class="tw-overflow-x-auto no-scrollbar">
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
                                @if ($id_pertemuan != "0")
                                    @forelse ($hasil_ujian as $row)
                                        <tr class="tw-whitespace-nowrap text-center">
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td class="text-left">
                                                {{ $row->nama_lengkap }}
                                                <br />
                                                <small class="tw-text-gray-500">{{ $row->jurusan_prodi_kelas }}</small>
                                            </td>
                                            <td>{{ $row->mulai ?? "--:--" }}</td>
                                            <td>{{ $row->lama_ujian ?: "--" }}</td>
                                            <td>{{ $row->pg_benar ?? 0 }}</td>
                                            <td>{{ $row->pg_salah ?? 0 }}</td>
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
                                                    <span class="tw-bg-blue-50 tw-text-xs tw-text-blue-600 tw-px-2 tw-py-1 tw-rounded-lg tw-font-semibold">Proses</span>
                                                @elseif ($row->dikoreksi == "1")
                                                    <a target="_blank" href="{{ url("/hasil-ujian-pertemuan/koreksi/" . $row->id_pertemuan . "/" . $row->id_anggota) }}" class="tw-no-underline tw-bg-green-100 tw-text-xs tw-tracking-wider tw-text-green-600 tw-px-2.5 tw-py-1.5 tw-rounded-md tw-font-semibold tw-whitespace-nowrap">
                                                        <i class="fas fa-check tw-text-xs tw-text-green-600"></i>
                                                        KOREKSI
                                                    </a>
                                                @else
                                                    <a target="_blank" href="{{ url("/hasil-ujian-pertemuan/koreksi/" . $row->id_pertemuan . "/" . $row->id_anggota) }}" class="tw-no-underline tw-bg-blue-100 tw-text-xs tw-tracking-wider tw-text-blue-600 tw-px-2.5 tw-py-1.5 tw-rounded-md tw-font-semibold tw-whitespace-nowrap">
                                                        <i class="fas fa-exclamation-triangle tw-text-xs tw-text-blue-600"></i>
                                                        KOREKSI
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center py-3" colspan="13">Belum ada anggota yang mengerjakan</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td class="text-center py-3" colspan="13">Pilih pertemuan terlebih dahulu</td>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title tw-font-bold">Input Nilai Manual (PK, JO, IS, ES)</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th class="text-left">Nama</th>
                                    <th>N. PG</th>
                                    <th>N. PK</th>
                                    <th>N. JO</th>
                                    <th>N. IS</th>
                                    <th>N. ES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilais as $index => $nilai)
                                    <tr class="text-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left">{{ $nilai->nama_lengkap }}</td>
                                        <td>{{ number_format($nilai->nilai_pg, 1) }}</td>
                                        <td>
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_pk" class="form-control form-control-sm text-center" style="width: 80px" />
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_jo" class="form-control form-control-sm text-center" style="width: 80px" />
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_is" class="form-control form-control-sm text-center" style="width: 80px" />
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" wire:model="inputan.{{ $nilai->id }}.nilai_es" class="form-control form-control-sm text-center" style="width: 80px" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button wire:click.prevent="updateNilai()" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
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
