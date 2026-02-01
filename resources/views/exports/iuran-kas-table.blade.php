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
            .total-row {
                background-color: #e6e6e6;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Laporan Iuran Kas</h2>
            <p>Dicetak pada: {{ now()->locale("id")->translatedFormat("d F Y H:i") }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="text-center" width="5%">No</th>
                    <th rowspan="2" class="text-left" style="min-width: 150px">Nama Lengkap</th>

                    @if (count($periodeList) > 0)
                        <th colspan="{{ count($periodeList) }}" class="text-center">Pertemuan</th>
                    @else
                        <th class="text-center">Periode</th>
                    @endif
                    <th rowspan="2" class="text-center" width="15%">Total</th>
                </tr>
                <tr>
                    @forelse ($periodeList as $periode)
                        <th class="text-center">{{ $periode }}</th>
                    @empty
                        <th class="text-center">-</th>
                    @endforelse
                </tr>
            </thead>
            <tbody>
                <!-- Pengurus -->
                <tr>
                    <td colspan="{{ count($periodeList) + 3 }}" class="font-bold" style="background-color: #ddd">Pengurus</td>
                </tr>
                @forelse ($matrix["pengurus"] as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $row["nama"] }}</td>
                        @foreach ($periodeList as $periode)
                            @php
                                $payment = $row["payments"][$periode] ?? null;
                                $status = $payment && $payment["status"] === "lunas" ? "Lunas" : "-";
                                $date = $payment && $payment["status"] === "lunas" ? \Carbon\Carbon::parse($payment["tanggal_bayar"])->format("d/m") : "";
                            @endphp

                            <td class="text-center">
                                {{ $status }}
                                <br />
                                <small>{{ $date }}</small>
                            </td>
                        @endforeach

                        <td class="text-right">Rp {{ number_format($row["total_bayar"], 0, ",", ".") }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($periodeList) + 3 }}" class="text-center">Tidak ada data</td></tr>
                @endforelse

                <!-- Anggota -->
                <tr>
                    <td colspan="{{ count($periodeList) + 3 }}" class="font-bold" style="background-color: #ddd">Anggota</td>
                </tr>
                @forelse ($matrix["anggota"] as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $row["nama"] }}</td>
                        @foreach ($periodeList as $periode)
                            @php
                                $payment = $row["payments"][$periode] ?? null;
                                $status = $payment && $payment["status"] === "lunas" ? "Lunas" : "-";
                                $date = $payment && $payment["status"] === "lunas" ? \Carbon\Carbon::parse($payment["tanggal_bayar"])->format("d/m") : "";
                            @endphp

                            <td class="text-center">
                                {{ $status }}
                                <br />
                                <small>{{ $date }}</small>
                            </td>
                        @endforeach

                        <td class="text-right">Rp {{ number_format($row["total_bayar"], 0, ",", ".") }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($periodeList) + 3 }}" class="text-center">Tidak ada data</td></tr>
                @endforelse

                <!-- Total -->
                <tr class="total-row">
                    <td colspan="{{ count($periodeList) + 2 }}" class="text-right">TOTAL KESELURUHAN</td>
                    <td class="text-right">Rp {{ number_format($summary["total_keseluruhan"], 0, ",", ".") }}</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
