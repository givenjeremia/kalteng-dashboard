<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\FileCategory;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->tableDataAdmin();
            return view('pages.users.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function tableDataAdmin(){
        if (request()->ajax())
        {
            $data = User::orderBy('id','desc')->get();
            $counter = 1;
            return Datatables::of($data)
                ->addColumn('No', function () use (&$counter)  {
                    return $counter++;
                })
                ->addColumn('Name', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->title.'</span>';
                })
                ->addColumn('Username', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->username.'</span>';
                })
                ->addColumn('Email', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->email.'</span>';
                })
                ->addColumn('Departement', function($item) {
                    return '<span  class="text-gray-800 fs-5 fw-bold mb-1">'.$item->departement->title.'</span>';
                })
                ->addColumn('Role', function($item) {
                    return '<span class="text-gray-800 fs-5 fw-bold mb-1">'.$item->getRoleNames()->first().'</span>';
                })
                ->addColumn('Action', function ($item) {
                    $encryptedIdString = "'".strval(encrypt($item->id))."'";
                    $button ='
                    <button onclick="showModalUpdate('.$encryptedIdString.')" class="btn btn-primary border border-1  py-2"><i class="fa-solid fa-pen"></i></button>
                    <button onclick="deleteTipe('.$encryptedIdString.')" class="btn btn-danger border border-1  py-2"><i class="fa-solid fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['No','Name','Username','Email','Departement','Role','Action'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $roles = Role::all(); // ambil semua role

            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.users.modal.create', compact('roles'))->render()
            ], 200);
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
                'name' => 'required',
                'username' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
    
            User::create($validated);
    
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
            $data = User::find($id);
        
            return response()->json([
                'status' => 'success',
                'msg'    => view('pages.users.modal.update', compact('data'))->render()
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
                'title' => 'required',
            ]);

            $data = User::find($id);
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
            $data = User::find($id);
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
