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

class RingkasanSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 35,
            'C' => 18,
            'D' => 18,
            'E' => 18,
            'F' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN PERTANGGUNGJAWABAN KEUANGAN');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', $this->data['tahunNama'] ?? '');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header styling
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'D9EAD3']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Data borders
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A4:F{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        return [];
    }

    public function array(): array
    {
        $rows = [];
        
        // Empty rows for title
        $rows[] = [''];
        $rows[] = [''];
        $rows[] = [''];
        
        // Header
        $rows[] = ['No', 'Uraian', 'Anggaran (Rp)', 'Realisasi (Rp)', 'Selisih (Rp)', '%'];

        // PEMASUKAN Section
        $rows[] = ['A', 'PEMASUKAN', '', '', '', ''];
        
        $no = 1;
        $totalAnggaranMasuk = $this->data['totalAnggaranPemasukan'] ?? 0;
        $totalRealisasiMasuk = $this->data['totalRealisasiPemasukan'] ?? 0;

        foreach ($this->data['ringkasanPemasukan'] as $item) {
            $anggaran = $item['anggaran'];
            $realisasi = $item['realisasi'];
            $selisih = $realisasi - $anggaran;
            
            $rows[] = [
                $no++,
                $item['nama'],
                $anggaran,
                $realisasi,
                $selisih,
                $item['persentase'] . '%'
            ];
        }

        $rows[] = ['', 'Total Pemasukan', $totalAnggaranMasuk, $totalRealisasiMasuk, $totalRealisasiMasuk - $totalAnggaranMasuk, ''];
        $rows[] = [''];

        // PENGELUARAN Section
        $rows[] = ['B', 'PENGELUARAN', '', '', '', ''];
        
        $no = 1;
        $totalAnggaranKeluar = $this->data['totalAnggaranPengeluaran'] ?? 0;
        $totalRealisasiKeluar = $this->data['totalRealisasiPengeluaran'] ?? 0;

        foreach ($this->data['ringkasanPengeluaran'] as $item) {
            $anggaran = $item['anggaran'];
            $realisasi = $item['realisasi'];
            $selisih = $anggaran - $realisasi;
            
            $rows[] = [
                $no++,
                $item['nama'],
                $anggaran,
                $realisasi,
                $selisih,
                $item['persentase'] . '%'
            ];
        }

        $rows[] = ['', 'Total Pengeluaran', $totalAnggaranKeluar, $totalRealisasiKeluar, $totalAnggaranKeluar - $totalRealisasiKeluar, ''];
        $rows[] = [''];

        // SALDO
        $saldoAnggaran = $this->data['saldoAnggaran'] ?? 0;
        $saldoRealisasi = $this->data['saldoRealisasi'] ?? 0;
        $rows[] = ['', 'SALDO AKHIR', $saldoAnggaran, $saldoRealisasi, $saldoRealisasi - $saldoAnggaran, ''];

        return $rows;
    }
}
