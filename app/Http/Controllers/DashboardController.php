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
        $mesin = Mesin::with(['proses', 'line'])->get();
        $filter_lines = Line::orderBy('name', 'asc')->get();
        $filter_proses = Proses::orderBy('name', 'asc')->get();

        return view('v1.dashboard', compact('filter_lines', 'filter_proses', 'mesin'));
    }

    public function detail($id)
    {
        $mesin = Mesin::with(['proses', 'line'])->findOrFail($id);

        return response()->json([
            'mesin' => $mesin,
        ]);
    }

}
