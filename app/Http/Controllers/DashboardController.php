<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mesin;
use App\Models\Proses;
use App\Models\Roles;
use App\Models\Line;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $filter_lines = Line::orderBy('name', 'asc')->get();
        $filter_proses = Proses::orderBy('name', 'asc')->get();

        return view('v1.dashboard', compact('filter_lines', 'filter_proses'));
    }

    public function getDataTableMesin(Request $request)
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
                        return '<span class="badge badge-light m-1">' . $item->name . '</span>';
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

}
