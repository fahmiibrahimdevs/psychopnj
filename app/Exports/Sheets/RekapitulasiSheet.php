<?php

namespace App\Exports\Sheets;

use App\Models\Anggaran;
use App\Models\Keuangan;
use App\Models\Department;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapitulasiSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $tahunId;

    public function __construct($tahunId)
    {
        $this->tahunId = $tahunId;
    }

    public function title(): string
    {
        return 'Rekapitulasi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 18,
            'C' => 18,
            'D' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REKAPITULASI PER KATEGORI');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header styling
        $sheet->getStyle('A3:D3')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FCE5CD']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Data borders
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A3:D{$lastRow}")->applyFromArray([
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
        $rows[] = ['Kategori', 'Total Anggaran', 'Total Realisasi', 'Sisa Anggaran'];

        // Per Departemen
        $rows[] = [''];
        $rows[] = ['PENGELUARAN PER DEPARTEMEN', '', '', ''];
        
        $departments = Department::where('id_tahun', $this->tahunId)->get();
        foreach ($departments as $dept) {
            $anggaran = Anggaran::where('id_tahun', $this->tahunId)
                ->where('kategori', 'pengeluaran')
                ->where('jenis', 'dept')
                ->where('id_department', $dept->id)
                ->sum('nominal');
            
            $realisasi = Keuangan::where('id_tahun', $this->tahunId)
                ->where('jenis', 'pengeluaran')
                ->where('kategori', 'dept')
                ->where('id_department', $dept->id)
                ->sum('nominal');

            if ($anggaran > 0 || $realisasi > 0) {
                $rows[] = [
                    $dept->nama_department,
                    $anggaran,
                    $realisasi,
                    $anggaran - $realisasi
                ];
            }
        }

        // Per Project
        $rows[] = [''];
        $rows[] = ['PENGELUARAN PER PROJECT', '', '', ''];
        
        $projects = Project::where('id_tahun', $this->tahunId)->get();
        foreach ($projects as $proj) {
            $anggaran = Anggaran::where('id_tahun', $this->tahunId)
                ->where('kategori', 'pengeluaran')
                ->where('jenis', 'project')
                ->where('id_project', $proj->id)
                ->sum('nominal');
            
            $realisasi = Keuangan::where('id_tahun', $this->tahunId)
                ->where('jenis', 'pengeluaran')
                ->where('kategori', 'project')
                ->where('id_project', $proj->id)
                ->sum('nominal');

            if ($anggaran > 0 || $realisasi > 0) {
                $rows[] = [
                    $proj->nama_project,
                    $anggaran,
                    $realisasi,
                    $anggaran - $realisasi
                ];
            }
        }

        // lainnya
        $rows[] = [''];
        $rows[] = ['PENGELUARAN LAINNYA', '', '', ''];
        
        $anggaranLainnya = Anggaran::where('id_tahun', $this->tahunId)
            ->where('kategori', 'pengeluaran')
            ->where('jenis', 'lainnya')
            ->sum('nominal');
        
        $realisasiLainnya = Keuangan::where('id_tahun', $this->tahunId)
            ->where('jenis', 'pengeluaran')
            ->where('kategori', 'lainnya')
            ->sum('nominal');

        $rows[] = [
            'Lainnya',
            $anggaranLainnya,
            $realisasiLainnya,
            $anggaranLainnya - $realisasiLainnya
        ];

        // Total Summary
        $rows[] = [''];
        $totalAnggaran = Anggaran::where('id_tahun', $this->tahunId)
            ->where('kategori', 'pengeluaran')
            ->sum('nominal');
        $totalRealisasi = Keuangan::where('id_tahun', $this->tahunId)
            ->where('jenis', 'pengeluaran')
            ->sum('nominal');
        
        $rows[] = ['TOTAL PENGELUARAN', $totalAnggaran, $totalRealisasi, $totalAnggaran - $totalRealisasi];

        return $rows;
    }
}
