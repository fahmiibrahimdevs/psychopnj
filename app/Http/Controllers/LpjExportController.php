<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunKepengurusan;
use App\Models\Anggaran;
use App\Models\Keuangan;
use App\Exports\LpjExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LpjExportController extends Controller
{
    public function exportPdf()
    {
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        
        if (!$activeTahun) {
            return back()->with('error', 'Tidak ada tahun kepengurusan aktif');
        }

        $data = $this->getLpjData($activeTahun->id);
        $data['tahunNama'] = $activeTahun->nama_tahun;

        $pdf = Pdf::loadView('exports.lpj-pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'Laporan_Keuangan_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $activeTahun->nama_tahun) . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportExcel()
    {
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        
        if (!$activeTahun) {
            return back()->with('error', 'Tidak ada tahun kepengurusan aktif');
        }

        $filename = 'Laporan_Keuangan_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $activeTahun->nama_tahun) . '.xlsx';
        
        return Excel::download(new LpjExport($activeTahun->id, $activeTahun->nama_tahun), $filename);
    }

    private function getLpjData($tahunId)
    {
        // Ringkasan Pemasukan
        $ringkasanPemasukan = [];
        $anggaranPemasukan = Anggaran::where('id_tahun', $tahunId)
            ->where('kategori', 'pemasukan')
            ->get();

        foreach ($anggaranPemasukan as $item) {
            $realisasi = Keuangan::where('id_tahun', $tahunId)
                ->where('jenis', 'pemasukan')
                ->where('kategori', $item->jenis)
                ->sum('nominal');
            
            $ringkasanPemasukan[] = [
                'nama' => $item->nama,
                'anggaran' => $item->nominal,
                'realisasi' => $realisasi,
                'persentase' => $item->nominal > 0 ? round(($realisasi / $item->nominal) * 100, 1) : 0
            ];
        }

        // Ringkasan Pengeluaran
        $ringkasanPengeluaran = [];
        $anggaranPengeluaran = Anggaran::where('id_tahun', $tahunId)
            ->where('kategori', 'pengeluaran')
            ->get();

        foreach ($anggaranPengeluaran as $item) {
            $queryRealisasi = Keuangan::where('id_tahun', $tahunId)
                ->where('jenis', 'pengeluaran')
                ->where('kategori', $item->jenis);
            
            if ($item->jenis === 'dept' && $item->id_department) {
                $queryRealisasi->where('id_department', $item->id_department);
            } elseif ($item->jenis === 'project' && $item->id_project) {
                $queryRealisasi->where('id_project', $item->id_project);
            }
            
            $realisasi = $queryRealisasi->sum('nominal');
            
            $ringkasanPengeluaran[] = [
                'nama' => $item->nama,
                'anggaran' => $item->nominal,
                'realisasi' => $realisasi,
                'persentase' => $item->nominal > 0 ? round(($realisasi / $item->nominal) * 100, 1) : 0
            ];
        }

        // Totals
        $totalAnggaranPemasukan = Anggaran::where('id_tahun', $tahunId)
            ->where('kategori', 'pemasukan')->sum('nominal');
        $totalRealisasiPemasukan = Keuangan::where('id_tahun', $tahunId)
            ->where('jenis', 'pemasukan')->sum('nominal');
        
        $totalAnggaranPengeluaran = Anggaran::where('id_tahun', $tahunId)
            ->where('kategori', 'pengeluaran')->sum('nominal');
        $totalRealisasiPengeluaran = Keuangan::where('id_tahun', $tahunId)
            ->where('jenis', 'pengeluaran')->sum('nominal');

        $saldoAnggaran = $totalAnggaranPemasukan - $totalAnggaranPengeluaran;
        $saldoRealisasi = $totalRealisasiPemasukan - $totalRealisasiPengeluaran;

        // Transaksi
        $transaksi = Keuangan::where('id_tahun', $tahunId)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return compact(
            'ringkasanPemasukan',
            'ringkasanPengeluaran',
            'totalAnggaranPemasukan',
            'totalRealisasiPemasukan',
            'totalAnggaranPengeluaran',
            'totalRealisasiPengeluaran',
            'saldoAnggaran',
            'saldoRealisasi',
            'transaksi'
        );
    }
}
