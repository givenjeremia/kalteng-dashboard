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
                ->addColumn('Sasaran', function ($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . $item->sasaran . '</span>';
                })
                ->addColumn('Indikator', function ($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . $item->indikator . '</span>';
                })
                ->addColumn('Target', function ($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . number_format($item->target, 2, ',', '.') . '</span>';
                })
                ->addColumn('Tercapai', function ($item) {
                    return '<span class="text-success fs-5 fw-bold mb-1">' . number_format($item->tercapai, 2, ',', '.') . '</span>';
                })
                ->addColumn('Persentase Capaian', function ($item) {
                    $formatted = number_format($item->persentase_capaian, 2, ',', '.');
                    $color = $item->persentase_capaian >= 100 ? 'text-success' : ($item->persentase_capaian >= 80 ? 'text-warning' : 'text-danger');
                    return '<span class="' . $color . ' fs-5 fw-bold mb-1">' . $formatted . '%</span>';
                })
                ->addColumn('Satuan', function ($item) {
                    return '<span class="fs-5 fw-bold mb-1">' . $item->satuan. '</span>';
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
                    'Sasaran',
                    'Indikator',
                    'Target',
                    'Tercapai',
                    'Persentase Capaian',
                    'Satuan',
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
        try {
            return response()->json(array('status' => 'success','msg' =>  view('pages.budget.e-performance.modal.create')->render()), 200);
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
            'sasaran' => 'required',
            'indikator' => 'required',
            'target' => 'required',
            'tercapai'=> 'required',
            'persentase_capaian'=> 'required',
            'satuan'=> 'required',
            
        ]);

        EPerformance::create($validated);

        return response()->json([
            'status' => 'success',
            'msg'    => 'Berhasil Tambah Data'
        ], 200);
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
    public function edit(string $id)
    {
        try {
            $data = EPerformance::where('uuid',$id)->firstOrFail();
        
            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.budget.e-performance.modal.update', compact('data'))->render()
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
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
               'sasaran' => 'required',
                'indikator' => 'required',
                'target' => 'required',
                'tercapai'=> 'required',
                'persentase_capaian'=> 'required',
                'satuan'=> 'required',
            
            ]);

            $data = EPerformance::where('uuid', $id)->firstOrFail();
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
            $data = EPerformance::where('uuid', $id)->firstOrFail();
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
