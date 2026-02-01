<!DOCTYPE html>
<html lang="id">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Laporan Keuangan - {{ $tahunNama }}</title>
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
                font-family: Arial, Helvetica, sans-serif;
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
                font-family: Arial, Helvetica, sans-serif;
            }

            .report-period {
                font-size: 10pt;
                color: #333;
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
            }

            /* TABLE STYLE */
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
                font-family: Arial, Helvetica, sans-serif !important;
            }

            /* Force font family on bold elements to prevent fallback to serif */
            strong,
            b,
            th,
            .fw-bold,
            .text-bold {
                font-family: Arial, Helvetica, sans-serif !important;
            }

            table th {
                background: #f0f0f0;
                font-weight: bold;
                text-align: center;
            }

            table td {
                text-align: left;
            }

            .row-header td {
                background: #e5e5e5;
                font-weight: bold;
            }

            .row-total td {
                background: #f5f5f5;
                font-weight: bold;
            }

            .row-saldo td {
                background: #ddd;
                font-weight: bold;
            }

            .fw-bold {
                font-weight: bold;
            }

            .text-muted {
                color: #777;
            }

            /* SIGNATURE */
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
        </style>
    </head>
    <body>
        <!-- ============================================ -->
        <!-- PAGE 1: RINGKASAN ANGGARAN VS REALISASI -->
        <!-- ============================================ -->
        <div class="container">
            <p class="print-date">Dicetak pada {{ now()->translatedFormat("l, d F Y H:i:s") }}</p>

            <div class="report-header">
                <h1 class="report-title">Laporan Keuangan</h1>
                <p class="report-period">Periode {{ $tahunNama }}</p>
            </div>

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
                    @forelse ($ringkasanPemasukan as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item["nama"] }}</td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($item["realisasi"] - $item["anggaran"], 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td class="text-center">{{ $item["persentase"] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                    <tr class="row-total">
                        <td></td>
                        <td>Total Pemasukan</td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($totalAnggaranPemasukan, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($totalRealisasiPemasukan, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($totalRealisasiPemasukan - $totalAnggaranPemasukan, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td class="text-center">{{ $totalAnggaranPemasukan > 0 ? round(($totalRealisasiPemasukan / $totalAnggaranPemasukan) * 100, 1) : 0 }}%</td>
                    </tr>

                    <!-- PENGELUARAN -->
                    <tr class="row-header">
                        <td class="text-center">B</td>
                        <td colspan="5">PENGELUARAN</td>
                    </tr>
                    @forelse ($ringkasanPengeluaran as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item["nama"] }}</td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($item["anggaran"], 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($item["realisasi"], 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($item["anggaran"] - $item["realisasi"], 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td class="text-center">{{ $item["persentase"] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                    <tr class="row-total">
                        <td></td>
                        <td>Total Pengeluaran</td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($totalAnggaranPengeluaran, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($totalRealisasiPengeluaran, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($totalAnggaranPengeluaran - $totalRealisasiPengeluaran, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td class="text-center">{{ $totalAnggaranPengeluaran > 0 ? round(($totalRealisasiPengeluaran / $totalAnggaranPengeluaran) * 100, 1) : 0 }}%</td>
                    </tr>

                    <!-- SALDO -->
                    <tr class="row-saldo">
                        <td></td>
                        <td>SALDO AKHIR</td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($saldoAnggaran, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($saldoRealisasi, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td>
                            <span style="float: left">Rp</span>
                            <span style="float: right">{{ number_format($saldoRealisasi - $saldoAnggaran, 0, ",", ".") }}</span>
                            <div style="clear: both"></div>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="page-break"></div>

        <!-- ============================================ -->
        <!-- PAGE 2: BUKU KAS -->
        <!-- ============================================ -->
        <div class="container">
            <p class="print-date">Dicetak pada {{ now()->translatedFormat("l, d F Y H:i:s") }}</p>

            <div class="report-header">
                <h1 class="report-title">Buku Kas</h1>
                <p class="report-period">Periode {{ $tahunNama }}</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 12%">Tanggal</th>
                        <th>Keterangan</th>
                        <th style="width: 14%">Pemasukan</th>
                        <th style="width: 14%">Pengeluaran</th>
                        <th style="width: 14%">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo = 0;
                    @endphp

                    @forelse ($transaksi as $index => $tx)
                        @php
                            if ($tx->jenis === "pemasukan") {
                                $saldo += $tx->nominal;
                            } else {
                                $saldo -= $tx->nominal;
                            }
                        @endphp

                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $tx->tanggal->format("d/m/Y") }}</td>
                            <td>{{ $tx->deskripsi }}</td>
                            <td>
                                @if ($tx->jenis === "pemasukan")
                                    <span style="float: left">Rp</span>
                                    <span style="float: right">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                    <div style="clear: both"></div>
                                @else
                                    <span style="text-align: center; display: block">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($tx->jenis === "pengeluaran")
                                    <span style="float: left">Rp</span>
                                    <span style="float: right">{{ number_format($tx->nominal, 0, ",", ".") }}</span>
                                    <div style="clear: both"></div>
                                @else
                                    <span style="text-align: center; display: block">-</span>
                                @endif
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($saldo, 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No data available on the table</td>
                        </tr>
                    @endforelse

                    @if (count($transaksi) > 0)
                        <tr class="row-saldo">
                            <td colspan="3" class="text-center">TOTAL</td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($totalRealisasiPemasukan, 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($totalRealisasiPengeluaran, 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                            <td>
                                <span style="float: left">Rp</span>
                                <span style="float: right">{{ number_format($saldoRealisasi, 0, ",", ".") }}</span>
                                <div style="clear: both"></div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="page-break"></div>

        <!-- ============================================ -->
        <!-- PAGE 3: PENGESAHAN -->
        <!-- ============================================ -->
        <div class="container">
            <p class="print-date">Dicetak pada {{ now()->translatedFormat("l, d F Y H:i:s") }}</p>

            <div class="report-header">
                <h1 class="report-title">Halaman Pengesahan</h1>
                <p class="report-period">Periode {{ $tahunNama }}</p>
            </div>

            <p style="text-align: justify; text-indent: 30px; line-height: 1.8; margin-bottom: 25px; font-family: Arial, Helvetica, sans-serif">Demikian Laporan Keuangan ini kami buat dengan sebenar-benarnya sebagai bentuk pertanggungjawaban pengelolaan keuangan organisasi. Apabila dikemudian hari terdapat kekeliruan dalam laporan ini, maka kami bersedia untuk memperbaikinya.</p>

            <p style="text-align: right; margin-bottom: 30px; font-family: Arial, Helvetica, sans-serif">Depok, {{ now()->translatedFormat("d F Y") }}</p>

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
