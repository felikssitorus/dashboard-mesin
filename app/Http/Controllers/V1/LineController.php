<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Line;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Auth;

class LineController extends Controller
{
    public function index()
    {
        return view('v1.line.index');
    }

    public function getDataTableLine(Request $request)
    {
        if ($request->ajax()) {
            $query = Line::query()->latest()->get();

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
        $line = $request->validate([
            'name'        => 'required|string|unique:lines,name|max:255',
        ]);

        try {
            Line::create($line);
            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Created',
                'redirect' => route('v1.line.index')
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
        $lineData = Line::findOrFail($id);
        return response()->json($lineData);
    }

    public function update(Request $request, $id)
    {
       $lineData = $request->validate([
            'name' => 'required|string|unique:lines,name,' . $id . '|max:255',
        ]);

        try {
            $line = Line::findOrFail($id);
            $line->update($lineData);

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Updated',
                'redirect' => route('v1.line.index')
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
            $line = Line::findOrFail($id);
            $line->delete();

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Deleted',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('v1.line.index')
            ]);
        }
    }

}
