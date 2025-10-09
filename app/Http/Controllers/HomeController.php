<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;

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

    public function dataDashboard(Request $request)
    {
        try {
            $tahun = $request->get('tahun', date('Y'));
            $bulan = $request->get('bulan', date('n'));
    
            $budgets = Budget::where('tahun', $tahun)
                            ->where('bulan','<=', $bulan)
                            ->with('departement')
                            ->get();
    
            if ($budgets->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Tidak Ada Data Bulan '.$bulan.' Pada Tahun '.$tahun,
                ], 404);
            }
    
            // Total pagu & realisasi
            $budgets_year = Budget::where('tahun', $tahun)
                        ->with('departement')
                        ->get();

            // Total setahun penuh
            $totalPagu =  $budgets_year->sum('pagu_pegawai')
                +  $budgets_year->sum('pagu_barang')
                +  $budgets_year->sum('pagu_modal');

            $totalRealisasi =  $budgets_year->sum('realisasi_pegawai')
                        +  $budgets_year->sum('realisasi_barang')
                        +  $budgets_year->sum('realisasi_modal');

            $persentase = $totalPagu > 0
            ? round(($totalRealisasi / $totalPagu) * 100, 2)
            : 0;
    
            // Data bulanan
            $monthlyData      = [];
            $monthlyPagu      = [];
            $monthlyRealisasi = [];
            $categories       = [];
    
            for ($m = 1; $m <= $bulan; $m++) {
                $mBudget = Budget::where('tahun', $tahun)->where('bulan', $m)->get();
    
                $pagu = $mBudget->sum('pagu_pegawai')
                      + $mBudget->sum('pagu_barang')
                      + $mBudget->sum('pagu_modal');
    
                $realisasi = $mBudget->sum('realisasi_pegawai')
                            + $mBudget->sum('realisasi_barang')
                            + $mBudget->sum('realisasi_modal');
    
                $monthlyPagu[]      = $pagu;
                $monthlyRealisasi[] = $realisasi;
                $monthlyData[]      = $pagu > 0 ? round(($realisasi / $pagu) * 100, 2) : 0;
                $categories[]       = date("M", mktime(0, 0, 0, $m, 1));
            }
    
            // Detail kategori
            $pegawaiRealisasi = $budgets->sum('realisasi_pegawai');
            $pegawaiTotal     = $budgets->sum('pagu_pegawai');
    
            $barangRealisasi  = $budgets->sum('realisasi_barang');
            $barangTotal      = $budgets->sum('pagu_barang');
    
            $modalRealisasi   = $budgets->sum('realisasi_modal');
            $modalTotal       = $budgets->sum('pagu_modal');
    
            // Hitung target Triwulan
            $currentQuarter   = ceil($bulan / 3); // triwulan ke berapa
            $quarterEndMonth  = $currentQuarter * 3;
            $quarterBudget = Budget::where('tahun', $tahun)
                ->whereBetween('bulan', [($currentQuarter - 1) * 3 + 1, $quarterEndMonth])
                ->get();
    
            $quarterPagu = $quarterBudget->sum('pagu_pegawai')
                         + $quarterBudget->sum('pagu_barang')
                         + $quarterBudget->sum('pagu_modal');
    
            $quarterRealisasi = $quarterBudget->sum('realisasi_pegawai')
                               + $quarterBudget->sum('realisasi_barang')
                               + $quarterBudget->sum('realisasi_modal');
    
            $triwulanPercent = $quarterPagu > 0
                ? round((($quarterRealisasi - $quarterPagu) / $quarterPagu) * 100, 2)
                : 0;
    
            // Hitung target Tahunan
            $yearBudget = Budget::where('tahun', $tahun)->get();
    
            $yearPagu = $yearBudget->sum('pagu_pegawai')
                      + $yearBudget->sum('pagu_barang')
                      + $yearBudget->sum('pagu_modal');
    
            $yearRealisasi = $yearBudget->sum('realisasi_pegawai')
                          + $yearBudget->sum('realisasi_barang')
                          + $yearBudget->sum('realisasi_modal');
    
            $tahunanPercent = $yearPagu > 0
                ? round((($yearRealisasi - $yearPagu) / $yearPagu) * 100, 2)
                : 0;
    
            // Render partial view kalau dipakai
            $html = view('pages.home.components.data', [
                'budgets'        => $budgets,
                'totalPagu'      => $totalPagu,
                'totalRealisasi' => $totalRealisasi,
                'persentase'     => $persentase,
                 // tambahan card bawah
                'triwulanPercent' => $triwulanPercent,
                'tahunanPercent'  => $tahunanPercent,
                'tahun'=>$tahun,
                'bulan'=>$bulan
            ])->render();
    
            return response()->json([
                'status'          => 'success',
                'html'            => $html,
                'persentase'      => $persentase,
    
                'pegawaiRealisasi'=> $pegawaiRealisasi,
                'pegawaiTotal'    => $pegawaiTotal,
                'barangRealisasi' => $barangRealisasi,
                'barangTotal'     => $barangTotal,
                'modalRealisasi'  => $modalRealisasi,
                'modalTotal'      => $modalTotal,
    
                'monthlyData'     => $monthlyData,     
                'monthlyPagu'     => $monthlyPagu, 
                'monthlyRealisasi'=> $monthlyRealisasi,
                'categories'      => $categories,       
    
                'totalPagu'       => $totalPagu,
                'totalRealisasi'  => $totalRealisasi,
    
                // tambahan card bawah
                'triwulanPercent' => $triwulanPercent,
                'tahunanPercent'  => $tahunanPercent,
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
    
    
    
}
