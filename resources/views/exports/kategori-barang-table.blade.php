<!DOCTYPE html>
<html lang="id">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Kategori Barang</title>
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
                padding: 8px;
                font-size: 11pt;
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
            .header {
                margin-bottom: 20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>Daftar Kategori Barang</h2>
            <p>Dicetak pada: {{ now()->locale("id")->translatedFormat("d F Y H:i") }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center" width="10%">No</th>
                    <th class="text-left" width="70%">Nama Kategori</th>
                    <th class="text-center" width="20%">Jumlah Barang</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $row->nama }}</td>
                        <td class="text-center">{{ $row->barangs_count ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
