<?php

namespace App\Http\Controllers;

use App\Models\EPerformance;
use Illuminate\Http\Request;

class EPerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (request()->ajax()){
                return $this->tableDataAdmin();
            }
            return view('pages.budget.e-performance.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tableDataAdmin()
    {
        if (request()->ajax()) {
            $data = EPerformance::with('departement')->orderBy('pkid', 'desc')->get();
            $counter = 1;

            return datatables()->of($data)
                ->addColumn('No', function () use (&$counter) {
                    return $counter++;
                })
                ->addColumn('Departement', function ($item) {
                    return $item->departement
                        ? '<span class="text-gray-800 fs-5 fw-bold mb-1">' . e($item->departement->title) . '</span>'
                        : '-';
                })
                ->addColumn('Tahun', function ($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . $item->tahun . '</span>';
                })
                ->addColumn('Bulan', function ($item) {
                    // Format nama bulan (contoh: Oktober)
                    $namaBulan = \Carbon\Carbon::createFromDate(null, $item->bulan)->locale('id')->monthName;
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . ucfirst($namaBulan) . '</span>';
                })
                ->addColumn('Target', function ($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . number_format($item->target, 2, ',', '.') . '</span>';
                })
                ->addColumn('Tercapai', function ($item) {
                    return '<span class="text-success fs-5 fw-bold mb-1">' . number_format($item->tercapai, 2, ',', '.') . '</span>';
                })
                ->addColumn('Tidak Tercapai', function ($item) {
                    return '<span class="text-danger fs-5 fw-bold mb-1">' . number_format($item->tidak_tercapai, 2, ',', '.') . '</span>';
                })
                ->addColumn('Persentase Capaian', function ($item) {
                    $formatted = number_format($item->persentase_capaian, 2, ',', '.');
                    $color = $item->persentase_capaian >= 100 ? 'text-success' : ($item->persentase_capaian >= 80 ? 'text-warning' : 'text-danger');
                    return '<span class="' . $color . ' fs-5 fw-bold mb-1">' . $formatted . '%</span>';
                })
                ->addColumn('Action', function ($item) {
                    $uuid = "'" . $item->uuid . "'";
                    return '
                        <button onclick="showModalUpdate(' . $uuid . ')" 
                            class="btn btn-sm btn-primary border border-1 py-2" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button onclick="deleteData(' . $uuid . ')" 
                            class="btn btn-sm btn-danger border border-1 py-2" title="Hapus">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    ';
                })
                ->rawColumns([
                    'No',
                    'Departement',
                    'Tahun',
                    'Bulan',
                    'Target',
                    'Tercapai',
                    'Tidak Tercapai',
                    'Persentase Capaian',
                    'Action',
                ])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EPerformance $ePerformance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EPerformance $ePerformance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EPerformance $ePerformance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EPerformance $ePerformance)
    {
        //
    }
}
