<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Mesin;
use App\Models\Proses;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Auth;

class MesinController extends Controller
{

    public function getDataTableMesin(Request $request)
    {
        if ($request->ajax()) {
            $query = Mesin::with(['proses'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('proses_name', function ($mesin) {
                    if ($mesin->proses->isEmpty()) {
                        return '-';
                    }
                    return $mesin->proses->map(function($item) {
                        return '<span class="badge badge-light-primary m-1">' . $item->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-icon btn-light-warning me-2" onclick="editRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->editColumn('kapasitas', function($mesin) {
                    $kapasitas = $mesin->kapasitas ?? '-';
                    return '<span class="text-muted fw-bold">' . $kapasitas . '</span>';
                })
                ->editColumn('speed', function($mesin) {
                    $speed = $mesin->speed ?? '-';
                    return '<span class="text-muted fw-bold">' . $speed . '</span>';
                })
                ->rawColumns([
                    'action', 'proses_name', 'kapasitas', 'speed'
                ])
                ->make(true);
        }
    }

    public function create()
    {
        $all_proses = Proses::all();
        return response()->json([
            'all_proses' => $all_proses,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kodeMesin'         => 'required|string|unique:mesins,kodeMesin',
            'name'              => 'required|string|max:255',
            'kapasitas'         => 'nullable|string',
            'speed'             => 'nullable|string',
            'jumlahOperator'    => 'required|integer',
            'proses_ids'        => 'required|array',
        ]);

        try {
            $mesin = Mesin::create([
                'kodeMesin'      => $validatedData['kodeMesin'],
                'name'           => $validatedData['name'],
                'kapasitas'      => $validatedData['kapasitas'] ?? null,
                'speed'          => $validatedData['speed'] ?? null,
                'jumlahOperator' => $validatedData['jumlahOperator'],
            ]);

            $mesin->proses()->attach($request->proses_ids);
            
            return response()->json([
                'success' => true,
                'message' => 'Mesin Has Been Created',
                'redirect' => route('v1.dashboard')
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $mesin = Mesin::findOrFail($id);
        $proses = Proses::all();
        return response()->json([
            'mesin' => $mesin,
            'proses' => $proses,
        ]);
    }

    public function update(Request $request, $id)
    {
       $validatedData = $request->validate([
            'kodeMesin'         => 'required|string|unique:mesins,kodeMesin',
            'name'              => 'required|string|max:255',
            'kapasitas'         => 'nullable|string',
            'speed'             => 'nullable|string',
            'jumlahOperator'    => 'required|integer',
            'proses_ids'        => 'required|array',
        ]);

        try {
            $mesin = Mesin::findOrFail($id);
            
            $mesin = Mesin::update([
                'kodeMesin'      => $validatedData['kodeMesin'],
                'name'           => $validatedData['name'],
                'kapasitas'      => $validatedData['kapasitas'] ?? null,
                'speed'          => $validatedData['speed'] ?? null,
                'jumlahOperator' => $validatedData['jumlahOperator'],
            ]);

            $mesin->proses()->sync($request->proses_ids);

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Updated',
                'redirect' => route('admin.user.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User Has Been Deleted',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('admin.user.index') 
            ]);
        }
    }

}
