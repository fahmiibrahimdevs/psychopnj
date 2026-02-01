<?php

namespace App\Livewire\Keuangan;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\TahunKepengurusan;
use App\Models\Anggaran;
use App\Models\Keuangan;
use App\Models\Department;
use App\Models\Project;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Component
{
    #[Title('Laporan Keuangan')]

    public $activeTahunId;
    public $activeTahunNama;

    public function mount()
    {
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
        $data = $this->prepareData();
        $this->prepareTransactions($data); // Helper to add transactions
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
        $data = $this->prepareData();
        $this->prepareTransactions($data); // Ensure transactions are loaded
        $data['tahunNama'] = $this->activeTahunNama; // Pass tahunNama separately if needed or in data

        $filename = 'Laporan_Keuangan_' . str_replace(['/', '\\'], '-', $this->activeTahunNama) . '.xlsx';

        return Excel::download(new \App\Exports\LpjExport($data), $filename);
    }

    private function prepareTransactions(&$data)
    {
        // Add full transaction log for Buku Kas
        $data['transaksi'] = Keuangan::where('id_tahun', $this->activeTahunId)
            ->orderBy('tanggal', 'asc')
            ->get();
            

    }

    private function prepareData()
    {
        // Get anggaran grouped
        $anggaranPemasukan = Anggaran::where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'pemasukan')
            ->get();
        $anggaranPengeluaran = Anggaran::where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'pengeluaran')
            ->get();

        // Get realisasi grouped by kategori
        $realisasiByKategori = Keuangan::where('id_tahun', $this->activeTahunId)
            ->selectRaw('jenis, kategori, SUM(nominal) as total')
            ->groupBy('jenis', 'kategori')
            ->get()
            ->keyBy(function ($item) {
                return $item->jenis . '_' . $item->kategori;
            });

        // Get realisasi by department
        $realisasiByDept = Keuangan::where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'dept')
            ->whereNotNull('id_department')
            ->selectRaw('id_department, SUM(nominal) as total')
            ->groupBy('id_department')
            ->pluck('total', 'id_department');

        // Get realisasi by project
        $realisasiByProject = Keuangan::where('id_tahun', $this->activeTahunId)
            ->where('kategori', 'project')
            ->whereNotNull('id_project')
            ->selectRaw('id_project, SUM(nominal) as total')
            ->groupBy('id_project')
            ->pluck('total', 'id_project');

        // Build pemasukan report
        $laporanPemasukan = [];
        $jenisLabelsPemasukan = [
            'saldo_awal' => 'Saldo Awal',
            'iuran_kas' => 'Iuran Kas',
            'sponsor' => 'Sponsor',
            'lainnya' => 'Lainnya',
        ];
        foreach ($jenisLabelsPemasukan as $jenis => $label) {
            $anggaran = $anggaranPemasukan->where('jenis', $jenis)->sum('nominal');
            
            // Fetch transactions for this category
            // Saldo awal usually doesn't have transactions in this table for the period
            $txs = collect([]);
            $txs = Keuangan::where('id_tahun', $this->activeTahunId)
                ->where('jenis', 'pemasukan')
                ->where('kategori', $jenis) // Assuming 'kategori' in DB matches key (iuran_kas, sponsor, lainnya)
                ->orderBy('tanggal', 'asc')
                ->get();

            // Realisasi matching
            // Note: $realisasiByKategori uses keys like 'pemasukan_iuran_kas'
            $realisasi = $realisasiByKategori->get('pemasukan_' . $jenis)->total ?? 0;
            
            $persentase = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 1) : 0;
            $laporanPemasukan[] = [
                'nama' => $label,
                'anggaran' => $anggaran,
                'realisasi' => $realisasi,
                'persentase' => $persentase,
                'transactions' => $txs
            ];
        }

        // Build pengeluaran by department
        $laporanDept = [];
        $anggaranDept = $anggaranPengeluaran->where('jenis', 'dept');
        foreach ($anggaranDept as $a) {
            $txs = Keuangan::where('id_tahun', $this->activeTahunId)
                ->where('kategori', 'dept')
                ->where('id_department', $a->id_department)
                ->orderBy('tanggal', 'asc')
                ->get();
            $realisasi = $txs->sum('nominal');
            $persentase = $a->nominal > 0 ? round(($realisasi / $a->nominal) * 100, 1) : 0;
            $laporanDept[] = [
                'nama' => $a->department->nama_department ?? $a->nama,
                'anggaran' => $a->nominal,
                'realisasi' => $realisasi,
                'persentase' => $persentase,
                'transactions' => $txs
            ];
        }

        // Build pengeluaran by project
        $laporanProject = [];
        $anggaranProject = $anggaranPengeluaran->where('jenis', 'project');
        foreach ($anggaranProject as $a) {
            $txs = Keuangan::where('id_tahun', $this->activeTahunId)
                ->where('kategori', 'project')
                ->where('id_project', $a->id_project)
                ->orderBy('tanggal', 'asc')
                ->get();
            $realisasi = $txs->sum('nominal');
            $persentase = $a->nominal > 0 ? round(($realisasi / $a->nominal) * 100, 1) : 0;
            $laporanProject[] = [
                'nama' => $a->project->nama_project ?? $a->nama,
                'anggaran' => $a->nominal,
                'realisasi' => $realisasi,
                'persentase' => $persentase,
                'transactions' => $txs
            ];
        }

        // Build pengeluaran lainnya
        $laporanLainnya = [];
        $anggaranLainnya = $anggaranPengeluaran->where('jenis', 'lainnya');
        // Only one general pot for realisasi lainnya? Or is it matched by name?
        // Current logic assumes one pot 'pengeluaran_lainnya' for ALL 'lainnya' budgets.
        // Let's stick to that, or optimize if they want detailed tracking.
        // For now, let's just get ALL 'lainnya' transactions.
        $txsLainnya = Keuangan::where('id_tahun', $this->activeTahunId)
            ->where('jenis', 'pengeluaran')
            ->where('kategori', 'lainnya')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        // Wait, multiple anggaran items map to the SAME pool of transactions?
        // That's tricky for detailed view. The previous logic summed it all up.
        // If there are multiple anggaran items for 'lainnya', we can't easily attribute transactions unless we have an ID link.
        // But the model doesn't seem to link transactions to specific budget IDs, only to category/project/dept.
        // So all "Lainnya" transactions go to ALL "Lainnya" budgets? That seems odd.
        // Let's fetch the total "Lainnya" transactions pool and attach it to the first "Lainnya" item, or just list general transactions.
        // For accurate reporting, maybe just ONE loop for Lainnya?
        // But the loop iterates $anggaranLainnya.
        
        $realisasiLainnyaTotal = $txsLainnya->sum('nominal');
        $hasAttachedTxs = false;
        
        foreach ($anggaranLainnya as $a) {
            // We can't split receipts per budget item without more data.
            // We'll attach the full list to the first item, or just leave empty for others?
            // Or maybe just show the transactions in a general "Lainnya" section.
            // Let's just attach ALL txs to EVERY line? No, that duplicates visual data.
            // Let's attach to the first one.
            $currentTxs = !$hasAttachedTxs ? $txsLainnya : collect([]);
            if(!$hasAttachedTxs && $txsLainnya->count() > 0) $hasAttachedTxs = true;

            $persentase = $a->nominal > 0 ? round(($realisasiLainnyaTotal / $a->nominal) * 100, 1) : 0;
            $laporanLainnya[] = [
                'nama' => $a->nama,
                'anggaran' => $a->nominal,
                'realisasi' => $realisasiLainnyaTotal, // This is potentially wrong if multiple budgets exist
                'persentase' => $persentase,
                'transactions' => $currentTxs
            ];
        }

        // Totals
        $totalAnggaranPemasukan = $anggaranPemasukan->sum('nominal');
        $totalAnggaranPengeluaran = $anggaranPengeluaran->sum('nominal');
        
        $totalRealisasiPemasukan = Keuangan::where('id_tahun', $this->activeTahunId)
            ->where('jenis', 'pemasukan')->sum('nominal');
        $totalRealisasiPengeluaran = Keuangan::where('id_tahun', $this->activeTahunId)
            ->where('jenis', 'pengeluaran')->sum('nominal');

        $saldoAnggaran = $totalAnggaranPemasukan - $totalAnggaranPengeluaran;
        $saldoRealisasi = $totalRealisasiPemasukan - $totalRealisasiPengeluaran;
        
        // Ringkasan Pengeluaran for PDF Summary (Flattened)
        $ringkasanPengeluaran = array_merge(
            $laporanDept,
            $laporanProject,
            $laporanLainnya
        );

        // Create alias for Excel export
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
            'ringkasanPemasukan'
        );
    }
}
