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
                                $keterangan = "Operasional " . $row["department"] . ": " . $row["deskripsi"];
                            } elseif ($row["project"] !== "-") {
                                $keterangan = $row["project"] . ": " . $row["deskripsi"];
                            }
                        }
                        // Untuk Pemasukan: tambahkan Kategori
                        else {
                            $keterangan = $row["kategori"] . ": " . $row["deskripsi"];
                        }
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
