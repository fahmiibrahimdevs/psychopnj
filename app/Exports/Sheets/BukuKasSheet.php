<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BukuKasSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function title(): string
    {
        return 'Buku Kas';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 40,
            'D' => 18,
            'E' => 18,
            'F' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'BUKU KAS');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header styling
        $sheet->getStyle('A3:F3')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'CFE2F3']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Data borders
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A3:F{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        return [];
    }

    public function array(): array
    {
        $rows = [];
        
        // Empty row for title
        $rows[] = [''];
        $rows[] = [''];
        
        // Header
        $rows[] = ['No', 'Tanggal', 'Keterangan', 'Pemasukan', 'Pengeluaran', 'Saldo'];

        $saldo = 0;
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $no = 1;

        foreach ($this->transaksi as $tx) {
            $pemasukan = $tx->jenis === 'pemasukan' ? $tx->nominal : 0;
            $pengeluaran = $tx->jenis === 'pengeluaran' ? $tx->nominal : 0;
            
            if ($tx->jenis === 'pemasukan') {
                $saldo += $tx->nominal;
                $totalPemasukan += $tx->nominal;
            } else {
                $saldo -= $tx->nominal;
                $totalPengeluaran += $tx->nominal;
            }

            $rows[] = [
                $no++,
                $tx->tanggal->format('d/m/Y'),
                $tx->deskripsi,
                $pemasukan ?: '-',
                $pengeluaran ?: '-',
                $saldo
            ];
        }

        $rows[] = [''];
        $rows[] = ['', '', 'TOTAL', $totalPemasukan, $totalPengeluaran, $saldo];

        return $rows;
    }
}
