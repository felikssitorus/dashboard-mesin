<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Line;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Services\System\LogActivityService;

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

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Tambah Line',
                    'catatan' => 'Berhasil menambah ' . $line['name'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Created',
                'redirect' => route('v1.line.index')
            ]);

        } catch (\Throwable $th) {

            // if (Auth::check()) {
            //     $user = Auth::user()->email;
            //     (new LogActivityService())->handle([
            //         'perusahaan' => '-',
            //         'user' => strtoupper($user),
            //         'tindakan' => 'Tambah Line',
            //         'catatan' => $th->getMessage(),
            //     ]);
            // }

            if (Auth::check()) {
                $user = Auth::user()->email;
                $user = json_decode(auth()->user()->result, true);
                (new LogActivityService())->handle([
                    'perusahaan' => strtoupper($data['CompName']),
                    'user' => strtoupper($user),
                    'tindakan' => 'Login',
                    'catatan' => 'Berhasil Login Account',
                ]);
            }
            

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

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Edit Line',
                    'catatan' => 'Berhasil mengubah ' . $lineData['name'],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Updated',
                'redirect' => route('v1.line.index')
            ]);
        } catch (\Throwable $th) {

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Edit Line',
                    'catatan' => $th->getMessage(),
                ]);
            }

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

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Hapus Line',
                    'catatan' => 'Berhasil menghapus ' . $line->name,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Deleted',
            ]);
        } catch (\Throwable $th) {

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Hapus Line',
                    'catatan' => $th->getMessage(),
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('v1.line.index')
            ]);
        }
    }

}
