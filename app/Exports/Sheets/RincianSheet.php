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

class RincianSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Rincian Detail';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 50, // Wider for descriptions/transactions
            'C' => 18,
            'D' => 18,
            'E' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Headers
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        return [];
    }
    
    public function array(): array
    {
        $rows = [];
        
        // Pemasukan Details
        $rows[] = ['I. RINCIAN PEMASUKAN'];
        $rows[] = ['No', 'Uraian', 'Anggaran', 'Realisasi', '%'];
        
        $no = 1;
        foreach ($this->data['laporanPemasukan'] as $item) {
            $rows[] = [
                $no++, 
                $item['nama'], 
                $item['anggaran'], 
                $item['realisasi'], 
                $item['persentase'] . '%'
            ];
            
            if (isset($item['transactions']) && count($item['transactions']) > 0) {
                foreach ($item['transactions'] as $tx) {
                     $rows[] = [
                         '', // Indent
                         '   - ' . $tx->deskripsi . ' (' . $tx->tanggal->format('d/m/Y') . ')',
                         '',
                         $tx->nominal,
                         ''
                     ];
                }
            }
        }
        $rows[] = ['']; // Spacer

        // Pengeluaran Dept Details
        $rows[] = ['II. RINCIAN PENGELUARAN DEPARTEMEN'];
        $rows[] = ['No', 'Departemen', 'Anggaran', 'Realisasi', '%'];
        
        $no = 1;
        foreach ($this->data['laporanDept'] as $item) {
            $rows[] = [
                $no++, 
                'Operasional ' . $item['nama'], 
                $item['anggaran'], 
                $item['realisasi'], 
                $item['persentase'] . '%'
            ];
            
            if (isset($item['transactions']) && count($item['transactions']) > 0) {
                foreach ($item['transactions'] as $tx) {
                     $rows[] = [
                         '', 
                         '   - ' . $tx->deskripsi . ' (' . $tx->tanggal->format('d/m/Y') . ')',
                         '',
                         $tx->nominal,
                         ''
                     ];
                }
            }
        }
        $rows[] = [''];

        // Pengeluaran Project Details
        $rows[] = ['III. RINCIAN PENGELUARAN PROJECT/KEGIATAN'];
        $rows[] = ['No', 'Project/Kegiatan', 'Anggaran', 'Realisasi', '%'];
        
        $no = 1;
        foreach ($this->data['laporanProject'] as $item) {
            $rows[] = [
                $no++, 
                $item['nama'], 
                $item['anggaran'], 
                $item['realisasi'], 
                $item['persentase'] . '%'
            ];
            
             if (isset($item['transactions']) && count($item['transactions']) > 0) {
                foreach ($item['transactions'] as $tx) {
                     $rows[] = [
                         '', 
                         '   - ' . $tx->deskripsi . ' (' . $tx->tanggal->format('d/m/Y') . ')',
                         '',
                         $tx->nominal,
                         ''
                     ];
                }
            }
        }
        $rows[] = [''];

        // Pengeluaran Lainnya
        $rows[] = ['IV. RINCIAN PENGELUARAN LAINNYA'];
        $rows[] = ['No', 'Item', 'Anggaran', 'Realisasi', '%'];
        
        $no = 1;
        foreach ($this->data['laporanLainnya'] as $item) {
            $rows[] = [
                $no++, 
                $item['nama'], 
                $item['anggaran'], 
                $item['realisasi'], 
                $item['persentase'] . '%'
            ];
            
             if (isset($item['transactions']) && count($item['transactions']) > 0) {
                foreach ($item['transactions'] as $tx) {
                     $rows[] = [
                         '', 
                         '   - ' . $tx->deskripsi . ' (' . $tx->tanggal->format('d/m/Y') . ')',
                         '',
                         $tx->nominal,
                         ''
                     ];
                }
            }
        }

        return $rows;
    }
}
