<?php

namespace App\Http\Controllers;

use App\Models\FileCategory;
use App\Models\Departement;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $file_categories = FileCategory::all();
            $performance     = Performance::with('media')->where('departement_id', $user->departement_id)->first();
            if ($user->hasRole('Super Admin')) {
                $departements    = Departement::all();
                $file_categories = FileCategory::all();
                $performances    = Performance::with('media')->get();
                return view('pages.performance.index_admin',compact('file_categories','performances','departements'));
            }
            return view('pages.performance.index',compact('file_categories','performance'));
        } catch (\Throwable $th) {
            dd($th);
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
        try {
            $user = auth()->user();
    
            $performance = Performance::where('departement_id', $user->departement_id)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->first();
    
            if (!$performance) {
                $performance = Performance::create([
                    'departement_id' => $user->departement_id,
                    'title'          => $request->title ?? 'Performance '.now()->format('M Y'),
                ]);
            }
    
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $categoryId => $file) {
                    if ($file) {
                        $oldFile = $performance->getMedia('files')
                            ->first(fn($media) => $media->getCustomProperty('file_category_id') == $categoryId);
    
                        if ($oldFile) {
                            $oldFile->delete();
                        }

                        $performance->addMedia($file)
                            ->withCustomProperties(['file_category_id' => $categoryId])
                            ->toMediaCollection('files');
                    }
                }
            }
    
            return response()->json([
                'status'  => 'success',
                'message' => 'Performance berhasil disimpan / diperbarui'
            ]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan',
                'error'   => $th->getMessage()
            ], 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }
}
