<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Ceiling;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Departement;
class BudgetController extends Controller
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
            return view('pages.budget.expense.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tableDataAdmin(){
        if (request()->ajax())
        {
            $data = Budget::orderBy('pkid','desc')->get();
            $counter = 1;
            return Datatables::of($data)
                ->addColumn('No', function () use (&$counter)  {
                    return $counter++;
                })
                ->addColumn('Departement', function($item) {
                    if($item->departement){
                        return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->departement->title.'</span>';
                    }
                    return '-';
                })
                ->addColumn('Ceiling', function($item) {
                    if($item->ceiling){
                        $formatted = number_format($item->ceiling->nominal, 0, ',', '.');
                        return '<span  class="text-gray-800 fs-5 fw-bold mb-1">Rp. '.$formatted.'</span>';
                    }
                    return '-';
                })
                ->addColumn('Month', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->bulan.'</span>';
                })
                ->addColumn('Year', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->tahun.'</span>';
                })
                ->addColumn('Realization Of Employee', function($item) {
                    $formatted = number_format($item->realisasi_pegawai, 0, ',', '.');
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">Rp. '.$formatted.'</span>';
                })
                ->addColumn('Realization Of Goods', function($item) {
                    $formatted = number_format($item->realisasi_barang, 0, ',', '.');
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">Rp. '.$formatted.'</span>';
                })
                ->addColumn('Realization Of Capital', function($item) {
                    $formatted = number_format($item->realisasi_modal, 0, ',', '.');
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">Rp. '.$formatted.'</span>';
                })
                ->addColumn('Action', function ($item) {
                    $encryptedIdString = "'".$item->uuid."'";
                    $button ='
                    <button onclick="showModalUpdate('.$encryptedIdString.')" class="btn btn-primary border border-1  py-2"><i class="fa-solid fa-pen"></i></button>
                    <button onclick="deleteData('.$encryptedIdString.')" class="btn btn-danger border border-1  py-2"><i class="fa-solid fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['No','Departement','Month','Year','Ceiling','Realization Of Employee','Realization Of Goods','Realization Of Capital','Action'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $ceilings = Ceiling::all();
            $departements = Departement::all();
            return response()->json(array('status' => 'success','msg' =>  view('pages.budget.expense.modal.create',compact('departements','ceilings'))->render()), 200);
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
                'ceiling_id' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'realisasi_pegawai' => 'required',
                'realisasi_barang' => 'required',
                'realisasi_modal' => 'required',
            ]);
    
            Budget::create($validated);
    
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = Budget::where('uuid',$id)->firstOrFail();
            $departements = Departement::all();
            $ceilings = Ceiling::all();
        
            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.budget.expense.modal.update', compact('data','departements','ceilings'))->render()
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
                'ceiling_id' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'realisasi_pegawai' => 'required',
                'realisasi_barang' => 'required',
                'realisasi_modal' => 'required',
            ]);

            $data = Budget::where('uuid', $id)->firstOrFail();
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
            $data = Budget::where('uuid', $id)->firstOrFail();
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
