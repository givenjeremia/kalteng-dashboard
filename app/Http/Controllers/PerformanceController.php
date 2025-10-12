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
    public function index(Request $request)
    {
        try {
            if (request()->ajax()){
                return $this->getData($request);
            }
            return view('pages.performance.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getData(Request $request)
    {
        try {
            // Ambil Get
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');
            // Render Dari sini
            $page = 'pages.performance.data.';
            $user = Auth::user();
            $file_categories = FileCategory::all();
            if ($user->hasRole('Super Admin')) {
                $departements    = Departement::all();
                $performances = Performance::with('media')
                ->when($tahun, function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                })
                ->when($bulan, function ($q) use ($bulan) {
                    $q->whereMonth('created_at', $bulan);
                })
                ->get();
                $page .= 'admin';
                $context = compact('file_categories','performances','departements','tahun','bulan');
            }
            else{
                $performance = Performance::with('media')
                ->where('departement_id', $user->departement_id)
                ->when($tahun, function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                })
                ->when($bulan, function ($q) use ($bulan) {
                    $q->whereMonth('created_at', $bulan);
                })
                ->first();
                $page .= 'departement';
                $context = compact('file_categories','performance','tahun','bulan');
            }
            return response()->json([
                'status' => 'success',
                'msg'    => view($page,$context)->render()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Gagal Menampilkan Data'
            ], 500);
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
