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
                            ->where('bulan', $bulan)
                            ->with('departement')
                            ->get();
    
            if ($budgets->isEmpty()) {
                $budgets = Budget::where('tahun', $tahun)->with('departement')->get();
            }
    
            $totalPagu = $budgets->sum('pagu_pegawai')
                       + $budgets->sum('pagu_barang')
                       + $budgets->sum('pagu_modal');
    
            $totalRealisasi = $budgets->sum('realisasi_pegawai')
                             + $budgets->sum('realisasi_barang')
                             + $budgets->sum('realisasi_modal');
    
            $persentase = $totalPagu > 0
                ? round(($totalRealisasi / $totalPagu) * 100, 2)
                : 0;
    
            $monthlyData      = [];
            $monthlyPagu      = [];
            $monthlyRealisasi = [];
            $categories       = [];
    
            for ($m=1; $m <= $bulan; $m++) {
                $mBudget = Budget::where('tahun', $tahun)->where('bulan', $m)->get();
    
                $pagu = $mBudget->sum('pagu_pegawai')
                      + $mBudget->sum('pagu_barang')
                      + $mBudget->sum('pagu_modal');
    
                $realisasi = $mBudget->sum('realisasi_pegawai')
                            + $mBudget->sum('realisasi_barang')
                            + $mBudget->sum('realisasi_modal');
    
                $monthlyPagu[]      = $pagu;
                $monthlyRealisasi[] = $realisasi;
                $monthlyData[]      = $pagu > 0 ? round(($realisasi/$pagu)*100,2) : 0;
                $categories[]       = date("M", mktime(0,0,0,$m,1));
            }
    

            $pegawaiPercent = $totalPagu > 0
                ? round(($budgets->sum('realisasi_pegawai') / max(1, $budgets->sum('pagu_pegawai'))) * 100, 2)
                : 0;
    
            $barangPercent  = $totalPagu > 0
                ? round(($budgets->sum('realisasi_barang') / max(1, $budgets->sum('pagu_barang'))) * 100, 2)
                : 0;
    
            $html = view('pages.home.components.data', [
                'budgets'        => $budgets,
                'totalPagu'      => $totalPagu,
                'totalRealisasi' => $totalRealisasi,
                'persentase'     => $persentase,
            ])->render();
    
            return response()->json([
                'status'          => 'success',
                'html'            => $html,
                'persentase'      => $persentase,
                'pegawaiPercent'  => $pegawaiPercent,
                'barangPercent'   => $barangPercent,
                'monthlyData'     => $monthlyData,     
                'monthlyPagu'     => $monthlyPagu, 
                'monthlyRealisasi'=> $monthlyRealisasi,
                'categories'      => $categories,       
                'totalPagu'       => $totalPagu,
                'totalRealisasi'  => $totalRealisasi,
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
    
    
}
