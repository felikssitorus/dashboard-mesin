<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Mesin;
use App\Models\Proses;
use App\Models\Line;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Services\System\LogActivityService;

class MesinController extends Controller
{

    public function index()
    {
        $all_proses = Proses::orderBy('name', 'asc')->get();

        $lines = Line::with('mesins.proses')->get();
        return view('v1.mesin.index', compact('all_proses', 'lines'));
    }

    public function getDataTableMesin(Request $request)
    {
        if ($request->ajax()) {
            $query = Mesin::with(['proses', 'line']);

            $userLine = auth()->user()->profile?->line;
            if ($userLine) {
                $query->where('line_id', $userLine->id);
            }

            // Terapkan filter Line jika ada
            if ($request->filled('filter_lines')) {
                $query->where('line_id', $request->filter_lines);
            }

            // Terapkan filter Proses jika ada
            if ($request->filled('filter_proses')) {
                $query->whereHas('proses', function($q) use ($request) {
                    $q->where('proses.id', $request->filter_proses);
                });
            }
            $query->latest();

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
                ->addColumn('line_name', function ($mesin) {
                    return $mesin->line ? $mesin->line->name : '-';
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
                ->editColumn('updated_at', function($mesin) {
                    return $mesin->tanggalUpdate;
                })
                ->rawColumns([
                    'action', 'proses_name', 'kapasitas', 'speed', 'line_name', 'updated_at'
                ])
                ->make(true);
        }
    }

    public function getDashboardDataTableMesin(Request $request)
    {
        if ($request->ajax()) {
            $query = Mesin::with(['proses', 'line']);

            // Terapkan filter Line jika ada
            if ($request->filled('filter_lines')) {
                $query->where('line_id', $request->filter_lines);
            }

            // Terapkan filter Proses jika ada
            if ($request->filled('filter_proses')) {
                $query->whereHas('proses', function($q) use ($request) {
                    $q->where('proses.id', $request->filter_proses);
                });
            }
            $query->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('proses_name', function ($mesin) {
                    if ($mesin->proses->isEmpty()) {
                        return '-';
                    }
                    return $mesin->proses->map(function($item) {
                        return $item->name;
                    })->implode(' ');
                })
                ->addColumn('line_name', function ($mesin) {
                    return $mesin->line ? $mesin->line->name : '-';
                })
                ->editColumn('line_name', function ($mesin) {
                    return $mesin->line ? $mesin->line->name : '-';
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
                    'proses_name', 'kapasitas', 'speed', 'line_name'
                ])
                ->make(true);
        }
    }

    public function create()
    {
        $userLine = auth()->user()->profile?->line;
        $all_proses = Proses::all();
        $all_line = Line::all();
        return response()->json([
            'all_proses' => $all_proses,
            'all_line' => $all_line,
            'userLine' => $userLine,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'line_id'          => 'required|exists:lines,id',
            'kodeMesin'         => 'required|string|unique:mesins,kodeMesin',
            'name'              => 'required|string|max:255',
            'kapasitas'         => 'nullable|string',
            'speed'             => 'nullable|string',
            'jumlahOperator'    => 'required|integer',
            'proses_ids'        => 'required|array',
        ]);

        try {
            $mesin = Mesin::create([
                'line_id'        => $validatedData['line_id'],
                'kodeMesin'      => $validatedData['kodeMesin'],
                'name'           => $validatedData['name'],
                'kapasitas'      => $validatedData['kapasitas'] ?? null,
                'speed'          => $validatedData['speed'] ?? null,
                'jumlahOperator' => $validatedData['jumlahOperator'],
            ]);

            $mesin->proses()->attach($request->proses_ids);

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Tambah Mesin',
                    'catatan' => 'Berhasil menambah data mesin',
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Machine Has Been Created',
                'redirect' => route('v1.mesin.index')
            ]);

        } catch (\Throwable $th) {
            
            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Tambah Mesin',
                    'catatan' => $th->getMessage(),
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
        $userLine = auth()->user()->profile?->line;
        $mesin = Mesin::with('proses', 'line')->findOrFail($id);
        $allProses = Proses::all();
        return response()->json([
            'userLine' => $userLine,
            'mesin' => $mesin,
            'proses' => $allProses,
        ]);
    }

    public function update(Request $request, $id)
    {
       $validatedData = $request->validate([
            'line_id'          => 'required|exists:lines,id',
            'proses_ids'        => 'required|array',
            'kodeMesin'         => 'required|string',
            'name'              => 'required|string|max:255',
            'kapasitas'         => 'nullable|string',
            'speed'             => 'nullable|string',
            'jumlahOperator'    => 'required|integer',
        ]);

        try {
            $mesin = Mesin::findOrFail($id);
            
            $mesin->update([
                'line_id'        => $validatedData['line_id'],
                'kodeMesin'      => $validatedData['kodeMesin'],
                'name'           => $validatedData['name'],
                'kapasitas'      => $validatedData['kapasitas'] ?? null,
                'speed'          => $validatedData['speed'] ?? null,
                'jumlahOperator' => $validatedData['jumlahOperator'],
            ]);

            $mesin->proses()->sync($request->proses_ids);

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Edit Mesin',
                    'catatan' => 'Berhasil mengubah data mesin',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Machine Has Been Updated',
                'redirect' => route('v1.mesin.index')
            ]);
        } catch (\Throwable $th) {

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Edit Mesin',
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
            $mesin = Mesin::findOrFail($id);
            $mesin->delete();

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Hapus Mesin',
                    'catatan' => 'Berhasil menghapus data mesin',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Machine Has Been Deleted',
            ]);
        } catch (\Throwable $th) {

            if (Auth::check()) {
                $user = Auth::user()->email;
                (new LogActivityService())->handle([
                    'perusahaan' => '-',
                    'user' => strtoupper($user),
                    'tindakan' => 'Hapus Mesin',
                    'catatan' => $th->getMessage(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('v1.mesin.index') 
            ]);
        }
    }

}
