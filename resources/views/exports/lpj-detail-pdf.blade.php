<!DOCTYPE html>
<html lang="id">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Laporan Keuangan Detail - {{ $tahunNama }}</title>
        <style>
            @page {
                margin: 15mm 20mm;
            }
            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10pt;
                background: #fff;
                color: #333;
            }
            .container {
                width: 100%;
                margin: 0 auto;
            }
            .page-break {
                page-break-after: always;
            }
            .print-date {
                font-size: 8pt;
                color: #999;
                margin-bottom: 15px;
            }
            .text-center {
                text-align: center;
            }
            .text-end {
                text-align: right;
            }
            .report-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .report-title {
                font-size: 14pt;
                font-weight: bold;
                margin: 0 0 5px 0;
            }
            .report-period {
                font-size: 10pt;
                color: #333;
                margin: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                font-size: 9pt;
            }
            table th,
            table td {
                border: 1px solid #333;
                padding: 8px 10px;
            }
            table th {
                background: #f0f0f0;
                font-weight: bold;
                text-align: center;
            }
            .row-header td {
                background: #e5e5e5;
                font-weight: bold;
            }
            .row-total td {
                background: #f5f5f5;
                font-weight: bold;
            }
            .section-title {
                font-size: 12pt;
                font-weight: bold;
                margin-bottom: 10px;
                margin-top: 20px;
                text-decoration: underline;
            }
            .signature-table {
                width: 100%;
                margin-top: 40px;
                border: none !important;
            }
            .signature-table td {
                border: none !important;
                text-align: center;
                padding: 10px 15px;
                vertical-align: top;
            }
            .signature-line {
                border-bottom: 1px solid #333;
                margin: 60px 15px 8px;
            }
            .text-muted {
                color: #777;
            }
            .float-left {
                float: left;
            }
            .float-right {
                float: right;
            }
            .clearfix {
                clear: both;
            }
        </style>
    </head>
    <body>
        <!-- PAGE 1: RINGKASAN (Summary) -->
        <div class="container">
            <p class="print-date">Dicetak pada {{ now()->translatedFormat("l, d F Y H:i:s") }}</p>
            <div class="report-header">
                <h1 class="report-title">Laporan Keuangan Detail</h1>
                <p class="report-period">Periode {{ $tahunNama }}</p>
            </div>

            <h3 class="section-title">I. Ringkasan Anggaran vs Realisasi</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Uraian</th>
                        <th style="width: 15%">Anggaran (Rp)</th>
                        <th style="width: 15%">Realisasi (Rp)</th>
                        <th style="width: 15%">Selisih (Rp)</th>
                        <th style="width: 7%">%</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PEMASUKAN -->
                    <tr class="row-header">
                        <td class="text-center">A</td>
                        <td colspan="5">PEMASUKAN</td>
                    </tr>
                    @foreach ($ringkasanPemasukan as $index => $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item["nama"] }}</td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"] - $item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td class="text-center">{{ $item["persentase"] }}%</td>
                        </tr>
                    @endforeach

                    <tr class="row-total">
                        <td></td>
                        <td>Total Pemasukan</td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($totalAnggaranPemasukan, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($totalRealisasiPemasukan, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($totalRealisasiPemasukan - $totalAnggaranPemasukan, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td class="text-center">{{ $totalAnggaranPemasukan > 0 ? round(($totalRealisasiPemasukan / $totalAnggaranPemasukan) * 100, 1) : 0 }}%</td>
                    </tr>

                    <!-- PENGELUARAN -->
                    <tr class="row-header">
                        <td class="text-center">B</td>
                        <td colspan="5">PENGELUARAN</td>
                    </tr>
                    @foreach ($ringkasanPengeluaran as $index => $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item["nama"] }}</td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"] - $item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td class="text-center">{{ $item["persentase"] }}%</td>
                        </tr>
                    @endforeach

                    <tr class="row-total">
                        <td></td>
                        <td>Total Pengeluaran</td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($totalAnggaranPengeluaran, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($totalRealisasiPengeluaran, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($totalAnggaranPengeluaran - $totalRealisasiPengeluaran, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td class="text-center">{{ $totalAnggaranPengeluaran > 0 ? round(($totalRealisasiPengeluaran / $totalAnggaranPengeluaran) * 100, 1) : 0 }}%</td>
                    </tr>
                    <!-- SALDO -->
                    <tr class="row-total" style="background: #ddd">
                        <td></td>
                        <td>SALDO AKHIR</td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($saldoAnggaran, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($saldoRealisasi, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td>
                            <span class="float-left">Rp</span>
                            <span class="float-right">{{ number_format($saldoRealisasi - $saldoAnggaran, 0, ",", ".") }}</span>
                            <div class="clearfix"></div>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="page-break"></div>

        <!-- PAGE 2: RINCIAN DETAIL (New Section) -->
        <div class="container">
            <h3 class="section-title">I. Rincian Pemasukan</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Sumber Pemasukan</th>
                        <th style="width: 20%">Anggaran</th>
                        <th style="width: 20%">Realisasi</th>
                        <th style="width: 10%">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanPemasukan as $item)
                        <!-- Main Item Row -->
                        <tr>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $loop->iteration }}</b></td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <strong>{{ $item["nama"] }}</strong>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $item["persentase"] }}%</b></td>
                        </tr>

                        <!-- Transaction Details Rows -->
                        @if (isset($item["transactions"]) && count($item["transactions"]) > 0)
                            @foreach ($item["transactions"] as $tx)
                                <tr>
                                    <td></td>
                                    <!-- Empty No -->
                                    <td style="padding-left: 25px; color: #555">
                                        - {{ $tx->deskripsi }}
                                        <span style="font-size: 8pt; color: #888">({{ $tx->tanggal->format("d/m/Y") }})</span>
                                    </td>
                                    <td></td>
                                    <!-- Empty Anggaran -->
                                    <td>
                                        <span class="float-left" style="color: #555">Rp</span>
                                        <span class="float-right" style="color: #555">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                        <div class="clearfix"></div>
                                    </td>
                                    <td></td>
                                    <!-- Empty % -->
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h3 class="section-title">II. Rincian Pengeluaran per Departemen</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Departemen</th>
                        <th style="width: 20%">Anggaran</th>
                        <th style="width: 20%">Realisasi</th>
                        <th style="width: 10%">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanDept as $item)
                        <!-- Main Item Row -->
                        <tr>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $loop->iteration }}</b></td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <strong>Operasional {{ $item["nama"] }}</strong>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $item["persentase"] }}%</b></td>
                        </tr>

                        <!-- Transaction Details Rows -->
                        @if (isset($item["transactions"]) && count($item["transactions"]) > 0)
                            @foreach ($item["transactions"] as $tx)
                                <tr>
                                    <td></td>
                                    <td style="padding-left: 25px; color: #555">
                                        - {{ $tx->deskripsi }}
                                        <span style="font-size: 8pt; color: #888">({{ $tx->tanggal->format("d/m/Y") }})</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        <span class="float-left" style="color: #555">Rp</span>
                                        <span class="float-right" style="color: #555">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                        <div class="clearfix"></div>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h3 class="section-title">III. Rincian Pengeluaran per Project/Kegiatan</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Project/Kegiatan</th>
                        <th style="width: 20%">Anggaran</th>
                        <th style="width: 20%">Realisasi</th>
                        <th style="width: 10%">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanProject as $item)
                        <!-- Main Item Row -->
                        <tr>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $loop->iteration }}</b></td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <strong>{{ $item["nama"] }}</strong>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $item["persentase"] }}%</b></td>
                        </tr>

                        <!-- Transaction Details Rows -->
                        @if (isset($item["transactions"]) && count($item["transactions"]) > 0)
                            @foreach ($item["transactions"] as $tx)
                                <tr>
                                    <td></td>
                                    <td style="padding-left: 25px; color: #555">
                                        - {{ $tx->deskripsi }}
                                        <span style="font-size: 8pt; color: #888">({{ $tx->tanggal->format("d/m/Y") }})</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        <span class="float-left" style="color: #555">Rp</span>
                                        <span class="float-right" style="color: #555">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                        <div class="clearfix"></div>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h3 class="section-title">IV. Rincian Pengeluaran Lainnya</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Item</th>
                        <th style="width: 20%">Anggaran</th>
                        <th style="width: 20%">Realisasi</th>
                        <th style="width: 10%">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanLainnya as $item)
                        <!-- Main Item Row -->
                        <tr>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $loop->iteration }}</b></td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <strong>{{ $item["nama"] }}</strong>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td style="vertical-align: top; background-color: #f9f9f9">
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                            <td class="text-center" style="vertical-align: top; background-color: #f9f9f9"><b>{{ $item["persentase"] }}%</b></td>
                        </tr>

                        <!-- Transaction Details Rows -->
                        @if (isset($item["transactions"]) && count($item["transactions"]) > 0)
                            @foreach ($item["transactions"] as $tx)
                                <tr>
                                    <td></td>
                                    <td style="padding-left: 25px; color: #555">
                                        - {{ $tx->deskripsi }}
                                        <span style="font-size: 8pt; color: #888">({{ $tx->tanggal->format("d/m/Y") }})</span>
                                    </td>
                                    <td></td>
                                    <td>
                                        <span class="float-left" style="color: #555">Rp</span>
                                        <span class="float-right" style="color: #555">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                        <div class="clearfix"></div>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="page-break"></div>

        <!-- PAGE 3: BUKU KAS -->
        <div class="container">
            <h3 class="section-title">V. Buku Kas</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 12%">Tanggal</th>
                        <th>Keterangan</th>
                        <th style="width: 14%">Masuk</th>
                        <th style="width: 14%">Keluar</th>
                        <th style="width: 14%">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo = 0;
                    @endphp

                    @forelse ($transaksi as $tx)
                        @php
                            if ($tx->jenis === "pemasukan") {
                                $saldo += $tx->nominal;
                            } else {
                                $saldo -= $tx->nominal;
                            }
                        @endphp

                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $tx->tanggal->format("d/m/Y") }}</td>
                            <td>{{ $tx->deskripsi }}</td>
                            <td>
                                @if ($tx->jenis == "pemasukan")
                                    <span class="float-left">Rp</span>
                                    <span class="float-right">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                    <div class="clearfix"></div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($tx->jenis == "pengeluaran")
                                    <span class="float-left">Rp</span>
                                    <span class="float-right">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                    <div class="clearfix"></div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="float-left">Rp</span>
                                <span class="float-right">{{ number_format($saldo, 0, ",", ".") }}</span>
                                <div class="clearfix"></div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="page-break"></div>

        <!-- PAGE 4: PENGESAHAN -->
        <div class="container">
            <h3 class="section-title">VI. Halaman Pengesahan</h3>
            <p style="text-align: justify; text-indent: 30px; line-height: 1.8; margin-bottom: 25px">Demikian Laporan Keuangan Detail ini kami buat dengan sebenar-benarnya sebagai bentuk pertanggungjawaban pengelolaan keuangan organisasi. Apabila dikemudian hari terdapat kekeliruan dalam laporan ini, maka kami bersedia untuk memperbaikinya.</p>
            <p style="text-align: right; margin-bottom: 30px">Depok, {{ now()->translatedFormat("d F Y") }}</p>
            <table class="signature-table">
                <tr>
                    <td style="width: 33%">
                        <p style="margin: 0 0 3px">Mengetahui,</p>
                        <p class="fw-bold" style="margin: 0">Ketua Umum</p>
                        <div class="signature-line"></div>
                        <small class="text-muted">NIM: ........................</small>
                    </td>
                    <td style="width: 33%">
                        <p style="margin: 0 0 3px">Menyetujui,</p>
                        <p class="fw-bold" style="margin: 0">Wakil Ketua</p>
                        <div class="signature-line"></div>
                        <small class="text-muted">NIM: ........................</small>
                    </td>
                    <td style="width: 33%">
                        <p style="margin: 0 0 3px">Dibuat oleh,</p>
                        <p class="fw-bold" style="margin: 0">Bendahara</p>
                        <div class="signature-line"></div>
                        <small class="text-muted">NIM: ........................</small>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
