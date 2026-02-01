<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body {
                font-family: sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th,
            td {
                border: 1px solid #000;
                padding: 5px;
                font-size: 10pt;
                vertical-align: middle;
            }
            th {
                background-color: #f2f2f2;
                text-align: center;
            }
            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .font-bold {
                font-weight: bold;
            }
            .header {
                margin-bottom: 20px;
                text-align: center;
            }
            .currency-cell {
                display: table;
                width: 100%;
            }
            .currency-left {
                display: table-cell;
                text-align: left;
            }
            .currency-right {
                display: table-cell;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Buku Kas - Transaksi Keuangan</h2>
            <p>Dicetak pada: {{ now()->locale("id")->translatedFormat("d F Y H:i") }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="12%">Tanggal</th>
                    <th class="text-left" width="30%">Keterangan</th>
                    <th class="text-right" width="18%">Masuk</th>
                    <th class="text-right" width="18%">Keluar</th>
                    <th class="text-right" width="17%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPemasukan = 0;
                    $totalPengeluaran = 0;
                @endphp

                @forelse ($data as $index => $row)
                    @php
                        $totalPemasukan += $row["pemasukan"];
                        $totalPengeluaran += $row["pengeluaran"];

                        // Format keterangan
                        $keterangan = $row["deskripsi"];

                        // Untuk Pengeluaran: tambahkan Dept/Project
                        if ($row["pengeluaran"] > 0) {
                            if ($row["department"] !== "-") {
                                $keterangan = "Operasional " . $row["department"] . ":\n" . $row["deskripsi"];
                            } elseif ($row["project"] !== "-") {
                                $keterangan = $row["project"] . ":\n" . $row["deskripsi"];
                            }
                        }
                        // Untuk Pemasukan: tambahkan Kategori
                        else {
                            $keterangan = $row["kategori"] . ":\n" . $row["deskripsi"];
                        }
                    @endphp

                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row["tanggal"])->format("d/m/Y") }}</td>
                        <td class="text-left" style="white-space: pre-line">{{ $keterangan }}</td>
                        <td style="padding: 5px">
                            @if ($row["pemasukan"] > 0)
                                <div class="currency-cell">
                                    <span class="currency-left">Rp.</span>
                                    <span class="currency-right">{{ number_format($row["pemasukan"], 0, ",", ".") }}</span>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td style="padding: 5px">
                            @if ($row["pengeluaran"] > 0)
                                <div class="currency-cell">
                                    <span class="currency-left">Rp.</span>
                                    <span class="currency-right">{{ number_format($row["pengeluaran"], 0, ",", ".") }}</span>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="font-bold" style="padding: 5px">
                            <div class="currency-cell">
                                <span class="currency-left">Rp.</span>
                                <span class="currency-right">{{ number_format($row["saldo"], 0, ",", ".") }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f2f2f2; font-weight: bold">
                    <td colspan="3" class="text-right">TOTAL</td>
                    <td style="padding: 5px">
                        <div class="currency-cell">
                            <span class="currency-left">Rp.</span>
                            <span class="currency-right">{{ number_format($totalPemasukan, 0, ",", ".") }}</span>
                        </div>
                    </td>
                    <td style="padding: 5px">
                        <div class="currency-cell">
                            <span class="currency-left">Rp.</span>
                            <span class="currency-right">{{ number_format($totalPengeluaran, 0, ",", ".") }}</span>
                        </div>
                    </td>
                    <td style="padding: 5px">
                        <div class="currency-cell">
                            <span class="currency-left">Rp.</span>
                            <span class="currency-right">{{ number_format($totalPemasukan - $totalPengeluaran, 0, ",", ".") }}</span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
