<?php

namespace App\Http\Controllers;

use App\Models\Ceiling;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CeilingController extends Controller
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
            return view('pages.budget.ceiling.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tableDataAdmin(){
        if (request()->ajax())
        {
            $data = Ceiling::orderBy('pkid','desc')->get();
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
                ->addColumn('Bulan', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->bulan.'</span>';
                })
                ->addColumn('Nominal', function($item) {
                    $formatted = number_format($item->nominal, 0, ',', '.');
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">Rp. '.$formatted.'</span>';
                })
                ->addColumn('Tahun', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->tahun.'</span>';
                })
                ->addColumn('Type Data', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->type_data.'</span>';
                })
                ->addColumn('Action', function ($item) {
                    $encryptedIdString = "'".$item->uuid."'";
                    $button ='
                    <button onclick="showModalUpdate('.$encryptedIdString.')" class="btn btn-primary border border-1  py-2"><i class="fa-solid fa-pen"></i></button>
                    <button onclick="deleteData('.$encryptedIdString.')" class="btn btn-danger border border-1  py-2"><i class="fa-solid fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['No','Departement','Bulan','Type Data','Nominal','Tahun','Action'])
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
            return response()->json(array('status' => 'success','msg' =>  view('pages.budget.ceiling.modal.create',compact('departements'))->render()), 200);
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
                'nominal' => 'required',
            ]);
    
            Ceiling::create($validated);
    
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
    public function show(Ceiling $ceiling)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $data = Ceiling::where('uuid',$id)->firstOrFail();
            $departements = Departement::all();
        
            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.budget.ceiling.modal.update', compact('data','departements'))->render()
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
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'departement_id' => 'required',
                'tahun' => 'required',
                'nominal' => 'required',
            ]);

            $data = Ceiling::where('uuid', $id)->firstOrFail();
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
    public function destroy($id)
    {
        try {
            $data = Ceiling::where('uuid', $id)->firstOrFail();
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
