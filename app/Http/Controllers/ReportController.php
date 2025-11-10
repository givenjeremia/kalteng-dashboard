<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Ceiling;
use App\Models\IKPAScore;
use App\Models\EPerformance;
use App\Models\Emonev;

class ReportController extends Controller
{
    public function preview(Request $request)
    {
        // $data = $this->sampleData();
        // return view('pages/reports/performance', $data);
    }

    public function download()
    {
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('n');
    
        $data = $this->sampleData($tahun, $bulan);
        $pdf = Pdf::loadView('pages/reports/performance', $data)->setPaper('a4', 'landscape'); 
        return $pdf->download('capaian-kinerja.pdf');
    }

    private function sampleData($tahun, $bulan): array
    {
        $data_eperformance = EPerformance::with('departement')
            ->orderBy('pkid', 'desc')
            ->get();
    
        $budgets = Budget::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->with(['departement', 'ceiling'])
            ->get();
    
        if ($budgets->isEmpty()) {
            throw new \Exception("Tidak ada data bulan $bulan tahun $tahun");
        }
    
        // ============================ PAGU ============================
        $ceilings = Ceiling::where('tahun', $tahun)->get();
        $pegawaiTotal = $ceilings->where('type_data','pegawai')->sum('nominal');
        $barangTotal  = $ceilings->where('type_data','barang')->sum('nominal');
        $modalTotal   = $ceilings->where('type_data','modal')->sum('nominal');
        $totalPagu    = $pegawaiTotal + $barangTotal + $modalTotal;
    
        // ============================ REALISASI TERAKHIR ============================
        $lastBudgets = $budgets->sortByDesc('bulan')
            ->unique(fn($b) => $b->departement_id.'-'.$b->ceiling_id);
    
        $pegawaiRealisasi = $lastBudgets->where('ceiling.type_data','pegawai')->sum('realisasi_pegawai');
        $barangRealisasi  = $lastBudgets->where('ceiling.type_data','barang')->sum('realisasi_barang');
        $modalRealisasi   = $lastBudgets->where('ceiling.type_data','modal')->sum('realisasi_modal');
    
        $totalRealisasi = $pegawaiRealisasi + $barangRealisasi + $modalRealisasi;
    
        $persentase = $totalPagu > 0
            ? round(($totalRealisasi / $totalPagu) * 100, 2)
            : 0;
    
        // ============================ IKPA ============================
        $ikpaData = IKPAScore::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->get();
    
        $ikpaRataRata = round($ikpaData->avg('nilai_ikpa') ?? 0, 2);
        $ikpaKeterangan = match (true) {
            $ikpaRataRata >= 95 => 'Sangat Baik',
            $ikpaRataRata >= 90 => 'Baik',
            $ikpaRataRata >= 80 => 'Cukup',
            default => 'Kurang',
        };
    
        // ============================ E-PERFORMANCE ============================
        $eperformanceData = EPerformance::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->get();
        $eperformanceCapaian = round($eperformanceData->avg('persentase_capaian') ?? 0, 2);
    
        // ============================ EMONEV ============================
        $emonevData = Emonev::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->get();
    
        $emonevKinerja  = round($emonevData->avg('kinerja_satker') ?? 0, 2);
        $emonevAnggaran = round($emonevData->avg('anggaran') ?? 0, 2);
    
        // ============================ TABEL HISTORI PER SATKER ============================
        $budgets_history = Budget::where('tahun', $tahun)
            ->where('bulan', '<=', $bulan)
            ->with(['departement', 'ceiling'])
            ->get();
    
        $last_budgets = $budgets_history
            ->sortByDesc('bulan')
            ->unique(fn($b) => $b->departement_id . '-' . $b->ceiling_id);
    
        $budgets_primitif_calc = $last_budgets
            ->groupBy('departement_id')
            ->map(function ($items) {
                return (object) [
                    'departement_id'    => $items->first()->departement_id,
                    'departement'       => $items->first()->departement,
                    'realisasi_pegawai' => $items->where('ceiling.type_data','pegawai')->sum('realisasi_pegawai'),
                    'realisasi_barang'  => $items->where('ceiling.type_data','barang')->sum('realisasi_barang'),
                    'realisasi_modal'   => $items->where('ceiling.type_data','modal')->sum('realisasi_modal'),
                ];
            })
            ->values();
    
        $ceilings_per_dept = Ceiling::selectRaw('
                departement_id,
                SUM(CASE WHEN type_data = "pegawai" THEN nominal ELSE 0 END) AS pagu_pegawai,
                SUM(CASE WHEN type_data = "barang"  THEN nominal ELSE 0 END) AS pagu_barang,
                SUM(CASE WHEN type_data = "modal"   THEN nominal ELSE 0 END) AS pagu_modal
            ')
            ->where('tahun', $tahun)
            ->groupBy('departement_id')
            ->get();
    
        $budgets_merged = $ceilings_per_dept->map(function ($c) use ($budgets_primitif_calc) {
            $b = $budgets_primitif_calc->firstWhere('departement_id', $c->departement_id);
    
            return (object) [
                'departement'        => $b?->departement ?? \App\Models\Departement::find($c->departement_id),
                'departement_id'     => $c->departement_id,
                'pagu_pegawai'       => (int) $c->pagu_pegawai,
                'pagu_barang'        => (int) $c->pagu_barang,
                'pagu_modal'         => (int) $c->pagu_modal,
                'realisasi_pegawai'  => (int) ($b?->realisasi_pegawai ?? 0),
                'realisasi_barang'   => (int) ($b?->realisasi_barang ?? 0),
                'realisasi_modal'    => (int) ($b?->realisasi_modal ?? 0),
            ];
        });
    
        $total_primitif = [
            'pagu_pegawai'       => $budgets_merged->sum('pagu_pegawai'),
            'pagu_barang'        => $budgets_merged->sum('pagu_barang'),
            'pagu_modal'         => $budgets_merged->sum('pagu_modal'),
            'realisasi_pegawai'  => $budgets_merged->sum('realisasi_pegawai'),
            'realisasi_barang'   => $budgets_merged->sum('realisasi_barang'),
            'realisasi_modal'    => $budgets_merged->sum('realisasi_modal'),
        ];
    
        // ============================ FINAL RETURN ============================

        $data_ikpa = IkpaScore::with('departement')
                ->orderBy('pkid', 'desc')
                ->get();
        return [
            'title' => "Dashboard Capaian Kinerja & Anggaran",
            'subtitle' => "Per Bulan $bulan Tahun $tahun",
            'instansi' => "Kantor Wilayah Kementerian Hukum Kalimantan Tengah",
            'tanggal' => now()->format('d F Y'),
            'logo' => public_path('logo.png'),
    
            'pagu' => [
                ['label' => 'Belanja Pegawai', 'value' => $pegawaiTotal],
                ['label' => 'Belanja Barang',  'value' => $barangTotal],
                ['label' => 'Belanja Modal',   'value' => $modalTotal],
            ],
    
            'realisasi' => [
                ['label' => 'Pegawai', 'value' => $pegawaiRealisasi],
                ['label' => 'Barang',  'value' => $barangRealisasi],
                ['label' => 'Modal',   'value' => $modalRealisasi],
            ],
    
            'ikpa' => [
                ['nama' => 'Nilai IKPA', 'capaian' => $ikpaRataRata],
            ],
            'data_ikpa'=>$data_ikpa,
    
            // Dashboard summary
            'totalPagu' => $totalPagu,
            'totalRealisasi' => $totalRealisasi,
            'persentase' => $persentase,
    
            'data_eperformance' => $data_eperformance,
            'eperformanceCapaian' => $eperformanceCapaian,
            'emonevKinerja' => $emonevKinerja,
            'emonevAnggaran' => $emonevAnggaran,
    
            // ✅ KIRIM INI KE PDF
            'budgets_primitif' => $budgets_merged,
            'total_primitif'   => $total_primitif,
            'tahun'            => $tahun,
            'bulan'            => $bulan,
        ];
    }
    
    
}
