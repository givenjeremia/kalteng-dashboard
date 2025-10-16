<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\IKPAScore;
use Illuminate\Http\Request;

class IKPAScoreController extends Controller
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
            return view('pages.budget.ikpa.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tableDataAdmin()
    {
        if (request()->ajax()) {
            $data = IkpaScore::with('departement')->orderBy('pkid', 'desc')->get();
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
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . e($item->tahun) . '</span>';
                })
                ->addColumn('Bulan', function ($item) {
                    $namaBulan = \Carbon\Carbon::createFromDate(null, $item->bulan)->locale('id')->monthName;
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . ucfirst($namaBulan) . '</span>';
                })
                ->addColumn('Deviation DIPA', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->deviation_dipa, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Revisi DIPA', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->revisi_dipa, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Penyerapan Anggaran', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->penyerapan_anggaran, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Capaian Output', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->capaian_output, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Penyelesaian Tagihan', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->penyelesaian_tagihan, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Pengelolaan UP/TUP', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->pengelolaan_up_tup, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Belanja Kontraktual', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . number_format($item->belanja_kontraktual, 2, ',', '.') . '%</span>';
                })
                ->addColumn('Nilai IKPA', function ($item) {
                    $color = $item->nilai_ikpa >= 95
                        ? 'text-success'
                        : ($item->nilai_ikpa >= 80 ? 'text-warning' : 'text-danger');
                    return '<span class="' . $color . ' fs-5 fw-bold mb-1">' . number_format($item->nilai_ikpa, 2, ',', '.') . '</span>';
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
                    'Deviation DIPA',
                    'Revisi DIPA',
                    'Penyerapan Anggaran',
                    'Capaian Output',
                    'Penyelesaian Tagihan',
                    'Pengelolaan UP/TUP',
                    'Belanja Kontraktual',
                    'Nilai IKPA',
                    'Action'
                ])
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $departements = Departement::all();
            return response()->json(array('status' => 'success','msg' =>  view('pages.budget.ikpa.modal.create',compact('departements'))->render()), 200);
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Gagal Menampilkan Form Tambah','err'=>$e->getMessage()), 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departement_id' => 'required',
            'tahun' => 'required',
            'bulan' => 'required',
            'deviation_dipa' => 'required',
            'revisi_dipa' => 'required',
            'penyerapan_anggaran'=> 'required',
            'capaian_output'=> 'required',
            'penyelesaian_tagihan'=> 'required',
            'pengelolaan_up_tup'=> 'required',
            'belanja_kontraktual'=> 'required',
            'nilai_ikpa'=> 'required',
            
        ]);

        IKPAScore::create($validated);

        return response()->json([
            'status' => 'success',
            'msg'    => 'Berhasil Tambah Data'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(IKPAScore $iKPAScore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $departements = Departement::all();
            $data = IKPAScore::where('uuid',$id)->firstOrFail();
        
            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.budget.ikpa.modal.update', compact('data','departements'))->render()
            ], 200);
        
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Gagal Menampilkan Form Edit',
                'err'    => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        try {
            $validated = $request->validate([
                'departement_id' => 'required',
                'tahun' => 'required',
                'bulan' => 'required',
                'deviation_dipa' => 'required',
                'revisi_dipa' => 'required',
                'penyerapan_anggaran'=> 'required',
                'capaian_output'=> 'required',
                'penyelesaian_tagihan'=> 'required',
                'pengelolaan_up_tup'=> 'required',
                'belanja_kontraktual'=> 'required',
                'nilai_ikpa'=> 'required',
            
            ]);

            $data = IKPAScore::where('uuid', $id)->firstOrFail();
            $data->update($validated);

            return response()->json([
                'status' => 'success',
                'msg'    => 'Berhasil Update Data'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Gagal Update Data',
                'err'    => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = IKPAScore::where('uuid', $id)->firstOrFail();
            $data->delete();
    
            return response()->json([
                'status' => 'success',
                'msg'    => 'Berhasil Hapus Data'
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Gagal Hapus Data',
                'err'    => $th->getMessage()
            ], 400);
        }
    }
}
