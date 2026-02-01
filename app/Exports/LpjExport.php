<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LpjExport implements WithMultipleSheets
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new Sheets\RingkasanSheet($this->data),
            'Rincian' => new Sheets\RincianSheet($this->data), // New Sheet
            'Buku Kas' => new Sheets\BukuKasSheet($this->data['transaksi'] ?? []),
            // 'Rekapitulasi' => new Sheets\RekapitulasiSheet($this->tahunId), // Maybe remove or update if needed
        ];
    }
}
