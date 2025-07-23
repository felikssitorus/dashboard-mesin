<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Proses;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Auth;

class ProsesController extends Controller
{
    public function index()
    {
        return view('v1.proses.index');
    }

    public function getDataTableProses(Request $request)
    {
        if ($request->ajax()) {
            $query = Proses::query()->latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-icon btn-light-warning me-2" onclick="editRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->rawColumns([
                    'action'
                ])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $proses = $request->validate([
            'name'        => 'required|string|unique:proses,name|max:255',
        ]);

        try {
            Proses::create($proses);
            return response()->json([
                'success' => true,
                'message' => 'Proses Has Been Created',
                'redirect' => route('v1.proses.index')
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
        $prosesData = Proses::findOrFail($id);
        return response()->json($prosesData);
    }

    public function update(Request $request, $id)
    {
       $prosesData = $request->validate([
            'name' => 'required|string|unique:proses,name,' . $id . '|max:255',
        ]);

        try {
            $proses = Proses::findOrFail($id);
            $proses->update($prosesData);

            return response()->json([
                'success' => true,
                'message' => 'Proses Has Been Updated',
                'redirect' => route('v1.proses.index')
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
            $proses = Proses::findOrFail($id);
            $proses->delete();

            return response()->json([
                'success' => true,
                'message' => 'Proses Has Been Deleted',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('v1.proses.index')
            ]);
        }
    }

}
