<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <title>Kategori Barang</title>
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
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $row)
                    <tr>
                        <td class="text-center">{{ $row->id }}</td>
                        <td class="text-left">{{ $row->nama }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </body>
</html>
