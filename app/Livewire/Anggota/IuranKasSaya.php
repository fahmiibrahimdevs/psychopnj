<?php

namespace App\Livewire\Anggota;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IuranKasSaya extends Component
{
    #[Title('Iuran Kas Saya')]

    public $anggota;
    public $iuranList = [];
    public $periodeList = [];
    public $totalBayar = 0;
    public $totalBelum = 0;

    public function mount()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            abort(403, 'Data anggota tidak ditemukan.');
        }

        $this->loadIuran();
    }

    public function loadIuran()
    {
        // Get all periods for this tahun
        $this->periodeList = DB::table('iuran_kas_periode')
            ->where('id_tahun', $this->anggota->id_tahun)
            ->orderBy('id')
            ->get();

        // Get paid records
        $paidRecords = DB::table('iuran_kas')
            ->where('id_anggota', $this->anggota->id)
            ->where('id_tahun', $this->anggota->id_tahun)
            ->get()
            ->keyBy('periode');

        $this->iuranList = [];
        $this->totalBayar = 0;
        $this->totalBelum = 0;

        if ($this->periodeList->isEmpty()) {
            // If no period defined, show raw payment records
            foreach ($paidRecords as $record) {
                $this->iuranList[] = (object)[
                    'nama_periode'  => $record->periode,
                    'status'        => 'sudah',
                    'tanggal_bayar' => $record->tanggal_bayar,
                ];
                $this->totalBayar++;
            }
        } else {
            foreach ($this->periodeList as $periode) {
                $paid = $paidRecords->get($periode->nama_periode);
                $status = $paid ? 'sudah' : 'belum';
                $tanggalBayar = $paid ? $paid->tanggal_bayar : null;

                $this->iuranList[] = (object)[
                    'nama_periode'  => $periode->nama_periode,
                    'status'        => $status,
                    'tanggal_bayar' => $tanggalBayar,
                ];

                if ($status === 'sudah') {
                    $this->totalBayar++;
                } else {
                    $this->totalBelum++;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.anggota.iuran-kas-saya');
    }
}
