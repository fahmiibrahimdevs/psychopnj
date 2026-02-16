<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Anggota;
use App\Models\TahunKepengurusan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use WithPagination;

    #[Title('Dashboard')]

    protected $paginationTheme = 'bootstrap';

    public $anggota;
    public $searchTerm = '';
    public $lengthData = 25;

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedLengthData()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $user = User::find(Auth::user()->id);
        
        if($user->hasRole(['chairman', 'admin_media', 'admin_pengajaran', 'admin_keuangan', 'admin_inventaris', 'admin_sekretaris', 'admin_project', 'super_admin'])) {
            return view('livewire.dashboard.dashboard-pengurus');
        } else if ($user->hasRole('anggota')) {
            $members = $this->loadAnggotaDashboard();
            return view('livewire.dashboard.dashboard-anggota', [
                'members' => $members
            ]);
        }

        return view('livewire.dashboard.dashboard');
    }

    private function loadAnggotaDashboard()
    {
        $this->anggota = Anggota::where('id_user', Auth::user()->id)->first();

        if (!$this->anggota) {
            return collect([]);
        }

        $tahunAktif = TahunKepengurusan::where('status', 'aktif')->first();
        
        if (!$tahunAktif) {
            return collect([]);
        }

        $search = '%' . $this->searchTerm . '%';

        // Get all relevant meetings for the active year
        $pertemuans = DB::table('pertemuan')
            ->join('program_pembelajaran', 'pertemuan.id_program', '=', 'program_pembelajaran.id')
            ->where('program_pembelajaran.id_tahun', $tahunAktif->id)
            ->where('pertemuan.tanggal', '<=', now()->toDateString())
            ->select('pertemuan.id', 'pertemuan.jenis_presensi')
            ->get()
            ->keyBy('id');

        // Calculate relevant meetings for anggota
        $relevantPertemuans = $pertemuans->filter(function($p) {
            $jenis = $p->jenis_presensi ? explode(',', $p->jenis_presensi) : ['pengurus', 'anggota'];
            return in_array('anggota', $jenis);
        });
        $relevantMeetingIds = $relevantPertemuans->keys()->toArray();

        // Query members with pagination and search
        $membersQuery = DB::table('anggota')
            ->select(
                'anggota.id',
                'anggota.nama_lengkap',
                'anggota.status_anggota',
                'anggota.foto',
                'anggota.jurusan_prodi_kelas'
            )
            ->where('anggota.id_tahun', $tahunAktif->id)
            ->where('anggota.status_aktif', 'aktif')
            ->where('anggota.status_anggota', 'anggota')
            ->where('anggota.nama_lengkap', 'LIKE', $search);

        // Add subquery for attendance count to sort by it
        if (!empty($relevantMeetingIds)) {
            $placeholders = implode(',', array_fill(0, count($relevantMeetingIds), '?'));
            $membersQuery->selectRaw(
                "(SELECT COUNT(*) FROM presensi_pertemuan WHERE presensi_pertemuan.id_anggota = anggota.id AND presensi_pertemuan.status = 'hadir' AND presensi_pertemuan.id_pertemuan IN ($placeholders)) as attendance_count", 
                $relevantMeetingIds
            )
            ->orderByDesc('attendance_count');
        } else {
            $membersQuery->selectRaw("0 as attendance_count");
        }

        $membersQuery->orderBy('anggota.nama_lengkap');

        $members = $membersQuery->paginate($this->lengthData);

        // Get member IDs from current page
        $memberIds = $members->pluck('id')->toArray();

        // Get presensi data for current page members only
        $presensiData = DB::table('presensi_pertemuan')
            ->whereIn('id_anggota', $memberIds)
            ->select('id_anggota', 'status', 'id_pertemuan')
            ->get()
            ->groupBy('id_anggota');

        // Process each member in current page
        $members->getCollection()->transform(function ($member) use ($presensiData, $relevantPertemuans, $relevantMeetingIds) {
            $memberPresensi = $presensiData->get($member->id, collect([]));

            $totalPertemuanWajib = $relevantPertemuans->count();

            // Filter presensi to only include relevant meetings
            $filteredPresensi = $memberPresensi->whereIn('id_pertemuan', $relevantMeetingIds);

            // Calculate stats
            $totalHadir = $filteredPresensi->where('status', 'hadir')->count();
            $totalIzin = $filteredPresensi->where('status', 'izin')->count();
            $totalSakit = $filteredPresensi->where('status', 'sakit')->count();
            $totalAlfa = $filteredPresensi->where('status', 'alfa')->count();

            // Calculate missing meetings
            $attendedMeetingIds = $filteredPresensi->pluck('id_pertemuan')->toArray();
            $missingCount = 0;
            foreach($relevantPertemuans as $rp) {
                if (!in_array($rp->id, $attendedMeetingIds)) {
                    $missingCount++;
                }
            }

            $totalAlfaCombined = $totalAlfa + $missingCount;
            $percentage = $totalPertemuanWajib > 0 ? ($totalHadir / $totalPertemuanWajib) * 100 : 0;

            $member->stats = [
                'hadir' => $totalHadir,
                'izin' => $totalIzin,
                'sakit' => $totalSakit,
                'alfa' => $totalAlfaCombined,
                'total_wajib' => $totalPertemuanWajib,
                'percentage' => round($percentage, 0)
            ];

            return $member;
        });

        return $members;
    }
}
