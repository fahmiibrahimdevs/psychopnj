<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IuranKasExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $matrix;
    protected $periodeList;
    protected $summary;

    public function __construct($matrix, $periodeList, $summary)
    {
        $this->matrix = $matrix;
        $this->periodeList = $periodeList;
        $this->summary = $summary;
    }

    public function view(): View
    {
        return view('exports.iuran-kas-table', [
            'matrix' => $this->matrix,
            'periodeList' => $this->periodeList,
            'summary' => $this->summary
        ]);
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1    => ['font' => ['bold' => true, 'size' => 14]],
            // Style the header row
            3    => ['font' => ['bold' => true]],
        ];
    }
}
