<div>
    <section class="section custom-section">
        <div class="section-header">
            <h1>Iuran Kas Saya</h1>
        </div>

        <div class="section-body">
            {{-- Summary Cards --}}
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card tw-border-t-4 tw-border-green-500">
                        <div class="card-body tw-text-center tw-py-4">
                            <h5 class="tw-text-3xl tw-font-bold tw-text-green-600">{{ $totalBayar }}</h5>
                            <p class="tw-text-sm tw-text-gray-500 tw-mb-0">Sudah Bayar</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card tw-border-t-4 tw-border-red-500">
                        <div class="card-body tw-text-center tw-py-4">
                            <h5 class="tw-text-3xl tw-font-bold tw-text-red-600">{{ $totalBelum }}</h5>
                            <p class="tw-text-sm tw-text-gray-500 tw-mb-0">Belum Bayar</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card tw-border-t-4 tw-border-blue-500">
                        <div class="card-body tw-text-center tw-py-4">
                            @php
                                $total = $totalBayar + $totalBelum;
                                $persen = $total > 0 ? round(($totalBayar / $total) * 100) : 0;
                            @endphp

                            <h5 class="tw-text-3xl tw-font-bold tw-text-blue-600">{{ $persen }}%</h5>
                            <p class="tw-text-sm tw-text-gray-500 tw-mb-0">Lunas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="tw-tracking-wider tw-text-[#34395e] tw-ml-6 tw-mt-6 tw-mb-2 tw-text-base tw-font-semibold">Status Pembayaran Iuran Kas</h3>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="tw-table-auto tw-w-full">
                            <thead>
                                <tr class="tw-text-gray-700 text-center">
                                    <th width="5%">No</th>
                                    <th class="text-left">Periode</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($iuranList as $index => $iuran)
                                    <tr class="text-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left tw-font-semibold">{{ $iuran->nama_periode }}</td>
                                        <td>
                                            @if ($iuran->status === "sudah")
                                                <span class="tw-bg-green-50 tw-text-xs tw-text-green-600 tw-px-3 tw-py-1.5 tw-rounded-lg tw-font-semibold">
                                                    <i class="fas fa-check-circle tw-mr-1"></i>
                                                    Sudah Bayar
                                                </span>
                                            @else
                                                <span class="tw-bg-red-50 tw-text-xs tw-text-red-600 tw-px-3 tw-py-1.5 tw-rounded-lg tw-font-semibold">
                                                    <i class="fas fa-times-circle tw-mr-1"></i>
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="tw-whitespace-nowrap">
                                            @if ($iuran->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($iuran->tanggal_bayar)->format("d M Y") }}
                                            @else
                                                <span class="tw-text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center tw-py-8 tw-text-gray-500">
                                            <i class="fas fa-inbox tw-text-4xl tw-mb-3 tw-text-gray-300 tw-block"></i>
                                            Belum ada data iuran kas
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
</div>
