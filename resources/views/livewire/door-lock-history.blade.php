<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Riwayat Door Lock</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <h3>Tabel Riwayat Door Lock</h3>
                <div class="card-body">
                    <div class="show-entries">
                        <p class="show-entries-show">Show</p>
                        <select wire:model.live="perPage" id="length-data">
                            <option value="10">10</option>
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
                        <input type="search" wire:model.live.debounce.750ms="search" id="search-data" placeholder="Search here..." class="form-control" />
                    </div>
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead class="tw-sticky tw-top-0">
                                <tr class="tw-text-gray-700">
                                    <th width="6%" class="text-center tw-whitespace-nowrap">No</th>
                                    <th class="tw-whitespace-nowrap">Waktu Akses</th>
                                    <th class="tw-whitespace-nowrap">Nama Anggota</th>
                                    <th class="tw-whitespace-nowrap">RFID Card</th>
                                    <th class="tw-whitespace-nowrap">Status</th>
                                    <th class="tw-whitespace-nowrap">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $index => $log)
                                    <tr class="text-center">
                                        <td>{{ $logs->firstItem() + $index }}</td>
                                        <td class="text-left">
                                            {{ $log->waktu_akses->format("d M Y, H:i:s") }}
                                            <div class="small text-muted">{{ $log->waktu_akses->diffForHumans() }}</div>
                                        </td>
                                        <td class="text-left">
                                            @if ($log->anggota)
                                                <div class="d-flex align-items-center">
                                                    @if ($log->anggota->foto)
                                                        <img src="{{ Storage::url($log->anggota->foto) }}" class="rounded-circle mr-2" width="30" height="30" style="object-fit: cover" />
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-2 text-secondary fw-bold" style="width: 30px; height: 30px; border: 1px solid #dee2e6">
                                                            {{ substr($log->anggota->nama_lengkap, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div>{{ $log->anggota->nama_lengkap }}</div>
                                                        <small class="text-muted">{{ $log->anggota->nama_jabatan }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge badge-secondary">Unknown User</span>
                                            @endif
                                        </td>
                                        <td class="text-left">
                                            <code class="bg-light text-dark px-2 py-1 rounded border">{{ $log->rfid_card }}</code>
                                        </td>
                                        <td class="text-left">
                                            @if ($log->status_akses == "granted")
                                                <span class="badge badge-success">GRANTED</span>
                                            @else
                                                <span class="badge badge-danger">DENIED</span>
                                            @endif
                                        </td>
                                        <td class="text-left">{{ $log->keterangan ?? "-" }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No data available in the table</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5 px-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
