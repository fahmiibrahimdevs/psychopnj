<!DOCTYPE html>
<html lang="id">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Data Barang</title>
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
                font-weight: bold;
                text-align: center;
            }
            .text-center {
                text-align: center;
            }
            .text-left {
                text-align: left;
            }
            .text-right {
                text-align: right;
            }
            .font-bold {
                font-weight: bold;
            }
            .header {
                margin-bottom: 20px;
                text-align: center;
            }
            .group-header {
                background-color: #e8e8e8;
                font-weight: bold;
                text-align: left;
            }
            .sub-header {
                background-color: #f5f5f5;
                font-weight: normal;
                text-align: left;
                padding-left: 20px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Data Barang Perlengkapan</h2>
            <p>Dicetak pada: {{ now()->locale("id")->translatedFormat("d F Y H:i") }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="12%">Kode</th>
                    <th class="text-left" width="28%">Nama Barang</th>
                    <th class="text-center" width="12%">Jumlah</th>
                    <th class="text-center" width="10%">Tersedia</th>
                    <th class="text-center" width="15%">Kondisi</th>
                    <th class="text-center" width="18%">Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $groupedByJenis = $data->groupBy("jenis");
                    $no = 1;
                @endphp

                @forelse ($groupedByJenis as $jenis => $itemsByJenis)
                    <!-- Group Header: Jenis -->
                    <tr>
                        <td colspan="7" class="group-header">
                            {{ $jenis }}
                        </td>
                    </tr>

                    @php
                        $groupedByKategori = $itemsByJenis->groupBy("kategori");
                    @endphp

                    @foreach ($groupedByKategori as $kategori => $items)
                        <!-- Sub Header: Kategori -->
                        <tr>
                            <td colspan="7" class="sub-header">
                                {{ $kategori }}
                            </td>
                        </tr>

                        <!-- Items -->
                        @foreach ($items as $row)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $row["kode"] }}</td>
                                <td class="text-left">{{ $row["nama"] }}</td>
                                <td class="text-center">{{ $row["jumlah"] }} {{ $row["satuan"] }}</td>
                                <td class="text-center font-bold">{{ $row["tersedia"] }}</td>
                                <td class="text-center">{{ $row["kondisi"] }}</td>
                                <td class="text-center">{{ $row["lokasi"] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
