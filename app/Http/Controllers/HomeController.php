<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Ceiling;
use App\Models\IKPAScore;
use App\Models\EPerformance;
use App\Models\Emonev;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            $tahun = $request->get('tahun') ?? date('Y');
            $bulan = $request->get('bulan') ?? date('n');
            return view('pages.home.index', compact( 'tahun', 'bulan'));
        } catch (\Throwable $e) {
            dd($e);
        }
        // return view('pages.home.index');
    }

    // public function dataDashboard(Request $request)
    // {
    //     try {
    //         $tahun = $request->get('tahun', date('Y'));
    //         $bulan = $request->get('bulan', date('n'));
    
    //         $budgets = Budget::where('tahun', $tahun)
    //                         ->where('bulan','<=', $bulan)
    //                         ->with('departement')
    //                         ->get();
    
    //         if ($budgets->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'msg'    => 'Tidak Ada Data Bulan '.$bulan.' Pada Tahun '.$tahun,
    //             ], 404);
    //         }
    
    //         // Total pagu & realisasi
    //         $budgets_year = Budget::where('tahun', $tahun)
    //                     ->with('departement')
    //                     ->get();

    //         // Total setahun penuh
    //         $totalPagu =  $budgets_year->sum('pagu_pegawai')
    //             +  $budgets_year->sum('pagu_barang')
    //             +  $budgets_year->sum('pagu_modal');

    //         $totalRealisasi =  $budgets_year->sum('realisasi_pegawai')
    //                     +  $budgets_year->sum('realisasi_barang')
    //                     +  $budgets_year->sum('realisasi_modal');

    //         $persentase = $totalPagu > 0
    //         ? round(($totalRealisasi / $totalPagu) * 100, 2)
    //         : 0;
    
    //         // Data bulanan
    //         $monthlyData      = [];
    //         $monthlyPagu      = [];
    //         $monthlyRealisasi = [];
    //         $categories       = [];
    
    //         for ($m = 1; $m <= $bulan; $m++) {
    //             $mBudget = Budget::where('tahun', $tahun)->where('bulan', $m)->get();
    
    //             $pagu = $mBudget->sum('pagu_pegawai')
    //                   + $mBudget->sum('pagu_barang')
    //                   + $mBudget->sum('pagu_modal');
    
    //             $realisasi = $mBudget->sum('realisasi_pegawai')
    //                         + $mBudget->sum('realisasi_barang')
    //                         + $mBudget->sum('realisasi_modal');
    
    //             $monthlyPagu[]      = $pagu;
    //             $monthlyRealisasi[] = $realisasi;
    //             $monthlyData[]      = $pagu > 0 ? round(($realisasi / $pagu) * 100, 2) : 0;
    //             $categories[]       = date("M", mktime(0, 0, 0, $m, 1));
    //         }
    
    //         // Detail kategori
    //         $pegawaiRealisasi = $budgets->sum('realisasi_pegawai');
    //         $pegawaiTotal     = $budgets->sum('pagu_pegawai');
    
    //         $barangRealisasi  = $budgets->sum('realisasi_barang');
    //         $barangTotal      = $budgets->sum('pagu_barang');
    
    //         $modalRealisasi   = $budgets->sum('realisasi_modal');
    //         $modalTotal       = $budgets->sum('pagu_modal');
    
    //         // Hitung target Triwulan
    //         $currentQuarter   = ceil($bulan / 3); // triwulan ke berapa
    //         $quarterEndMonth  = $currentQuarter * 3;
    //         $quarterBudget = Budget::where('tahun', $tahun)
    //             ->whereBetween('bulan', [($currentQuarter - 1) * 3 + 1, $quarterEndMonth])
    //             ->get();
    
    //         $quarterPagu = $quarterBudget->sum('pagu_pegawai')
    //                      + $quarterBudget->sum('pagu_barang')
    //                      + $quarterBudget->sum('pagu_modal');
    
    //         $quarterRealisasi = $quarterBudget->sum('realisasi_pegawai')
    //                            + $quarterBudget->sum('realisasi_barang')
    //                            + $quarterBudget->sum('realisasi_modal');
    
    //         $triwulanPercent = $quarterPagu > 0
    //             ? round((($quarterRealisasi - $quarterPagu) / $quarterPagu) * 100, 2)
    //             : 0;
    
    //         // Hitung target Tahunan
    //         $yearBudget = Budget::where('tahun', $tahun)->get();
    
    //         $yearPagu = $yearBudget->sum('pagu_pegawai')
    //                   + $yearBudget->sum('pagu_barang')
    //                   + $yearBudget->sum('pagu_modal');
    
    //         $yearRealisasi = $yearBudget->sum('realisasi_pegawai')
    //                       + $yearBudget->sum('realisasi_barang')
    //                       + $yearBudget->sum('realisasi_modal');
    
    //         $tahunanPercent = $yearPagu > 0
    //             ? round((($yearRealisasi - $yearPagu) / $yearPagu) * 100, 2)
    //             : 0;
    
    //         // Render partial view kalau dipakai
    //         $html = view('pages.home.components.data', [
    //             'budgets'        => $budgets,
    //             'totalPagu'      => $totalPagu,
    //             'totalRealisasi' => $totalRealisasi,
    //             'persentase'     => $persentase,
    //              // tambahan card bawah
    //             'triwulanPercent' => $triwulanPercent,
    //             'tahunanPercent'  => $tahunanPercent,
    //             'tahun'=>$tahun,
    //             'bulan'=>$bulan
    //         ])->render();
    
    //         return response()->json([
    //             'status'          => 'success',
    //             'html'            => $html,
    //             'persentase'      => $persentase,
    
    //             'pegawaiRealisasi'=> $pegawaiRealisasi,
    //             'pegawaiTotal'    => $pegawaiTotal,
    //             'barangRealisasi' => $barangRealisasi,
    //             'barangTotal'     => $barangTotal,
    //             'modalRealisasi'  => $modalRealisasi,
    //             'modalTotal'      => $modalTotal,
    
    //             'monthlyData'     => $monthlyData,     
    //             'monthlyPagu'     => $monthlyPagu, 
    //             'monthlyRealisasi'=> $monthlyRealisasi,
    //             'categories'      => $categories,       
    
    //             'totalPagu'       => $totalPagu,
    //             'totalRealisasi'  => $totalRealisasi,
    
    //             // tambahan card bawah
    //             'triwulanPercent' => $triwulanPercent,
    //             'tahunanPercent'  => $tahunanPercent,
    //         ]);
    
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'status'  => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    


