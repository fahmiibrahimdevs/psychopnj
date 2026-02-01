<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <title>Data Barang</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th,
            td {
                border: 1px solid #000;
                padding: 8px;
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
            .group-header {
                background-color: #e8e8e8;
                font-weight: bold;
                text-align: left;
            }
            .sub-header {
                background-color: #f5f5f5;
                font-weight: normal;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Tersedia</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                    <th>Keterangan</th>
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
                        <td colspan="11" class="group-header">
                            {{ $jenis }}
                        </td>
                    </tr>

                    @php
                        $groupedByKategori = $itemsByJenis->groupBy("kategori");
                    @endphp

                    @foreach ($groupedByKategori as $kategori => $items)
                        <!-- Sub Header: Kategori -->
                        <tr>
                            <td colspan="11" class="sub-header">
                                {{ $kategori }}
                            </td>
                        </tr>

                        <!-- Items -->
                        @foreach ($items as $row)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $row["kode"] }}</td>
                                <td class="text-left">{{ $row["nama"] }}</td>
                                <td class="text-center">{{ $row["kategori"] }}</td>
                                <td class="text-center">{{ $row["jenis"] }}</td>
                                <td class="text-center">{{ $row["jumlah"] }}</td>
                                <td class="text-center">{{ $row["satuan"] }}</td>
                                <td class="text-center">{{ $row["tersedia"] }}</td>
                                <td class="text-center">{{ $row["kondisi"] }}</td>
                                <td class="text-center">{{ $row["lokasi"] }}</td>
                                <td class="text-left">{{ $row["keterangan"] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
