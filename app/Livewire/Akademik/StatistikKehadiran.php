<?php

namespace App\Livewire\Akademik;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use App\Models\TahunKepengurusan;

class StatistikKehadiran extends Component
{
    use WithPagination;

    #[Title('Statistik Kehadiran')]

    protected $paginationTheme = 'bootstrap';

    public $activeTab = 'pengurus';
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

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        $tahunAktif = TahunKepengurusan::where('status', 'aktif')->first();

        if (!$tahunAktif) {
            return view('livewire.akademik.statistik-kehadiran', [
                'data' => collect([]),
            ]);
        }

        $search = '%' . $this->searchTerm . '%';

        // 1. Get all relevant meetings for the active year, keyed by ID for O(1) lookup
        $pertemuans = DB::table('pertemuan')
            ->join('program_pembelajaran', 'pertemuan.id_program', '=', 'program_pembelajaran.id')
            ->where('program_pembelajaran.id_tahun', $tahunAktif->id)
            ->select('pertemuan.id', 'pertemuan.jenis_presensi', 'pertemuan.judul_pertemuan', 'pertemuan.pertemuan_ke', 'program_pembelajaran.nama_program')
            ->get()
            ->keyBy('id');

        // Calculate Relevant Meeting IDs for the current Tab
        $relevantMeetingIds = $pertemuans->filter(function($p) {
            $jenis = $p->jenis_presensi ? explode(',', $p->jenis_presensi) : ['pengurus', 'anggota'];
            return in_array($this->activeTab, $jenis);
        })->pluck('id')->toArray();

        // 2. Query Members with Pagination filtered by Active Tab
        $membersQuery = DB::table('anggota')
            ->select(
                'anggota.id',
                'anggota.nama_lengkap',
                'anggota.status_anggota',
                'anggota.foto',
                'anggota.jurusan_prodi_kelas',
                'departments.nama_department'
            )
            ->leftJoin('departments', 'anggota.id_department', '=', 'departments.id')
            ->where('anggota.id_tahun', $tahunAktif->id)
            ->where('anggota.status_aktif', 'aktif')
            ->where('anggota.nama_lengkap', 'LIKE', $search)
            ->where('anggota.status_anggota', $this->activeTab);
            
        // Add subquery for attendance count to sort by it
        if (!empty($relevantMeetingIds)) {
            $placeholders = implode(',', array_fill(0, count($relevantMeetingIds), '?'));
            // Bindings must be flat array
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

        // 3. Process current page items to calculate stats
        $memberIds = $members->pluck('id');

        // Optimized: Only fetch presensi data, no joins needed (we have meeting info in $pertemuans)
        $presensiData = DB::table('presensi_pertemuan')
            ->whereIn('id_anggota', $memberIds)
            ->select('id_anggota', 'status', 'id_pertemuan')
            ->get()
            ->groupBy('id_anggota');

        // Transform the collection
        $members->getCollection()->transform(function ($member) use ($presensiData, $pertemuans) {
            $memberPresensi = $presensiData->get($member->id, collect([]));

            // Calculate Total Required Meetings for specific member role
            $relevantPertemuans = $pertemuans->filter(function($p) use ($member) {
                $jenis = $p->jenis_presensi ? explode(',', $p->jenis_presensi) : ['pengurus', 'anggota'];
                return in_array($member->status_anggota, $jenis);
            });
            $totalPertemuanWajib = $relevantPertemuans->count();

            // Filter presensi to only include relevant meetings (ignore meetings where they are no longer invited)
            $relevantPertemuanIds = $relevantPertemuans->keys()->toArray(); // Keys are IDs because of keyBy('id')
            $filteredPresensi = $memberPresensi->whereIn('id_pertemuan', $relevantPertemuanIds);

            // Calculate Counts using filtered data
            $totalHadir = $filteredPresensi->where('status', 'hadir')->count();
            $totalIzin  = $filteredPresensi->where('status', 'izin')->count();
            $totalSakit = $filteredPresensi->where('status', 'sakit')->count();
            $totalAlfa  = $filteredPresensi->where('status', 'alfa')->count();

            // Calculate Percentage
            $percentage = $totalPertemuanWajib > 0 ? ($totalHadir / $totalPertemuanWajib) * 100 : 0;
            $percentage = round($percentage, 0);

            // Attendance History with Program Name for grouping
            $history = $filteredPresensi->map(function($p) use ($pertemuans) {
                // Lookup meeting details from loaded collection
                $meeting = $pertemuans[$p->id_pertemuan] ?? null;
                if (!$meeting) return null;

                return [
                    'program' => $meeting->nama_program,
                    'pertemuan' => $meeting->judul_pertemuan . ' (Ke-' . $meeting->pertemuan_ke . ')',
                    'status' => $p->status,
                    'date_order' => $meeting->pertemuan_ke 
                ];
            })->filter(); // filter out nulls

            // Identify Missing Meetings
            $attendedMeetingIds = $filteredPresensi->pluck('id_pertemuan')->toArray();
            $missingMeetings = [];
            
            foreach($relevantPertemuans as $rp) {
                if (!in_array($rp->id, $attendedMeetingIds)) {
                    $missingMeetings[] = [
                        'program' => $rp->nama_program,
                        'pertemuan' => $rp->judul_pertemuan . ' (Ke-' . $rp->pertemuan_ke . ')',
                        'status' => 'tanpa_keterangan',
                        'date_order' => $rp->pertemuan_ke
                    ];
                }
            }
            
            // Treat missing as alfa in counts if needed
            $totalAlfaCombined = $totalAlfa + count($missingMeetings);

            // Add stats to member object
            $member->stats = [
                'hadir' => $totalHadir,
                'izin' => $totalIzin,
                'sakit' => $totalSakit,
                'alfa' => $totalAlfaCombined,
                'total_wajib' => $totalPertemuanWajib,
                'percentage' => $percentage
            ];
            
            // Merge, Sort, and Group History
            $fullHistory = $history->merge($missingMeetings)->sortBy('date_order')->values();
            
            // Group by Program Name
            $groupedHistory = $fullHistory->groupBy('program')->toArray();
            
            $member->history = $groupedHistory;

            return $member;
        });

        return view('livewire.akademik.statistik-kehadiran', [
            'data' => $members
        ]);
    }
}
