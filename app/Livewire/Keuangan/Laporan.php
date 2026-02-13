<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Anggaran;
use App\Models\Keuangan;
use App\Models\Department;
use App\Models\Project;
use App\Models\JenisAnggaran;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\WithPermissionCache;

class Laporan extends Component
{
    use WithPermissionCache;
    #[Title('Laporan Keuangan')]

    public $activeTahunId;
    public $activeTahunNama;

    public function mount()
    {
        $this->cacheUserPermissions();
        $activeTahun = TahunKepengurusan::where('status', 'aktif')->first();
        $this->activeTahunId = $activeTahun ? $activeTahun->id : null;
        $this->activeTahunNama = $activeTahun ? $activeTahun->nama_tahun : '-';
    }

    public function render()
    {
        $data = $this->prepareData();
        return view('livewire.keuangan.laporan', $data);
    }

    public function downloadPdf()
    {
        $data = $this->prepareData(true);
        $data['tahunNama'] = $this->activeTahunNama;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.lpj-detail-pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = 'Laporan_Keuangan_' . str_replace(['/', '\\'], '-', $this->activeTahunNama) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function downloadExcel()
    {
        $data = $this->prepareData(true);
        $data['tahunNama'] = $this->activeTahunNama;

        $filename = 'Laporan_Keuangan_' . str_replace(['/', '\\'], '-', $this->activeTahunNama) . '.xlsx';

        return Excel::download(new \App\Exports\LpjExport($data), $filename);
    }



    private function prepareData($includeTransactions = false)
    {
        // 1. Fetch ALL Keuangan (Transactions) for the year efficiently
        $allTransactions = \Illuminate\Support\Facades\DB::table('keuangan')
            ->where('id_tahun', $this->activeTahunId)
            ->orderBy('tanggal', 'asc')
            ->get();
            
        // Convert dates to Carbon objects for compatibility
        $allTransactions->transform(function ($item) {
            $item->tanggal = \Carbon\Carbon::parse($item->tanggal);
            return $item;
        });

        // 2. Fetch ALL Anggaran (Budgets) with Joins
        $allAnggaran = \Illuminate\Support\Facades\DB::table('anggaran')
            ->leftJoin('departments', 'anggaran.id_department', '=', 'departments.id')
            ->leftJoin('projects', 'anggaran.id_project', '=', 'projects.id')
            ->select(
                'anggaran.*',
                'departments.nama_department',
                'projects.nama_project'
            )
            ->where('anggaran.id_tahun', $this->activeTahunId)
            ->get();

        $anggaranPemasukan = $allAnggaran->where('kategori', 'pemasukan');
        $anggaranPengeluaran = $allAnggaran->where('kategori', 'pengeluaran');

        // 3. Build Reports
        // Build pemasukan report
        $laporanPemasukan = [];
        $jenisAnggaranPemasukan = \Illuminate\Support\Facades\DB::table('jenis_anggaran')
            ->where('nama_kategori', 'pemasukan')
            ->orderBy('nama_jenis')
            ->get();
        
        foreach ($jenisAnggaranPemasukan as $ja) {
            $jenis = $ja->nama_jenis;
            $anggaran = $anggaranPemasukan->where('jenis', $jenis)->sum('nominal');
            
            // Filter transactions for this category
            $txs = $allTransactions->filter(function ($item) use ($jenis) {
                return $item->jenis === 'pemasukan' && $item->kategori === $jenis;
            });
            
            $realisasi = $txs->sum('nominal');
            
            $persentase = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 1) : 0;
            $laporanPemasukan[] = [
                'nama' => $jenis,
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'persentase' => $persentase,
                'transactions' => $includeTransactions ? $txs : collect([])
            ];
        }

        // Build pengeluaran by department
        $laporanDept = [];
        $anggaranDept = $anggaranPengeluaran->where('jenis', 'Departemen');
        foreach ($anggaranDept as $a) {
            $txs = $allTransactions->filter(function ($item) use ($a) {
                return $item->kategori === 'Departemen' && $item->id_department == $a->id_department;
            });
            
            $realisasi = $txs->sum('nominal');
            $persentase = $a->nominal > 0 ? round(($realisasi / $a->nominal) * 100, 1) : 0;
            $laporanDept[] = [
                'nama' => $a->nama_department ?? $a->nama,
                'anggaran' => $a->nominal,
                'realisasi' => $realisasi,
                'persentase' => $persentase,
                'transactions' => $includeTransactions ? $txs : collect([])
            ];
        }

        // Build pengeluaran by project
        $laporanProject = [];
        $anggaranProject = $anggaranPengeluaran->where('jenis', 'Project');
        foreach ($anggaranProject as $a) {
             $txs = $allTransactions->filter(function ($item) use ($a) {
                return $item->kategori === 'Project' && $item->id_project == $a->id_project;
            });

            $realisasi = $txs->sum('nominal');
            $persentase = $a->nominal > 0 ? round(($realisasi / $a->nominal) * 100, 1) : 0;
            $laporanProject[] = [
                'nama' => $a->nama_project ?? $a->nama,
                'anggaran' => $a->nominal,
                'realisasi' => $realisasi,
                'persentase' => $persentase,
                'transactions' => $includeTransactions ? $txs : collect([])
            ];
        }

        // Build pengeluaran lainnya
        $laporanLainnya = [];
        $anggaranLainnya = $anggaranPengeluaran->where('jenis', 'Pengeluaran Lainnya');
        
        // Filter 'lainnya' transactions once
        $txsLainnya = $allTransactions->filter(function ($item) {
             return $item->jenis === 'pengeluaran' && $item->kategori === 'Pengeluaran Lainnya';
        });
            
        $realisasiLainnyaTotal = $txsLainnya->sum('nominal');
        $hasAttachedTxs = false;
        
        foreach ($anggaranLainnya as $a) {
            $currentTxs = (!$hasAttachedTxs && $includeTransactions) ? $txsLainnya : collect([]);
            if(!$hasAttachedTxs && $txsLainnya->count() > 0) $hasAttachedTxs = true;

            $persentase = $a->nominal > 0 ? round(($realisasiLainnyaTotal / $a->nominal) * 100, 1) : 0;
            $laporanLainnya[] = [
                'nama' => $a->nama,
                'anggaran' => $a->nominal,
                'realisasi' => $realisasiLainnyaTotal, // Shared realized total
                'persentase' => $persentase,
                'transactions' => $currentTxs
            ];
        }

        // Totals
        $totalAnggaranPemasukan = $anggaranPemasukan->sum('nominal');
        $totalAnggaranPengeluaran = $anggaranPengeluaran->sum('nominal');
        
        $totalRealisasiPemasukan = $allTransactions->where('jenis', 'pemasukan')->sum('nominal');
        $totalRealisasiPengeluaran = $allTransactions->where('jenis', 'pengeluaran')->sum('nominal');

        $saldoAnggaran = $totalAnggaranPemasukan - $totalAnggaranPengeluaran;
        $saldoRealisasi = $totalRealisasiPemasukan - $totalRealisasiPengeluaran;
        
        // Ringkasan Pengeluaran for PDF Summary
        $ringkasanPengeluaran = array_merge(
            $laporanDept,
            $laporanProject,
            $laporanLainnya
        );

        $ringkasanPemasukan = $laporanPemasukan;

        return compact(
            'laporanPemasukan',
            'laporanDept',
            'laporanProject',
            'laporanLainnya',
            'totalAnggaranPemasukan',
            'totalAnggaranPengeluaran',
            'totalRealisasiPemasukan',
            'totalRealisasiPengeluaran',
            'saldoAnggaran',
            'saldoRealisasi',
            'ringkasanPengeluaran',
            'ringkasanPemasukan',
            'allTransactions' // Optional if needed directly
        ) + ($includeTransactions ? ['transaksi' => $allTransactions] : []);
    }
}