public function dataDashboard(Request $request)
{
    try {
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('n'));


        $budgets = Budget::where('tahun', $tahun)
                        ->where('bulan', '<=', $bulan)
                        ->with(['departement', 'ceiling'])
                        ->get();

        if ($budgets->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'msg'    => "Tidak Ada Data Bulan $bulan Pada Tahun $tahun",
            ], 404);
        }


        $ceilings = Ceiling::where('tahun', $tahun)->get();
        $pegawaiTotal = $ceilings->where('type_data', 'pegawai')->sum('nominal');
        $barangTotal  = $ceilings->where('type_data', 'barang')->sum('nominal');
        $modalTotal   = $ceilings->where('type_data', 'modal')->sum('nominal');
        $totalPagu    = $pegawaiTotal + $barangTotal + $modalTotal;


        // $pegawaiRealisasi = $budgets->filter(fn($b) => optional($b->ceiling)->type_data === 'pegawai')
        // ->sum('realisasi_pegawai');
    
        // $barangRealisasi = $budgets->filter(fn($b) => optional($b->ceiling)->type_data === 'barang')
        //     ->sum('realisasi_barang');
        
        // $modalRealisasi = $budgets->filter(fn($b) => optional($b->ceiling)->type_data === 'modal')
        //     ->sum('realisasi_modal');

        // Ambil hanya record terakhir per departement_id + ceiling_id
        $lastBudgets = $budgets
        ->sortByDesc('bulan') 
        ->unique(fn($b) => $b->departement_id . '-' . $b->ceiling_id); 

        $pegawaiRealisasi = $lastBudgets->filter(fn($b) => optional($b->ceiling)->type_data === 'pegawai')
        ->sum('realisasi_pegawai');

        $barangRealisasi = $lastBudgets->filter(fn($b) => optional($b->ceiling)->type_data === 'barang')
        ->sum('realisasi_barang');

        $modalRealisasi = $lastBudgets->filter(fn($b) => optional($b->ceiling)->type_data === 'modal')
        ->sum('realisasi_modal');
        
        // dd($pegawaiRealisasi);
        $totalRealisasi   = $pegawaiRealisasi + $barangRealisasi + $modalRealisasi;


        $persentase = $totalPagu > 0
            ? round(($totalRealisasi / $totalPagu) * 100, 2)
            : 0;


        $monthlyData = [];
        $monthlyPagu = [];
        $monthlyRealisasi = [];
        $categories = [];

        for ($m = 1; $m <= $bulan; $m++) {
            $mBudget = $budgets->where('bulan', $m);

            $realisasi = $mBudget->sum('realisasi_pegawai')
                        + $mBudget->sum('realisasi_barang')
                        + $mBudget->sum('realisasi_modal');

            $monthlyRealisasi[] = $realisasi;
            $monthlyPagu[]      = $totalPagu;
            $monthlyData[]      = $totalPagu > 0 ? round(($realisasi / $totalPagu) * 100, 2) : 0;
            $categories[]       = date("M", mktime(0, 0, 0, $m, 1));
        }

   
        $currentQuarter  = ceil($bulan / 3);
        $quarterStartMonth = ($currentQuarter - 1) * 3 + 1;
        $quarterEndMonth   = $currentQuarter * 3;

        $quarterBudget = $budgets->whereBetween('bulan', [$quarterStartMonth, $quarterEndMonth]);
        $quarterRealisasi = $quarterBudget->sum('realisasi_pegawai')
                            + $quarterBudget->sum('realisasi_barang')
                            + $quarterBudget->sum('realisasi_modal');

        $triwulanPercent = $totalPagu > 0
            ? round(($quarterRealisasi / $totalPagu) * 100, 2)
            : 0;

        // $yearBudget = Budget::where('tahun', $tahun)->get();

        // $yearRealisasi = $yearBudget->sum('realisasi_pegawai')
        //                 + $yearBudget->sum('realisasi_barang')
        //                 + $yearBudget->sum('realisasi_modal');

        // $tahunanPercent = $totalPagu > 0
        //     ? round(($yearRealisasi / $totalPagu) * 100, 2)
        //     : 0;

        $yearBudget = Budget::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan) // ambil sampai bulan berjalan
            ->with(['ceiling'])
            ->get();

        // Ambil hanya data terakhir per departemen + ceiling
        $lastYearBudgets = $yearBudget
            ->sortByDesc('bulan')
            ->unique(fn($b) => $b->departement_id . '-' . $b->ceiling_id);

        // Hitung total realisasi terakhir per kategori
        $pegawaiRealisasiLast = $lastYearBudgets
            ->filter(fn($b) => optional($b->ceiling)->type_data === 'pegawai')
            ->sum('realisasi_pegawai');

        $barangRealisasiLast = $lastYearBudgets
            ->filter(fn($b) => optional($b->ceiling)->type_data === 'barang')
            ->sum('realisasi_barang');

        $modalRealisasiLast = $lastYearBudgets
            ->filter(fn($b) => optional($b->ceiling)->type_data === 'modal')
            ->sum('realisasi_modal');

        // Total semua kategori
        $yearRealisasiLast = $pegawaiRealisasiLast + $barangRealisasiLast + $modalRealisasiLast;

        // Hitung persen dari total pagu
        $tahunanPercent = $totalPagu > 0
            ? round(($yearRealisasiLast / $totalPagu) * 100, 2)
            : 0;

        $ikpaData = IKPAScore::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->get();

    
        $ikpaRataRata = $ikpaData->avg('nilai_ikpa') ?? 0;
        $ikpaKeterangan = match (true) {
            $ikpaRataRata >= 95 => 'Sangat Baik',
            $ikpaRataRata >= 90 => 'Baik',
            $ikpaRataRata >= 80 => 'Cukup',
            default => 'Kurang',
        };


        $eperformanceData = EPerformance::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->get();
        $eperformanceCapaian = $eperformanceData->avg('persentase_capaian') ?? 0;

   
        $emonevData = Emonev::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->get();

        $emonevKinerja  = $emonevData->isNotEmpty() ? $emonevData->avg('kinerja_satker') : 0;
        $emonevAnggaran = $emonevData->isNotEmpty() ? $emonevData->avg('anggaran') : 0;
        $emonevKeterangan = $emonevData->isNotEmpty()
    ? $emonevData->groupBy('keterangan')->sortByDesc(fn($g) => $g->count())->keys()->first()
    : '-';



        // New Triwulan Data
        $triwulanList = [
            1 => [1, 3],   // TW1: Jan–Mar
            2 => [4, 6],   // TW2: Apr–Jun
            3 => [7, 9],   // TW3: Jul–Sep
            4 => [10, 12], // TW4: Okt–Des
        ];
        
        $triwulanData = collect();
        
        foreach ($triwulanList as $tw => [$start, $end]) {
            $quarterBudget = $budgets->filter(fn($b) =>
                $b->bulan >= $start && $b->bulan <= $end
            );
        
            // Ambil hanya data terakhir per departemen+ceiling di triwulan ini
            $lastQuarterBudgets = $quarterBudget
                ->sortByDesc('bulan')
                ->unique(fn($b) => $b->departement_id . '-' . $b->ceiling_id);
        
            // Hitung total realisasi terakhir per triwulan
            $pegawai = $lastQuarterBudgets
                ->filter(fn($b) => optional($b->ceiling)->type_data === 'pegawai')
                ->sum('realisasi_pegawai');
        
            $barang = $lastQuarterBudgets
                ->filter(fn($b) => optional($b->ceiling)->type_data === 'barang')
                ->sum('realisasi_barang');
        
            $modal = $lastQuarterBudgets
                ->filter(fn($b) => optional($b->ceiling)->type_data === 'modal')
                ->sum('realisasi_modal');
        
            $total = $pegawai + $barang + $modal;
        
            $percent = $totalPagu > 0
                ? round(($total / $totalPagu) * 100, 2)
                : 0;
        
            $triwulanData->push([
                'kode' => "TW$tw",
                'periode' => "Bulan $start-$end",
                'pegawai' => $pegawai,
                'barang' => $barang,
                'modal' => $modal,
                'total' => $total,
                'persentase' => $percent,
            ]);
        }
        // dd($triwulanData);

  // ============================
