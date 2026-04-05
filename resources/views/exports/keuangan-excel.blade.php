<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body style="font-family: sans-serif; color: #111111">
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
                    <th style="text-align: left; font-weight: bold">Deskripsi</th>
                    <th style="text-align: right; font-weight: bold">Pemasukan</th>
                    <th style="text-align: right; font-weight: bold">Pengeluaran</th>
                    <th style="text-align: right; font-weight: bold">Saldo</th>
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
                            <tr style="background-color: #f2f2f2">
                                <td colspan="3" style="text-align: right">Total {{ $currentMonth }}</td>
                                <td style="text-align: right">Rp {{ number_format($monthlyPemasukan, 0, ",", ".") }}</td>
                                <td style="text-align: right">Rp {{ number_format($monthlyPengeluaran, 0, ",", ".") }}</td>
                                <td></td>
                            </tr>
                        @endif

                        <tr style="background-color: #ededed">
                            <td colspan="6" style="text-align: left; color: #111111; font-weight: bold">
                                {{ ucfirst($rowMonth) }}
                            </td>
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
                        <td style="text-align: center">{{ $row["no"] }}</td>
                        <td style="text-align: center">{{ \Carbon\Carbon::parse($row["tanggal"])->translatedFormat("d M Y") }}</td>
                        <td style="text-align: left">
                            <div style="font-weight: bold; color: #111111">{{ $prefix }}</div>
                            <div style="color: #333333">{{ $row["deskripsi"] }}</div>
                        </td>
                        <td style="text-align: right">{{ $row["pemasukan"] > 0 ? "Rp " . number_format($row["pemasukan"], 0, ",", ".") : "-" }}</td>
                        <td style="text-align: right">{{ $row["pengeluaran"] > 0 ? "Rp " . number_format($row["pengeluaran"], 0, ",", ".") : "-" }}</td>
                        <td style="text-align: right">Rp {{ number_format($row["saldo"], 0, ",", ".") }}</td>
                    </tr>

                    @if ($loop->last)
                        <tr style="background-color: #f2f2f2">
                            <td colspan="3" style="text-align: right">Total {{ $currentMonth }}</td>
                            <td style="text-align: right">Rp {{ number_format($monthlyPemasukan, 0, ",", ".") }}</td>
                            <td style="text-align: right">Rp {{ number_format($monthlyPengeluaran, 0, ",", ".") }}</td>
                            <td></td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
