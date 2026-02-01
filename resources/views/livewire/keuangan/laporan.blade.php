<div>
    <section class="section custom-section">
        <div class="section-header tw-flex tw-flex-wrap tw-items-center tw-justify-between">
            <div class="tw-flex tw-items-center">
                <h1>Laporan Keuangan</h1>
                <span class="tw-bg-blue-100 tw-text-blue-700 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-medium tw-ml-3">
                    {{ $activeTahunNama }}
                </span>
            </div>
            <div class="tw-flex tw-gap-2">
                <button wire:click="downloadPdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf tw-mr-1"></i>
                    Export PDF
                </button>
                <button wire:click="downloadExcel" class="btn btn-success">
                    <i class="fas fa-file-excel tw-mr-1"></i>
                    Export Excel
                </button>
            </div>
        </div>

        <div class="section-body">
            <!-- Summary Cards -->
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-4 tw-px-4 lg:tw-px-0">
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Anggaran Pemasukan</p>
                            <h4 class="tw-text-lg tw-font-bold tw-text-gray-700">Rp {{ number_format($totalAnggaranPemasukan, 0, ",", ".") }}</h4>
                            <p class="tw-text-xs tw-text-green-600">Realisasi: Rp {{ number_format($totalRealisasiPemasukan, 0, ",", ".") }}</p>
                        </div>
                        <i class="fas fa-arrow-down tw-text-green-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Anggaran Pengeluaran</p>
                            <h4 class="tw-text-lg tw-font-bold tw-text-gray-700">Rp {{ number_format($totalAnggaranPengeluaran, 0, ",", ".") }}</h4>
                            <p class="tw-text-xs tw-text-red-600">Realisasi: Rp {{ number_format($totalRealisasiPengeluaran, 0, ",", ".") }}</p>
                        </div>
                        <i class="fas fa-arrow-up tw-text-red-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Selisih Anggaran</p>
                            <h4 class="tw-text-lg tw-font-bold tw-text-blue-600">Rp {{ number_format($saldoAnggaran, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-balance-scale tw-text-blue-500 tw-text-2xl"></i>
                    </div>
                </div>
                <div class="tw-bg-white tw-rounded-lg tw-p-4 tw-shadow">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p class="tw-text-sm tw-text-gray-500">Saldo Realisasi</p>
                            <h4 class="tw-text-lg tw-font-bold {{ $saldoRealisasi >= 0 ? "tw-text-green-600" : "tw-text-red-600" }}">Rp {{ number_format($saldoRealisasi, 0, ",", ".") }}</h4>
                        </div>
                        <i class="fas fa-wallet {{ $saldoRealisasi >= 0 ? "tw-text-green-500" : "tw-text-red-500" }} tw-text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-4 tw-px-4 lg:tw-px-0">
                <!-- Pemasukan -->
                <div class="card tw-mb-0">
                    <h3 class="tw-text-green-700">
                        <i class="fas fa-arrow-down tw-mr-2"></i>
                        Anggaran vs Realisasi Pemasukan
                    </h3>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="tw-table-auto tw-w-full">
                                <thead>
                                    <tr class="tw-text-gray-700">
                                        <th class="tw-whitespace-nowrap">Sumber</th>
                                        <th class="tw-whitespace-nowrap text-right">Anggaran</th>
                                        <th class="tw-whitespace-nowrap text-right">Realisasi</th>
                                        <th class="tw-whitespace-nowrap text-center" width="100">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporanPemasukan as $item)
                                        <tr>
                                            <td>{{ $item["nama"] }}</td>
                                            <td>
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="tw-text-green-600 tw-font-medium">
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="tw-flex tw-items-center tw-gap-2">
                                                    <div class="tw-flex-1 tw-bg-gray-200 tw-rounded-full tw-h-2">
                                                        <div class="tw-bg-green-500 tw-h-2 tw-rounded-full" style="width: {{ min($item["persentase"], 100) }}%"></div>
                                                    </div>
                                                    <span class="tw-text-xs tw-font-medium">{{ $item["persentase"] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center tw-text-gray-500">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tbody class="tw-bg-green-50 tw-font-semibold">
                                    <tr>
                                        <td>Total</td>
                                        <td>
                                            <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                <span>Rp</span>
                                                <span>{{ number_format($totalAnggaranPemasukan, 0, ",", ".") }}</span>
                                            </div>
                                        </td>
                                        <td class="tw-text-green-600">
                                            <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                <span>Rp</span>
                                                <span>{{ number_format($totalRealisasiPemasukan, 0, ",", ".") }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $totalAnggaranPemasukan > 0 ? round(($totalRealisasiPemasukan / $totalAnggaranPemasukan) * 100, 1) : 0 }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran per Departemen -->
                <div class="card tw-mb-0">
                    <h3 class="tw-text-red-700">
                        <i class="fas fa-building tw-mr-2"></i>
                        Pengeluaran per Departemen
                    </h3>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="tw-table-auto tw-w-full">
                                <thead>
                                    <tr class="tw-text-gray-700">
                                        <th width="35%" class="tw-whitespace-nowrap">Departemen</th>
                                        <th class="tw-whitespace-nowrap text-right">Anggaran</th>
                                        <th class="tw-whitespace-nowrap text-right">Realisasi</th>
                                        <th class="tw-whitespace-nowrap text-center" width="100">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporanDept as $item)
                                        <tr>
                                            <td>{{ $item["nama"] }}</td>
                                            <td>
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="tw-text-red-600 tw-font-medium">
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="tw-flex tw-items-center tw-gap-2">
                                                    <div class="tw-flex-1 tw-bg-gray-200 tw-rounded-full tw-h-2">
                                                        <div class="tw-bg-red-500 tw-h-2 tw-rounded-full" style="width: {{ min($item["persentase"], 100) }}%"></div>
                                                    </div>
                                                    <span class="tw-text-xs tw-font-medium {{ $item["persentase"] > 100 ? "tw-text-red-600" : "" }}">{{ $item["persentase"] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center tw-text-gray-500">Tidak ada anggaran departemen</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran per Project -->
                <div class="card tw-mb-0">
                    <h3 class="tw-text-purple-700">
                        <i class="fas fa-project-diagram tw-mr-2"></i>
                        Pengeluaran per Project/Kegiatan
                    </h3>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="tw-table-auto tw-w-full">
                                <thead>
                                    <tr class="tw-text-gray-700">
                                        <th class="tw-whitespace-nowrap">Project/Kegiatan</th>
                                        <th class="tw-whitespace-nowrap text-right">Anggaran</th>
                                        <th class="tw-whitespace-nowrap text-right">Realisasi</th>
                                        <th class="tw-whitespace-nowrap text-center" width="100">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporanProject as $item)
                                        <tr>
                                            <td>{{ $item["nama"] }}</td>
                                            <td>
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="tw-text-purple-600 tw-font-medium">
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="tw-flex tw-items-center tw-gap-2">
                                                    <div class="tw-flex-1 tw-bg-gray-200 tw-rounded-full tw-h-2">
                                                        <div class="tw-bg-purple-500 tw-h-2 tw-rounded-full" style="width: {{ min($item["persentase"], 100) }}%"></div>
                                                    </div>
                                                    <span class="tw-text-xs tw-font-medium {{ $item["persentase"] > 100 ? "tw-text-red-600" : "" }}">{{ $item["persentase"] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center tw-text-gray-500">Tidak ada anggaran project/kegiatan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pengeluaran Lainnya -->
                <div class="card tw-mb-0">
                    <h3 class="tw-text-yellow-700">
                        <i class="fas fa-ellipsis-h tw-mr-2"></i>
                        Pengeluaran Lainnya
                    </h3>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="tw-table-auto tw-w-full">
                                <thead>
                                    <tr class="tw-text-gray-700">
                                        <th class="tw-whitespace-nowrap">Item</th>
                                        <th class="tw-whitespace-nowrap text-right">Anggaran</th>
                                        <th class="tw-whitespace-nowrap text-right">Realisasi</th>
                                        <th class="tw-whitespace-nowrap text-center" width="100">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($laporanLainnya as $item)
                                        <tr>
                                            <td>{{ $item["nama"] }}</td>
                                            <td>
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="tw-text-yellow-600 tw-font-medium">
                                                <div class="tw-flex tw-justify-between tw-whitespace-nowrap">
                                                    <span>Rp</span>
                                                    <span>{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="tw-flex tw-items-center tw-gap-2">
                                                    <div class="tw-flex-1 tw-bg-gray-200 tw-rounded-full tw-h-2">
                                                        <div class="tw-bg-yellow-500 tw-h-2 tw-rounded-full" style="width: {{ min($item["persentase"], 100) }}%"></div>
                                                    </div>
                                                    <span class="tw-text-xs tw-font-medium">{{ $item["persentase"] }}%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center tw-text-gray-500">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
