<?php

namespace App\Http\Controllers\System\Permission;

use App\Http\Controllers\Controller;
use App\Models\PermissionsLine;
use App\Models\Line;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class PermissionLineController extends Controller
{
    public function getDataTablePermission(Request $request)
    {
        if ($request->ajax()) {
            $query = Line::query()->latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('urls', function ($row) {
                    $count = $row->permission ? count($row->permission) : 0;
                    $urls = $count . ' Urls Access permission to line';
                    return $urls;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.permissionLine.edit', $row->id) . '" class="btn btn-sm btn-icon btn-light-warning me-2"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i></a>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->rawColumns([
                    'urls',
                    'action'
                ])
                ->make(true);
        }
    }

    public function index()
    {
        return view('admin.line.index');
    }

    public function create()
    {
        $routes = Route::getRoutes()->getRoutesByName();
        return view('admin.line.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'urls' => 'required|array',
            'name' => 'required|string|unique:lines,name|max:255',
        ]);

        try {
            DB::beginTransaction();

            $line = Line::create([
                'name' => $request->name
            ]);

            // Perbarui izin berdasarkan URL yang dipilih
            foreach ($request->input('urls', []) as $url) {
                $line->permission()->create(['url' => $url]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permission Line Has Been Created',
                'redirect' => route('admin.permissionLine.index')
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function edit($id)
    {
        $line = Line::with('permission')->find($id);

        $routes = Route::getRoutes()->getRoutesByName();
        return view('admin.line.edit', compact('line', 'routes'));
    }
    public function update(Request $request, $id)
    {
        $line = Line::findOrFail($id);
        $line->update(['name' => $request->line_id]);

        // Hapus permissions lama jika ada
        $line->permission()->delete();

        // Perbarui izin berdasarkan URL yang dipilih
        foreach ($request->input('urls', []) as $url) {
            $line->permission()->create(['url' => $url]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success Update',
            'redirect' => route('admin.permissionLine.index')
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.'
            ]);
        }

        $line = Line::find($id);

        // Hapus permissions melalui relasi
        $line->permission()->delete();

        // Hapus line
        $line->delete();

        return response()->json([
            'success' => true,
            'message' => 'Line deleted successfully.'
        ]);
    }

}
