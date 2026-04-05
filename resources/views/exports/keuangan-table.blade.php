<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style>
            body {
                font-family: sans-serif;
                font-size: 10px;
                color: #111111;
            }

            .header {
                text-align: center;
                margin-bottom: 12px;
            }

            .header h2 {
                margin: 0 0 4px;
                font-size: 16px;
            }

            .header p {
                margin: 0;
                font-size: 10px;
                color: #4b5563;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #d1d5db;
                padding: 6px;
                vertical-align: top;
            }

            th {
                background: #e0e0e0;
                text-align: center;
                font-weight: 700;
            }

            .text-left {
                text-align: left;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .month-header td {
                background: #eeeeee;
                font-weight: 700;
                letter-spacing: 0.2px;
            }

            .month-total td {
                background: #f5f5f5;
                font-weight: 700;
            }

            .prefix {
                font-weight: 700;
                color: #222222;
                margin-bottom: 2px;
            }

            .desc {
                color: #333333;
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
                    <th width="6%">No</th>
                    <th width="14%">Tanggal</th>
                    <th>Deskripsi</th>
                    <th width="16%">Pemasukan</th>
                    <th width="16%">Pengeluaran</th>
                    <th width="16%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $currentMonth = null;
                    $monthlyPemasukan = 0;
                    $monthlyPengeluaran = 0;
                @endphp

                @forelse ($data as $row)
                    @php
                        $rowMonth = \Carbon\Carbon::parse($row["tanggal"])->translatedFormat("F Y");
                    @endphp

                    @if ($currentMonth !== $rowMonth)
                        @if ($currentMonth !== null)
                            <tr class="month-total">
                                <td colspan="3" class="text-right">Total {{ $currentMonth }}</td>
                                <td class="text-right">Rp {{ number_format($monthlyPemasukan, 0, ",", ".") }}</td>
                                <td class="text-right">Rp {{ number_format($monthlyPengeluaran, 0, ",", ".") }}</td>
                                <td></td>
                            </tr>
                        @endif

                        <tr class="month-header">
                            <td colspan="6" class="text-left">{{ ucfirst($rowMonth) }}</td>
                        </tr>

                        @php
                            $currentMonth = $rowMonth;
                            $monthlyPemasukan = 0;
                            $monthlyPengeluaran = 0;
                        @endphp
                    @endif

                    @php
                        $monthlyPemasukan += $row["pemasukan"];
                        $monthlyPengeluaran += $row["pengeluaran"];

                        $prefix = "";

                        if (in_array($row["kategori_raw"], ["departemen", "dept"]) && $row["department"] !== "-") {
                            $prefix = "Dept. " . $row["department"] . ":";
                        } elseif ($row["kategori_raw"] === "project" && $row["project"] !== "-") {
                            $prefix = "Project " . $row["project"] . ":";
                        } else {
                            $prefix = $row["kategori"] . ":";
                        }
                    @endphp

                    <tr>
                        <td class="text-center">{{ $row["no"] }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row["tanggal"])->translatedFormat("d M Y") }}</td>
                        <td class="text-left">
                            <div class="prefix">{{ $prefix }}</div>
                            <div class="desc">{{ $row["deskripsi"] }}</div>
                        </td>
                        <td class="text-right">
                            {{ $row["pemasukan"] > 0 ? "Rp " . number_format($row["pemasukan"], 0, ",", ".") : "-" }}
                        </td>
                        <td class="text-right">
                            {{ $row["pengeluaran"] > 0 ? "Rp " . number_format($row["pengeluaran"], 0, ",", ".") : "-" }}
                        </td>
                        <td class="text-right">Rp {{ number_format($row["saldo"], 0, ",", ".") }}</td>
                    </tr>

                    @if ($loop->last)
                        <tr class="month-total">
                            <td colspan="3" class="text-right">Total {{ $currentMonth }}</td>
                            <td class="text-right">Rp {{ number_format($monthlyPemasukan, 0, ",", ".") }}</td>
                            <td class="text-right">Rp {{ number_format($monthlyPengeluaran, 0, ",", ".") }}</td>
                            <td></td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
