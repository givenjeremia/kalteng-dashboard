<?php

namespace App\Http\Controllers;

use App\Models\Emonev;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EMonevController extends Controller
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
            return view('pages.budget.e-monev.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tableDataAdmin()
    {
        if (request()->ajax()) {
            $data = Emonev::with('departement')->orderBy('pkid', 'desc')->get();
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
                ->addColumn('Bulan', function ($item) {
                    $namaBulan = date("F", mktime(0, 0, 0, $item->bulan, 1));
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . $namaBulan . '</span>';
                })
                ->addColumn('Tahun', function ($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">' . $item->tahun . '</span>';
                })
                ->addColumn('Anggaran', function ($item) {
                    $formatted = number_format($item->anggaran, 2, ',', '.');
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">Rp ' . $formatted . '</span>';
                })
                ->addColumn('Kinerja', function ($item) {
                    $formatted = number_format($item->kinerja_satker, 2, ',', '.');
                    return '<span class="text-success fs-5 fw-bold mb-1">' . $formatted . '%</span>';
                })
                ->addColumn('Keterangan', function ($item) {
                    return '<span class="text-gray-700 fs-6 fw-semibold">' . e($item->keterangan ?? '-') . '</span>';
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
                ->rawColumns(['No', 'Departement', 'Bulan', 'Tahun', 'Anggaran', 'Kinerja', 'Keterangan', 'Action'])
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
            return response()->json(array('status' => 'success','msg' =>  view('pages.budget.e-monev.modal.create',compact('departements'))->render()), 200);
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Gagal Menampilkan Form Tambah','err'=>$e->getMessage()), 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'departement_id' => 'required',
                'tahun' => 'required',
                'bulan' => 'required',
                'anggaran' => 'required',
                'kinerja_satker' => 'required',
                'keterangan' => 'required',
            ]);
    
            Emonev::create($validated);
    
            return response()->json([
                'status' => 'success',
                'msg'    => 'Berhasil Tambah Data'
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Gagal Tambah Data',
                'err'    => $th->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = Emonev::where('uuid',$id)->firstOrFail();
            $departements = Departement::all();
        
            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.budget.e-monev.modal.update', compact('data','departements'))->render()
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
                'departement_id' => 'required',
                'tahun' => 'required',
                'bulan' => 'required',
                'anggaran' => 'required',
                'kinerja_satker' => 'required',
                'keterangan' => 'required',
            ]);

            $data = Emonev::where('uuid', $id)->firstOrFail();
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
            $data = Emonev::where('uuid', $id)->firstOrFail();
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
