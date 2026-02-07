<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th colspan="6" style="text-align: center; font-weight: bold; font-size: 14pt">Buku Kas - Transaksi Keuangan</th>
                </tr>
                <tr>
                    <th colspan="6" style="text-align: center">Dicetak pada: {{ now()->locale("id")->translatedFormat("d F Y H:i") }}</th>
                </tr>
                <tr>
                    <th style="text-align: center; font-weight: bold">No</th>
                    <th style="text-align: center; font-weight: bold">Tanggal</th>
                    <th style="text-align: left; font-weight: bold">Keterangan</th>
                    <th style="text-align: right; font-weight: bold">Masuk</th>
                    <th style="text-align: right; font-weight: bold">Keluar</th>
                    <th style="text-align: right; font-weight: bold">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPemasukan = 0;
                    $totalPengeluaran = 0;

                    $currentMonth = null;
                    $monthlyPemasukan = 0;
                    $monthlyPengeluaran = 0;
                @endphp

                @forelse ($data as $index => $row)
                    @php
                        // Subtotals Logic
                        $rowMonth = \Carbon\Carbon::parse($row["tanggal"])->translatedFormat("F Y");
                    @endphp

                    @if ($currentMonth !== $rowMonth)
                        {{-- Close previous month summary --}}
                        @if ($currentMonth !== null)
                            <tr style="background-color: #f2f2f2">
                                <td colspan="3" style="text-align: right">Total {{ $currentMonth }}</td>
                                <td style="text-align: right">{{ $monthlyPemasukan }}</td>
                                <td style="text-align: right">{{ $monthlyPengeluaran }}</td>
                                <td></td>
                            </tr>
                        @endif

                        {{-- New Month Header --}}
                        <tr style="background-color: #f3f4f6">
                            <td colspan="6" style="text-align: left; color: #374151">
                                {{ $rowMonth }}
                            </td>
                        </tr>

                        @php
                            $currentMonth = $rowMonth;
                            $monthlyPemasukan = 0;
                            $monthlyPengeluaran = 0;
                        @endphp
                    @endif

                    @php
                        // Accumulate monthly totals
                        $monthlyPemasukan += $row["pemasukan"];
                        $monthlyPengeluaran += $row["pengeluaran"];

                        $totalPemasukan += $row["pemasukan"];
                        $totalPengeluaran += $row["pengeluaran"];

                        // Format keterangan
                        $prefix = "";
                        // Handle Departemen
                        if (($row["kategori"] === "Departemen" || $row["kategori"] === "dept") && $row["department"] !== "-") {
                            $prefix = "Dept. " . $row["department"] . ":";
                        }
                        // Handle Project
                        elseif (($row["kategori"] === "Project" || $row["kategori"] === "project") && $row["project"] !== "-") {
                            $prefix = "Project " . $row["project"] . ":";
                        }
                        // Handle Other Categories
                        elseif (! in_array(strtolower($row["kategori"]), ["dept", "project", "departemen"])) {
                            $prefix = ucwords(str_replace("_", " ", $row["kategori"])) . ":";
                        }

                        $keterangan = $prefix ? $prefix . "\n" . $row["deskripsi"] : $row["deskripsi"];
                    @endphp

                    <tr>
                        <td style="text-align: center">{{ $index + 1 }}</td>
                        <td style="text-align: center">{{ \Carbon\Carbon::parse($row["tanggal"])->format("d/m/Y") }}</td>
                        <td style="text-align: left">{{ $keterangan }}</td>
                        <td style="text-align: right">
                            @if ($row["pemasukan"] > 0)
                                {{ $row["pemasukan"] }}
                            @else
                            @endif
                        </td>
                        <td style="text-align: right">
                            @if ($row["pengeluaran"] > 0)
                                {{ $row["pengeluaran"] }}
                            @else
                            @endif
                        </td>
                        <td style="text-align: right">{{ $row["saldo"] }}</td>
                    </tr>

                    @if ($loop->last)
                        <tr style="background-color: #f2f2f2">
                            <td colspan="3" style="text-align: right">Total {{ $currentMonth }}</td>
                            <td style="text-align: right">{{ $monthlyPemasukan }}</td>
                            <td style="text-align: right">{{ $monthlyPengeluaran }}</td>
                            <td></td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold">
                    <td colspan="3" style="text-align: right">TOTAL</td>
                    <td style="text-align: right">{{ $totalPemasukan }}</td>
                    <td style="text-align: right">{{ $totalPengeluaran }}</td>
                    <td style="text-align: right">{{ $totalPemasukan - $totalPengeluaran }}</td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>