// AGREGASI UNTUK TABEL REKAP (HISTORI BULAN TERAKHIR)
// ============================

// Ambil data budgets sampai bulan berjalan
$budgets_history = Budget::where('tahun', $tahun)
->where('bulan', '<=', $bulan)
->with(['departement', 'ceiling'])
->get();

// Ambil hanya record terakhir (bulan terbesar) untuk setiap departemen + ceiling
$last_budgets = $budgets_history
->sortByDesc('bulan')
->unique(fn($b) => $b->departement_id . '-' . $b->ceiling_id);

// Kelompokkan berdasarkan departemen, lalu jumlahkan per kategori realisasi
$budgets_primitif = $last_budgets
->groupBy('departement_id')
->map(function ($items) {
    return (object) [
        'departement_id' => $items->first()->departement_id,
        'departement' => $items->first()->departement,
        'realisasi_pegawai' => $items->where('ceiling.type_data', 'pegawai')->sum('realisasi_pegawai'),
        'realisasi_barang' => $items->where('ceiling.type_data', 'barang')->sum('realisasi_barang'),
        'realisasi_modal' => $items->where('ceiling.type_data', 'modal')->sum('realisasi_modal'),
    ];
})
->values(); // reset key index

// Ambil pagu per departemen dari ceilings
$ceilings_per_dept = Ceiling::selectRaw('
    departement_id,
    SUM(CASE WHEN type_data = "pegawai" THEN nominal ELSE 0 END) AS pagu_pegawai,
    SUM(CASE WHEN type_data = "barang" THEN nominal ELSE 0 END) AS pagu_barang,
    SUM(CASE WHEN type_data = "modal" THEN nominal ELSE 0 END) AS pagu_modal
')
->where('tahun', $tahun)
->groupBy('departement_id')
->get();

// Merge pagu dan realisasi terakhir jadi satu koleksi
$budgets_merged = $ceilings_per_dept->map(function ($c) use ($budgets_primitif) {
$b = $budgets_primitif->firstWhere('departement_id', $c->departement_id);

return (object) [
    'departement' => $b?->departement ?? \App\Models\Departement::find($c->departement_id),
    'departement_id' => $c->departement_id,
    'pagu_pegawai' => $c->pagu_pegawai ?? 0,
    'pagu_barang' => $c->pagu_barang ?? 0,
    'pagu_modal' => $c->pagu_modal ?? 0,
    'realisasi_pegawai' => $b?->realisasi_pegawai ?? 0,
    'realisasi_barang' => $b?->realisasi_barang ?? 0,
    'realisasi_modal' => $b?->realisasi_modal ?? 0,
];
});

// Hitung total keseluruhan
$total_primitif = [
'pagu_pegawai' => $budgets_merged->sum('pagu_pegawai'),
'pagu_barang' => $budgets_merged->sum('pagu_barang'),
'pagu_modal' => $budgets_merged->sum('pagu_modal'),
'realisasi_pegawai' => $budgets_merged->sum('realisasi_pegawai'),
'realisasi_barang' => $budgets_merged->sum('realisasi_barang'),
'realisasi_modal' => $budgets_merged->sum('realisasi_modal'),
];

        $html = view('pages.home.components.data', [
            'budgets'         => $budgets,
            'budgets_primitif' => $budgets_merged,
            'total_primitif' =>$total_primitif,

            'totalPagu'       => $totalPagu,
            'totalRealisasi'  => $totalRealisasi,
            'persentase'      => $persentase,
            'triwulanPercent' => $triwulanPercent,
            'triwulan_summary' => $triwulanData,
            'tahunanPercent'  => $tahunanPercent,
            'tahun'           => $tahun,
            'bulan'           => $bulan,
            // tambahan dashboard
            'ikpaRataRata'     => round($ikpaRataRata, 2),
            'ikpaKeterangan'   => $ikpaKeterangan,
            'eperformanceCapaian' => round($eperformanceCapaian, 2),
            'emonevKinerja'    => round($emonevKinerja, 2),
            'emonevAnggaran'   => round($emonevAnggaran, 2),
            'emonevKeterangan'=> $emonevKeterangan,
        ])->render();

        return response()->json([
            'status' => 'success',
            'html'   => $html,
            'persentase' => $persentase,
            'pegawaiRealisasi' => $pegawaiRealisasi,
            'pegawaiTotal'     => $pegawaiTotal,
            'barangRealisasi'  => $barangRealisasi,
            'barangTotal'      => $barangTotal,
            'modalRealisasi'   => $modalRealisasi,
            'modalTotal'       => $modalTotal,
            'monthlyData'      => $monthlyData,
            'monthlyPagu'      => $monthlyPagu,
            'monthlyRealisasi' => $monthlyRealisasi,
            'categories'       => $categories,
            'totalPagu'        => $totalPagu,
            'totalRealisasi'   => $totalRealisasi,
            'triwulanPercent'  => $triwulanPercent,
            'tahunanPercent'   => $tahunanPercent,
            'ikpa' => [
                'nilai' => round($ikpaRataRata, 2),
                'keterangan' => $ikpaKeterangan,
            ],
            'eperformance' => [
                'persentase' => round($eperformanceCapaian, 2),
            ],
            'emonev' => [
                'kinerja_satker' => round($emonevKinerja, 2),
                'anggaran' => round($emonevAnggaran, 2),
           
            ],
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}

    
    
    
    
    
    
    
}
